<?php

namespace App\Http\Controllers\Admin\Web;

use App\Http\Controllers\Controller;
use App\Models\Web\WebAlbum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Str;

class AlbumController extends Controller
{
    public function index(){
        // In your controller method
        $albumImages = WebAlbum::orderBy('created_dt_tm', 'desc')->get();
        $imageArray = $albumImages->pluck('image')->toArray();
        $albumImageStr = implode(',', $imageArray);
        return view("admin.web.album.index", compact("albumImages","albumImageStr"));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:jpeg,jpg,png,gif,pdf|max:3584',
        ]);

        try {

            if ($request->hasFile('file')) {

                $file = $request->file('file');

                $filename = Str::random(10) . '-' . $file->getClientOriginalName();

                $file->move(public_path('images/album'), $filename);

                WebAlbum::create([
                    'image' => $filename,
                ]);
            }

            return response()->json(['success' => true]);

        } catch (\Exception $e) {

            return response()->json([
                'error' => 'Upload failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteImages(Request $request)
    {
        try {

            if ($request->filled('imageIdStr')) {

                $ids = explode(',', $request->imageIdStr);

                $images = WebAlbum::whereIn('id', $ids)->get();

                foreach ($images as $img) {

                    $path = public_path('images/album/' . $img->image);

                    if (file_exists($path)) {
                        unlink($path);
                    }

                    $img->delete();
                }
            }

            return redirect()->route('admin.album.index')
                ->with('success', 'Images deleted successfully');

        } catch (\Exception $e) {

            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    public function albumDetails()
    {
        $albumImages = WebAlbum::get();

        $albumImageStr = $albumImages->pluck('image')->implode(',');

        return view('admin.web.album.albumdetails', compact('albumImages','albumImageStr'));
    }
}
