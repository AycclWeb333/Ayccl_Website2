<?php

namespace App\Http\Controllers\Admin\ExternalLinks;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\Page;
use App\Models\Post;
use App\Models\PostDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Traits\CloudMediaTrait;

class ExternalLinksController extends Controller
{
    use CloudMediaTrait;

    public $pageId = 8;
    public $route  = 'external-links';
    public $view   = 'admin-panel.external-links';

    public function index()
    {
        try {
            $posts = Post::where('page_id', $this->pageId)->orderBy('category_id')->get();
            $page  = Page::findOrFail($this->pageId);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
        return view("$this->view.index", compact('posts', 'page'));
    }

    public function show($locale, $id)
    {
        $media = Media::findOrFail($id);

        $path = public_path($media->link);

        if (!file_exists($path)) {
            abort(404, 'File not found.');
        }

        return response()->file($path, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . basename($path) . '"',
        ]);
    }

    public function create()
    {
        return view("$this->view.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'title'    => 'required',
                'title_en' => 'required',
                'value'    => 'required',
                'files'    => 'required',
            ],
            [
                'title.required'    => __('adminlte::adminlte.title_required'),
                'title_en.required' => __('adminlte::adminlte.title_en_required'),
                'value.required'    => __('adminlte::adminlte.value_required'),
                'files.required'    => __('adminlte::adminlte.files_required'),
            ]
        );

        try {
            DB::beginTransaction();

            // 1. Create Post
            $post              = new Post();
            $post->category_id = 16;
            $post->page_id     = $this->pageId;
            if (isset($request->order)) {
                $post->order = $request->order;
            } else {
                $maxOrder    = Post::where('page_id', $this->pageId)->where('category_id', 16)->max('order');
                $post->order = $maxOrder + 1;
            }
            $post->active = true;
            $post->save();

            // 2. Create PostDetail
            $postDetail              = new PostDetail();
            $postDetail->post_id     = $post->id;
            $postDetail->category_id = 16;
            $postDetail->title       = $request->title;
            $postDetail->title_en    = $request->title_en;
            $postDetail->content     = $request->value;
            $postDetail->content_en  = $request->value;
            $postDetail->order       = $request->order ?? 1;
            $postDetail->active      = $request->active ?? true;
            $postDetail->color       = '';
            $postDetail->save();

            // 3. Upload image/icon to cloud (DO Spaces) via CloudMediaTrait
            if ($request->hasFile('files')) {
                $files = is_array($request->file('files')) ? $request->file('files') : [$request->file('files')];

                foreach ($files as $file) {
                    $imageData = $this->uploadSingleCloudImage($file, $this->route, $post->id);

                    $media                  = new Media();
                    $media->media_type_id   = 1;
                    $media->filepath        = $imageData['filepath'];
                    $media->thumbnailpath   = $imageData['thumbnailpath'];
                    $media->alt             = $imageData['alt'];
                    $media->setAltEnAttribute($imageData['alt']);
                    $media->link            = $request->value; // External URL
                    $media->media_able_id   = $post->id;
                    $media->media_able_type = Post::class;
                    $media->save();
                }
            }

            DB::commit();

            return redirect()->route("$this->route.index", app()->getLocale())
                ->with(['success' => __('adminlte::adminlte.succCreate')]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($locale, int $id)
    {
        try {
            $post = Post::findOrFail($id);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
        return view("$this->view.edit", compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $locale, int $id)
    {
        if ($request->category_id == 13) {
            $request->validate(
                [
                    'title'    => 'required',
                    'title_en' => 'required',
                    'value'    => 'required',
                ],
                [
                    'title.required'    => __('adminlte::adminlte.title_required'),
                    'title_en.required' => __('adminlte::adminlte.title_en_required'),
                    'value.required'    => __('adminlte::adminlte.value_required'),
                ]
            );
        } else {
            $request->validate(
                [
                    'title'    => 'required',
                    'title_en' => 'required',
                    'value'    => 'required',
                    'files'    => 'nullable',
                ],
                [
                    'title.required'    => __('adminlte::adminlte.title_required'),
                    'title_en.required' => __('adminlte::adminlte.title_en_required'),
                    'value.required'    => __('adminlte::adminlte.value_required'),
                    'files.required'    => __('adminlte::adminlte.files_required'),
                ]
            );
        }

        try {
            $post = Post::findOrFail($id);
            DB::beginTransaction();

            $post->save();

            // 2. Update PostDetail
            $postDetail              = PostDetail::where('post_id', $post->id)->firstOrFail();
            $postDetail->title       = $request->title;
            $postDetail->title_en    = $request->title_en;
            $postDetail->content     = $request->value;
            $postDetail->content_en  = $request->value;
            $postDetail->order       = $postDetail->order ?? 1;
            $postDetail->active      = $postDetail->active ?? true;
            $postDetail->save();

            // 3. Upload new image to cloud if provided, replacing the old one
            if ($request->hasFile('files')) {
                $files = is_array($request->file('files')) ? $request->file('files') : [$request->file('files')];

                foreach ($files as $file) {
                    $doDisk      = Storage::disk('do');
                    $extractPath = fn($url) => ltrim(parse_url($url, PHP_URL_PATH), '/');

                    // Delete old cloud image if exists
                    $oldMedia = $post->mediaOne;
                    if ($oldMedia) {
                        if ($oldMedia->filepath && str_starts_with($oldMedia->filepath, 'http')) {
                            $path = $extractPath($oldMedia->filepath);
                            if ($doDisk->exists($path)) $doDisk->delete($path);
                        }
                        if ($oldMedia->thumbnailpath && str_starts_with($oldMedia->thumbnailpath, 'http')) {
                            $path = $extractPath($oldMedia->thumbnailpath);
                            if ($doDisk->exists($path)) $doDisk->delete($path);
                        }
                        $media = $oldMedia;
                    } else {
                        $media                  = new Media();
                        $media->media_able_id   = $post->id;
                        $media->media_able_type = Post::class;
                    }

                    // Upload new image to cloud
                    $imageData = $this->uploadSingleCloudImage($file, $this->route, $post->id);

                    $media->media_type_id = 1;
                    $media->filepath      = $imageData['filepath'];
                    $media->thumbnailpath = $imageData['thumbnailpath'];
                    $media->alt           = $imageData['alt'];
                    $media->setAltEnAttribute($imageData['alt']);
                    $media->link          = $request->value ?? null;
                    $media->save();
                }
            } else {
                // Just update the link (external URL) even if no new image was uploaded
                $oldMedia = $post->mediaOne;
                if ($oldMedia) {
                    $oldMedia->link = $request->value ?? $oldMedia->link;
                    $oldMedia->save();
                }
            }

            // Ensure media exists (except category 13)
            $mediaCount = Media::where('media_able_id', $post->id)->count();
            if ($mediaCount === 0 && $post->category_id != 13) {
                throw new \Exception(__('adminlte::adminlte.files_required'));
            }

            DB::commit();

            return redirect()->route("$this->route.index", app()->getLocale())
                ->with(['success' => __('adminlte::adminlte.succEdit')]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($locale, string $id)
    {
        try {
            DB::beginTransaction();
            $post = Post::findOrFail($id);

            // 1. Delete related PostDetail records
            $post->postDetail()->delete();

            // 2. Delete cloud media files and DB records
            $this->deleteCloudMediaDirectory($post, $this->route, $id);

            // 3. Delete the post
            $post->delete();

            DB::commit();

            return redirect()->route("$this->route.index", app()->getLocale())
                ->with(['success' => __('adminlte::adminlte.succDelete')]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }
}
