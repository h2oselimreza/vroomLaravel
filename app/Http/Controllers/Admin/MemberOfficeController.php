<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MemberType;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeOfficeRequest;
use App\Http\Requests\MemberOfficeRequest;
use App\Models\Employee;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MemberOfficeController extends Controller
{
    
    public function index($id){
        $data = Member::findOrFail($id);
        return view("admin.members.office.edit",compact("data"));
    }

    public function update(MemberOfficeRequest $request, Member $member)
    {
        DB::transaction(function () use ($request, $member) {
            $data = $request->validated();
            // Set donar_member_id based on member_type
            $data['donar_member_id'] = null;
            if ($request->member_type === MemberType::DONATE->value) {
                $data['donar_member_id'] = $request->donarMemberId;
            }
            $member->update($data);
        });

        return redirect()
            ->route('admin.member.module.office.index', $member->id)
            ->with('success', 'Member office information updated successfully.');
    }
}
