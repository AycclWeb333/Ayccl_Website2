<?php

namespace App\Providers;

use App\Models\Post;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
      /*  $address = Post::where("page_id", 7)
                        ->where('order', 1)
                        ->where('active', true)
                        ->with(relations: "postDetailOne")->first();

        $socialLinks = Post::where("page_id", 8)
                        ->where('category_id', operator: 16)
                        ->where('active', true)
                        ->with("mediaOne")->get();

        $systems = Post::where("page_id", 8)
                        ->where('category_id', operator: 14)
                        ->where('active', true)
                        ->with("mediaOne","postDetailOne")->get();

        $floatingButtons = Post::where("page_id", 8)
                        ->where('category_id', operator: 15)
                        ->where('active', true)
                        ->with("mediaOne","postDetailOne")->get();

        $socialMediaPages = Post::where("page_id", 8)
                        ->where('category_id', operator: 13)
                        ->where('active', true)
                        ->with("mediaOne","postDetailOne")->get();

        View::composer('daisyUI.footer', function ($view) use ($address) {
            $view->with('address', $address);
        });
        View::composer('daisyUI.footer', function ($view) use ($socialLinks) {
            $view->with('socialLinks', $socialLinks);
        });
        // View::composer('daisyUI.navbar-upper', function ($view) use ($loginicons) {
        //     $view->with('loginicons', $loginicons);
        // });
        View::composer('daisyUI.navbar-upper-2', function ($view) use ($systems ) {
            $view->with('systems', $systems );
        });
        View::composer('daisyUI.floating-button', function ($view) use ($floatingButtons ) {
            $view->with('floatingButtons', $floatingButtons );
        });
        View::composer('daisyUI.social-media', function ($view) use ($socialMediaPages ) {
            $view->with('socialMediaPages', $socialMediaPages );
        });
        */

        if (!app()->runningInConsole()) {

        View::composer(['daisyUI.footer', 'daisyUI.navbar-upper-2', 'daisyUI.floating-button', 'daisyUI.social-media'], function ($view) {

            // انقل الاستعلامات إلى هنا داخل الـ closure
            // لكي لا يتم استدعاء قاعدة البيانات إلا عند عرض الصفحة فعلياً
            $address = Post::where("page_id", 7)->where('order', 1)->where('active', true)->with("postDetailOne")->first();
            $socialLinks = Post::where("page_id", 8)->where('category_id', 16)->where('active', true)->with("mediaOne")->get();
            $systems = Post::where("page_id", 8)->where('category_id', 14)->where('active', true)->with("mediaOne","postDetailOne")->get();
            $floatingButtons = Post::where("page_id", 8)->where('category_id', 15)->where('active', true)->with("mediaOne","postDetailOne")->get();
            $socialMediaPages = Post::where("page_id", 8)->where('category_id', 13)->where('active', true)->orderBy('order')->with("mediaOne","postDetailOne")->get();

            $view->with([
                'address' => $address,
                'socialLinks' => $socialLinks,
                'systems' => $systems,
                'floatingButtons' => $floatingButtons,
                'socialMediaPages' => $socialMediaPages
            ]);
        });
    }
    }
}
