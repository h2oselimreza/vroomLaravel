<?php

namespace App\Http\Controllers\Client\Employee;

use App\Http\Controllers\Client\ClientBaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Client\Employee\ClientEmployeeRequest;
use App\Models\CustomerEmployee;
use App\Repositories\Client\EmployeeRepository;
use App\Services\Client\GenerateMonthlyToken;
use App\Services\TokenService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientEmployeeController extends ClientBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(EmployeeRepository $employeeRepository)
    {
        $data = $employeeRepository->getClientEmployeeProfile(null, [], null, auth()->user()?->customerEmployee?->company);
        return view('client.employee.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(EmployeeRepository $employeeRepository)
    {
        $occupations = [];
        return view('client.employee.create-edit',compact('occupations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, GenerateMonthlyToken $generateMonthlyToken, TokenService $tokenService)
    {

       $request->validate([
            'employee_name' => 'required|string',
            'primary_mobile' => 'required|string',
            'driving_license_no' => 'required|string',
        ]);

        DB::beginTransaction();

        try {

            $employeeDob = $request->dob ? Carbon::parse($request->dob)->format('Y-m-d') : null;

            $employee = [
                'company' => auth()->user()?->customerEmployee?->company,

                'employee_name' => trim($request->employee_name),
                'primary_mobile' => trim($request->primary_mobile),
                'driving_license_no' => trim($request->driving_license_no),

                'national_id' => $request->national_id,
                'gender' => $request->gender,
                'religion' => $request->religion,
                'nationality' => $request->nationality,
                'dob' => $employeeDob,
                'blood_group' => $request->blood_group,
                'marital_status' => $request->marital_status,

                'anniversary' => $request->anniversary 
                    ? Carbon::parse($request->anniversary)->format('Y-m-d') 
                    : null,

                'spouse_name' => $request->spouse_name,
                'spouse_occupation' => $request->spouse_occupation,
                'spouse_office_address' => $request->spouse_office_address,
                'spouse_contact' => $request->spouse_contact,

                'secendary_mobile' => $request->secendary_mobile,
                'email' => $request->email,

                'emer_contact_name' => $request->emer_contact_name,
                'emer_contact_address' => $request->emer_contact_address,
                'emer_contact_relation' => $request->emer_contact_relation,
                'emer_conatct_mobile' => $request->emer_conatct_mobile,

                'present_address' => $request->present_address,
                'employee_permanent_address' => $request->employee_permanent_address,
                'employee_tnt_phone' => $request->employee_tnt_phone,

                'father_name' => $request->father_name,
                'father_occupation' => $request->father_occupation,
                'father_office_address' => $request->father_office_address,
                'father_contact' => $request->father_contact,

                'mother_name' => $request->mother_name,
                'mother_occupation' => $request->mother_occupation,
                'mother_office_address' => $request->mother_office_address,
                'mother_contact' => $request->mother_contact,

                'guardian_name' => $request->guardian_name,
                'guardian_contact' => $request->guardian_contact,
                'guardian_relation' => $request->guardian_relation,
                'guardian_house_address' => $request->guardian_house_address,

                'passport_no' => $request->passport_no,
                'passposrt_expiry_date' => $request->passposrt_expiry_date 
                    ? Carbon::parse($request->passposrt_expiry_date)->format('Y-m-d') 
                    : null,

                'driving_license_expiry_date' => $request->driving_license_expiry_date 
                    ? Carbon::parse($request->driving_license_expiry_date)->format('Y-m-d') 
                    : null,

                'ref_one_name' => $request->ref_one_name,
                'ref_one_mobile' => $request->ref_one_mobile,
                'ref_one_email' => $request->ref_one_email,
                'ref_one_address' => $request->ref_one_address,

                'ref_two_name' => $request->ref_two_name,
                'ref_two_mobile' => $request->ref_two_mobile,
                'ref_two_email' => $request->ref_two_email,
                'ref_two_address' => $request->ref_two_address,

                'customer_type' => auth()->user()?->customerEmployee?->customer_type,///
            ];

            $customerType = 'corp_customer';
             // new correction
            if ($customerType == 'corp_customer') {
                $employee['updated_type'] = 'corporate_employee';
                $employee['created_type'] = 'corporate_employee';
            } else if ($customerType == 'indv_customer') {
                $employee['updated_type'] = 'indv_employee';
                $employee['created_type'] = 'indv_employee';
            }
            // ✅ marital logic
            if ($employee['marital_status'] === 'Single') {
                $employee['anniversary'] = null;
                $employee['spouse_name'] = null;
                $employee['spouse_occupation'] = null;
                $employee['spouse_office_address'] = null;
                $employee['spouse_contact'] = null;
            }

            // ✅ Duplicate check
            $exists = CustomerEmployee::where('employee_name', $employee['employee_name'])
                ->where('primary_mobile', $employee['primary_mobile'])
                ->exists();

            if ($exists) {
                DB::rollBack();
                return back()->with('error', 'Employee already exists!');
            }

            // ✅ Generate Employee ID
            if ($employee['customer_type'] == 'indv_customer') {
                $employee['employee_id'] = 'IE' . $generateMonthlyToken->generateMonthlyToken('IE');
            } else {
                $employee['employee_id'] = 'FE' . $tokenService->getTokenByCode('FE');
            }

            CustomerEmployee::create($employee);

            DB::commit();

            return redirect()->route('client.employee.index')
                ->with('success', 'Employee created successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    private function generateEmployeeId($type)
    {
        if ($type == 'indv_customer') {
            return 'indv_employee';
        } else {
            return 'corp_customer';
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = CustomerEmployee::findOrFail($id);
        $occupations = [];
        return view('client.employee.create-edit',compact('occupations','data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClientEmployeeRequest $request, $id)
    {
        $employeeModel = CustomerEmployee::where('id', $id)->firstOrFail();

        DB::beginTransaction();

        try {

            $employeeDob = $request->dob ? Carbon::parse($request->dob)->format('Y-m-d') : null;

            $employee = [
                'company' => auth()->user()->company_code ?? '01',

                'employee_name' => trim($request->employee_name),
                'primary_mobile' => trim($request->primary_mobile),
                'driving_license_no' => trim($request->driving_license_no),

                'national_id' => $request->national_id,
                'gender' => $request->gender,
                'religion' => $request->religion,
                'nationality' => $request->nationality,
                'dob' => $employeeDob,
                'blood_group' => $request->blood_group,
                'marital_status' => $request->marital_status,

                'anniversary' => $request->anniversary 
                    ? Carbon::parse($request->anniversary)->format('Y-m-d') 
                    : null,

                'spouse_name' => $request->spouse_name,
                'spouse_occupation' => $request->spouse_occupation,
                'spouse_office_address' => $request->spouse_office_address,
                'spouse_contact' => $request->spouse_contact,

                'secendary_mobile' => $request->secendary_mobile,
                'email' => $request->email,

                'emer_contact_name' => $request->emer_contact_name,
                'emer_contact_address' => $request->emer_contact_address,
                'emer_contact_relation' => $request->emer_contact_relation,
                'emer_conatct_mobile' => $request->emer_conatct_mobile,

                'present_address' => $request->present_address,
                'employee_permanent_address' => $request->employee_permanent_address,
                'employee_tnt_phone' => $request->employee_tnt_phone,

                'father_name' => $request->father_name,
                'father_occupation' => $request->father_occupation,
                'father_office_address' => $request->father_office_address,
                'father_contact' => $request->father_contact,

                'mother_name' => $request->mother_name,
                'mother_occupation' => $request->mother_occupation,
                'mother_office_address' => $request->mother_office_address,
                'mother_contact' => $request->mother_contact,

                'guardian_name' => $request->guardian_name,
                'guardian_contact' => $request->guardian_contact,
                'guardian_relation' => $request->guardian_relation,
                'guardian_house_address' => $request->guardian_house_address,

                'passport_no' => $request->passport_no,
                'passposrt_expiry_date' => $request->passposrt_expiry_date 
                    ? Carbon::parse($request->passposrt_expiry_date)->format('Y-m-d') 
                    : null,

                'driving_license_expiry_date' => $request->driving_license_expiry_date 
                    ? Carbon::parse($request->driving_license_expiry_date)->format('Y-m-d') 
                    : null,

                'ref_one_name' => $request->ref_one_name,
                'ref_one_mobile' => $request->ref_one_mobile,
                'ref_one_email' => $request->ref_one_email,
                'ref_one_address' => $request->ref_one_address,

                'ref_two_name' => $request->ref_two_name,
                'ref_two_mobile' => $request->ref_two_mobile,
                'ref_two_email' => $request->ref_two_email,
                'ref_two_address' => $request->ref_two_address,

                'customer_type' => 'need_to_add',///
            ];

            $customerType = 'corp_customer';
             // new correction
            if ($customerType == 'corp_customer') {
                $employee['updated_type'] = 'corporate_employee';
                $employee['created_type'] = 'corporate_employee';
            } else if ($customerType == 'indv_customer') {
                $employee['updated_type'] = 'indv_employee';
                $employee['created_type'] = 'indv_employee';
            }
            // ✅ marital logic
            if ($employee['marital_status'] === 'Single') {
                $employee['anniversary'] = null;
                $employee['spouse_name'] = null;
                $employee['spouse_occupation'] = null;
                $employee['spouse_office_address'] = null;
                $employee['spouse_contact'] = null;
            }

            // duplicate check
            $exists = CustomerEmployee::where('employee_name', $employee['employee_name'])
                ->where('primary_mobile', $employee['primary_mobile'])
                ->where('id', '!=', $id)
                ->exists();
            if ($exists) {
                DB::rollBack();
                return back()->with('error', 'Employee already exists!');
            }

            // ✅ Generate Employee ID
            $employee['employee_id'] = $this->generateEmployeeId($employee['customer_type']);

             $employeeModel->update($employee);

            DB::commit();

            return redirect()->route('client.employee.edit', $employeeModel->id)
                ->with('success', 'Employee updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
