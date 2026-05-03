<?php

namespace App\Http\Controllers\Admin\Place;

use App\Http\Controllers\Controller;
use App\Models\Admin\Place\Place;
use App\Models\Admin\Place\PlaceFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PlaceAttachmentController extends Controller
{
    public function edit($placeCode){
         $files = PlaceFile::where(
            ['place' => $placeCode,
            'file_type'=>'attachment',
            'is_active'=>1
            ])
        ->orderBy('created_dt_tm', 'desc')->get();
        $imageArray = $files->pluck('file_name')->toArray();
        $albumImageStr = implode(',', $imageArray);

        $data = Place::where('place_code', $placeCode)->first();
        return view('admin.place.attachment',compact('data', 'files'));
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
            $fileName = now()->format('YmdHis') . Str::random(13) . '.' . $extension;

            $file->move(public_path('assets/files/place'), $fileName);

            $data = [
                'original_name'   => $file->getClientOriginalName(),
                'file_type'       => config('constants.ATTACHMENT_FILE') ?? 'attachment',
                'place'        => $placeCode,
                'file_name'       => $fileName,
            ];

            $result = PlaceFile::create($data);

            return redirect()->route('admin.place.attachment.edit', [$placeCode, 
            ])->with('success', 'Attachment uploaded successfully');
        }

        return redirect()->route('admin.place.attachment.edit', $placeCode);
    }

    public function destroy($id){
        $file = PlaceFile::where(['id'=>$id,'file_type'=>'attachment'])->first();
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
