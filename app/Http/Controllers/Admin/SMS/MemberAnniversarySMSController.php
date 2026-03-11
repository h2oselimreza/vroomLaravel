<?php

namespace App\Http\Controllers\Admin\SMS;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\SentSms;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MemberAnniversarySMSController extends Controller
{
     public function index(){
        return view("admin.sms.member-anniversary-sms");
    }

    public function getMemberAnniversarySMSData(Request $request){
        if ($request->ajax()) {

            $members = Member::getBirthdayOrAnniversaryMember(0);

            return DataTables::of($members)

                ->addIndexColumn()

                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="rowCheckbox" value="'.$row->id.'">';
                })
                ->rawColumns(['checkbox'])
                ->make(true);

        }
    }


    public function sendMemberAnniversarySms(Request $request, SmsService $smsService, $checkFlag = 1)
    {
    
        if ($checkFlag == 1) {

            $IdArr = explode(",", $request->ids);
            
            $msgType = 'memberAnniversary';

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

                SentSms::updateSmsStatus($IdArr,$msgType);

                return redirect()->back()->with('success', 'Sms has successfully sent!');
            }

            return redirect()->back()->with('error', 'No birthday members found.');

        } else {
            return redirect()->back()->with('error', 'check flag value is not matching');
        }
    }
}
