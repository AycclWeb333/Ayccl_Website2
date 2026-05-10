<?php

namespace App\Http\Controllers\Admin\AboutUs;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Media;
use App\Models\Page;
use App\Models\Post;
use App\Models\PostDetail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use App\Traits\CloudMediaTrait;

class CertificatesController extends Controller
{
    use CloudMediaTrait;
    public $pageId = 26;
    public $route = 'prizes-and-certificates';
    public $view = 'admin-panel.about-us.prizes-and-certificates';
    public function index()
    {
        try{
            $posts = Post::where('page_id', $this->pageId)->get();
            $page = Page::findOrFail($this->pageId);
        }
        catch(\Exception $e){
            Log::error('CertificatesController index error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
        return view("$this->view.index", compact('posts', 'page'));
    }

    public function show($locale, $id)
    {
        $media = Media::findOrFail($id);

        // Full path to file
        $path = public_path($media->link);

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
        try{
            $categories = Category::whereHas('postDetail', function ($query) {
                $query->whereHas('post', function ($q) {
                    $q->where('page_id', $this->pageId);
                });
            })
            ->select('id', 'name','name_en')
            ->distinct()
            ->get();
        }
        catch(\Exception $e){
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
        return view("$this->view.create", compact( 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'title'      => 'required',
                'title_en'   => 'required',
                'content_ar'    => 'required',
                'content_en' => 'required',
                'files'      => 'required',
                'files_pdf'      => 'required',
            ],
            [
                'title.required'      => __('adminlte::adminlte.title_required'),
                'title_en.required'   => __('adminlte::adminlte.title_en_required'),
                'content_ar.required'    => __('adminlte::adminlte.content_required'),
                'content_en.required' => __('adminlte::adminlte.content_en_required'),
                'files.required'      => __('adminlte::adminlte.files_required'),
                'files_pdf.required'      => __('adminlte::adminlte.files_required'),
            ]
        );


        try {
            DB::beginTransaction();
            // 1. Create Post
            $post = new Post();
            $post->category_id = 1;
            $post->page_id = $this->pageId; // default page
            if (isset($request->order))
                $post->order     = $request->order;
            else {
                $maxOrder = Post::where('page_id', $this->pageId)->where('active', true)->max('order');
                $post->order = $maxOrder +1;
            }
            $post->active = true;
            $post->save();

            // 2. Create PostDetail
            $postDetail = new PostDetail();
            $postDetail->post_id   = $post->id;
            $postDetail->category_id = $request->category_id;
            $postDetail->title = $request->title;
            $postDetail->title_en  = $request->title_en;
            $postDetail->content   = $request->content_ar;
            $postDetail->content_en = $request->content_en;
            $postDetail->color     = $request->color;
            $postDetail->order     = $request->order ?? 1;
            $postDetail->active    = $request->active ?? true;
            $postDetail->save();

            // Force Spatie/Image to use GD instead of Imagick

            // 3) Upload Media (if provided)
            $this->storeCombinedMedia($request, $post->id, $this->route, Post::class);
            // Commit after processing all files
            DB::commit();

            return redirect()->route("$this->route.index", app()->getLocale())
                ->with(['success' => __('adminlte::adminlte.succCreate')]);
        } catch (\Exception $e) {
            DB::rollBack();
            // return redirect()->back()->withErrors(['error' => $e->getMessage()]);
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($locale , int $id)
    {
        try{
            $post = Post::findOrFail($id);
            $categories = Category::whereHas('postDetail', function ($query) {
                $query->whereHas('post', function ($q) {
                    $q->where('page_id', $this->pageId);
                });
            })
            ->select('id', 'name','name_en')
            ->distinct()
            ->get();
        }
        catch(\Exception $e){
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
        return view("$this->view.edit", compact(  'post', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $locale, int $id)
    {
        // dd($request);
        $request->validate(
            [
                'title'      => 'required',
                'title_en'   => 'required',
                // 'slug'       => 'required|string',
                // 'slug_en'    => 'required|string',
                // 'date'       => 'required|date',
                'content_ar'    => 'required',
                'content_en' => 'required',
                // 'files'      => 'required',
            ],
            [
                'title.required'      => __('adminlte::adminlte.title_required'),
                'title_en.required'   => __('adminlte::adminlte.title_en_required'),
                // 'slug.required'       => __('adminlte::adminlte.slug_required'),
                // 'slug.unique'         => __('adminlte::adminlte.slug_unique'),
                // 'slug_en.required'    => __('adminlte::adminlte.slug_en_required'),
                // 'slug_en.unique'      => __('adminlte::adminlte.slug_en_unique'),
                // 'date.required'       => __('adminlte::adminlte.date_required'),
                'content_ar.required'    => __('adminlte::adminlte.content_required'),
                'content_en.required' => __('adminlte::adminlte.content_en_required'),
                // 'files.required'      => __('adminlte::adminlte.files_required'),
                ]
        );


        try {
            $post = Post::findOrFail($id);
            DB::beginTransaction();
            // dd($post);
            $post->category_id = $request->category_id;
            $post->page_id = $this->pageId; // default page
            // $post->date = $request->date;
            // if (isset($request->order))
            //     $post->order     = $request->order;
            // else {
            //     $maxOrder = Post::where('page_id', $this->pageId)->where('active', true)->max('order');
            //     $post->order = $maxOrder +1;
            // }
            // $post->order = $request->order ?? 1;
            // $post->active = true;
            $post->save();

            // 2. Create PostDetail
            $postDetail = PostDetail::where('post_id' , $post->id)->firstOrFail();
            // $postDetail->post_id   = $post->id;
            $postDetail->category_id = $request->category_id;
            $postDetail->title = $request->title;
            $postDetail->title_en  = $request->title_en;
            // $postDetail->setSlugAttribute($request->slug);
            // $postDetail->setSlugEnAttribute($request->slug_en);
            $postDetail->content   = $request->content_ar;
            $postDetail->content_en = $request->content_en;
            // $postDetail->color     = $request->color?? '';
            // $postDetail->order     = $postDetail->order ?? 1;
            $postDetail->active    = $postDetail->active ?? true;
            $postDetail->save();

            // Force Spatie/Image to use GD instead of Imagick
            // 3. Update Media using CloudMediaTrait
            $media = Media::where('media_able_id', $post->id)->where('media_able_type', Post::class)->first();
            if (!$media) {
                $media = new Media();
                $media->media_able_id = $post->id;
                $media->media_able_type = Post::class;
            }

            $this->updateCombinedMedia($request, $media, $post->id, $this->route);
            // Commit after processing all files
            DB::commit();

            return redirect()->route("$this->route.index", app()->getLocale())
                ->with(['success' => __('adminlte::adminlte.succEdit')]);
        } catch (\Exception $e) {
            DB::rollBack();
            // return redirect()->back()->withErrors(['error' => $e->getMessage()]);
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($locale ,string $id)
    {
        try {
            DB::beginTransaction();
            $post = Post::findOrFail($id);

            // 1. Delete all related PostDetail records first
            $post->postDetail()->delete();
            // 2. Delete all related Media records and their files
            $this->deleteCloudMediaDirectory($post, $this->route, $id);
            // 3. Delete the parent post
            $post->delete();

            DB::commit();

            return redirect()->route("$this->route.index", app()->getLocale())->with(['success' => __('adminlte::adminlte.succDelete')]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }
}
