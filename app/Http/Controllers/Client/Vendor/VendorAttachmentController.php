<?php

namespace App\Http\Controllers\Client\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Client\CorporateVendor;
use App\Repositories\MasterData\MasterDataRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VendorAttachmentController extends Controller
{
    public function edit($vedorCode)
    {
        try {

            if (!$vedorCode) {
                return redirect()->route('client.vendor.venor-list.index')->with('error','Vendor id is not found');
            }

            // ⚠️ XSS clean equivalent (Laravel safe handling)
            $vedorCode = strip_tags($vedorCode);

            // ✅ Check vendor exists
            $flag = DB::table('corporate_vendor')
                ->where('vendor_code', $vedorCode)
                ->where('company', Auth::user()->customerEmployee->company)
                ->first();

            if (!$flag) {
                return redirect()->route('client.vendor.venor-list.index')->with('error','Vendor not found');
            }

            // Get attachments
            $attachedFiles = DB::table('corporate_vendor_file')
                ->where('vendor', $vedorCode)
                ->where('file_type', 'attachment')
                ->where('is_active', 1)
                ->get();

            $vendor = CorporateVendor::where('vendor_code', $vedorCode)->first();    

            return view('client.vendor.attachment.create-edit', [
                'vedorCode' => $vedorCode,
                'disableFlag' => 0,
                'attachedFiles' => $attachedFiles,
                'vendor' => $vendor
            ]);

        } catch (\Exception $e) {
                return redirect()->route('client.vendor.venor-list.index')->with('error',$e->getMessage());
        }
    }

    public function store(Request $request)
    {  
        try {
            $vendorCode = $request->vendorCode;
            if (!$vendorCode) {
                return redirect()->route('client.vendor.venor-list.index')->with('error','Vendor code is not found');
            }

            // Vendor validation (same logic)
            $flag = DB::table('corporate_vendor')
                ->where('vendor_code', $vendorCode)
                ->where('company', Auth::user()->customerEmployee->company)
                ->first();
            if (!$flag) {
                return redirect()->route('client.vendor.venor-list.index')->with('error','Vendor code is not found');
            }

            $insertArr = [];
            $dateTime = Carbon::now();
            $userId = Auth::user()->user_id ?? Auth::id();
            // File upload handling
            if ($request->hasFile('file')) {

                $file = $request->file('file');

                $originalName = $file->getClientOriginalName();
                $ext = $file->getClientOriginalExtension();

                $fileName = $fileName = 'a_' . now()->format('Ymd_His_u') . '.' . $ext;

                $destinationPath = public_path('assets/client/files/vendor/');

                $file->move($destinationPath, $fileName);

                $insertArr[] = [
                    'file_type' => config('constants.ATTACHMENT_FILE'),
                    'vendor' => $vendorCode,
                    'original_name' => $originalName,
                    'file_name' => $fileName,
                    'created_by' => $userId,
                    'created_dt_tm' => $dateTime,
                    'updated_by' => $userId,
                    'updated_dt_tm' => $dateTime,
                ];
            }

            if (!empty($insertArr)) {

                $result = DB::table('corporate_vendor_file')->insert($insertArr);

                return redirect()->route('client.vendor.attachment.edit',$vendorCode)->with('success','Vendor attachment add successfully');
            }

        } catch (\Exception $e) {

            return redirect()->route('client.vendor.attachment.edit')->with('error', $e->getMessage());
        }
    }


    public function destroy(Request $request, MasterDataRepository $masterDataRepository)
    {

        $fileId = (int) $request->get('fileId');
        $vendorCode = (string) $request->get('verndorCode');

        if ($fileId) {

            $result = $masterDataRepository->deleteFile($fileId);

            return response()->json($result);

        } else {

            return response()->json(2);

        }

    }
}
