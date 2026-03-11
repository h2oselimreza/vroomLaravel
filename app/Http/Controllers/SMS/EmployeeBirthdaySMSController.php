<?php

namespace App\Http\Controllers\SMS;

use App\Models\Employee;
use App\Models\SentSms;
use App\Services\SmsService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class EmployeeBirthdaySMSController extends Controller
{
    public function index()
    {
        return view("admin.sms.employee-birthday-sms");
    }

    public function getEmployeeData(Request $request){
        if ($request->ajax()) {

            $today = Carbon::now();

            $employees = Employee::select([
                'id',
                'employee_id',
                'employee_name',
                'designation',
                'primary_mobile',
            ])
            ->whereDay('dob', $today->day)
            ->whereMonth('dob', $today->month)
            ->where('birthday_sms_status', 0)
            ->where('is_active', 1);

            return DataTables::of($employees)
            ->addIndexColumn()
            ->addColumn('checkbox', function ($row) {
                return '<input type="checkbox" class="rowCheckbox" value="'.$row->id.'">';
            })
            ->orderColumn('DT_RowIndex', function ($query, $order) {
                $query->orderBy('id', $order);
            })
            ->rawColumns(['checkbox'])
            ->make(true);

        }
    }

    public function sendMemberBirthdaySms(Request $request, SmsService $smsService, $checkFlag = 1)
    {
    
        if ($checkFlag == 1) {

            $IdArr = explode(",", $request->ids);

            $msgType = 'employeeBirthday';

            $responsedbdata = $smsService->getDataForMess($IdArr, $msgType);
            if ($responsedbdata['msgCount'] > 0) {

                $smsService->sendMessageV2($responsedbdata['message']);

                $refNo = uniqid("ap", false);

                $smsArr = [
                    'reference_number' => $refNo,
                    'sms_template' => $msgType,
                    'sms_count' => $responsedbdata['msgCount'],
                    'custom_sms' => null,
                    'channel_type' => 'mobileNo',
                    'module_type' => 'member_birthday_sms',
                    'mobile_number' => null,
                ];

                SentSms::create($smsArr);

                SentSms::EmployeeBirthdayStatusChange($IdArr);

                return redirect()->back()->with('success', 'Sms has successfully sent!');
            }

            return redirect()->back()->with('error', 'No birthday members found.');

        } else {
            return redirect()->back()->with('error', 'check flag value is not matching');
        }
    }
}
