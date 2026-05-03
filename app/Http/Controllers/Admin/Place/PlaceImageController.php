<?php

namespace App\Http\Controllers\Admin\Place;

use App\Http\Controllers\Controller;
use App\Models\Admin\Place\Place;
use App\Models\Admin\Place\PlaceFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PlaceImageController extends Controller
{
    public function edit($placeCode){
         $files = PlaceFile::where(
            ['place' => $placeCode,
            'file_type'=>'other_image',
            'is_active'=>1
            ])
        ->orderBy('created_dt_tm', 'desc')->get();
        $imageArray = $files->pluck('file_name')->toArray();
        $albumImageStr = implode(',', $imageArray);

        $data = Place::where('place_code', $placeCode)->first();
        return view('admin.place.image',compact('data', 'files'));
    }

     public function update(Request $request, $placeCode)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $place = Place::where('place_code', $placeCode)->firstOrFail();

        if ($request->hasFile('image')) {
            
            $destinationPath = 'assets/images/place/';

            if ($place->profile_image) {
                $oldFile = public_path($destinationPath . $place->profile_image);
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }

            $file = $request->file('image');
            
            $imageName = time() . '-' . Str::random(10) . '-' . $file->getClientOriginalName();

            $file->move(public_path($destinationPath), $imageName);

            // 4. Update database
            $place->update([
                'profile_image' => $imageName
            ]);
        }

        return redirect()
            ->route('admin.place.place-image.edit', $placeCode)
            ->with('success', 'Photo updated successfully');
    }

    public function store(Request $request)
    {
        $request->validate([
            'placeCode' => 'required|exists:places,place_code',
            'file'       => 'required|file|max:5120',
        ]);

        $placeCode = $request->placeCode;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            
            $extension = $file->getClientOriginalExtension();
            $fileName = 'a' . Str::random(13) . '.' . $extension;
            
            $file->move(public_path('assets/files/place'), $fileName);

            $data = [
                'original_name'   => $file->getClientOriginalName(),
                'file_type'       => 'other_image',
                'place'        => $placeCode,
                'file_name'       => $fileName,
            ];

            $result = PlaceFile::create($data);

            return redirect()->route('admin.place.place-image.edit', [$placeCode, 
            ])->with('success', 'Attachment uploaded successfully');
        }

        return redirect()->route('admin.place.place-info.edit', $placeCode)->with('error','Please try again');
    }

    public function destroy($id){
        $file = PlaceFile::where(['id'=>$id,'file_type'=>'other_image'])->first();
        // file path
        $path = public_path('assets/files/place' . $file->file_name);

        // delete file from folder
        if (File::exists($path)) {
            File::delete($path);
        }
        // delete DB record
        $file->delete();

        return redirect()->back()->with('success', 'File deleted successfully');
    }
}
