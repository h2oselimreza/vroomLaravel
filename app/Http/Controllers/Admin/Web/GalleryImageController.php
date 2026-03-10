<?php

namespace App\Http\Controllers\Admin\Web;

use App\Http\Controllers\Controller;
use App\Models\Web\WebGalleryImage;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GalleryImageController extends Controller
{
     public function showAlbum($albumId)
    {
        $galleryAlbumImages = WebGalleryImage::where('gallery_album', $albumId)
            ->where('is_active', 1)
            ->get();

        return view('admin.gallery.home_gallery', compact('galleryAlbumImages', 'albumId'));
    }

    // Update home gallery images
    public function updateHomeGallery(Request $request)
    {

        $imageCount = (int) $request->input('imageCount');
        $albumId = (int) $request->input('albumId');

        // Reset all home_flag for this album
        WebGalleryImage::where('gallery_album', $albumId)
            ->update(['home_flag' => 0]);

        // Collect checked images
        $checkedImages = [];
        for ($i = 1; $i < $imageCount; $i++) {
            if ($request->has('galleryCheckbox'.$i)) {
                $checkedImages[] = $request->input('imageId'.$i);
            }
        }

        // Update checked images to home_flag = 1
        if (!empty($checkedImages)) {
            WebGalleryImage::whereIn('id', $checkedImages)
                ->update([
                    'home_flag' => 1,
                ]);
        }

        return redirect()->route('admin.gallery-image.module.edit', $albumId)
            ->with('success', 'Successfully updated home gallery images!');
    }

    public function destroy($album_id, $gallery_id)
    {
        $image = WebGalleryImage::findOrFail($gallery_id);

        // delete file and DB
        $imagePath = public_path('assets/images/websiteImages/' . $image->image);
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
        $image->delete();

        return redirect()->route('admin.gallery-image.module.edit', $album_id)
            ->with('success', 'Image deleted successfully!');
    }
}
