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



class DocumentsController extends Controller
{
        /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $posts = Post::where('page_id', 54)->latest()->paginate(10);
        try {
            
            $page = Page::findOrFail($this->pageId);
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
    //     try {
    //         
            $page = Page::findOrFail($this->pageId);
    //     } catch (\Exception $e) {
    //         return redirect()->back()->with(['error' => $e->getMessage()]);
    //     }
        return view('admin-panel.media-center.documents.create');
    }

    public function store(Request $request)
    {
        
        $request->validate(
            [
                'title'      => 'required',
                'title_en'   => 'required',
                // 'slug'       => 'required|string|unique:post_details,slug',
                // 'slug_en'    => 'required|string|unique:post_details,slug_en',
                // 'date'       => 'required|date',
                // 'content_ar'    => 'required',
                // 'content_en' => 'required',
                'files'      => 'required',
            ],
            [
                'title.required'      => __('adminlte::adminlte.title_required'),
                'title_en.required'   => __('adminlte::adminlte.title_en_required'),
                // 'slug.required'       => __('adminlte::adminlte.slug_required'),
                // 'slug.unique'         => __('adminlte::adminlte.slug_unique'),
                // 'slug_en.required'    => __('adminlte::adminlte.slug_en_required'),
                // 'slug_en.unique'      => __('adminlte::adminlte.slug_en_unique'),
                // 'date.required'       => __('adminlte::adminlte.date_required'),
                // 'content_ar.required'    => __('adminlte::adminlte.content_required'),
                // 'content_en.required' => __('adminlte::adminlte.content_en_required'),
                'files.required'      => __('adminlte::adminlte.files_required'),
            ]
        );

        
        try {
            DB::beginTransaction();
            // 1. Create Post
            $post = new Post();
            $post->category_id = $request->category_id;
            $post->page_id = 54; // default page
            // $post->date = $request->date;
            if (isset($request->order))
                $post->order     = $request->order;
            else {
                $maxOrder = Post::where('page_id', 54)->where('active', true)->max('order');
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

            // Force Spatie/Image to use GD instead of Imagick

            // 3) Upload Media (if provided)
            if ($request->hasFile('files')) {
                $filearr = $request->file('files');
                $file = $filearr[0];
                // 1) Get the original file name from the UploadedFile object
                $originalFileName = Media::getAlt($file->getClientOriginalName());
            
                // 2) Define paths based on your requirements
                $filePath = "files/documents/{$post->id}/{$originalFileName}";
                $thumbnailPath = "images/thumbnails/document.png";
                $destinationPath = public_path("files/documents/{$post->id}");
            
                // 3) Create the directory if it doesn't exist
                File::makeDirectory($destinationPath, 0755, true, true);
            
                // 4) Move the file to the correct location using its original name
                $file->move($destinationPath, $originalFileName);
            
                // 5) Save the database record
                $media = new Media();
                $media->media_type_id = 3; 
                $media->thumbnailpath = $thumbnailPath;
                $media->filepath = $filePath;
                $media->alt = $post->postDetailOne->title;
                $media->setAltEnAttribute($post->postDetailOne->title_en);
                $media->link = $filePath;
                $media->media_able_id = $post->id;
                $media->media_able_type = Post::class;
                $media->save();
            } else {
                // You should handle the case where no file is uploaded
                throw new \Exception(__('adminlte::adminlte.files_required'));
            }
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
        // dd($request);
        $request->validate(
            [
                'title'      => 'required',
                'title_en'   => 'required',
                // 'slug'       => 'required|string',
                // 'slug_en'    => 'required|string',
                // 'date'       => 'required|date',
                // 'content_ar'    => 'required',
                // 'content_en' => 'required',
                // 'files'      => 'required',
            ],
            [
                'title.required'      => __('adminlte::adminlte.title_required'),
                'title_en.required'   => __('adminlte::adminlte.title_en_required'),
                // 'slug.required'       => __('adminlte::adminlte.slug_required'),
                // // 'slug.unique'         => __('adminlte::adminlte.slug_unique'),
                // 'slug_en.required'    => __('adminlte::adminlte.slug_en_required'),
                // // 'slug_en.unique'      => __('adminlte::adminlte.slug_en_unique'),
                // 'date.required'       => __('adminlte::adminlte.date_required'),
                // 'content_ar.required'    => __('adminlte::adminlte.content_required'),
                // 'content_en.required' => __('adminlte::adminlte.content_en_required'),
                // 'files.required'      => __('adminlte::adminlte.files_required'),
            ]
        );


        try {
            $post = Post::findOrFail($id);
            DB::beginTransaction();
            $post->category_id = 3;
            $post->page_id = 54; // default page
            // $post->date = $request->date;
            if (isset($request->order))
                $post->order     = $request->order;
            else {
                $maxOrder = Post::where('page_id', 54)->where('active', true)->max('order');
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

            // Force Spatie/Image to use GD instead of Imagick

            if ($request->hasFile('files')) {
                $filearr = $request->file('files');
                $file = $filearr[0];
                // 1) Get the original file name from the UploadedFile object
                $originalFileName = Media::getAlt($file->getClientOriginalName());
            
                // 2) Define paths based on your requirements
                $filePath = "files/documents/{$post->id}/{$originalFileName}";
                $thumbnailPath = "images/thumbnails/document.png";
                $destinationPath = public_path("files/documents/{$post->id}");
            
                // 3) Create the directory if it doesn't exist
                File::makeDirectory($destinationPath, 0755, true, true);
            
                // 4) Move the file to the correct location using its original name
                $file->move($destinationPath, $originalFileName);
            
                // 5) Save the database record
                $media = new Media();
                $media->media_type_id = 3; 
                $media->thumbnailpath = $thumbnailPath;
                $media->filepath = $filePath;
                $media->alt = $post->postDetailOne->title;
                $media->setAltEnAttribute($post->postDetailOne->title_en);
                $media->link = $filePath;
                $media->media_able_id = $post->id;
                $media->media_able_type = Post::class;
                $media->save();
            } 
            $media = Media::where('media_able_id', $post->id)->count();
            if ($media == 0) {
                throw new \Exception(__('adminlte::adminlte.files_required')    );
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
            $directoryPath = "files/documents/{$post->id}";

                // 1. Delete the entire directory and all its contents from storage
                if (Storage::disk('images')->exists($directoryPath)) {
                    Storage::disk('images')->deleteDirectory($directoryPath);
                }
            // Delete the record from the database
            $post->mediaOne()->delete();
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
