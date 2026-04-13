<?php

namespace App\Http\Controllers\Client\Employee;

use App\Http\Controllers\Controller;
use App\Models\CustomerEmployee;
use Illuminate\Http\Request;

class ClientPhotographController extends Controller
{
    public function edit($employeeId){
        $data = CustomerEmployee::where('id', $employeeId)->first();
        return view('client.employee.photograph.create-edit',compact('data'));
    }

    public function update(Request $request, $id)
    {
    
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);
        $employee = CustomerEmployee::where('id', $id)->firstOrFail();

        if ($request->hasFile('image')) {
            
            $destinationPath = 'assets/client/images/employee/';

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
            ->route('client.employee.photograph.edit', $id)
            ->with('success', 'Photo updated successfully');
    }
}
