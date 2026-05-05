<?php

namespace App\Http\Controllers\Admin\Corporate_customer\Employee;

use App\Http\Controllers\Controller;
use App\Models\CustomerEmployee;
use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CompanyEmployeeOfficeController extends Controller
{
    public function edit($employeeId){
        $data = CustomerEmployee::where('employee_id',$employeeId)->first();
        $user = User::where(['user_id' => $employeeId, 'is_active' => 1])->first();
        
        $blocked = config('constants.USERGROUP_BLOCKLIST', []);
        $currentUserGroup = Auth::user()->user_group ?? null;
        //dd($blocked, $currentUserGroup);
        $panelType = 'client';
        $userGroup =  UserGroup::query()
            ->where('is_active', 1)
            ->when($panelType, fn($q) => $q->where('panel_type', $panelType))
            ->when(!in_array($currentUserGroup, $blocked), fn($q) => $q->whereNotIn('id', $blocked))
            ->get();
        return view('admin.corporate_customer.employee.office-update',compact('data','userGroup','user'));
    }

    public function update(Request $request, $employeeId)
    {
        $request->validate([
            'emp_type'           => 'required|in:system_manager,driver',
            'designation'        => 'nullable|string|max:255',
            'first_joining_date' => 'required|date|date_format:Y-m-d',
        ], [
            'emp_type.required'           => 'Please select a valid employee type.',
            'first_joining_date.required' => 'The joining date is required for official records.',
        ]);

        try {

            $employee = CustomerEmployee::where('employee_id', $employeeId)->first();

            $employee->update([
                'emp_type'           => $request->emp_type,
                'designation'        => $request->designation,
                'first_joining_date' => $request->first_joining_date,
            ]);

            $systemUserCheck = $request->system_user; // 0 or 1

            if ($systemUserCheck && Auth::user()->user_id != $employeeId) {
                // Check if user already exists
                $user = User::where('user_id', $employeeId)->first();

                if ($user) {
                    // If exists → activate
                    $user->update([
                        'is_active'   => 1,
                        'updated_by'  => Auth::user()->user_id,
                        'updated_dt_tm' => now(),
                    ]);
                } else {
                    // If not exists → create
                    User::create([
                        'user_id'  => $employee->employee_id,
                        'username' => $employee->employee_id,
                        'password' => Hash::make('123456789'),
                        'user_group' => $request->user_group,
                        'email' => $employee->email,
                        'panel_type' => 'client',
                        'user_type_code' => 'corporate_employee',
                        'full_name' => $employee->employee_name,
                        'contact_no' => $employee->primary_mobile,
                        'created_by' => Auth::user()->user_id,
                        'created_type' => 'admin_employee',
                        'created_dt_tm' => now(),
                        'updated_by' => Auth::user()->user_id,
                        'updated_type' => 'admin_employee',
                        'updated_dt_tm' => now(),
                    ]);

                }

            }else{
                if(Auth::user()->user_id != $employeeId){
                    $user = User::where('user_id', $employeeId)->first();
                    $user->update([
                        'is_active'   => 0,
                        'updated_by'  => Auth::user()->user_id,
                        'updated_dt_tm' => now(),
                    ]);
                }
            }

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
