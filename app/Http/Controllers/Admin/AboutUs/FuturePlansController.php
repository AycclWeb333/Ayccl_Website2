<?php

namespace App\Http\Controllers\Admin\AboutUs;

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

class FuturePlansController extends Controller
{
     public $pageId = 24;
     public $route = 'future-plans';
     public $view = 'admin-panel.about-us.future-plans';

    public function index()
    {
        try{
            $posts = Post::where('page_id', $this->pageId)->get();
            $page = Page::findOrFail($this->pageId);
        }
        catch(\Exception $e){
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
        return view("$this->view.index", compact('posts', 'page'));
    }
    public function create()
    {
        try{
            $categories = Category::whereHas('postDetail', function ($query) {
                $query->whereHas('post', function ($q) {
                    $q->where('page_id', $this->pageId);
                });
            })
            ->select('id', 'name','name_en')
            ->distinct()
            ->get();
        }
        catch(\Exception $e){
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
        return view("$this->view.create", compact( 'categories'));
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
                'content_ar'    => 'required',
                'content_en' => 'required',
                'files'      => 'required',
            ],
            [
                'title.required'      => __('adminlte::adminlte.title_required'),
                'title_en.required'   => __('adminlte::adminlte.title_en_required'),
                'content_ar.required'    => __('adminlte::adminlte.content_required'),
                'content_en.required' => __('adminlte::adminlte.content_en_required'),
                'files.required'      => __('adminlte::adminlte.files_required'),
            ]
        );


        try {
            DB::beginTransaction();
            // 1. Create Post
            $post = new Post();
            $post->category_id = 1;
            $post->page_id = $this->pageId; // default page
            if (isset($request->order))
                $post->order     = $request->order;
            else {
                $maxOrder = Post::where('page_id', $this->pageId)->where('active', true)->max('order');
                $post->order = $maxOrder +1;
            }
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
            $postDetail->color     = $request->color;
            $postDetail->order     = $request->order ?? 1;
            $postDetail->active    = $request->active ?? true;
            $postDetail->save();

            // Force Spatie/Image to use GD instead of Imagick

            // 3) Upload Media (if provided)
            if ($request->hasFile('files')) {
                $files = is_array($request->file('files')) ? $request->file('files') : [$request->file('files')];

                foreach ($files as $file) {
                    // 1) Names & paths
                    $fileName      = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $ext           = strtolower($file->getClientOriginalExtension());
                    $uniqueName    = "{$fileName}-" . time() . ".{$ext}";

                    $originalRel   = "images/$this->route/{$post->id}/{$uniqueName}";
                    $thumbRel      = "images/$this->route/{$post->id}/thumbnails/{$uniqueName}";

                    $originalDir   = public_path("images/$this->route/{$post->id}");
                    $thumbDir      = public_path("images/$this->route/{$post->id}/thumbnails");

                    // 2) Ensure directories exist
                    File::makeDirectory($originalDir, 0755, true, true);
                    File::makeDirectory($thumbDir, 0755, true, true);

                    // 3) Move original file to /public (no Imagick)
                    $absoluteOriginal = public_path($originalRel);
                    $file->move($originalDir, $uniqueName);

                    // 4) (Optional) Optimize original with spatie/image-optimizer
                    try {
                        if (class_exists(\Spatie\ImageOptimizer\OptimizerChainFactory::class)) {
                            $optimizerChain = OptimizerChainFactory::create();
                            $optimizerChain->optimize($absoluteOriginal);
                        }
                    } catch (\Throwable $e) {
                        dd($e);
                        // swallow optimization errors (missing binaries, etc.)
                    }

                    // 5) Create 200x200 thumbnail with GD
                    $absoluteThumb = public_path($thumbRel);

                    // Load source via GD
                    $src = match ($ext) {
                        'jpg', 'jpeg' => imagecreatefromjpeg($absoluteOriginal),
                        'png'        => imagecreatefrompng($absoluteOriginal),
                        'gif'        => imagecreatefromgif($absoluteOriginal),
                        'webp'       => function_exists('imagecreatefromwebp') ? imagecreatefromwebp($absoluteOriginal) : null,
                        default      => null,
                    };

                    if ($src) {
                        $srcW = imagesx($src);
                        $srcH = imagesy($src);

                        // Fit & crop center to 200x200
                        $targetW = 300;
                        $targetH = 300;
                        $scale   = max($targetW / $srcW, $targetH / $srcH);
                        $newW    = (int) round($srcW * $scale);
                        $newH    = (int) round($srcH * $scale);

                        $resized = imagecreatetruecolor($newW, $newH);

                        // Preserve transparency for PNG/WebP
                        if (in_array($ext, ['png', 'webp'])) {
                            imagealphablending($resized, false);
                            imagesavealpha($resized, true);
                            $transparent = imagecolorallocatealpha($resized, 0, 0, 0, 127);
                            imagefilledrectangle($resized, 0, 0, $newW, $newH, $transparent);
                        }

                        imagecopyresampled($resized, $src, 0, 0, 0, 0, $newW, $newH, $srcW, $srcH);

                        $thumb = imagecreatetruecolor($targetW, $targetH);

                        if (in_array($ext, ['png', 'webp'])) {
                            imagealphablending($thumb, false);
                            imagesavealpha($thumb, true);
                            $transparent = imagecolorallocatealpha($thumb, 0, 0, 0, 127);
                            imagefilledrectangle($thumb, 0, 0, $targetW, $targetH, $transparent);
                        }

                        $offsetX = (int) floor(($newW - $targetW) / 2);
                        $offsetY = (int) floor(($newH - $targetH) / 2);
                        imagecopy($thumb, $resized, 0, 0, $offsetX, $offsetY, $targetW, $targetH);

                        // Save thumbnail
                        match ($ext) {
                            'jpg', 'jpeg' => imagejpeg($thumb, $absoluteThumb, 90),
                            'png'        => imagepng($thumb, $absoluteThumb, 9),
                            'gif'        => imagegif($thumb, $absoluteThumb),
                            'webp'       => function_exists('imagewebp') ? imagewebp($thumb, $absoluteThumb, 90) : null,
                            default      => null,
                        };

                        imagedestroy($thumb);
                        imagedestroy($resized);
                        imagedestroy($src);
                    }

                    // 6) Save DB record
                    $media                 = new Media();
                    $media->media_type_id  = 1;
                    $media->thumbnailpath  = $thumbRel;      // store relative path
                    $media->filepath       = $originalRel;   // store relative path
                    $media->alt            = $fileName;
                    $media->setAltEnAttribute($fileName);
                    $media->link           = $request->link ?? null;
                    $media->media_able_id  = $post->id;
                    $media->media_able_type = Post::class;
                    $media->save();
                }

                // Commit after processing all files (do NOT commit inside the loop)
                DB::commit();
            }

            return redirect()->route("$this->route.index", app()->getLocale())
                ->with(['success' => __('adminlte::adminlte.succCreate')]);
        } catch (\Exception $e) {
            DB::rollBack();
            // return redirect()->back()->withErrors(['error' => $e->getMessage()]);
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($locale , int $id)
    {
        try{
            $post = Post::findOrFail($id);
        }
        catch(\Exception $e){
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
        return view("$this->view.edit", compact(  'post'));
    }

    /**
     * Update the specified resource in storage.
     */
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
                'content_ar'    => 'required',
                'content_en' => 'required',
                // 'files'      => 'required',
            ],
            [
                'title.required'      => __('adminlte::adminlte.title_required'),
                'title_en.required'   => __('adminlte::adminlte.title_en_required'),
                // 'slug.required'       => __('adminlte::adminlte.slug_required'),
                // 'slug.unique'         => __('adminlte::adminlte.slug_unique'),
                // 'slug_en.required'    => __('adminlte::adminlte.slug_en_required'),
                // 'slug_en.unique'      => __('adminlte::adminlte.slug_en_unique'),
                // 'date.required'       => __('adminlte::adminlte.date_required'),
                'content_ar.required'    => __('adminlte::adminlte.content_required'),
                'content_en.required' => __('adminlte::adminlte.content_en_required'),
                // 'files.required'      => __('adminlte::adminlte.files_required'),
                ]
        );


        try {
            $post = Post::findOrFail($id);
            DB::beginTransaction();
            // dd($post);
            $post->category_id = $request->category_id;
            $post->page_id = $this->pageId; // default page
            // $post->date = $request->date;
            // if (isset($request->order))
            //     $post->order     = $request->order;
            // else {
            //     $maxOrder = Post::where('page_id', $this->pageId)->where('active', true)->max('order');
            //     $post->order = $maxOrder +1;
            // }
            // $post->order = $request->order ?? 1;
            // $post->active = true;
            $post->save();

            // 2. Create PostDetail
            $postDetail = PostDetail::where('post_id' , $post->id)->firstOrFail();
            // $postDetail->post_id   = $post->id;
            $postDetail->category_id = $request->category_id;
            $postDetail->title = $request->title;
            $postDetail->title_en  = $request->title_en;
            // $postDetail->setSlugAttribute($request->slug);
            // $postDetail->setSlugEnAttribute($request->slug_en);
            $postDetail->content   = $request->content_ar;
            $postDetail->content_en = $request->content_en;
            // $postDetail->color     = $request->color?? '';
            $postDetail->order     = $postDetail->order ?? 1;
            $postDetail->active    = $postDetail->active ?? true;
            $postDetail->save();

            // Force Spatie/Image to use GD instead of Imagick

            // 3) Upload Media (if provided)
            if ($request->hasFile('files'))
            {
                $files = is_array($request->file('files')) ? $request->file('files') : [$request->file('files')];

                foreach ($files as $file) {
                    // 1) Names & paths
                    $fileName      = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $ext           = strtolower($file->getClientOriginalExtension());
                    $uniqueName    = "{$fileName}-" . time() . ".{$ext}";

                    $originalRel   = "images/$this->route/{$post->id}/{$uniqueName}";
                    $thumbRel      = "images/$this->route/{$post->id}/thumbnails/{$uniqueName}";

                    $originalDir   = public_path("images/$this->route/{$post->id}");
                    $thumbDir      = public_path("images/$this->route/{$post->id}/thumbnails");

                    // 2) Ensure directories exist
                    File::makeDirectory($originalDir, 0755, true, true);
                    File::makeDirectory($thumbDir, 0755, true, true);

                    // 3) Move original file to /public (no Imagick)
                    $absoluteOriginal = public_path($originalRel);
                    $file->move($originalDir, $uniqueName);

                    // 4) (Optional) Optimize original with spatie/image-optimizer
                    try {
                        if (class_exists(\Spatie\ImageOptimizer\OptimizerChainFactory::class)) {
                            $optimizerChain = OptimizerChainFactory::create();
                            $optimizerChain->optimize($absoluteOriginal);
                        }
                    } catch (\Throwable $e) {
                        // swallow optimization errors (missing binaries, etc.)
                    }

                    // 5) Create 200x200 thumbnail with GD
                    $absoluteThumb = public_path($thumbRel);

                    // Load source via GD
                    $src = match ($ext) {
                        'jpg', 'jpeg' => imagecreatefromjpeg($absoluteOriginal),
                        'png'        => imagecreatefrompng($absoluteOriginal),
                        'gif'        => imagecreatefromgif($absoluteOriginal),
                        'webp'       => function_exists('imagecreatefromwebp') ? imagecreatefromwebp($absoluteOriginal) : null,
                        default      => null,
                    };

                    if ($src) {
                        $srcW = imagesx($src);
                        $srcH = imagesy($src);

                        // Fit & crop center to 200x200
                        $targetW = 500;
                        $targetH = 500;
                        $scale   = max($targetW / $srcW, $targetH / $srcH);
                        $newW    = (int) round($srcW * $scale);
                        $newH    = (int) round($srcH * $scale);

                        $resized = imagecreatetruecolor($newW, $newH);

                        // Preserve transparency for PNG/WebP
                        if (in_array($ext, ['png', 'webp'])) {
                            imagealphablending($resized, false);
                            imagesavealpha($resized, true);
                            $transparent = imagecolorallocatealpha($resized, 0, 0, 0, 127);
                            imagefilledrectangle($resized, 0, 0, $newW, $newH, $transparent);
                        }

                        imagecopyresampled($resized, $src, 0, 0, 0, 0, $newW, $newH, $srcW, $srcH);

                        $thumb = imagecreatetruecolor($targetW, $targetH);

                        if (in_array($ext, ['png', 'webp'])) {
                            imagealphablending($thumb, false);
                            imagesavealpha($thumb, true);
                            $transparent = imagecolorallocatealpha($thumb, 0, 0, 0, 127);
                            imagefilledrectangle($thumb, 0, 0, $targetW, $targetH, $transparent);
                        }

                        $offsetX = (int) floor(($newW - $targetW) / 2);
                        $offsetY = (int) floor(($newH - $targetH) / 2);
                        imagecopy($thumb, $resized, 0, 0, $offsetX, $offsetY, $targetW, $targetH);

                        // Save thumbnail
                        match ($ext) {
                            'jpg', 'jpeg' => imagejpeg($thumb, $absoluteThumb, 90),
                            'png'        => imagepng($thumb, $absoluteThumb, 9),
                            'gif'        => imagegif($thumb, $absoluteThumb),
                            'webp'       => function_exists('imagewebp') ? imagewebp($thumb, $absoluteThumb, 90) : null,
                            default      => null,
                        };

                        imagedestroy($thumb);
                        imagedestroy($resized);
                        imagedestroy($src);
                    }

                    $oldMediaId = $post->mediaOne->id ?? null;
                    $media = Media::findOrNew($oldMediaId);
                    
                    if($oldMediaId){
                        if ($media->filepath && Storage::disk('images')->exists($media->filepath)) {
                            Storage::disk('images')->delete($media->filepath);
                        }
                        if ($media->thumbnailpath && Storage::disk('images')->exists($media->thumbnailpath)) {
                            Storage::disk('images')->delete($media->thumbnailpath);
                        }
                    }
                    $media->media_type_id  = 1;
                    $media->thumbnailpath  = $thumbRel;      // store relative path
                    $media->filepath       = $originalRel;   // store relative path
                    $media->alt            = $fileName;
                    $media->setAltEnAttribute($fileName);
                    $media->link           = $request->link ?? null;
                    $media->media_able_id  = $post->id;
                    $media->media_able_type = Post::class;
                    $media->save();
                }

                // Commit after processing all files (do NOT commit inside the loop)
            }
            $media = Media::where('media_able_id', $post->id)->count();
            if ($media == 0) {
                throw new \Exception(__('adminlte::adminlte.files_required')    );
            }
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
    public function destroy($locale ,string $id)
    {
        try {
            DB::beginTransaction();
            $post = Post::findOrFail($id);

            // 1. Delete all related PostDetail records first
            $post->postDetail()->delete();
            // 2. Delete all related Media records and their files
            $post->media->each(function (Media $media) {
                // Delete the image file from files
                if (Storage::disk('images')->exists($media->filepath)) {
                    Storage::disk('images')->delete($media->filepath);
                }
                // Delete the thumbnail file from storage
                if (Storage::disk('images')->exists($media->thumbnailpath)) {
                    Storage::disk('images')->delete($media->thumbnailpath);
                }
                // Delete the record from the database
                $media->delete();
            });
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
