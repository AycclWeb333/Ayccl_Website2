<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use Illuminate\Http\Request;

class MediaCenterController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    private $pageId = 2;
    private $path = "landingPage.media-center.";
    public function newsAndActivitiesIndex()
    {
        $pageId = 51;
        $page = Page::findOrFail($pageId);
        $posts = Post::where("page_id", $page->id)
            ->where('active', true)
            ->with(['postDetail', 'media'])->get();

        // News (7), Activities (2)
        $categories = Category::whereIn('id', [7, 2])->get()
            ->sortBy(function ($category) {
                return array_search($category->id, [7, 2]);
            })->values();

        return view($this->path . "news-activities", compact('posts', 'page', 'categories'));
    }
    public function newsShowIndex($locale, $id, $slug = null)
    {
        $pageId = 51;
        $page = Page::findOrFail($pageId);

        // Fetch post safely using ID only to avoid Arabic slug encoding 404 errors
        $post = Post::where("id", $id)
            ->where("page_id", $pageId)
            ->with(['postDetail', 'media'])
            ->firstOrFail();
            
        return view($this->path . "news-page", compact('post', 'page'));
    }

    public function photosGalaryIndex()
    {
        $pageId = 52;
        $page = Page::findOrFail($pageId);
        $posts = Post::where("page_id", $page->id)->where('active',true)->with(['postDetailOne', 'media'])->get();
        $categories = $posts->pluck('postDetailOne.category_id');
        $categories = Category::whereIn('id', $categories)->get();
        return view($this->path . "photos-galary", compact('posts', 'page', 'categories'));
    }
    public function videosIndex()
    {
        $pageId = 53;
        $page = Page::findOrFail($pageId);

        $posts = Post::where("page_id", $page->id)->where('active', true)->with(['postDetailOne', 'mediaOne'])->get();
        $categories = $posts->pluck('postDetailOne.category_id');
        $categories = Category::whereIn('id', $categories)->get();
        return view($this->path . "videos", compact('posts', 'page', 'categories'));
    }
    public function documentsIndex()
    {
        $pageId = 54;
        $page = Page::findOrFail($pageId);
        $posts = Post::where("page_id", $page->id)->where('active', true)->with(['postDetail', 'mediaOne'])->get();
        return view($this->path . "documents", compact('posts', 'page'));
    }
    public function inspectionCertificatesIndex()
    {
        $pageId = 55;
         $page = Page::findOrFail($pageId);
        $posts = Post::where("page_id", $page->id)
        ->where('active', true)
        ->with(['postDetail', 'media'])->get();
        return view($this->path . "inspection-certificates", compact('posts', 'page'));
    }

    public function specificationsIndex()
    {
        $pageId = 56;
        $page = Page::findOrFail($pageId);
        $posts = Post::where("page_id", $page->id)
        ->where('active', true)
        ->with(['postDetail', 'media'])->get();

        return view($this->path . "specifications", compact('posts', 'page'));
    }

    public function documentsShowIndex($locale, $id, $slug = null)
    {
        return $this->genericShow(54, $id);
    }

    public function inspectionCertificatesShowIndex($locale, $id, $slug = null)
    {
        return $this->genericShow(55, $id);
    }

    public function specificationsShowIndex($locale, $id, $slug = null)
    {
        return $this->genericShow(56, $id);
    }

    private function genericShow($pageId, $id)
    {
        $page = Page::findOrFail($pageId);

        // Fetch post safely using ID only
        $post = Post::where("id", $id)
            ->where("page_id", $pageId)
            ->with(['postDetail', 'media'])
            ->firstOrFail();
            
        return view($this->path . "news-page", compact('post', 'page'));
    }

    public function downloadMedia($locale, $id)
    {
        $media = \App\Models\Media::findOrFail($id);
        
        $path = $media->link ?? $media->filepath;
        
        if (empty($path)) {
            abort(404, 'File path is not defined.');
        }

        $fileName = basename($path);
        
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $contentTypes = [
            'pdf'  => 'application/pdf',
            'png'  => 'image/png',
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif'  => 'image/gif',
            'webp' => 'image/webp',
            'doc'  => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls'  => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ];
        $contentType = $contentTypes[$extension] ?? 'application/octet-stream';

        // Check if the path is a full URL (cloud storage)
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            try {
                set_time_limit(0);
                
                return response()->streamDownload(function () use ($path) {
                    $stream = fopen($path, 'r');
                    if ($stream) {
                        fpassthru($stream);
                        fclose($stream);
                    }
                }, $fileName, [
                    'Content-Type' => $contentType,
                    'Content-Disposition' => 'attachment; filename="' . $fileName . '"'
                ]);
            } catch (\Exception $e) {
                abort(404, 'Could not download remote file.');
            }
        } else {
            // Local file
            $localPath = public_path($path);
            if (!file_exists($localPath)) {
                $localPath = storage_path('app/public/' . $path);
                if (!file_exists($localPath)) {
                    abort(404, 'File not found.');
                }
            }
            
            return response()->download($localPath, $fileName, [
                'Content-Type' => $contentType
            ]);
        }
    }
}