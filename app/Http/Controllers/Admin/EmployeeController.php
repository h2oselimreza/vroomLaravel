<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Services\TokenService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\EmployeeRequest;
use Illuminate\Support\Facades\DB;


class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("admin.employee.index");
    }

    public function getEmployeeData(Request $request){
        if ($request->ajax()) {

            $employee = Employee::select([
                'id',
                'employee_name',
                'designation',
                'primary_mobile',
                'is_active',
            ]);

            return DataTables::of($employee)

                ->addIndexColumn()

                ->addColumn('action', content: function ($employee) {
                    $editUrl   = route('admin.employee.module.edit', $employee->id);
                    $activeInactiveUrl   = route('admin.employee.status', $employee->id);
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.employee.createEdit');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmployeeRequest $request, TokenService $tokenService)
    {
        $prefix = "P-EMP-";

        DB::transaction(function () use ($request, $tokenService, $prefix) {
            // Generate token inside the transaction
            $employeeId = $prefix . $tokenService->getTokenByCode($prefix);

            $data = $request->validated();
            $data['employee_id'] = $employeeId;

            Employee::create($data);
        });

        return redirect()
            ->route('admin.employee.module.index')
            ->with('success', 'Employee created successfully.');
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
        $data = Employee::findOrFail($id);
        return view('admin.employee.createEdit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmployeeRequest $request, Employee $employee)
    {
        $employee->update($request->validated());

        return redirect()
            ->route('admin.employee.module.edit', $employee->id)
            ->with('success', 'Employee updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function updateStatus($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->is_active = $employee->is_active == 1 ? 0 : 1;
        $employee->save();
        return redirect()->back()->with('success', 'Status Updated Successfully');
    }
}
