<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeOfficeRequest;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeOfficeController extends Controller
{
    public function edit($id){
        $data = Employee::findOrFail($id);
        return view("admin.employee_office.office",compact("data"));
    }

    public function update(EmployeeOfficeRequest $request, Employee $employee)
    {
        $employee->update($request->validated());

        return redirect()
            ->route('admin.employee.office.edit', $employee->id)
            ->with('success', 'Employee office information updated successfully.');
    }

}
