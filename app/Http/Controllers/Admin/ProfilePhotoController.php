<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfilePhotoRequest;
use App\Models\Employee;
use App\Models\Member;
use Illuminate\Support\Facades\Storage;

class ProfilePhotoController extends Controller
{
    public function edit($id){
        $data = Employee::findOrFail($id);
        return view("admin.employee.profile_photo.index",compact("data"));
    }

    public function update(ProfilePhotoRequest $request, $id)
    {
        // Find employee by ID
        $employee = Employee::findOrFail($id);

        if ($request->hasFile('employee_image')) {

            // Delete old image if exists
            if ($employee->employee_image &&
                Storage::disk('public')->exists('employee/' . $employee->employee_image)) {

                Storage::disk('public')->delete('employee/' . $employee->employee_image);
            }

            // Store new image
            $imageName = time() . '.' . $request->file('employee_image')->extension();

            $request->file('employee_image')
                    ->storeAs('employee', $imageName, 'public');

            // Update database
            $employee->update([
                'employee_image' => $imageName
            ]);
        }

        return redirect()
            ->route('admin.profile.photo.edit', $id)
            ->with('success', 'Photo updated successfully');
    }
}
