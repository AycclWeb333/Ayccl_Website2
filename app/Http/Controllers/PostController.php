<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function activation($locale, int $id)
    {
        // $msg = '';
        try {
            // Use a transaction closure for automatic commit and rollback
            $msg= DB::transaction(function () use ($id) : string {
                // 1. Find the post by its ID
                $post = Post::findOrFail($id);
                $activation = !($post->active);
                // 2. Explicitly set the 'active' status to true
                $post->update(['active' => $activation]);
                $msg2 = $activation ==true ? __('adminlte::adminlte.succActivate') : __('adminlte::adminlte.succDeactivate');
                return $msg2;
            });
            return redirect()->back()->with(['success' => $msg]);

            // 3. Redirect back with a success message
        } catch (\Exception $e) {
            // If anything goes wrong, the transaction is automatically rolled back
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }
    public function mediaDestroy($locale, int $id)
    {
        try {
            $media = Media::findOrFail($id);
            $field = request()->input('field');

            DB::transaction(function () use ($media, $field) {
                if ($field == 'filepath') {
                    // Delete the image/file from filepath
                    if ($media->filepath && Storage::disk('images')->exists($media->filepath)) {
                        Storage::disk('images')->delete($media->filepath);
                    }
                    if ($media->thumbnailpath && Storage::disk('images')->exists($media->thumbnailpath)) {
                        Storage::disk('images')->delete($media->thumbnailpath);
                    }
                    
                    // If we have a link (PDF), move it to filepath to satisfy the NOT NULL constraint
                    if ($media->link) {
                        $media->filepath = $media->link;
                        $media->thumbnailpath = null;
                        $media->link = null;
                        $media->save();
                    } else {
                        $media->delete();
                    }
                } elseif ($field == 'link') {
                    // Delete the file from link
                    if ($media->link && Storage::disk('images')->exists($media->link)) {
                        Storage::disk('images')->delete($media->link);
                    }
                    $media->link = null;
                    
                    // Since filepath is NOT NULL, we don't need to worry about it being null here 
                    // unless it was already empty (which shouldn't happen).
                    if (empty($media->filepath)) {
                        $media->delete();
                    } else {
                        $media->save();
                    }
                } else {
                    // Default behavior (delete everything)
                    if ($media->filepath && Storage::disk('images')->exists($media->filepath)) {
                        Storage::disk('images')->delete($media->filepath);
                    }
                    if ($media->thumbnailpath && Storage::disk('images')->exists($media->thumbnailpath)) {
                        Storage::disk('images')->delete($media->thumbnailpath);
                    }
                    if ($media->link && Storage::disk('images')->exists($media->link)) {
                        Storage::disk('images')->delete($media->link);
                    }
                    $media->delete();
                }
            });

            return response()->json(['success' => true], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
