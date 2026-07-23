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
use Spatie\Image\Image;
use Spatie\Image\Drivers\GdDriver;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File; // This is the new import
use PhpParser\Node\Expr\Throw_;
use Spatie\Image\Drivers\ImageDriver;
use App\Traits\CloudMediaTrait;

class PhotosGalaryController extends Controller
{
    use CloudMediaTrait;

    public $pageId = 52;
    public $route = 'photos';
    public $view = 'admin-panel.media-center.photos';


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $posts = Post::where('page_id', 52)->latest()->paginate(10);
        try{
            $page = Page::findOrFail($this->pageId);
            $posts = Post::where('page_id', $this->pageId)->latest()->get();
        }
        catch(\Exception $e){
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
        return view("$this->view.index", compact('posts', 'page'));
    }
    public function show(string $id)
    {
        try{
            $page = Page::findOrFail($this->pageId);
            $posts = Post::where('page_id', $this->pageId)->latest()->get();
        }
        catch(\Exception $e){
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
        return view("$this->view.index", compact('posts', 'page'));
    }
    public function create()
    {
        try{
            $categories = Category::where('type', $this->pageId)
                ->select('id', 'name', 'name_en')
                ->get();
            // Fallback: if no categories found with type filter, get all
            if ($categories->isEmpty()) {
                $categories = Category::select('id', 'name', 'name_en')->get();
            }
        }
        catch(\Exception $e){
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
        return view("$this->view.create", compact( 'categories'));
    }

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
            $post->category_id = $request->category_id ?? 1;
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

            // 3) Upload Multiple Media using CloudMediaTrait
            $this->storeMultipleMedia($request, $post->id, $this->route, Post::class);
            DB::commit();

            return redirect()->route("$this->route.index", app()->getLocale())
                ->with(['success' => __('adminlte::adminlte.succCreate')]);
        } catch (\Exception $e) {
            DB::rollBack();
            // return redirect()->back()->withErrors(['error' => $e->getMessage()]);
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function edit($locale , int $id)
    {
        try{
            $post = Post::findOrFail($id);
            $categories = Category::where('type', $this->pageId)
                ->select('id', 'name', 'name_en')
                ->get();
            if ($categories->isEmpty()) {
                $categories = Category::select('id', 'name', 'name_en')->get();
            }
        }
        catch(\Exception $e){
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
        return view("$this->view.edit", compact( 'categories', 'post'));
    }

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
            if (isset($request->order))
                $post->order     = $request->order;
            else {
                $maxOrder = Post::where('page_id', $this->pageId)->where('active', true)->max('order');
                $post->order = $maxOrder +1;
            }
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
            $postDetail->order     = $postDetail->order ?? 1;
            $postDetail->active    = $postDetail->active ?? true;
            $postDetail->save();

            // 3. Append any newly uploaded images (keep existing, add new ones)
            if ($request->hasFile('files')) {
                $this->storeMultipleMedia($request, $post->id, $this->route, Post::class);
            }
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

    public function destroy($locale , int $id)
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
