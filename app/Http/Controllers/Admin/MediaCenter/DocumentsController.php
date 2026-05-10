<?php

namespace App\Http\Controllers\Admin\MediaCenter;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Media;
use App\Models\Page;
use App\Models\Post;
use App\Models\PostDetail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Image\Image;
use Spatie\Image\Drivers\GdDriver;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File; // This is the new import
use PhpParser\Node\Expr\Throw_;
use Spatie\Image\Drivers\ImageDriver;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use App\Traits\CloudMediaTrait;

class DocumentsController extends Controller
{
    use CloudMediaTrait;

    public $pageId = 54;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $page = Page::findOrFail($this->pageId);
            $posts = Post::where('page_id', $this->pageId)->latest()->get();
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
        return view('admin-panel.media-center.documents.index', compact('posts', 'page'));
    }
    public function show($locale, $id)
{
    $media = Media::findOrFail($id);

    // Full path to file
    $path = public_path($media->filepath);

    if (!file_exists($path)) {
        abort(404, 'File not found.');
    }

    return response()->file($path, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="' . basename($path) . '"'
    ]);
}
    public function create()
    {
        try {
            $page = Page::findOrFail($this->pageId);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
        return view('admin-panel.media-center.documents.create', compact('page'));
    }

    public function store(Request $request)
    {
        $fileVal = $this->getFileValidation();
        // Override rules for Documents module which uses 'files' for PDFs
        $fileVal['rules']['files.*'] = 'mimes:pdf|max:10240';
        $fileVal['messages']['files.*.mimes'] = __('adminlte::adminlte.file_type_pdf');
        $fileVal['messages']['files.*.max'] = __('adminlte::adminlte.file_limit_pdf');

        $request->validate(
            array_merge($fileVal['rules'], [
                'title'      => 'required',
                'title_en'   => 'required',
                'files'      => 'required',
            ]),
            array_merge($fileVal['messages'], [
                'title.required'      => __('adminlte::adminlte.title_required'),
                'title_en.required'   => __('adminlte::adminlte.title_en_required'),
                'files.required'      => __('adminlte::adminlte.files_required'),
            ])
        );

        
        try {
            DB::beginTransaction();
            // 1. Create Post
            $post = new Post();
            $post->category_id = $request->category_id;
            $post->page_id = $this->pageId; // default page
            // $post->date = $request->date;
            if (isset($request->order))
                $post->order     = $request->order;
            else {
                $maxOrder = Post::where('page_id', $this->pageId)->where('active', true)->max('order');
                $post->order = $maxOrder + 1;
            }
            // $post->order = $request->order ?? 1;
            $post->active = true;
            $post->save();

            // 2. Create PostDetail
            $postDetail = new PostDetail();
            $postDetail->post_id   = $post->id;
            // $postDetail->category_id = $request->category_id;
            $postDetail->title = $request->title;
            $postDetail->title_en  = $request->title_en;
            // $postDetail->setSlugAttribute($request->slug);
            // $postDetail->setSlugEnAttribute($request->slug_en);
            // $postDetail->content   = $request->content_ar;
            // $postDetail->content_en = $request->content_en;
            // $postDetail->color     = $request->color;
            $postDetail->order     = $request->order ?? 1;
            $postDetail->active    = $request->active ?? true;
            $postDetail->save();

            // 3) Upload Media (if provided)
            if (!$request->hasFile('files')) {
                throw new \Exception(__('adminlte::adminlte.files_required'));
            }

            $this->storeDocumentMedia($request, $post->id, 'documents', Post::class, $post->postDetailOne->title, $post->postDetailOne->title_en);
            DB::commit();
            
            return redirect()->route('documents.index', app()->getLocale())
                ->with(['success' => __('adminlte::adminlte.succCreate')]);
        } catch (\Exception $e) {
            DB::rollBack();
            // return redirect()->back()->withErrors(['error' => $e->getMessage()]);
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function edit($locale, int $id)
    {
        try {
            $post = Post::findOrFail($id);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
        return view('admin-panel.media-center.documents.edit', compact('post'));
    }

    public function update(Request $request, $locale, int $id)
    {
        $fileVal = $this->getFileValidation();
        // Override rules for Documents module which uses 'files' for PDFs
        $fileVal['rules']['files.*'] = 'mimes:pdf|max:10240';
        $fileVal['messages']['files.*.mimes'] = __('adminlte::adminlte.file_type_pdf');
        $fileVal['messages']['files.*.max'] = __('adminlte::adminlte.file_limit_pdf');

        $request->validate(
            array_merge($fileVal['rules'], [
                'title'      => 'required',
                'title_en'   => 'required',
            ]),
            array_merge($fileVal['messages'], [
                'title.required'      => __('adminlte::adminlte.title_required'),
                'title_en.required'   => __('adminlte::adminlte.title_en_required'),
            ])
        );


        try {
            $post = Post::findOrFail($id);
            DB::beginTransaction();
            $post->category_id = 3;
            $post->page_id = $this->pageId; // default page
            // $post->date = $request->date;
            if (isset($request->order))
                $post->order     = $request->order;
            else {
                $maxOrder = Post::where('page_id', $this->pageId)->where('active', true)->max('order');
                $post->order = $maxOrder + 1;
            }
            // $post->order = $request->order ?? 1;
            // $post->active = true;
            $post->save();

            // 2. Create PostDetail
            $postDetail = PostDetail::where('post_id', $post->id)->firstOrFail();
            // $postDetail->post_id   = $post->id;
            // $postDetail->category_id = $request->category_id;
            $postDetail->title = $request->title;
            $postDetail->title_en  = $request->title_en;
            // $postDetail->setSlugAttribute($request->slug);
            // $postDetail->setSlugEnAttribute($request->slug_en);
            // $postDetail->content   = $request->content_ar;
            // $postDetail->content_en = $request->content_en;
            // $postDetail->color     = $request->color ?? '';
            $postDetail->order     = $request->order ?? 1;
            $postDetail->active    = $request->active ?? true;
            $postDetail->save();

            $media = Media::firstOrNew([
                'media_able_id' => $post->id,
                'media_able_type' => Post::class
            ]);

            $this->updateDocumentMedia($request, $media, $post->id, 'documents', $post->postDetailOne->title, $post->postDetailOne->title_en);

            if (!$media->filepath && !$request->hasFile('files')) {
                throw new \Exception(__('adminlte::adminlte.files_required'));
            }
            DB::commit();

            return redirect()->route('documents.index', app()->getLocale())
                ->with(['success' => __('adminlte::adminlte.succEdit')]);
        } catch (\Exception $e) {
            DB::rollBack();
            // return redirect()->back()->withErrors(['error' => $e->getMessage()]);
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function destroy($locale, int $id)
    {
        try {
            DB::beginTransaction();
            $post = Post::findOrFail($id);

            // 1. Delete all related PostDetail records first
            $post->postDetail()->delete();
            // 2. Delete all related Media records and their directories from Cloud
            $this->deleteCloudMediaDirectory($post, 'documents', $id);
            // 3. Delete the parent post
            $post->delete();

            DB::commit();

            return redirect()->route('documents.index', app()->getLocale())->with(['success' => __('adminlte::adminlte.succDelete')]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }
}
