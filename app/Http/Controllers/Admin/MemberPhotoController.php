<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MemberPhotoRequest;
use App\Models\Member;
use Illuminate\Support\Facades\Storage;

class MemberPhotoController extends Controller
{
    public function edit($id){
        $data = Member::findOrFail($id);
        return view("admin.members.profile_photo.index",compact("data"));
    }

    public function update(MemberPhotoRequest $request, $id)
    {
        // Find employee by ID
        $employee = Member::findOrFail($id);

        if ($request->hasFile('member_image')) {

            // Delete old image if exists
            if ($employee->member_image &&
                Storage::disk('public')->exists('member/' . $employee->member_image)) {

                Storage::disk('public')->delete('member/' . $employee->member_image);
            }

            // Store new image
            $imageName = time() . '.' . $request->file('member_image')->extension();

            $request->file('member_image')
                    ->storeAs('member', $imageName, 'public');

            // Update database
            $employee->update([
                'member_image' => $imageName
            ]);
        }

        return redirect()
            ->route('admin.member.photo.edit', $id)
            ->with('success', 'Photo updated successfully');
    }
}
