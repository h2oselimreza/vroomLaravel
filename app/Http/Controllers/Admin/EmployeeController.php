<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
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

            $block = Employee::select([
                'id',
                'employee_name',
                'designation',
                'primary_mobile',
                'is_active',
            ]);

            return DataTables::of($block)

                ->addIndexColumn()

                ->addColumn('action', content: function ($block) {
                    $viewUrl   = route('admin.employee.module.show', $block->id);
                    $editUrl   = route('admin.employee.module.edit', $block->id);
                    $deleteUrl = route('admin.employee.module.destroy', $block->id);
                    return '
                        <a href="'.$viewUrl.'" 
                        class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary action_button_modify">
                            <span class="ui-button-text">&nbsp;View</span>
                        </a>

                        <a href="'.$editUrl.'" 
                        class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary action_button_modify">
                            <span class="ui-button-text">&nbsp;Edit</span>
                        </a>

                        <a onclick="deleteRecord(\''.$deleteUrl.'\')" 
                        href="javascript:void(0)" 
                        class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary action_button_modify">
                            <span class="ui-button-text">&nbsp;Delete</span>
                        </a>
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
    public function store(EmployeeRequest $request)
    {
        //dd($request->all());
        DB::transaction(function () use ($request) {
        Employee::create($request->validated());
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
}
