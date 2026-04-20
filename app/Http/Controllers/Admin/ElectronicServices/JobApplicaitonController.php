<?php

namespace App\Http\Controllers\Admin\ElectronicServices;

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

class JobApplicaitonController extends Controller
{
    public $pageId = 101;
    public $route = 'job-application';
    public $view = 'admin-panel.electronic-services.job-application';
    public function index()
    {
        try{
            $posts = Post::where('page_id', $this->pageId)->get();
        }
        catch(\Exception $e){
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
        return view("$this->view.index", compact('posts'));
    }

    public function show($locale, $id)
    {
        $media = Media::findOrFail($id);

        // Full path to file
        $path = public_path($media->link);

        if (!file_exists($path)) {
            abort(404, 'File not found.');
        }

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . basename($path) . '"'
        ]);
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
                'files'      => 'nullable',
                'link'      => 'nullable',
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
            // Automatically assign category if not provided (now that UI field is removed)
            $catId = $request->category_id;
            if (!$catId) {
                // Get category from any existing post on this page
                $existingPost = Post::where('page_id', $this->pageId)->first();
                $catId = $existingPost ? $existingPost->category_id : 1; 
            }
            $post->category_id = $catId;
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

            // 3) Upload & Save Media (Only if files or PDF are provided)
            if ($request->hasFile('files') || $request->hasFile('files_pdf')) {
                $media = new Media();
                $media->media_type_id  = 1;
                $media->media_able_id  = $post->id;
                $media->media_able_type = Post::class;
                $media->link           = $request->link; // default link if any

                // Handle Image Files
                if ($request->hasFile('files')) {
                    $files = is_array($request->file('files')) ? $request->file('files') : [$request->file('files')];
                    foreach ($files as $file) {
                        $fileName      = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                        $ext           = strtolower($file->getClientOriginalExtension());
                        $uniqueName    = "{$fileName}-" . time() . ".{$ext}";

                        $originalRel   = "images/$this->route/{$post->id}/{$uniqueName}";
                        $thumbRel      = "images/$this->route/{$post->id}/thumbnails/{$uniqueName}";

                        $originalDir   = public_path("images/$this->route/{$post->id}");
                        $thumbDir      = public_path("images/$this->route/{$post->id}/thumbnails");

                        File::makeDirectory($originalDir, 0755, true, true);
                        File::makeDirectory($thumbDir, 0755, true, true);

                        $absoluteOriginal = public_path($originalRel);
                        $file->move($originalDir, $uniqueName);

                        // Thumbnail logic
                        $absoluteThumb = public_path($thumbRel);
                        $src = match ($ext) {
                            'jpg', 'jpeg' => imagecreatefromjpeg($absoluteOriginal),
                            'png'        => imagecreatefrompng($absoluteOriginal),
                            'gif'        => imagecreatefromgif($absoluteOriginal),
                            'webp'       => function_exists('imagecreatefromwebp') ? imagecreatefromwebp($absoluteOriginal) : null,
                            default      => null,
                        };

                        if ($src) {
                            $srcW = imagesx($src); $srcH = imagesy($src);
                            $targetW = 300; $targetH = 300;
                            $scale   = max($targetW / $srcW, $targetH / $srcH);
                            $newW    = (int) round($srcW * $scale); $newH    = (int) round($srcH * $scale);
                            $resized = imagecreatetruecolor($newW, $newH);
                            if (in_array($ext, ['png', 'webp'])) {
                                imagealphablending($resized, false); imagesavealpha($resized, true);
                                imagefilledrectangle($resized, 0, 0, $newW, $newH, imagecolorallocatealpha($resized, 0, 0, 0, 127));
                            }
                            imagecopyresampled($resized, $src, 0, 0, 0, 0, $newW, $newH, $srcW, $srcH);
                            $thumb = imagecreatetruecolor($targetW, $targetH);
                            if (in_array($ext, ['png', 'webp'])) {
                                imagealphablending($thumb, false); imagesavealpha($thumb, true);
                                imagefilledrectangle($thumb, 0, 0, $targetW, $targetH, imagecolorallocatealpha($thumb, 0, 0, 0, 127));
                            }
                            imagecopy($thumb, $resized, 0, 0, (int) floor(($newW - $targetW) / 2), (int) floor(($newH - $targetH) / 2), $targetW, $targetH);
                            match ($ext) {
                                'jpg', 'jpeg' => imagejpeg($thumb, $absoluteThumb, 90),
                                'png'        => imagepng($thumb, $absoluteThumb, 9),
                                'gif'        => imagegif($thumb, $absoluteThumb),
                                'webp'       => function_exists('imagewebp') ? imagewebp($thumb, $absoluteThumb, 90) : null,
                                default      => null,
                            };
                            imagedestroy($thumb); imagedestroy($resized); imagedestroy($src);
                        }

                        $media->thumbnailpath  = $thumbRel;
                        $media->filepath       = $originalRel;
                        $media->alt            = $fileName;
                        $media->setAltEnAttribute($fileName);
                    }
                }

                // Handle PDF File
                if ($request->hasFile('files_pdf')) {
                    $filearr = $request->file('files_pdf');
                    $file = is_array($filearr) ? $filearr[0] : $filearr;
                    $originalFileName = Media::getAlt($file->getClientOriginalName());
                    $pdfPath = "files/$this->route/{$post->id}/{$originalFileName}";
                    $destinationPath = public_path("files/$this->route/{$post->id}");
                    File::makeDirectory($destinationPath, 0755, true, true);
                    $file->move($destinationPath, $originalFileName);
                    $media->link = $pdfPath;
                    
                    // If no image was uploaded, we might want to set some defaults for media
                    if (!$media->filepath) {
                        $media->filepath = $pdfPath;
                        $media->alt = $originalFileName;
                    }
                }

                $media->save();
            }

