<?php

namespace App\Http\Controllers\Client\Employee;

use App\Http\Controllers\Controller;
use App\Models\CustomerEmployee;
use Illuminate\Http\Request;

class ClientOfficeController extends Controller
{
    public function edit($id){
        $data = CustomerEmployee::findOrFail($id);
        return view('client.employee.office.create-edit',compact('data'));
    }

    public function update(Request $request, $id){

        $request->validate([
            'emp_type'           => 'required|in:system_manager,driver',
            'designation'        => 'nullable|string|max:255',
            'first_joining_date' => 'required|date|date_format:Y-m-d',
        ], [
            'emp_type.required'           => 'Please select a valid employee type.',
            'first_joining_date.required' => 'The joining date is required for official records.',
        ]);

        try {

            $employee = CustomerEmployee::where('id', $id)->first();

            $employee->update([
                'emp_type'           => $request->emp_type,
                'designation'        => $request->designation,
                'first_joining_date' => $request->first_joining_date,
            ]);

            return redirect()
                ->back()
                ->with('success', 'Employee information has been updated successfully.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'An error occurred while updating: ' . $e->getMessage()]);
        }
    }
}
