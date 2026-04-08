<?php

namespace App\Http\Controllers\Admin\Corporate_customer\Employee;

use App\Http\Controllers\Controller;
use App\Models\CustomerEmployee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanyEmployeePhotographController extends Controller
{
    public function edit($employeeId){
        $data = CustomerEmployee::where('employee_id', $employeeId)->first();
        return view('admin.corporate_customer.employee.photograph',compact('data'));
    }

    public function update(Request $request, $employeeId)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $employee = CustomerEmployee::where('employee_id', $employeeId)->firstOrFail();

        if ($request->hasFile('image')) {
            
            $destinationPath = 'assets/images/employee/';

            if ($employee->employee_image) {
                $oldFile = public_path($destinationPath . $employee->employee_image);
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }

            $file = $request->file('image');
            
            $imageName = \Illuminate\Support\Str::random(10) . '-' . $file->getClientOriginalName();


            $file->move(public_path($destinationPath), $imageName);

            // 4. Update database
            $employee->update([
                'employee_image' => $imageName
            ]);
        }

        return redirect()
            ->route('admin.customer-employee.photo.edit', $employeeId)
            ->with('success', 'Photo updated successfully');
    }
}
