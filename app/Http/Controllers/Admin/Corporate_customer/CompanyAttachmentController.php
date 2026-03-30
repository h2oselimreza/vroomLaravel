<?php

namespace App\Http\Controllers\Admin\Corporate_customer;

use App\Http\Controllers\Controller;
use App\Models\CompanyFile;
use App\Models\CorporateCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Str;
use Illuminate\Support\Facades\File;

class CompanyAttachmentController extends Controller
{
    public function edit($company_code){
        $albumImages = CompanyFile::where(
            ['company'=>$company_code,
            'file_type'=>'attachment',
            'is_active'=>1
            ])
        ->orderBy('created_dt_tm', 'desc')->get();
        $imageArray = $albumImages->pluck('image')->toArray();
        $albumImageStr = implode(',', $imageArray);
        $data = CorporateCompany::where('company_code',$company_code)->first();
        return view('admin.corporate_customer.attachment',compact('albumImages','albumImageStr','data','company_code'));
    }

    public function store(Request $request)
    {
        // 1. Validation: Match the limits specified in your Dropzone (3.5MB)
        $request->validate([
            'file' => 'required|file|mimes:jpeg,jpg,png,gif,pdf|max:3584',
            'company_code' => 'required' 
        ]);

        try {
            if ($request->hasFile('file')) {
                $file = $request->file('file');

                // 2. Generate a clean, unique filename
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $nameOnly = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $filename = Str::slug($nameOnly) . '-' . time() . '.' . $extension;

                // 3. Define path (Consistent with your frontend assets)
                $destinationPath = public_path('assets/images/websiteImages');

                // 4. Database Transaction for data integrity
                return DB::transaction(function () use ($file, $originalName, $filename, $destinationPath, $request) {
                    
                    // Move the file
                    $file->move($destinationPath, $filename);

                    // 5. Create Record (Pass a flat array, not $insertArr[])
                    CompanyFile::create([
                        'file_type' => 'attachment',
                        'company'   => $request->company_code,
                        'original_name' => $originalName,
                        'file_name'     => $filename, // Matches your Blade variable $albumImage->image
                    ]);

                    return response()->json(['success' => true, 'filename' => $filename]);
                });
            }
        } catch (\Exception $e) {
            // 6. Log the error for debugging, but don't show sensitive details to users
            Log::error("Gallery Upload Error: " . $e->getMessage());

            return response()->json([
                'error' => 'Upload failed',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id){
        $file = CompanyFile::findOrFail($id);
        // file path
        $path = public_path('assets/images/websiteImages/' . $file->file_name);

        // delete file from folder
        if (File::exists($path)) {
            File::delete($path);
        }
        // delete DB record
        $file->delete();

        return redirect()->back()->with('success', 'File deleted successfully');
    }
}
