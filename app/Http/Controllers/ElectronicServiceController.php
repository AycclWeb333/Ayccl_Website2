<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Post;
use Exception;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isNull;

class ElectronicServiceController extends Controller
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
    private $pageId = 10;
     private $path = "landingPage.electronic-services.";
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

     public function jobApplicationIndex()
    {
        try{
            $pageId = 101;
            $page = Page::findOrFail($pageId);
            $posts = Post::where("page_id", $page->id)->where('active',true)->with(['postDetailOne', 'mediaOne'])->get();
        }catch(Exception $e){
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
         return view($this->path."job-application", compact('posts', 'page'));
    }

    public function askForVisitIndex ()
    {
        try{
            $pageId = 102;
            $page = Page::findOrFail($pageId);
            $posts = Post::where("page_id", $page->id)->where('active',true)->with(['postDetail', 'mediaOne'])->get();
        }catch(Exception $e){
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
         return view($this->path."ask-visit", compact('posts', 'page'));
    }
    public function askForTrainingIndex ()
    {
        try{
            $pageId = 103;
            $page = Page::findOrFail($pageId);
            $posts = Post::where("page_id", $page->id)->where('active',true)->with(['postDetail', 'mediaOne'])->get();
        }catch(Exception $e){
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
         return view($this->path."ask-training", compact('posts', 'page'));
    }
}
