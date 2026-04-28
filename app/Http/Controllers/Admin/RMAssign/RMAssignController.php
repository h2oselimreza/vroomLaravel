<?php

namespace App\Http\Controllers\Admin\RMAssign;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Repositories\AdminEmployeeRepository;
use App\Repositories\Client\EmployeeRepository;
use App\Repositories\CommonRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RMAssignController extends Controller
{
    public function index(AdminEmployeeRepository $adminEmployeeRepository, CommonRepository $commonRepository){
        $employees = $adminEmployeeRepository->getEmpPersonalInfo(null, null, null, null);
        $companies = $commonRepository->getCompanyList(1, config('constants.CORPORATE_CUST'));
        return view('admin.rm-assign.index',compact('employees','companies'));
    }

    public function store(Request $request)
    {
        try {
            // dd($request->all());
            $companyCount = (int) $request->companyCount;
            $updateArr = [];

            for ($i = 1; $i < $companyCount; $i++) {

                $companyCode = $request->input('companyCode' . $i);
                $rmId = $request->input('rmId' . $i);

                $arr = [];
                $arr['rm_id'] = $rmId ? $rmId : null;
                $arr['company_code'] = $companyCode;
                $arr['updated_by'] = Auth::user()->user_id ?? null;
                $arr['updated_dt_tm'] = now();

                $updateArr[] = $arr;
            }

            foreach ($updateArr as $data) {

                DB::table('corporate_companies')
                    ->where('company_code', $data['company_code'])
                    ->update([
                        'rm_id' => $data['rm_id'],
                        'updated_by' => $data['updated_by'],
                        'updated_dt_tm' => $data['updated_dt_tm'],
                    ]);
            }

            return redirect()
                ->route('admin.rm-rm-assign.index')
                ->with('success', 'RM assign successfully');

        } catch (\Throwable $e) {

            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }
}
