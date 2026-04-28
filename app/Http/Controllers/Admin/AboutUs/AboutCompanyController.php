<?php

namespace App\Http\Controllers\Admin\AboutUs;

use App\Models\Page;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostDetail;
use App\Models\Media;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AboutCompanyController extends Controller
{
    public $pageId = 21;
    public $route = 'about-company';
    public $view = 'admin-panel.about-us.about-company';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $posts = Post::where('page_id', $this->pageId)->orderby('order')->get();
            $page = Page::findOrFail($this->pageId);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
        return view("$this->view.index", compact('posts', 'page'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try{
        $path = base_path('vendor/blade-ui-kit/blade-heroicons/resources/svg');
            $files = File::allFiles($path);

            $icons = [];
            foreach ($files as $file) {
                // Get filename only: o-shield-check.svg
                $filename = $file->getFilename();

                // Remove .svg extension
                $name = str_replace('.svg', '', $filename);

                // Blade UI name requires heroicon- prefix
                $icons[] = [
                    'value' => 'heroicon-' . $name, // e.g., heroicon-o-shield-check
                    'label' => $name,               // for display: o-shield-check
                ];
            }

            // Optional: sort alphabetically
            usort($icons, fn($a, $b) => strcmp($a['label'], $b['label']));

        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
        return view("$this->view.create", compact( 'icons'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'title'      => 'required',
                'title_en'   => 'required',
                'icon'    => 'required|string',
                'content_ar'    => 'required',
                'content_en' => 'required',
            ],
            [
                'title.required'      => __('adminlte::adminlte.title_required'),
                'title_en.required'   => __('adminlte::adminlte.title_en_required'),
                'icon.required'    => __('adminlte::adminlte.videoLink_required'),
                'content_ar.required'    => __('adminlte::adminlte.content_required'),
                'content_en.required' => __('adminlte::adminlte.content_en_required'),
            ]
        );

        try {
            DB::beginTransaction();
            // 1. Create Post
            $post = new Post();
            // $post->category_id = $request->category_id;
            $post->page_id = $this->pageId; // default page
            // $post->date = $request->date;
            if (isset($request->order))
                $post->order     = $request->order;
            else {
                $maxOrder = Post::where('page_id', $this->pageId)->where('active', true)->max('order');
                $post->order = $maxOrder + 1;
            }
            $post->active = true;
            $post->save();

            // 2. Create PostDetail
            $postDetail = new PostDetail();
            $postDetail->post_id   = $post->id;
            $postDetail->category_id = 1;
            $postDetail->title = $request->title;
            $postDetail->title_en  = $request->title_en;
            $postDetail->content   = $request->content_ar;
            $postDetail->content_en = $request->content_en;
            $postDetail->order     = $request->order ?? 1;
            $postDetail->active    = $request->active ?? true;
            $postDetail->color    = $request->icon;
            $postDetail->save();

            DB::commit();

            return redirect()->route("$this->route.index", app()->getLocale())
                ->with(['success' => __('adminlte::adminlte.succCreate')]);
        } catch (\Exception $e) {
            DB::rollBack();
            // return redirect()->back()->withErrors(['error' => $e->getMessage()]);
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($locale, string $id)
    {
        try {
            $post = Post::findOrFail($id);

            $path = base_path('vendor/blade-ui-kit/blade-heroicons/resources/svg');
            $files = File::allFiles($path);

            $icons = [];
            foreach ($files as $file) {
                // Get filename only: o-shield-check.svg
                $filename = $file->getFilename();

                // Remove .svg extension
                $name = str_replace('.svg', '', $filename);

                // Blade UI name requires heroicon- prefix
                $icons[] = [
                    'value' => 'heroicon-' . $name, // e.g., heroicon-o-shield-check
                    'label' => $name,               // for display: o-shield-check
                ];
            }

            // Optional: sort alphabetically
            usort($icons, fn($a, $b) => strcmp($a['label'], $b['label']));

        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
        return view("$this->view.edit", compact('post' , 'icons'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $locale, string $id)
    {
        $iconSet = isset($request->icon);
        if ($iconSet) {
            $request->validate(
                [
                    'title'      => 'required',
                    'title_en'   => 'required',
                    'icon'    => 'required|string',
                    'content_ar'    => 'required',
                    'content_en' => 'required',
                ],
                [
                    'title.required'      => __('adminlte::adminlte.title_required'),
                    'title_en.required'   => __('adminlte::adminlte.title_en_required'),
                    'icon.required'    => __('adminlte::adminlte.videoLink_required'),
                    'content_ar.required'    => __('adminlte::adminlte.content_required'),
                    'content_en.required' => __('adminlte::adminlte.content_en_required'),
                ]
            );
        } else {
            $request->validate(
                [
                    'title'      => 'required',
                    'title_en'   => 'required',
                    'content_ar'    => 'required',
                    'content_en' => 'required',
                ],
                [
                    'title.required'      => __('adminlte::adminlte.title_required'),
                    'title_en.required'   => __('adminlte::adminlte.title_en_required'),
                    'content_ar.required'    => __('adminlte::adminlte.content_required'),
                    'content_en.required' => __('adminlte::adminlte.content_en_required'),
                ]
            );
        }

        try {
            DB::beginTransaction();
            // 1. Create Post
            $post = Post::findOrFail($id);
            // $post->order = $post->order;
            $post->active = true;
            $post->save();
            // 2. Create PostDetail
            $postDetail = PostDetail::where('post_id', $post->id)->firstOrFail();
            // $postDetail->post_id   = $post->id;
            $postDetail->title = $request->title;
            $postDetail->title_en  = $request->title_en;
            $postDetail->content   = $request->content_ar;
            $postDetail->content_en = $request->content_en;
            $postDetail->color     = $request->icon;
            $postDetail->order     = $request->order ?? 1;
            $postDetail->active    = $request->active ?? true;
            $postDetail->save();

            DB::commit();

            return redirect()->route("$this->route.index", app()->getLocale())
                ->with(['success' => __('adminlte::adminlte.succEdit')]);
        } catch (\Exception $e) {
            DB::rollBack();
            // return redirect()->back()->withErrors(['error' => $e->getMessage()]);
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($locae , string $id)
    {
        try {
            DB::beginTransaction();
            $post = Post::findOrFail($id);
            // 1. Delete all related PostDetail records first
            $post->postDetail()->delete();
            // 3. Delete the parent post
            $post->delete();

            DB::commit();

            return redirect()->route("$this->route.index", app()->getLocale())->with(['success' => __('adminlte::adminlte.succDelete')]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }
}
