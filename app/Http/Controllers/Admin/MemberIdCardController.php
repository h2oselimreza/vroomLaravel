<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class MemberIdCardController extends Controller
{
    public function index(){
        return view("admin.member-id-card.index");
    }

    public function getMemberIdCardData(Request $request){
        if ($request->ajax()) {

            $members = $this->getMemberPersonalInfo('','','','');

            return DataTables::of($members)

                ->addIndexColumn()

                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="rowCheckbox" value="'.$row->member_id.'">';
                })
                ->rawColumns(['checkbox'])
                ->make(true);

        }
    }

    private function getMemberPersonalInfo($memberId = null, $memberIdArr = [], $flag = null, $memberAutoIdArr = [])
    {
        $query = \DB::table('members')
            ->select(
                'members.id',
                'members.member_id',
                'members.member_name',
                'members.father_contact as contact_no',
                'blocks.block_name as block',
                'roads.road_name as road',
                'members.society_plot',
                'members.society_flat',
                'members.is_active as status',
            )
            ->leftJoin('blocks', 'blocks.block_code', '=', 'members.society_block')
            ->leftJoin('roads', 'roads.road_code', '=', 'members.society_road')
            ->leftJoin('common_table as member_type_tb', 'member_type_tb.element_code', '=', 'members.member_type')
            ->leftJoin('common_table as occupation_father_tb', 'occupation_father_tb.element_code', '=', 'members.father_occupation')
            ->leftJoin('common_table as occupation_mother_tb', 'occupation_mother_tb.element_code', '=', 'members.mother_occupation')
            ->leftJoin('common_table as occupation_spouse_tb', 'occupation_spouse_tb.element_code', '=', 'members.spouse_occupation')
            ->leftJoin('common_table as occupation_member_tb', 'occupation_member_tb.element_code', '=', 'members.member_occupation')
            ->leftJoin('members as first_introduced_tb', 'first_introduced_tb.member_id', '=', 'members.first_introduced_by')
            ->leftJoin('members as second_introduced_tb', 'second_introduced_tb.member_id', '=', 'members.second_introduced_by')
            ->leftJoin('users', 'users.user_id', '=', 'members.member_id')
            ->leftJoin('user_group', 'user_group.id', '=', 'users.user_group');

        if (is_null($flag)) {
            $query->where('members.is_active', 1);
        }

        if (!empty($memberId)) {
            $query->where('members.member_id', $memberId);
        } elseif (!empty($memberIdArr)) {
            $query->whereIn('members.member_id', $memberIdArr);
        } elseif (!empty($memberAutoIdArr)) {
            $query->whereIn('members.id', $memberAutoIdArr);
        }

        $query->orderBy('members.created_dt_tm', 'DESC');

        return $query->get();
    }

    public function PrintMemberIdCard(Request $request){
        //dd($request->all());
        $memberIdArr = explode(',', $request->member_ids);
        $isIdCard = $request->isIdCard;
        $members = $this->getMemberIdCardInfo(null, $memberIdArr,1, null);
        //dd($members);
        if (!empty($isIdCard)) {
            return view("admin.member-id-card.showMemberListPrintView", compact("members"));
        }else{
            //dd($members);
            return view("admin.member-id-card.memberIdCardPrintView", compact("members"));
        }
        
    }

    private function getMemberIdCardInfo(
        $memberId = null,
        $memberIdArr = [],
        $flag = null,
        $memberAutoIdArr = []
    ) {
        $query = DB::table('members')
            ->select(
                'members.*',
                'blocks.block_name',
                'roads.road_name',
                'member_type_tb.element as member_type_name',
                'first_introduced_tb.member_name as first_intro_member_name',
                'second_introduced_tb.member_name as second_intro_member_name',
                'user_group.group_name',
                'occupation_father_tb.element as father_occupation_name',
                'occupation_mother_tb.element as mother_occupation_name',
                'occupation_spouse_tb.element as spouse_occupation_name',
                'occupation_member_tb.element as member_occupation_name'
            )
            ->leftJoin('blocks', 'blocks.block_code', '=', 'members.society_block')
            ->leftJoin('roads', 'roads.road_code', '=', 'members.society_road')
            ->leftJoin('common_table as member_type_tb', 'member_type_tb.element_code', '=', 'members.member_type')
            ->leftJoin('common_table as occupation_father_tb', 'occupation_father_tb.element_code', '=', 'members.father_occupation')
            ->leftJoin('common_table as occupation_mother_tb', 'occupation_mother_tb.element_code', '=', 'members.mother_occupation')
            ->leftJoin('common_table as occupation_spouse_tb', 'occupation_spouse_tb.element_code', '=', 'members.spouse_occupation')
            ->leftJoin('common_table as occupation_member_tb', 'occupation_member_tb.element_code', '=', 'members.member_occupation')
            ->leftJoin('members as first_introduced_tb', 'first_introduced_tb.member_id', '=', 'members.first_introduced_by')
            ->leftJoin('members as second_introduced_tb', 'second_introduced_tb.member_id', '=', 'members.second_introduced_by')
            ->leftJoin('users', 'users.user_id', '=', 'members.member_id')
            ->leftJoin('user_group', 'user_group.id', '=', 'users.user_group');

        // Active filter
        if (is_null($flag)) {
            $query->where('members.is_active', 1);
        }

        // Conditions
        if ($memberId) {
            $query->where('members.member_id', $memberId);
        } elseif (!empty($memberIdArr)) {
            $query->whereIn('members.member_id', $memberIdArr);
        } elseif (!empty($memberAutoIdArr)) {
            $query->whereIn('members.id', $memberAutoIdArr);
        }

        return $query
            ->orderByDesc('members.created_dt_tm')
            ->get()
            ->toArray();
    }
}
