<?php

namespace App\Http\Controllers\Admin\HomeService;

use App\Http\Controllers\Controller;
use App\Models\Client\HomeServiceAppSummaryGen;
use App\Models\Employee;
use App\Repositories\Client\HomeServiceRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeServiceAssignToEmployeeController extends Controller
{
    public function index(Request $request, HomeServiceRepository $homeServiceRepository)
    {
        $assignEmpFlag = 2;

        $assignEmpDropDown = $request->get('assignEmpDropDown');
        if ($assignEmpDropDown) {
            $assignEmpFlag = $assignEmpDropDown;
        }
        $assignEmpFlag = $assignEmpFlag;
        $statusLists = array(config('constants.APPOINTMENT_ACCEPT'));
        $employees = $homeServiceRepository->getHomeServiceEmployee(null, [], $flag = null);
        $arr['status'] = config('constants.APPOINTMENT_ACCEPT');
        $arr['assignEmpFlag'] = $assignEmpFlag;
        $arr['companyCode'] = "";
        $status = config('constants.APPOINTMENT_ACCEPT');
        $appointmentLists = $homeServiceRepository->getHomeServiceList($arr);
        return view('admin.home-service.assign-employee.index', compact('employees','appointmentLists','status','statusLists'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'appointmentNo' => 'required|string',
            'employeeId'    => 'required|string',
        ]);

        $appointmentNo = trim($request->input('appointmentNo'));
        $employeeId    = trim($request->input('employeeId'));

        // Fetch data
        $homeService = DB::table('home_service_app_summary_gen')
            ->where('appointment_no', $appointmentNo)
            ->first();

        $employee = DB::table('employee') // adjust table name if different
            ->where('employee_id', $employeeId)
            ->where('is_active', 1)
            ->first();

        // Check conditions (same as your logic)
        if ($employee && $homeService && $homeService->status == config('constants.APPOINTMENT_ACCEPT')) {

            $updateArr = [
                'assign_emp'       => $employeeId,
                'assign_emp_dt_tm' => Carbon::now(),
                //'updated_by'       => Auth::id(),
                'updated_type'     => config('constants.P_ADMIN'),
                //'updated_dt_tm'    => Carbon::now(),
            ];

            DB::table('home_service_app_summary_gen')
                ->where('appointment_no', $appointmentNo)
                ->update($updateArr);

            return response('1'); // same response as CI
        }

        return response('2');
    }
}
