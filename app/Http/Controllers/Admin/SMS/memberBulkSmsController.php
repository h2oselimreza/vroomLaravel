<?php

namespace App\Http\Controllers\Admin\SMS;

use App\Http\Controllers\Controller;
use App\Models\Block;
use App\Models\Member;
use App\Models\Road;
use App\Services\SmsService;
use Faker\Provider\ar_EG\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class memberBulkSmsController extends Controller
{
    public function index() {
        $blocks = Block::where('is_active',1)->get()->toArray();
        $roads = Road::where('is_active',1)->get()->toArray();
        $blockRoads = json_encode($this->getBlockRoads());
        $commonTableElementArr = array('type' => 'occupation');
        $occupations = $this->getCommonTableElement($commonTableElementArr);
        $commonTableElementArr = array('type' => 'member_type');
        $memberTypes = $this->getCommonTableElement($commonTableElementArr);
        $bloodGroups = [["value" => "O+", "label" => "O+"], ["value" => "O-", "label" => "O-"], ["value" => "A+", "label" => "A+"], ["value" => "A-", "label" => "A-"], ["value" => "B+", "label" => "B+"], ["value" => "B-", "label" => "B-"], ["value" => "AB+", "label" => "AB+"], ["value" => "AB-", "label" => "AB-"]];
        return view('admin.sms.member-bulk-sms',compact('blocks','roads','blockRoads','occupations','memberTypes','bloodGroups'));
    }

    public function showMemberBulkSmsPanel(Request $request)
    {
        $memberType = $request->memberType;
        $block = $request->block;
        $road = $request->road;
        $occupation = $request->occupation;
        $bloodGroup = $request->bloodGroup;
        $checkBulkMemberFlag = $request->listFlag;
        $memberIdStr = '';

        if ($checkBulkMemberFlag == 1) {

            return view('admin.sms.custom-member-bulk-sms', Compact('memberType','block','road','occupation','bloodGroup','memberIdStr','checkBulkMemberFlag'));

        } elseif ($checkBulkMemberFlag == 2) {
            $members = Member::getMemberDetails([
                'block'=>$block,
                'memberType'=>$memberType,
                'road' => $road,
                'occupation' => $occupation,
                'bloodGroup' => $bloodGroup,
                'checkBulkMemberFlag' => $checkBulkMemberFlag,
                'memberIdStr'=> $memberIdStr
                ]);
            return view('admin.sms.sms-member-list-view',compact('members'));

        }

        return redirect()->back();
    }

    public function showMemberSmsPanelFromList(Request $request)
    {
        $data = [
            'breadcrumbModuleUrl' => route('admin.member-bulk-sms.index'),
            'memberType' => '',
            'block' => '',
            'road' => '',
            'occupation' => '',
            'bloodGroup' => '',
            'checkBulkMemberFlag' => 2,
        ];

        $memberIdArr = $request->member_ids 
            ? explode(',', $request->member_ids) 
            : [];
        if (count($memberIdArr) > 0) {
            $data['members'] = Member::getSmsMemberList($memberIdArr);
            $data['memberIdStr'] = implode(',', $memberIdArr);
            return view('admin.sms.custom-member-sms-panel-view', $data);
        } else {
            // Redirect back to the bulk SMS page if no members selected
            return redirect()->route('admin.member-bulk-sms.index')
                            ->with('error', 'No members selected!');
        }
    }

    public function sendMemberCustomBulkMsg(Request $request, SmsService $smsService)
    {
        $insertSmsDetailsArr = [];

        $createUser = Auth::user()->user_id;
        $createdDtTm = now();

        $customMsg = trim($request->input('customMsg'));

        $arr = [
            'memberType'  => $request->memberType,
            'block'       => $request->block,
            'road'        => $request->road,
            'occupation'  => $request->occupation,
            'bloodGroup'  => $request->bloodGroup,
            'memberIdStr' => $request->memberIdStr,
        ];

        $smsTemplate = "bulkMemberCustom";

        if ($arr['memberIdStr']) {
            $smsTemplate = "selectedMemberBulkCustom";
        }

        $refNo = uniqid("ap", false);

        $singleMsgCount = $this->singleMshCount($customMsg);

        // Get Members
        $members = $this->getMemberCustomBulkMsgData($arr);
        // Format SMS
        $responsedbdata = $smsService->getFormatedMessArrayV2($members, $smsTemplate, $customMsg);
        // Send SMS
        $smsService->sendMessageV2($responsedbdata['message']);

        foreach ($members as $member) {
            if (!empty($member['mob1'])) {

                $insertSmsDetailsArr[] = [
                    'mobile_no'      => $member['mob1'],
                    'summary_ref_no' => $refNo,
                    'template'       => $smsTemplate,
                    'created_by'       => $createUser,
                    'created_dt_tm'    => $createdDtTm,
                ];
            }
        }
        // dd($insertSmsDetailsArr);
        $messcount = count($insertSmsDetailsArr);

        $smsArr = [
            'reference_number' => $refNo,
            'sms_template'     => $smsTemplate,
            'sms_count'        => $messcount + ($messcount * $singleMsgCount),
            'custom_sms'       => $customMsg,
            'job_status'       => 0,
            'created_by'       => $createUser,
            'created_dt_tm'    => $createdDtTm,
            'updated_by'       => $createUser,
            'updated_dt_tm'    => $createdDtTm
        ];

        if (!empty($insertSmsDetailsArr)) {

            DB::table('sent_sms')->insert($smsArr);

            DB::table('sent_sms_details')->insert($insertSmsDetailsArr);

            return redirect()->route('admin.member-bulk-sms.index')
                 ->with('success', 'SMS sent successfully!');
        }

        return redirect()->route('admin.member-bulk-sms.index')
                 ->with('success', 'No member found to send sms.');
    }


    public function getMemberCustomBulkMsgData($arr)
    {
        $members = DB::table('members')
            ->select('primary_mobile as mob1')
            ->where('is_active', 1)

            ->when(!empty($arr['memberIdStr']), function ($query) use ($arr) {
                $query->whereIn('id', explode(',', $arr['memberIdStr']));
            }, function ($query) use ($arr) {

                $query->when(!empty($arr['memberType']), function ($q) use ($arr) {
                    $q->whereIn('member_type', explode(',', $arr['memberType']));
                });

                $query->when(!empty($arr['block']), function ($q) use ($arr) {
                    $q->whereIn('society_block', explode(',', $arr['block']));
                });

                $query->when(!empty($arr['road']), function ($q) use ($arr) {
                    $q->whereIn('society_road', explode(',', $arr['road']));
                });

                $query->when(!empty($arr['occupation']), function ($q) use ($arr) {
                    $q->whereIn('member_occupation', explode(',', $arr['occupation']));
                });

                $query->when(!empty($arr['bloodGroup']), function ($q) use ($arr) {
                    $q->whereIn('blood_group', explode(',', $arr['bloodGroup']));
                });

            })
            ->get()
            ->map(function ($item) {
                return (array) $item; // convert object to array
            })
            ->toArray();

        return $members;
    }

    public function getEmployeeCustomBulkMsgData($arr)
    {
        return DB::table('employee')
            ->select('primary_mobile as mob1')
            ->where('is_active', 1)
            ->when($arr['employeeIdStr'], function ($query) use ($arr) {
                $query->whereIn('id', explode(',', $arr['employeeIdStr']));
            }, function ($query) use ($arr) {
                if (!empty($arr['designation'])) {
                    $query->whereIn('designation', explode(',', $arr['designation']));
                }
            })
            ->get()
            ->toArray();
    }

    private function singleMshCount($msgText) {
        $msgLength = strlen($msgText);
        $longMsgcount = 0;
        $messcount = 1;
        if ($msgLength > 160) {
            $longMsgcount = (int) ($msgLength / 160);
            if ($msgLength % 160 == 0) {
                $longMsgcount--;
            }
        }
        $messcount = $messcount + $longMsgcount;

        return $messcount;
    }

    private function getBlockRoads()
    {
        return DB::table('block_roads')
            ->select(
                'block_roads.block',
                'block_roads.road',
                'blocks.block_name',
                'roads.road_name'
            )
            ->join('blocks', 'blocks.block_code', '=', 'block_roads.block')
            ->join('roads', 'roads.road_code', '=', 'block_roads.road')
            ->where('block_roads.status', 1)
            ->get()
            ->toArray();
    }

    private function getCommonTableElement($params)
    {
        $query = DB::table('common_table');

        if (isset($params['type'])) {
            $query->where('type', $params['type']);
        }

        if (isset($params['depend_on_element'])) {
            $query->where('depend_on_element', $params['depend_on_element']);
        }

        return $query->get()->toArray();
    }
}
