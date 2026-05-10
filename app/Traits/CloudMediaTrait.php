<?php

namespace App\Traits;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Spatie\ImageOptimizer\OptimizerChainFactory;

trait CloudMediaTrait
{
    /**
     * حذف جميع الميديا المرتبطة بالموديل، بما في ذلك المجلدات بالكامل من السحابة والمحلي.
     */
    public function deleteCloudMediaDirectory($model, $route, $id)
    {
        $doDisk = Storage::disk('do');
        $extractPath = function ($url) {
            return ltrim(parse_url($url, PHP_URL_PATH), '/');
        };

        // حذف الملفات المرتبطة وتفريغ قاعدة البيانات
        if ($model->media) {
            $model->media->each(function (Media $media) use ($doDisk, $extractPath) {
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
        }

        // حذف المجلدات بالكامل من الاستضافة السحابية
        $doDisk->deleteDirectory("images/{$route}/{$id}");
        $doDisk->deleteDirectory("files/{$route}/{$id}");

        // حذف المجلدات المؤقتة المحلية للتأكيد
        $imageDir = public_path("images/{$route}/{$id}");
        $fileDir  = public_path("files/{$route}/{$id}");
        if (File::isDirectory($imageDir)) File::deleteDirectory($imageDir);
        if (File::isDirectory($fileDir)) File::deleteDirectory($fileDir);
    }

    /**
     * معالجة ورفع صورة واحدة مع التصدغير والضغط
     */
    public function uploadSingleCloudImage($file, $route, $modelId, $targetW = 500, $targetH = 500)
    {
        $doDisk = Storage::disk('do');

        $fileName   = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $ext        = strtolower($file->getClientOriginalExtension());
        $uniqueName = "{$fileName}-" . time() . ".{$ext}";

        $originalRel = "images/{$route}/{$modelId}/{$uniqueName}";
        $thumbRel    = "images/{$route}/{$modelId}/thumbnails/{$uniqueName}";

        $originalDir = public_path("images/{$route}/{$modelId}");
        $thumbDir    = public_path("images/{$route}/{$modelId}/thumbnails");

        File::makeDirectory($originalDir, 0755, true, true);
        File::makeDirectory($thumbDir, 0755, true, true);

        $absoluteOriginal = $originalDir . '/' . $uniqueName;
        $file->move($originalDir, $uniqueName);

        // الضغط
        try {
            if (class_exists(\Spatie\ImageOptimizer\OptimizerChainFactory::class)) {
                $optimizerChain = OptimizerChainFactory::create();
                $optimizerChain->optimize($absoluteOriginal);
            }
        } catch (\Throwable $e) {
        }

        // تصغير الصورة لإنشاء نسخة مصغرة
        $absoluteThumb = $thumbDir . '/' . $uniqueName;
        $this->createThumbnailGD($absoluteOriginal, $absoluteThumb, $ext, $targetW, $targetH);

        // الرفع السحابي
        $doDisk->put($originalRel, file_get_contents($absoluteOriginal), 'public');
        if (file_exists($absoluteThumb)) {
            $doDisk->put($thumbRel, file_get_contents($absoluteThumb), 'public');
        }

        $result = [
            'filepath' => $doDisk->url($originalRel),
            'thumbnailpath' => file_exists($absoluteThumb) ? $doDisk->url($thumbRel) : null,
            'alt' => $fileName,
            'ext' => $ext
        ];

        // التنظيف المحلي
        if (file_exists($absoluteOriginal)) unlink($absoluteOriginal);
        if (file_exists($absoluteThumb)) unlink($absoluteThumb);

        return $result;
    }

    /**
     * رفع ملف PDF واحد
     */
    public function uploadSingleCloudPdf($file, $route, $modelId)
    {
        $doDisk = Storage::disk('do');
        $originalFileName = Media::getAlt($file->getClientOriginalName());

        $pdfPath = "files/{$route}/{$modelId}/{$originalFileName}";
        $destinationPath = public_path("files/{$route}/{$modelId}");

        File::makeDirectory($destinationPath, 0755, true, true);
        $absolutePdf = $destinationPath . '/' . $originalFileName;
        $file->move($destinationPath, $originalFileName);

        $doDisk->put($pdfPath, file_get_contents($absolutePdf), 'public');

        $result = [
            'link' => $doDisk->url($pdfPath),
            'alt' => pathinfo($originalFileName, PATHINFO_FILENAME)
        ];

        if (file_exists($absolutePdf)) unlink($absolutePdf);

        return $result;
    }

    /**
     * رفع وحفظ صورة و/أو PDF في سجل (Record) واحد مدمج (كما في المنتجات)
     */
    public function storeCombinedMedia(Request $request, $modelId, $route, $modelType)
    {
        $imageData = null;
        if ($request->hasFile('files')) {
            $files = is_array($request->file('files')) ? $request->file('files') : [$request->file('files')];
            $imageData = $this->uploadSingleCloudImage($files[0], $route, $modelId);
        }

        $pdfData = null;
        if ($request->hasFile('files_pdf')) {
            $filesPdf = is_array($request->file('files_pdf')) ? $request->file('files_pdf') : [$request->file('files_pdf')];
            $pdfData = $this->uploadSingleCloudPdf($filesPdf[0], $route, $modelId);
        }

        if ($imageData || $pdfData || $request->has('link')) {
            $media = new Media();
            $media->media_type_id = 1;

            if ($imageData) {
                $media->filepath      = $imageData['filepath'];
                $media->thumbnailpath = $imageData['thumbnailpath'];
                $media->alt           = $imageData['alt'];
                $media->setAltEnAttribute($imageData['alt']);
            }
            if ($pdfData) {
                $media->link = $pdfData['link'];
                if (!$imageData) {
                    $media->alt = $pdfData['alt'];
                    $media->setAltEnAttribute($pdfData['alt']);
                }
            } elseif ($request->has('link')) {
                $media->link = $request->link;
            }

            $media->media_able_id = $modelId;
            $media->media_able_type = $modelType;
            $media->save();
        }
    }

    /**
     * تحديث السجل المدمج (صورة + PDF) وحذف القديم تلقائياً
     */
    public function updateCombinedMedia(Request $request, Media $media, $modelId, $route)
    {
        $doDisk = Storage::disk('do');
        $extractPath = function ($url) {
            return ltrim(parse_url($url, PHP_URL_PATH), '/');
        };

        if ($request->hasFile('files')) {
            // مسح الصور القديمة
            if ($media->filepath) {
                $path = $extractPath($media->filepath);
                if ($doDisk->exists($path)) $doDisk->delete($path);
            }
            if ($media->thumbnailpath) {
                $path = $extractPath($media->thumbnailpath);
                if ($doDisk->exists($path)) $doDisk->delete($path);
            }

            $files = is_array($request->file('files')) ? $request->file('files') : [$request->file('files')];
            $imageData = $this->uploadSingleCloudImage($files[0], $route, $modelId);

            $media->filepath = $imageData['filepath'];
            $media->thumbnailpath = $imageData['thumbnailpath'];
            $media->alt = $imageData['alt'];
            $media->setAltEnAttribute($imageData['alt']);
        }

        if ($request->hasFile('files_pdf')) {
            // مسح الـ PDF القديم
            if ($media->link && !filter_var($media->link, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED) === false) {
                $path = $extractPath($media->link);
                if ($doDisk->exists($path)) $doDisk->delete($path);
            }

            $filesPdf = is_array($request->file('files_pdf')) ? $request->file('files_pdf') : [$request->file('files_pdf')];
            $pdfData = $this->uploadSingleCloudPdf($filesPdf[0], $route, $modelId);

            $media->link = $pdfData['link'];
            if (!$media->filepath) {
                $media->alt = $pdfData['alt'];
                $media->setAltEnAttribute($pdfData['alt']);
            }
        } elseif ($request->has('link')) {
            $media->link = $request->link;
        }

        $media->save();
    }

    /**
     * حفظ ميديا متعددة لمعارض الصور وغيرها
     */
    public function storeMultipleMedia(Request $request, $modelId, $route, $modelType)
    {
        if ($request->hasFile('files')) {
            $files = is_array($request->file('files')) ? $request->file('files') : [$request->file('files')];
            foreach ($files as $file) {
                $imageData = $this->uploadSingleCloudImage($file, $route, $modelId);

                $media = new Media();
                $media->media_type_id = 1;
                $media->filepath      = $imageData['filepath'];
                $media->thumbnailpath = $imageData['thumbnailpath'];
                $media->alt           = $imageData['alt'];
                $media->setAltEnAttribute($imageData['alt']);
                $media->media_able_id = $modelId;
                $media->media_able_type = $modelType;
                $media->save();
            }
        }
    }

    /**
     * رفع ملفات Documents (PDF فقط)
     */
    public function storeDocumentMedia(Request $request, $modelId, $route, $modelType, $title, $title_en)
    {
        if ($request->hasFile('files')) {
            $filesPdf = is_array($request->file('files')) ? $request->file('files') : [$request->file('files')];
            $pdfData = $this->uploadSingleCloudPdf($filesPdf[0], $route, $modelId);

            $media = new Media();
            $media->media_type_id = 3;
            $media->thumbnailpath = "images/thumbnails/document.png";
            $media->filepath      = $pdfData['link']; // Documents uses filepath for PDF
            $media->link          = $pdfData['link'];
            $media->alt           = $title;
            $media->setAltEnAttribute($title_en);
            $media->media_able_id = $modelId;
            $media->media_able_type = $modelType;
            $media->save();
        }
    }

    public function updateDocumentMedia(Request $request, Media $media, $modelId, $route, $title, $title_en)
    {
        $doDisk = Storage::disk('do');
        $extractPath = function ($url) {
            return ltrim(parse_url($url, PHP_URL_PATH), '/');
        };

        if ($request->hasFile('files')) {
            if ($media->filepath) {
                $path = $extractPath($media->filepath);
                if ($doDisk->exists($path)) $doDisk->delete($path);
            }

            $filesPdf = is_array($request->file('files')) ? $request->file('files') : [$request->file('files')];
            $pdfData = $this->uploadSingleCloudPdf($filesPdf[0], $route, $modelId);

            $media->filepath = $pdfData['link'];
            $media->link     = $pdfData['link'];
        }
        
        $media->alt = $title;
        $media->setAltEnAttribute($title_en);
        $media->save();
    }

    /**
     * أداة تصغير الصورة المركزية (GD Logic)
     */
    private function createThumbnailGD($absoluteOriginal, $absoluteThumb, $ext, $targetW, $targetH)
    {
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
    }
}
