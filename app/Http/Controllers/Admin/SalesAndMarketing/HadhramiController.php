<?php

namespace App\Http\Controllers\Admin\SalesAndMarketing;

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

class HadhramiController extends Controller
{
    use CloudMediaTrait;

    public $pageId = 31;
    public $route = 'hadhrami';
    public $view = 'admin-panel.sales-and-marketing.hadhrami';

   public function index()
   {
       try{
           $posts = Post::where('page_id', $this->pageId)->get();
            $page = Page::findOrFail($this->pageId);
       }
       catch(\Exception $e){
           return redirect()->back()->with(['error' => $e->getMessage()]);
       }
       return view("$this->view.index", compact('posts', 'page'));
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
        $fileVal = $this->getFileValidation();
        $request->validate(
            array_merge($fileVal['rules'], [
                'title'      => 'required',
                'title_en'   => 'required',
                'content_ar' => 'required',
                'content_en' => 'required',
                'files'      => 'required',
            ]),
            array_merge($fileVal['messages'], [
                'title.required'      => __('adminlte::adminlte.title_required'),
                'title_en.required'   => __('adminlte::adminlte.title_en_required'),
                'content_ar.required' => __('adminlte::adminlte.content_required'),
                'content_en.required' => __('adminlte::adminlte.content_en_required'),
                'files.required'      => __('adminlte::adminlte.files_required'),
            ])
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

           // 3) Upload Media (Multiple) using CloudMediaTrait
           $this->storeMultipleMedia($request, $post->id, $this->route, Post::class);

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
   public function edit($locale , int $id)
   {
       try{
           $post = Post::findOrFail($id);
       }
       catch(\Exception $e){
           return redirect()->back()->with(['error' => $e->getMessage()]);
       }
       return view("$this->view.edit", compact(  'post'));
   }

   /**
    * Update the specified resource in storage.
    */
    public function update(Request $request, $locale, int $id)
    {
        $fileVal = $this->getFileValidation();
        $request->validate(
            array_merge($fileVal['rules'], [
                'title'      => 'required',
                'title_en'   => 'required',
                'content_ar' => 'required',
                'content_en' => 'required',
            ]),
            array_merge($fileVal['messages'], [
                'title.required'      => __('adminlte::adminlte.title_required'),
                'title_en.required'   => __('adminlte::adminlte.title_en_required'),
                'content_ar.required' => __('adminlte::adminlte.content_required'),
                'content_en.required' => __('adminlte::adminlte.content_en_required'),
            ])
        );

       
       try {
           $post = Post::findOrFail($id);
           DB::beginTransaction();

           $post->category_id = $request->category_id;
           $post->page_id     = $this->pageId;
           $post->save();

           // 2. Update PostDetail
           $postDetail = PostDetail::where('post_id', $post->id)->firstOrFail();
           $postDetail->category_id = $request->category_id;
           $postDetail->title       = $request->title;
           $postDetail->title_en    = $request->title_en;
           $postDetail->content     = $request->content_ar;
           $postDetail->content_en  = $request->content_en;
           $postDetail->order       = $postDetail->order ?? 1;
           $postDetail->active      = $postDetail->active ?? true;
           $postDetail->save();

           // 3. Append newly uploaded images (existing images deleted individually via Krajee)
           if ($request->hasFile('files')) {
               $this->storeMultipleMedia($request, $post->id, $this->route, Post::class);
           }

           // Ensure at least one media record remains
           $mediaCount = Media::where('media_able_id', $post->id)
                              ->where('media_able_type', Post::class)
                              ->count();
           if ($mediaCount === 0) {
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
           return redirect()->back()->with(['error' => $e->getMessage()])->withInput();
       }
   }
}
