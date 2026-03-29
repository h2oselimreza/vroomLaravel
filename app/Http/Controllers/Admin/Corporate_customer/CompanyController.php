<?php

namespace App\Http\Controllers\Admin\Corporate_customer;

use App\Http\Controllers\Controller;
use App\Models\CorporateCompany;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CompanyController extends Controller
{
    public function index(){
        return view('admin.corporate_customer.index');
    }

    public function getCompanyData(Request $request){
        if ($request->ajax()) {

            $companies = CorporateCompany::select([
                'id',
                'title',
                'company_code',
                'company_mobile',
                'address',
                'package',
                'status'
            ]);

            return DataTables::of($companies)

                ->addIndexColumn()

                ->addColumn('action', content: function ($companies) {
                    $editUrl   = route('admin.company-modules.edit', $companies->id);
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
}
