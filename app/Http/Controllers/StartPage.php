<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Post;
use App\View\Components\product;
use Illuminate\Http\Request;


class StartPage extends Controller
{
    public function index(){
        $page= Page::findOrFail(1);

        $contactDetails = Post::where("page_id", 7)->where('active', true)
            ->with(['postDetail' => function ($query) {
                $query->orderBy('order', 'asc'); // descending
            }])
            ->get();
            $map = Post::where("page_id", 8)
                        ->where("category_id", 13)
                        ->where("active",true)
                        ->with('postDetail')
                        ->orderBy('order')
                        ->firstOrFail();

        $slideshows= Post::where('page_id',$page->id)->where('active',true)->with('mediaOne', 'PostDetailOne')->get();
        $isoCertificates= Post::where('page_id',26)->where('active',true)->with('mediaOne', 'PostDetailOne')->get();

        $products = Post::where('page_id',32)->where('active',true)->with('mediaOne', 'PostDetailOne')->get();

        $hadramiProjects = Post::where('page_id', 31)->where('active', true)->with(['postDetailOne', 'media'])->orderBy('created_at', 'desc')->take(6)->get();

        $statistics = \App\Models\Statistic::where('active', true)->orderBy('order')->get();

        return view('welcome', compact('contactDetails','map', 'slideshows', 'isoCertificates', 'products', 'hadramiProjects', 'page', 'statistics'));
    }
}
