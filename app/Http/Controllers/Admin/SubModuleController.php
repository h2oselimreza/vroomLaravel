<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubModules;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SubModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("admin.sub_modules.index");
    }

    public function getSubModulesData(Request $request){
        if ($request->ajax()) {

            $employee = SubModules::select([
                'id',
                'sub_module_name',
                'sub_module_code',
                'panel_type',
                'module',
            ]);

            return DataTables::of($employee)

                ->addIndexColumn()

                ->addColumn('action', content: function ($employee) {
                    $editUrl   = route('admin.sub-modules.edit', $employee->id);
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
                            </ul>
                        </div>
                    ';
                })

                ->rawColumns(['action'])
                ->make(true);

        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
