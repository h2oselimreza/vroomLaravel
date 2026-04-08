<?php

namespace App\Http\Controllers\Admin\Corporate_customer\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MasterData\CustomeCompanyRequest;
use App\Models\CustomerEmployee;
use App\Repositories\CustomeEmployeeRepository;
use App\Services\TokenService;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

class CompanyEmployeeController extends Controller
{
    public function index(CustomeEmployeeRepository $customeEmployeeRepository){
        //  $employee = $customeEmployeeRepository->getCustomeEmployee(null, [], 1, null);
        //  dd( $employee);
        return view('admin.corporate_customer.employee.employee-list');
    }

    public function getCustomerEmployeeData(Request $request, CustomeEmployeeRepository $customeEmployeeRepository){
        if ($request->ajax()) {
            $employee = $customeEmployeeRepository->getCustomeEmployee(null, [], 1, $request->company_code);
            return DataTables::of($employee)

                ->addIndexColumn()
                ->addColumn('user_group', function($row) {
                    // Ensure you are accessing the joined value from your previous SQL query
                    return $row->group_name ?? 'N/A'; 
                })
                ->addColumn('is_reset', function($row) {
                    return $row->is_reset ?? 0;
                })
                ->addColumn('is_active', function($row) {
                    return $row->is_active ? 'Active':'Inactive';
                })
                ->addColumn('system_user', function($row) {
                    return $row->system_user ? 'Yes':'No';
                })
                ->addColumn('is_reset', function($row) {
                    return $row->is_reset ? 'Yes':'No';
                })
                ->addColumn('action', content: function ($employee) {
                    $editUrl   = route('admin.customer-employee.edit', $employee->employee_id);
                    $activeInactiveUrl   = route('admin.customer-employee.edit', $employee->employee_id);
                    $statusText = $employee->is_active == 1 ? 'Inactive' : 'Active';
                    return '
                        <div class="dropdown">
                            <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Actions
                            </button>

                            <ul class="dropdown-menu dropdown-menu-end">

                                <!-- Edit -->
                                <li>
                                    <a class="dropdown-item d-flex align-items-center" href="' . $editUrl . '">
                                        <i class="fa fa-edit me-2"></i> Edit
                                    </a>
                                </li>

                                <!-- Active / Inactive -->
                                <li>
                                    <form action="' . $activeInactiveUrl . '" method="POST">
                                        ' . csrf_field() . '
                                        <input type="hidden" name="_method" value="PATCH">
                                        <button type="submit" class="dropdown-item d-flex align-items-center">
                                            <i class="fa fa-toggle-on me-2"></i> ' . $statusText . '
                                        </button>
                                    </form>
                                </li>

                            </ul>
                        </div>
                    ';
                })

                ->rawColumns(['is_active', 'action'])
                ->make(true);

        }
    }

    public function create(){
        // if(!request('company_code')){
        //     return redirect()
        //     ->route('admin.customer-employee.create');
        // }
        return view('admin.corporate_customer.employee.employee-create-edit');
    }

    public function store(CustomeCompanyRequest $customeCompanyRequest, TokenService $tokenService){
        //dd($customeCompanyRequest->all());
        $prefix = "FE";

        DB::transaction(function () use ($customeCompanyRequest, $tokenService, $prefix) {
            // Generate token inside the transaction
            $employeeId = $prefix . $tokenService->getTokenByCode($prefix);

            $data = $customeCompanyRequest->validated();
            $data['employee_id'] = $employeeId;
            $data['customer_type'] = 'corp_customer';
            $data['company'] = $customeCompanyRequest->company_code;
            $data['created_type'] = auth()->user()->user_id;
            $data['updated_type'] = auth()->user()->user_id;
            CustomerEmployee::create($data);
        });

        return redirect()
            ->route('admin.customer-employee.create',['company_code' => $customeCompanyRequest->company_code])
            ->with('success', 'Employee created successfully.');
    }

    public function edit(String $employeeId){
        $data = CustomerEmployee::where('employee_id',$employeeId)->first();
        return view('admin.corporate_customer.employee.employee-create-edit', compact('data'));
    }

    public function update(CustomeCompanyRequest $request, $employeeId)
    {
        $customerEmployee = CustomerEmployee::where('employee_id',$employeeId)->first();
        $customerEmployee->update($request->validated());

        return redirect()
            ->route('admin.company-employee.index', ['company_code'=> $customerEmployee->company])
            ->with('success', 'Employee updated successfully.');
    }
}
