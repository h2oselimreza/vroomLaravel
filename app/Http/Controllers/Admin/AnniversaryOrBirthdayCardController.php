<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Block;
use App\Models\Road;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnniversaryOrBirthdayCardController extends Controller
{
    public function index(){
        $blocks = Block::where('is_active',1)->get()->toArray();
        $roads = Road::where('is_active',1)->get()->toArray();
        $commonTableElementArr = array('type' => 'occupation');
        $occupations = $this->getCommonTableElement($commonTableElementArr);
        $commonTableElementArr = array('type' => 'member_type');
        $memberTypes = $this->getCommonTableElement($commonTableElementArr);
        $bloodGroups = [["value" => "O+", "label" => "O+"], ["value" => "O-", "label" => "O-"], ["value" => "A+", "label" => "A+"], ["value" => "A-", "label" => "A-"], ["value" => "B+", "label" => "B+"], ["value" => "B-", "label" => "B-"], ["value" => "AB+", "label" => "AB+"], ["value" => "AB-", "label" => "AB-"]];
        
        return view('admin.anniversary-birthday-card.anniversary-member-filter',compact('blocks','roads','occupations','memberTypes','bloodGroups'));

    }

    public function showMemberAnniversaryCardPanel(Request $request)
    {

        $data = [];
        $data['breadcrumbModuleUrl'] = "admin/AnniversaryCard/memberList";
        $data['memberType'] = $request->memberType;
        $data['cardType'] = $request->cardType;
        $data['block'] = $request->block;
        $data['road'] = $request->road;
        $data['occupation'] = $request->occupation;
        $data['bloodGroup'] = $request->bloodGroup;
        $data['checkBulkMemberFlag'] = $request->listFlag;
        $data['anniversaryDate'] = $request->anniversaryDate;

        $data['memberIdStr'] = '';

        if ($data['checkBulkMemberFlag'] == 1) {

            // In original CI code this part was commented
            // return view('admin.sms.customMemberSmsPanelView', $data);

        } elseif ($data['checkBulkMemberFlag'] == 2) {

            $data['members'] = $this->getMemberDetails($data);
            return view(
                'admin.anniversary-birthday-card.anniversary-member-list-view',
                $data
            );
        }
    }

    public function getMemberDetails($arr)
    {
        $query = DB::table('members')
            ->select(
                'members.*',
                'blocks.block_name',
                'roads.road_name',
                'member_type_tb.element as member_type_name',
                'occupation_member_tb.element as member_occupation_name'
            )
            ->leftJoin('blocks', 'blocks.block_code', '=', 'members.society_block')
            ->leftJoin('roads', 'roads.road_code', '=', 'members.society_road')
            ->leftJoin('common_table as member_type_tb', 'member_type_tb.element_code', '=', 'members.member_type')
            ->leftJoin('common_table as occupation_member_tb', 'occupation_member_tb.element_code', '=', 'members.member_occupation')
            ->where('members.is_active', 1);

        if (!empty($arr['anniversaryDate'])) {

            $date = Carbon::parse($arr['anniversaryDate']);

            if ($arr['cardType'] == 'anniversary') {
                $query->whereDay('members.anniversary', $date->day)
                    ->whereMonth('members.anniversary', $date->month);
            }

            if ($arr['cardType'] == 'birthday') {
                $query->whereDay('members.dob', $date->day)
                    ->whereMonth('members.dob', $date->month);
            }
        }

        if (!empty($arr['memberType'])) {
            $query->whereIn('members.member_type', explode(',', $arr['memberType']));
        }

        if (!empty($arr['block'])) {
            $query->whereIn('members.society_block', explode(',', $arr['block']));
        }

        if (!empty($arr['road'])) {
            $query->whereIn('members.society_road', explode(',', $arr['road']));
        }

        if (!empty($arr['occupation'])) {
            $query->whereIn('members.member_occupation', explode(',', $arr['occupation']));
        }

        if (!empty($arr['bloodGroup'])) {
            $query->whereIn('members.blood_group', explode(',', $arr['bloodGroup']));
        }

        return $query->get()->toArray();
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
