<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\SentSms;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MemberBirthdaySMSController extends Controller
{
    public function index(){
        return view("admin.sms.index");
    }

    public function getMemberBirthdaySMSData(Request $request){
        if ($request->ajax()) {

            $members = Member::getBirthdayMember();

            return DataTables::of($members)

                ->addIndexColumn()

                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="rowCheckbox" value="'.$row->id.'">';
                })
                ->rawColumns(['checkbox'])
                ->make(true);

        }
    }


    public function sendMemberBirthdaySms(Request $request, SmsService $smsService, $checkFlag = 1)
    {
    
        if ($checkFlag == 1) {

            $memberIdArr = explode(",", $request->member_ids);

            $msgType = 'memberBirthday';

            $responsedbdata = $smsService->getDataForMess($memberIdArr, $msgType);
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

                SentSms::memberBirthdayStatusChange($memberIdArr);

                return redirect()->back()->with('success', 'Sms has successfully sent!');
            }

            return redirect()->route('admin.memberBirthdaySms');

        } else {
            return redirect()->route('home');
        }
    }
}
