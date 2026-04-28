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

class VideosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $posts = Post::where('page_id', 53)->latest()->paginate(10);
        try {
            
            $page = Page::findOrFail($this->pageId);
            $categories = Category::where('type',53 )->get();
            // dd($posts);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
        return view('admin-panel.media-center.videos.index', compact('posts', 'categories'));
    }
    public function show(string $id)
    {
        try {
            
            $page = Page::findOrFail($this->pageId);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
        return view('admin-panel.media-center.videos.index', compact('posts', 'page'));
    }
    public function create()
    {
        try {
            $categories = Category::where('type' ,53)
                ->get();
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
        return view('admin-panel.media-center.videos.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'title'      => 'required',
                'title_en'   => 'required',
                'videoLink'    => 'required|string|unique:media,filepath',
                'date'       => 'required|date',
            ],
            [
                'title.required'      => __('adminlte::adminlte.title_required'),
                'title_en.required'   => __('adminlte::adminlte.title_en_required'),
                'videoLink.required'    => __('adminlte::adminlte.videoLink_required'),
                'videoLink.unique'      => __('adminlte::adminlte.videoLink_unique'),
                'date.required'       => __('adminlte::adminlte.date_required'),
            ]
        );

        try {
            DB::beginTransaction();
            // 1. Create Post
            $post = new Post();
            $post->category_id = $request->category_id;
            $post->page_id = 53; // default page
            $post->date = $request->date;
            if (isset($request->order))
                $post->order     = $request->order;
            else {
                $maxOrder = Post::where('page_id', 53)->where('active', true)->max('order');
                $post->order = $maxOrder + 1;
            }
            $post->order = $request->order ?? 1;
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
            $postDetail->order     = $request->order ?? 1;
            $postDetail->active    = $request->active ?? true;
            $postDetail->save();


            $videoLink = $request->videoLink;
            $videoID = $request->videoLink;
            $youtubePattern = '/^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:.+)?$/';
            // Vimeo pattern
            $vimeoPattern = '/^(?:https?:\/\/)?(?:www\.)?vimeo\.com\/(\d+)$/';
            $link = null;
            $id = null;

            // Check for a YouTube video
            if (preg_match($youtubePattern, $videoLink, $matches)) {
                $videoID = $matches[1];
                $videoThumbnail = "https://img.youtube.com/vi/{$videoID}/hqdefault.jpg";
            }

            // https://img.youtube.com/vi/6LI2GvW15NM/hqdefault.jpg
            $media = new Media();
            $media->media_type_id  = 1;
            $media->thumbnailpath  = $videoThumbnail;      // store relative path
            $media->filepath       = $videoID;   // store relative path
            $media->link           = $request->videoLink ?? null;
            $media->media_able_id  = $post->id;
            $media->media_able_type = Post::class;
            $media->save();
            DB::commit();

            return redirect()->route('videos.index', app()->getLocale())
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
            $categories = Category::where('type' ,53)
                ->get();
            // $categories = Category::whereHas('postDetail', function ($query) {
            //     $query->whereHas('post', function ($q) {
            //         $q->where('page_id', 53);
            //         });
            //     })
            //     ->select('id', 'name', 'name_en')
            //     ->distinct()
            //     ->get();
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
        return view('admin-panel.media-center.videos.edit', compact('categories', 'post'));
    }

    public function update(Request $request, $locale, int $id)
    {
        $request->validate(
            [
                'title'      => 'required',
                'title_en'   => 'required',
                'videoLink'    => 'required|string|unique:media,filepath',
                'date'       => 'required|date',
            ],
            [
                'title.required'      => __('adminlte::adminlte.title_required'),
                'title_en.required'   => __('adminlte::adminlte.title_en_required'),
                'videoLink.required'    => __('adminlte::adminlte.videoLink_required'),
                'videoLink.unique'      => __('adminlte::adminlte.videoLink_unique'),
                'date.required'       => __('adminlte::adminlte.date_required'),
            ]
        );


        try {
            $post = Post::findOrFail($id);
            DB::beginTransaction();
            // dd($post);
            $post->category_id = $request->category_id;
            $post->page_id = 53; // default page
            $post->date = $request->date;
            $post->save();

            // 2. Create PostDetail
            $postDetail = PostDetail::where('post_id', $post->id)->firstOrFail();
            $postDetail->category_id = $request->category_id;
            $postDetail->title = $request->title;
            $postDetail->title_en  = $request->title_en;
            $postDetail->content   = $request->content_ar;
            $postDetail->content_en = $request->content_en;
            $postDetail->save();


            $videoLink = $request->videoLink;
            $videoID = $request->videoLink;
            $youtubePattern = '/^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:.+)?$/';
            // Vimeo pattern
            $vimeoPattern = '/^(?:https?:\/\/)?(?:www\.)?vimeo\.com\/(\d+)$/';
            $link = null;
            $id = null;

            // Check for a YouTube video
            if (preg_match($youtubePattern, $videoLink, $matches)) {
                $videoID = $matches[1];
                $videoThumbnail = "https://img.youtube.com/vi/{$videoID}/hqdefault.jpg";
            }
            // https://img.youtube.com/vi/6LI2GvW15NM/hqdefault.jpg
            // https://img.youtube.com/vi/6LI2GvW15NM/hqdefault.jpg
            $media = Media::where('media_able_id', $post->id)->firstOrFail();
            $media->thumbnailpath  = $videoThumbnail;      // store relative path
            $media->filepath       = $videoID;   // store relative path
            $media->link           = $request->videoLink ?? null;
            $media->media_able_id  = $post->id;
            $media->media_able_type = Post::class;
            $media->save();
            DB::commit();

            return redirect()->route('videos.index', app()->getLocale())
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
            // 2. Delete all related Media records and their files
            // $media = Media::where('imageable_id', $id)->firstOrFail();
            $post->media()->delete();
            // 3. Delete the parent post
            $post->delete();

            DB::commit();

            return redirect()->route('videos.index', app()->getLocale())->with(['success' => __('adminlte::adminlte.succDelete')]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }
}
