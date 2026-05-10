<?php

namespace App\Http\Controllers\Admin\General;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Models\Page;
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
use App\Traits\CloudMediaTrait;

class PagesController extends Controller
{
    use CloudMediaTrait;
    public $backgroundFolder = "pages";
    public function update(Request $request, $locale, int $id)
    {

        $request->validate(
            [
                'content_ar'    => 'required',
                'content_en' => 'required',
                'files'      => 'required',
            ],
            [
                'content_ar.required'    => __('adminlte::adminlte.content_required'),
                'content_en.required' => __('adminlte::adminlte.content_en_required'),
                'files.required'      => __('adminlte::adminlte.files_required'),
            ]
        );
        try {
            $page = Page::findOrFail($id);

            DB::beginTransaction();

            $page->content = $request->content_ar;
            $page->content_en = $request->content_en;

            // 3) Upload Media (if provided)
            if ($request->hasFile('files')) 
            {
                $files = is_array($request->file('files')) ? $request->file('files') : [$request->file('files')];
                
                // Delete old background if exists
                if ($page->background) {
                    $extractPath = function ($url) {
                        return ltrim(parse_url($url, PHP_URL_PATH), '/');
                    };
                    $oldPath = $extractPath($page->background);
                    if (Storage::disk('do')->exists($oldPath)) {
                        Storage::disk('do')->delete($oldPath);
                    }
                }

                $imageData = $this->uploadSingleCloudImage($files[0], $this->backgroundFolder, $page->id);
                $page->background = $imageData['filepath'];
            }
            $page->save();
                // Commit after processing all files (do NOT commit inside the loop)
                DB::commit();
                
                return redirect()->back()->with(['success' => __('adminlte::adminlte.succEdit')]);
                // return redirect()->route("$this->backgroundFolder.index", app()->getLocale())
                // ->with(['success' => __('adminlte::adminlte.succEdit')]);
            }
         catch (\Exception $e) {
            DB::rollBack();
            // return redirect()->back()->withErrors(['error' => $e->getMessage()]);
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

}
