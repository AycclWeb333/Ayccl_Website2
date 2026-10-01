<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SalesController extends Controller
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
    private $pageId = 3;
     private $path = "landingPage.sales-and-marketing.";
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function hadramiIndex()
    {
         // $pageId = app()->getlocale() =='ar' ? 23 : 123 ;
        
        //  $pageId = 31;
        //  $page = Page::findOrFail($pageId);
        //  $posts = Post::where("page_id", $page->id)->get();
        //  return view($this->path."hadrami", compact('posts', 'page'));
        try{
            $pageId = 31;
            $page = Page::findOrFail($pageId);
            $posts = Post::where("page_id", $page->id)->where('active',true)->latest()->with(['postDetailOne', 'media'])->get();
        }catch(Exception $e){
            return redirect()->back()->with(['error' => $e->getMessage()]);    
        }
        return view($this->path . "hadrami", compact('posts', 'page'));
    }
    
     public function productsIndex()
    {
        try{
            $pageId = 32;
            $page = Page::findOrFail($pageId);
            $posts = Post::where("page_id", $page->id)->where('active',true)->with(['postDetailOne', 'mediaOne'])->get();
        }catch(Exception $e){
            return redirect()->back()->with(['error' => $e->getMessage()]);    
        }
         return view($this->path."products", compact('posts', 'page'));
    }
    public function viewPdf($locale, $filename)
    {
        // Clean the filename and ensure it's safe
        $filename = basename($filename);
        
        // The path should be relative to your public directory
        $path = public_path('files/products/' . $filename);
        
        // Check if file exists
        if (!file_exists($path)) {
            abort(404, 'PDF file not found');
        }
        
        // Return the PDF file with inline content disposition
        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"'
        ]);
    }
    // public function details() {
    //     $details = (object) [
    //         'heroTitle' => 'name',
    //         'deviderTitle' => 'نبذة عن الشركة',
    //         'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quae, voluptatum!',
    //     ];
    //     $product = (object) [
    //         'id' => '3',
    //         'pname' => 'اسمنت حضرموت',
    //         'pimage' => 'images/product3.png',
    //         'description' => '
    //         يعتبر إسمنت حضرموت من أكثر أنواع الإسمنت البورتلاندي شيوعاً واستخداماً ويصنع هذا الإسمنت طبقاً للمواصفات الأوروبية:
    //         <br>
    //         BS EN 197-1:2000 CEM II 42.5N
    //         <br>
    //         ويدخل في جميع الأعمال الخرسانية والخرسانة المسبقة الإجهاد أو الصب والأنابيب الخرسانية، وصالح لجميع أعمال البلوك والبلاط الإسمنتي.
    //         <br>
    //         يتم توفير المنتج بشكل أكياس عبوة 50 كيلو جرام أو على شكل سائب في جميع أنحاء الجمهورية اليمنية. ',
    //         'specs' => ['الخرسانة الجاهزة.', 'القواعد والأساسات.', 'الصبات الخرسانية.', 'الكباري وبناء الأبراج.', 'الطرق الخرسانية.'],
    //         'downloadLink' => '/files/تشطيب.pdf'
    //     ];


    //     return view('landingPage.productdetails', compact('details', 'product'));
    // }


    public function customerserviceIndex ()
    {
        $pageId = 33;
        $page = Page::findOrFail($pageId);
        $posts = Post::where("page_id", $page->id)->with('mediaOne')->get();
        
         return view($this->path."customer-service", compact('posts', 'page'));
    }
    public function suggestionsandcomplaintsIndex ()
    {
        $pageId = 34;
         $page = Page::findOrFail($pageId);
         $posts = Post::where("page_id", $page->id)->with('mediaOne')->get();
        
         return view($this->path."suggestions-complaints", compact('posts', 'page'));
    }
}
