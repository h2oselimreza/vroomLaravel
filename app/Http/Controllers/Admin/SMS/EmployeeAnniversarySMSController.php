<?php

namespace App\Http\Controllers\Admin\SMS;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\SentSms;
use App\Services\SmsService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class EmployeeAnniversarySMSController extends Controller
{
     public function index()
    {
        return view("admin.sms.employee-aaniversary-sms");
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
            ->whereDay('anniversary', $today->day)
            ->whereMonth('anniversary', $today->month)
            ->where('anniversary_sms_status', 0)
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

    public function sendMemberAnniversarySms(Request $request, SmsService $smsService, $checkFlag = 1)
    {
    
        if ($checkFlag == 1) {

            $IdArr = explode(",", $request->ids);

            $msgType = 'employeeAnniversary';

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

                SentSms::updateSmsStatus($IdArr, $msgType);

                return redirect()->back()->with('success', 'Sms has successfully sent!');
            }

            return redirect()->back()->with('error', 'No birthday members found.');

        } else {
            return redirect()->back()->with('error', 'check flag value is not matching');
        }
    }
}
