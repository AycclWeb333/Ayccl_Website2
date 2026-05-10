<?php

namespace App\Http\Controllers\Admin\SalesAndMarketing;

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
class ProductsController extends Controller
{
    public $pageId = 32;
     public $route = 'products';
     public $view = 'admin-panel.sales-and-marketing.products';

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
        $fileVal = $this->getFileValidation();
        $request->validate(
            array_merge($fileVal['rules'], [
                'title'      => 'required',
                'title_en'   => 'required',
                'content_ar' => 'required',
                'content_en' => 'required',
                'files'      => 'required', // Specific requirement for store
            ]),
            array_merge($fileVal['messages'], [
                'title.required'      => __('adminlte::adminlte.title_required'),
                'title_en.required'   => __('adminlte::adminlte.title_en_required'),
                'content_ar.required' => __('adminlte::adminlte.content_required'),
                'content_en.required' => __('adminlte::adminlte.content_en_required'),
                'files.required'      => __('adminlte::adminlte.files_required'),
            ])
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
            $thumbRel    = null;
            $originalRel = null;
            $fileName    = null;
            $pdfPath     = null;

            if (!$request->hasFile('files')) {
               throw new \Exception(__('adminlte::adminlte.files_required'));
           }
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

                    // Upload to DigitalOcean Spaces
                    $doDisk = Storage::disk('do');
                    $doDisk->put($originalRel, file_get_contents($absoluteOriginal), 'public');
                    if (file_exists($absoluteThumb)) {
                        $doDisk->put($thumbRel, file_get_contents($absoluteThumb), 'public');
                    }

                    // Get Full Cloud URLs
                    $originalRel = $doDisk->url($originalRel);
                    $thumbRel = $doDisk->url($thumbRel);

                    // Clean up local temp files
                    unlink($absoluteOriginal);
                    if (file_exists($absoluteThumb)) {
                        unlink($absoluteThumb);
                    }
                }

            }

            // saving pdf files
            if ($request->hasFile('files_pdf')) {
                $filearr = $request->file('files_pdf');
                $file = $filearr[0];
                // 1) Get the original file name from the UploadedFile object
                $originalFileName = Media::getAlt($file->getClientOriginalName());

                // 2) Define paths based on your requirements
                $pdfPath = "files/$this->route/{$post->id}/{$originalFileName}";
                $destinationPath = public_path("files/$this->route/{$post->id}");

                // 3) Create the directory if it doesn't exist
                File::makeDirectory($destinationPath, 0755, true, true);

                // 4) Move the file to the correct location using its original name
                $absolutePdf = $destinationPath . '/' . $originalFileName;
                $file->move($destinationPath, $originalFileName);

                // 5) Upload to DigitalOcean
                $doDisk = Storage::disk('do');
                $doDisk->put($pdfPath, file_get_contents($absolutePdf), 'public');
                $pdfPath = $doDisk->url($pdfPath);

                // Clean up local file
                unlink($absolutePdf);
            }
            // 6) Save DB record
            $media                 = new Media();
            $media->media_type_id  = 1;
            $media->thumbnailpath  = $thumbRel;      // store relative path
            $media->filepath       = $originalRel;   // store relative path
            $media->alt            = $fileName;
            $media->setAltEnAttribute($fileName);
            $media->link           = $pdfPath ;
            $media->media_able_id  = $post->id;
            $media->media_able_type = Post::class;
            $media->save();
            // Commit after processing all files
            DB::commit();

            return redirect()->route("$this->route.index", app()->getLocale())
                ->with(['success' => __('adminlte::adminlte.succCreate')]);
        } catch (\Exception $e) {
            DB::rollBack();
            // return redirect()->back()->withErrors(['error' => $e->getMessage()]);
            return redirect()->back()->with(['error' => $e->getMessage()])->withInput();
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
        $fileVal = $this->getFileValidation();
        
        // Custom logic: If no image exists in DB, make 'files' required
        $post = Post::find($id);
        $hasImageInDb = false;
        if ($post) {
            foreach ($post->media as $m) {
                if ($m->filepath && in_array(strtolower(pathinfo($m->filepath, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    $hasImageInDb = true;
                    break;
                }
            }
        }

        if (!$hasImageInDb) {
            $fileVal['rules']['files'] = 'required';
            $fileVal['messages']['files.required'] = __('adminlte::adminlte.files_required');
        }

        $request->validate(
            array_merge([
                'title'      => 'required',
                'title_en'   => 'required',
                'content_ar' => 'required',
                'content_en' => 'required',
            ], $fileVal['rules']),
            array_merge([
                'title.required'      => __('adminlte::adminlte.title_required'),
                'title_en.required'   => __('adminlte::adminlte.title_en_required'),
                'content_ar.required' => __('adminlte::adminlte.content_required'),
                'content_en.required' => __('adminlte::adminlte.content_en_required'),
            ], $fileVal['messages'])
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
            // $postDetail->order     = $postDetail->order ?? 1;
            $postDetail->active    = $postDetail->active ?? true;
            $postDetail->save();

            // Force Spatie/Image to use GD instead of Imagick
            $oldMediaId = $post->mediaOne->id ?? null;
            $media = Media::findOrNew($oldMediaId);

            // Initialize variables with existing values to avoid "undefined variable" if no new files are uploaded
            $thumbRel    = $media->thumbnailpath;
            $originalRel = $media->filepath;
            $fileName    = $media->alt;
            $pdfPath     = $media->link;

            // Handle fallback where PDF is in filepath (due to granular deletion)
            if (!$pdfPath && $originalRel && strtolower(pathinfo($originalRel, PATHINFO_EXTENSION)) == 'pdf') {
                $pdfPath = $originalRel;
                $originalRel = null;
                $thumbRel = null;
            }

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

                    // 5) Create thumbnail with GD
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

                        // Fit & crop center to target
                        $targetW = 500;
                        $targetH = 500;
                        $scale   = max($targetW / $srcW, $targetH / $srcH);
                        $newW    = (int) round($srcW * $scale);
                        $newH    = (int) round($srcH * $scale);

                        $resized = imagecreatetruecolor($newW, $newH);

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

                    // Upload to DigitalOcean Spaces
                    $doDisk = Storage::disk('do');
                    $doDisk->put($originalRel, file_get_contents($absoluteOriginal), 'public');
                    if (file_exists($absoluteThumb)) {
                        $doDisk->put($thumbRel, file_get_contents($absoluteThumb), 'public');
                    }

                    // Get Full Cloud URLs
                    $originalRel = $doDisk->url($originalRel);
                    $thumbRel = $doDisk->url($thumbRel);

                    // Clean up local temp files
                    unlink($absoluteOriginal);
                    if (file_exists($absoluteThumb)) {
                        unlink($absoluteThumb);
                    }

                    // 6) Delete old files from Cloud if replacing
                    if($oldMediaId){
                        // Helper to extract path from Cloud URL
                        $extractPath = function($url) {
                            $parsed = parse_url($url, PHP_URL_PATH);
                            return ltrim($parsed, '/');
                        };

                        if ($media->filepath && $media->filepath != $pdfPath) {
                            $oldImgPath = $extractPath($media->filepath);
                            if($doDisk->exists($oldImgPath)) $doDisk->delete($oldImgPath);
                        }
                        if ($media->thumbnailpath) {
                            $oldThumbPath = $extractPath($media->thumbnailpath);
                            if($doDisk->exists($oldThumbPath)) $doDisk->delete($oldThumbPath);
                        }
                    }
                }
            }

            if ($request->hasFile('files_pdf')) {
                $filearr = $request->file('files_pdf');
                $file = $filearr[0];
                $originalFileName = Media::getAlt($file->getClientOriginalName());

                $pdfPath = "files/$this->route/{$post->id}/{$originalFileName}";
                $destinationPath = public_path("files/$this->route/{$post->id}");

                $doDisk = Storage::disk('do');

                if($oldMediaId){
                    if ($media->link) {
                        $oldPdfPath = ltrim(parse_url($media->link, PHP_URL_PATH), '/');
                        if($doDisk->exists($oldPdfPath)) $doDisk->delete($oldPdfPath);
                    }
                }
                File::makeDirectory($destinationPath, 0755, true, true);
                
                $absolutePdf = $destinationPath . '/' . $originalFileName;
                $file->move($destinationPath, $originalFileName);

                // Upload to DigitalOcean
                $doDisk->put($pdfPath, file_get_contents($absolutePdf), 'public');
                $pdfPath = $doDisk->url($pdfPath);

                // Clean up local file
                unlink($absolutePdf);
            }

            $hasImage = false;
            foreach ($post->media as $m) {
                if ($m->filepath && in_array(strtolower(pathinfo($m->filepath, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    $hasImage = true;
                    break;
                }
            }
            if ($request->hasFile('files')) {
                $hasImage = true;
            }

            if (!$hasImage) {
                throw new \Exception(__('adminlte::adminlte.files_required'));
            }

            $media->media_type_id  = 1;
            $media->thumbnailpath  = $thumbRel;
            $media->filepath       = $originalRel;
            $media->alt            = $fileName;
            $media->setAltEnAttribute($fileName);
            $media->link           = $pdfPath;
            $media->media_able_id  = $post->id;
            $media->media_able_type = Post::class;
            $media->save();
            // Commit after processing all files
            DB::commit();

            return redirect()->route("$this->route.index", app()->getLocale())
                ->with(['success' => __('adminlte::adminlte.succEdit')]);
        } catch (\Exception $e) {
            DB::rollBack();
            // return redirect()->back()->withErrors(['error' => $e->getMessage()]);
            return redirect()->back()->with(['error' => $e->getMessage()])->withInput();
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
            // 2. Delete all related Media records and their files from Cloud
            $doDisk = Storage::disk('do');
            $extractPath = function($url) {
                return ltrim(parse_url($url, PHP_URL_PATH), '/');
            };

            $post->media->each(function (Media $media) use ($doDisk, $extractPath) {
                if ($media->filepath) {
                    $path = $extractPath($media->filepath);
                    if ($doDisk->exists($path)) $doDisk->delete($path);
                }
                if ($media->thumbnailpath) {
                    $path = $extractPath($media->thumbnailpath);
                    if ($doDisk->exists($path)) $doDisk->delete($path);
                }
                if ($media->link) {
                    $path = $extractPath($media->link);
                    if ($doDisk->exists($path)) $doDisk->delete($path);
                }
                $media->delete();
            });
            // 3. Delete the parent post
            $post->delete();

            // 4. Delete local temp directories if they exist
            $imageDir = public_path("images/$this->route/{$id}");
            $fileDir  = public_path("files/$this->route/{$id}");

            if (File::isDirectory($imageDir)) File::deleteDirectory($imageDir);
            if (File::isDirectory($fileDir)) File::deleteDirectory($fileDir);

            DB::commit();

            return redirect()->route("$this->route.index", app()->getLocale())->with(['success' => __('adminlte::adminlte.succDelete')]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['error' => $e->getMessage()])->withInput();
        }
    }
}