            // Commit after processing
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
        $request->validate(
            [
                'category_id' => 'required',
                'title'      => 'required',
                'title_en'   => 'required',
                'content_ar'    => 'required',
                'content_en' => 'required',
                'files'      => 'nullable',
                'link'      => 'nullable',
            ],
            [
                'category_id.required'  => __('adminlte::adminlte.category_id_required'),
                'title.required'      => __('adminlte::adminlte.title_required'),
                'title_en.required'   => __('adminlte::adminlte.title_en_required'),
                'content_ar.required'    => __('adminlte::adminlte.content_ar_required'),
                'content_en.required' => __('adminlte::adminlte.content_en_required'),
            ]
        );

        try {
            DB::beginTransaction();

            // 1. Update Post
            $post = Post::findOrFail($id);
            // Automatically assign category if not provided (now that UI field is removed)
            $catId = $request->category_id;
            if (!$catId) {
                // Get category from any existing post on this page, or default to some logic
                $existingPost = Post::where('page_id', $this->pageId)->first();
                $catId = $existingPost ? $existingPost->category_id : 1; 
            }
            $post->category_id = $catId;
            $post->save();

            // 2. Update PostDetail
            $postDetail = PostDetail::where('post_id' , $post->id)->firstOrFail();
            $postDetail->category_id = $request->category_id;
            $postDetail->title = $request->title;
            $postDetail->title_en  = $request->title_en;
            $postDetail->content   = $request->content_ar;
            $postDetail->content_en = $request->content_en;
            $postDetail->active    = $postDetail->active ?? true;
            $postDetail->save();

            // 3. Handle Media Update (Only if files or PDF are provided)
            if ($request->hasFile('files') || $request->hasFile('files_pdf')) {
                $media = Media::findOrNew($post->mediaOne->id ?? null);
                $media->media_type_id  = 1;
                $media->media_able_id  = $post->id;
                $media->media_able_type = Post::class;
                
                if (!$media->exists) {
                    $media->link = $request->link;
                }

                // Handle Image Files
                if ($request->hasFile('files')) {
                    $files = is_array($request->file('files')) ? $request->file('files') : [$request->file('files')];
                    foreach ($files as $file) {
                        $fileName      = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                        $ext           = strtolower($file->getClientOriginalExtension());
                        $uniqueName    = "{$fileName}-" . time() . ".{$ext}";

                        $originalRel   = "images/$this->route/{$post->id}/{$uniqueName}";
                        $thumbRel      = "images/$this->route/{$post->id}/thumbnails/{$uniqueName}";

                        $originalDir   = public_path("images/$this->route/{$post->id}");
                        $thumbDir      = public_path("images/$this->route/{$post->id}/thumbnails");

                        File::makeDirectory($originalDir, 0755, true, true);
                        File::makeDirectory($thumbDir, 0755, true, true);

                        $absoluteOriginal = public_path($originalRel);
                        $file->move($originalDir, $uniqueName);

                        // Thumbnail logic (GD)
                        $absoluteThumb = public_path($thumbRel);
                        $src = match ($ext) {
                            'jpg', 'jpeg' => imagecreatefromjpeg($absoluteOriginal),
                            'png'        => imagecreatefrompng($absoluteOriginal),
                            'gif'        => imagecreatefromgif($absoluteOriginal),
                            'webp'       => function_exists('imagecreatefromwebp') ? imagecreatefromwebp($absoluteOriginal) : null,
                            default      => null,
                        };

                        if ($src) {
                            $srcW = imagesx($src); $srcH = imagesy($src);
                            $targetW = 500; $targetH = 500;
                            $scale   = max($targetW / $srcW, $targetH / $srcH);
                            $newW    = (int) round($srcW * $scale); $newH = (int) round($srcH * $scale);
                            $resized = imagecreatetruecolor($newW, $newH);
                            if (in_array($ext, ['png', 'webp'])) {
                                imagealphablending($resized, false); imagesavealpha($resized, true);
                                imagefilledrectangle($resized, 0, 0, $newW, $newH, imagecolorallocatealpha($resized, 0, 0, 0, 127));
                            }
                            imagecopyresampled($resized, $src, 0, 0, 0, 0, $newW, $newH, $srcW, $srcH);
                            $thumb = imagecreatetruecolor($targetW, $targetH);
                            if (in_array($ext, ['png', 'webp'])) {
                                imagealphablending($thumb, false); imagesavealpha($thumb, true);
                                imagefilledrectangle($thumb, 0, 0, $targetW, $targetH, imagecolorallocatealpha($thumb, 0, 0, 0, 127));
                            }
                            imagecopy($thumb, $resized, 0, 0, (int) floor(($newW - $targetW) / 2), (int) floor(($newH - $targetH) / 2), $targetW, $targetH);
                            match ($ext) {
                                'jpg', 'jpeg' => imagejpeg($thumb, $absoluteThumb, 90),
                                'png'        => imagepng($thumb, $absoluteThumb, 9),
                                'gif'        => imagegif($thumb, $absoluteThumb),
                                'webp'       => function_exists('imagewebp') ? imagewebp($thumb, $absoluteThumb, 90) : null,
                                default      => null,
                            };
                            imagedestroy($thumb); imagedestroy($resized); imagedestroy($src);
                        }

                        // Delete old files
                        if($post->mediaOne != null) {
                            if (Storage::disk('images')->exists($media->filepath)) Storage::disk('images')->delete($media->filepath);
                            if (Storage::disk('images')->exists($media->thumbnailpath)) Storage::disk('images')->delete($media->thumbnailpath);
                        }

                        $media->thumbnailpath  = $thumbRel;
                        $media->filepath       = $originalRel;
                        $media->alt            = $fileName;
                        $media->setAltEnAttribute($fileName);
                    }
                }

                // Handle PDF File
                if ($request->hasFile('files_pdf')) {
                    $filearr = $request->file('files_pdf');
                    $file = is_array($filearr) ? $filearr[0] : $filearr;
                    $originalFileName = Media::getAlt($file->getClientOriginalName());
                    $pdfPath = "files/$this->route/{$post->id}/{$originalFileName}";
                    $destinationPath = public_path("files/$this->route/{$post->id}");

                    if($post->mediaOne != null && $media->link){
                        if (Storage::disk('images')->exists($media->link)) Storage::disk('images')->delete($media->link);
                    }
                    File::makeDirectory($destinationPath, 0755, true, true);
                    $file->move($destinationPath, $originalFileName);
                    
                    $media->link = $pdfPath;
                    if (!$media->filepath) {
                        $media->filepath = $pdfPath;
                        $media->alt = $originalFileName;
                    }
                }
                $media->save();
            }

            DB::commit();
            return redirect()->route("$this->route.index", app()->getLocale())
                ->with(['success' => __('adminlte::adminlte.succEdit')]);
        } catch (\Exception $e) {
            DB::rollBack();
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
