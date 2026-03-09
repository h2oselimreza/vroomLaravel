<?php

namespace App\Http\Controllers\WebSite;

use App\Http\Controllers\Controller;
use App\Models\Web\WebGalleryAlbum;
use App\Models\Web\WebGalleryImage;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index(){
        $pageHeading = 'Gallery';
        $galleryAlbums = WebGalleryAlbum::where('is_active', 1)->get()->toArray();
        $galleryAlbumImages = WebGalleryImage::getGalleryAlbumImage(null, 0);
        return view('website.gallery.index', compact('pageHeading','galleryAlbums','galleryAlbumImages'));
    }

    public function show($albumId = null, $albumName = null){
      $galleryAlbumImages = WebGalleryImage::getGalleryAlbumImage($albumId, 0);
      $galleryAlbumName = $albumName;
      return view('website.gallery.gallery-details', compact('galleryAlbumImages','galleryAlbumName'));
    }
}
