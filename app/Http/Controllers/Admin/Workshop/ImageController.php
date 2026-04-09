<?php

namespace App\Http\Controllers\Admin\Workshop;

use App\Http\Controllers\Controller;
use App\Models\Admin\Workshop\Workshop;
use App\Models\Admin\Workshop\WorkshopFile;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class ImageController extends Controller
{
    public function edit($workshopCode){
         $files = WorkshopFile::where(
            ['workshop' => $workshopCode,
            'file_type'=>'other_image',
            'is_active'=>1
            ])
        ->orderBy('created_dt_tm', 'desc')->get();
        $imageArray = $files->pluck('file_name')->toArray();
        $albumImageStr = implode(',', $imageArray);

        $data = Workshop::where('workshop_code', $workshopCode)->first();
        return view('admin.work-shop.work-shop-image.create-edit',compact('data', 'files'));
    }

     public function update(Request $request, $workshopCode)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $employee = Workshop::where('workshop_code', $workshopCode)->firstOrFail();

        if ($request->hasFile('image')) {
            
            $destinationPath = 'assets/images/workshop/';

            if ($employee->profile_image) {
                $oldFile = public_path($destinationPath . $employee->profile_image);
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }

            $file = $request->file('image');
            
            $imageName = Str::random(10) . '-' . $file->getClientOriginalName();


            $file->move(public_path($destinationPath), $imageName);

            // 4. Update database
            $employee->update([
                'profile_image' => $imageName
            ]);
        }

        return redirect()
            ->route('admin.workshop-image.edit', $workshopCode)
            ->with('success', 'Photo updated successfully');
    }

    public function store(Request $request)
    {
        $request->validate([
            'workshopCode' => 'required|exists:workshops,workshop_code',
            'file'       => 'required|file|max:5120',
        ]);

        $workshopCode = $request->workshopCode;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            
            $extension = $file->getClientOriginalExtension();
            $fileName = 'a' . Str::random(13) . '.' . $extension;
            
            $file->move(public_path('assets/files/workshop'), $fileName);

            $data = [
                'original_name'   => $file->getClientOriginalName(),
                'file_type'       => defined('ATTACHMENT_FILE') ? ATTACHMENT_FILE : 'other_image',
                'workshop'        => $workshopCode,
                'file_name'       => $fileName,
            ];

            $result = WorkshopFile::create($data);

            return redirect()->route('admin.customer-employee.attachment.edit', [
                'employeeId' => $workshopCode, 
            ])->with('success', 'Attachment uploaded successfully');
        }

        return redirect()->route('admin.customer-employee.attachment.edit', $workshopCode);
    }

    public function destroy($id){
        $file = WorkshopFile::where(['id'=>$id,'file_type'=>'other_image'])->first();
        // file path
        $path = public_path('assets/files/workshop' . $file->file_name);

        // delete file from folder
        if (File::exists($path)) {
            File::delete($path);
        }
        // delete DB record
        $file->delete();

        return redirect()->back()->with('success', 'File deleted successfully');
    }
}
