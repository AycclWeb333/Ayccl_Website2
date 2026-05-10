<?php

namespace App\Http\Controllers\Admin\AboutUs;

use App\Models\Page;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostDetail;
use App\Models\Media;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Spatie\Image\Image;
use Spatie\Image\Drivers\GdDriver;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use Illuminate\Support\Facades\Storage;
use Spatie\Image\Drivers\ImageDriver;
use App\Traits\CloudMediaTrait;

class ManagementController extends Controller
{
    use CloudMediaTrait;
    public $pageId = 22;
    public $route = 'management-board';
    public $view = 'admin-panel.about-us.management-board';
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $posts = Post::where('page_id', $this->pageId)->orderby('order')->get();
            $page = Page::findOrFail($this->pageId);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
        return view("$this->view.index", compact('posts', 'page'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("$this->view.create", );
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
            ],
            [
                'title.required'      => __('adminlte::adminlte.title_required'),
                'title_en.required'   => __('adminlte::adminlte.title_en_required'),
                'content_ar.required'    => __('adminlte::adminlte.content_required'),
                'content_en.required' => __('adminlte::adminlte.content_en_required'),
                'files.required'      => __('adminlte::adminlte.files_required'),
            ]
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
            $post->active = true;
            $post->save();

            // 2. Create PostDetail
            $postDetail = new PostDetail();
            $postDetail->post_id   = $post->id;
            $postDetail->title = $request->title;
            $postDetail->title_en  = $request->title_en;
            $postDetail->content   = $request->content_ar;
            $postDetail->content_en = $request->content_en;
            // $postDetail->color     = $request->color;
            $postDetail->order     = $request->order ?? 1;
            $postDetail->active    = $request->active ?? true;
            $postDetail->save();

            // Force Spatie/Image to use GD instead of Imagick

            // 3) Upload Media (if provided)
            $this->storeCombinedMedia($request, $post->id, $this->route, Post::class);
                $media = Media::where('media_able_id', $post->id)->count();
                if ($media == 0) {
                    throw new \Exception(__('adminlte::adminlte.files_required'));
                }
                // Commit after processing all files (do NOT commit inside the loop)
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($locale, string $id)
    {
        try {
            $post = Post::findOrFail($id);      
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
        return view("$this->view.edit", compact('post' ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $locale,string $id)
    {
        $request->validate(
            [
                'title'      => 'required',
                'title_en'   => 'required',
                'content_ar'    => 'required',
                'content_en' => 'required',
                // 'files'      => 'required',
            ],
            [
                'title.required'      => __('adminlte::adminlte.title_required'),
                'title_en.required'   => __('adminlte::adminlte.title_en_required'),
                'content_ar.required'    => __('adminlte::adminlte.content_required'),
                'content_en.required' => __('adminlte::adminlte.content_en_required'),
                // 'files.required'      => __('adminlte::adminlte.files_required'),
            ]
        );


        try {
            DB::beginTransaction();
            // 1. Create Post
            $post = Post::findOrFail($id);
            // $post->category_id = $request->category_id;
            // $post->page_id = $this->pageId; // default page
            // $post->date = $request->date;
            // if (isset($request->order))
            //     $post->order     = $request->order;
            // else {
            //     $maxOrder = Post::where('page_id', $this->pageId)->where('active', true)->max('order');
            //     $post->order = $maxOrder + 1;
            // }
            // $post->active = true;
            $post->save();

            // 2. Create PostDetail
            $postDetail = PostDetail::where('post_id', $post->id)->firstOrFail();
            // $postDetail->post_id   = $post->id;
            $postDetail->title = $request->title;
            $postDetail->title_en  = $request->title_en;
            $postDetail->slug = $request->slug??'';
            $postDetail->slug_en  = $request->slug_en??'';
            $postDetail->content   = $request->content_ar;
            $postDetail->content_en = $request->content_en;
            // $postDetail->color     = $request->icon;
            // $postDetail->order     = $request->order ?? 1;
            // $postDetail->active    = $request->active ?? true;
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
            $media = Media::where('media_able_id', $post->id)->count();
                if ($media == 0) {
                    throw new \Exception(__('adminlte::adminlte.files_required'));
                }
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
