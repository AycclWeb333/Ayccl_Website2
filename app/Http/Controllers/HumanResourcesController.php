<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Post;
use Exception;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isNull;

class HumanResourcesController extends Controller
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
    private $pageId = 2;
     private $path = "landingPage.human-resources.";
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function employeesAdvantagesIndex()
    {
         // $pageId = app()->getlocale() =='ar' ? 23 : 123 ;

         try{
            $pageId = 41;
            $page = Page::findOrFail($pageId);
            $posts = Post::where("page_id", $page->id)->where('active',true)->latest()->with(['postDetail', 'mediaOne'])->get();
        }catch(Exception $e){
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
         return view($this->path."employees-advantages", compact('posts', 'page'));
    }

     public function jobApplicationIndex()
    {
        try{
            $pageId = 42;
            $page = Page::findOrFail($pageId);
            $posts = Post::where("page_id", $page->id)->where('active',true)->with(['postDetail', 'mediaOne'])->get();
        }catch(Exception $e){
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
         return view($this->path."job-application", compact('posts', 'page'));
    }

    public function askForVisitIndex ()
    {
        try{
            $pageId = 43;
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
            $pageId = 44;
            $page = Page::findOrFail($pageId);
            $posts = Post::where("page_id", $page->id)->where('active',true)->with(['postDetail', 'mediaOne'])->get();
        }catch(Exception $e){
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
         return view($this->path."ask-training", compact('posts', 'page'));
    }

    public function employeesIndex ()
    {
        try{
            $pageId = 45;
            $page = Page::findOrFail($pageId);
            $posts = Post::where("page_id", $page->id)->where('active',true)->latest()->with(['postDetail', 'mediaOne'])->get();
        }catch(Exception $e){
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
         return view($this->path."employees", compact('posts', 'page'));
    }

    public function ourGuestsIndex ()
    {
        try{
            $pageId = 46;
            $page = Page::findOrFail($pageId);
            $posts = Post::where("page_id", $page->id)->where('active',true)->latest()->with(['postDetail', 'mediaOne'])->get();
        }catch(Exception $e){
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
         return view($this->path."our-guests", compact('posts', 'page'));
    }
}
