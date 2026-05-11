<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Post;
use Exception;
use Illuminate\Http\Request;

class CementBlogWebController extends Controller
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

    private $pageId =  9;
    private $path = "landingPage.cement-blog.";



    public function index()
    {
        try{
            $pageId = 9;
            $page = Page::findOrFail($pageId);
            $cementBlogs = Post::where("page_id", $page->id)->where('active', true)
            ->with(['postDetailOne', 'mediaOne'])
            ->orderBy('order', 'asc')
            ->get();

        }catch(Exception $e){
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
        return view($this->path . "cement-blog", compact('cementBlogs', 'page'));
    }
    public function show($locale, $id)
    {
        try {
            $post = Post::with(['postDetailOne', 'media'])->findOrFail($id);
            $page = Page::findOrFail($this->pageId);
        } catch (Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
        return view($this->path . "show", compact('post', 'page'));
    }
}
