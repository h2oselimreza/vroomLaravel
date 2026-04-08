<?php

namespace App\Http\Controllers\Admin\Corporate_customer\Employee;

use App\Http\Controllers\Controller;
use App\Models\CompanyFile;
use App\Models\CorporateCompany;
use App\Models\CustomerEmployee;
use App\Models\CustomerEmployeeFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CompanyEmployeeAttachmentController extends Controller
{
    public function edit($employeeId){
        $albumImages = CustomerEmployeeFile::where(
            ['employee'=>$employeeId,
            'file_type'=>'attachment',
            'is_active'=>1
            ])
        ->orderBy('created_dt_tm', 'desc')->get();
        $imageArray = $albumImages->pluck('file_name')->toArray();
        $albumImageStr = implode(',', $imageArray);
        $data = CustomerEmployee::where('employee_id',$employeeId)->first();
        return view('admin.corporate_customer.employee.attachment',compact('albumImages','albumImageStr','data','employeeId'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'employeeId' => 'required|exists:customer_employee,employee_id', // Ensure employee exists
            'file'       => 'required|file|max:5120',      // 5MB limit example
        ]);

        $employeeId = $request->employeeId;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            
            $extension = $file->getClientOriginalExtension();
            $fileName = 'a' . Str::random(13) . '.' . $extension;
            
            $file->move(public_path('assets/files/employee'), $fileName);

            $data = [
                'original_name'   => $file->getClientOriginalName(),
                'file_type'       => defined('ATTACHMENT_FILE') ? ATTACHMENT_FILE : 'attachment',
                'employee'        => $employeeId,
                'file_name'       => $fileName,
            ];

            $result = CustomerEmployeeFile::create($data);

            return redirect()->route('admin.customer-employee.attachment.edit', [
                'employeeId' => $employeeId, 
            ])->with('success', 'Attachment uploaded successfully');
        }

        return redirect()->route('admin.customer-employee.attachment.edit', $employeeId);
    }

    public function destory($employeeId){
        $file = CustomerEmployeeFile::where('employee',$employeeId)->first();
        // file path
        $path = public_path('assets/files/employee' . $file->file_name);

        // delete file from folder
        if (File::exists($path)) {
            File::delete($path);
        }
        // delete DB record
        $file->delete();

        return redirect()->back()->with('success', 'File deleted successfully');
    }
}
