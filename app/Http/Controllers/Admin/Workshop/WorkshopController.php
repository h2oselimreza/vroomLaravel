<?php

namespace App\Http\Controllers\Admin\Workshop;

use App\Http\Controllers\Controller;
use App\Models\Admin\Workshop\Workshop;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class WorkshopController extends Controller
{
    public function index(){
        return view('admin.work-shop.index');
    }

    public function getWorkshopData(Request $request){
         if ($request->ajax()) {
            $data = Workshop::get();
            return DataTables::of($data)

                ->addIndexColumn()

                ->addColumn('is_active', function($row) {
                    return $row->is_active ? 'Active':'Inactive';
                })

                ->addColumn('action', content: function ($data) {
                    $editUrl   = route('admin.workshop-general-info.edit', $data->workshop_code);
                    $activeInactiveUrl   = route('admin.workshop-general-info.edit', $data->workshop_code);
                    $statusText = $data->is_active == 1 ? 'Inactive' : 'Active';
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
}
