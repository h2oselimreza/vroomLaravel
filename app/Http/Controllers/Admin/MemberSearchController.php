<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Block;
use App\Models\Road;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MemberSearchController extends Controller
{
    public function index(){
        $blocks = Block::where("is_active",1)->get();
        $roads = Road::where("is_active",1)->get();
        $commonTableElementArr = array('type' => 'occupation');
        $occupations = $this->getCommonTableElement($commonTableElementArr);
        $commonTableElementArr = array('type' => 'member_type');
        $memberTypes = $this->getCommonTableElement($commonTableElementArr);
        $bloodGroups = [["value" => "O+", "label" => "O+"], ["value" => "O-", "label" => "O-"], ["value" => "A+", "label" => "A+"], ["value" => "A-", "label" => "A-"], ["value" => "B+", "label" => "B+"], ["value" => "B-", "label" => "B-"], ["value" => "AB+", "label" => "AB+"], ["value" => "AB-", "label" => "AB-"]];

        return view("admin.members.member_search.index",
        compact("blocks","roads","occupations","memberTypes","bloodGroups"));
    }

    private function getCommonTableElement(array $params) {
        $query =DB::table('common_table');

        if (!empty($params['type'])) {
            $query->where('type', $params['type']);
        }

        if (!empty($params['depend_on_element'])) {
            $query->where('depend_on_element', $params['depend_on_element']);
        }

        return $query->get(); // Returns a Collection of objects
    }

    public function search(Request $request){
        $data['memberType']      = $request->input('memberType');
        $data['gender']          = $request->input('gender');
        $data['block']           = $request->input('block');
        $data['road']            = $request->input('road');
        $data['occupation']      = $request->input('occupation');
        $data['bloodGroup']      = $request->input('bloodGroup');
        $data['anniversaryDate'] = $request->input('anniversaryDate');
        $data['birthdayDate']    = $request->input('birthdayDate');
        $data['dob']             = $request->input('dob');
        $members = $this->getSearchedMembers($data);
        return view('admin.members.member_search.search-list', compact('members'));  
    }

    private function getSearchedMembers(array $arr)
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

        // Filters
        if(!empty($arr['memberType'])) {
            $query->whereIn('members.member_type', explode(',', $arr['memberType']));
        }

        if(!empty($arr['block'])) {
            $query->whereIn('members.society_block', explode(',', $arr['block']));
        }

        if(!empty($arr['road'])) {
            $query->whereIn('members.society_road', explode(',', $arr['road']));
        }

        if(!empty($arr['occupation'])) {
            $query->whereIn('members.member_occupation', explode(',', $arr['occupation']));
        }

        if(!empty($arr['bloodGroup'])) {
            $query->whereIn('members.blood_group', explode(',', $arr['bloodGroup']));
        }

        if(!empty($arr['anniversaryDate'])) {
            $date = \Carbon\Carbon::parse($arr['anniversaryDate']);
            $query->whereDay('members.anniversary', $date->day)
                ->whereMonth('members.anniversary', $date->month);
        }

        if(!empty($arr['birthdayDate'])) {
            $date = \Carbon\Carbon::parse($arr['birthdayDate']);
            $query->whereDay('members.dob', $date->day)
                ->whereMonth('members.dob', $date->month);
        }

        if(!empty($arr['gender'])) {
            $query->where('members.gender', $arr['gender']);
        }

        if(!empty($arr['dob'])) {
            $query->whereDate('members.dob', $arr['dob']); // Compare full date
        }

        // Optional ordering
        // $query->orderBy('members.member_id', 'asc')
        //       ->orderBy('members.donar_member_id', 'asc');

        return $query->get(); // Returns a Collection of objects
    }

    public function print(Request $request){
        $memberIdArr = $array = explode(',', $request->input('member_ids'));
        $personalInformations = $this->getMemberPersonalInfoOrderBy('',$memberIdArr);
        $educationQualifications = $this->getMemberEducationalDetails('',$memberIdArr);
        $workingExperiences = $this->getMemberWorkingDetails('',$memberIdArr);
        $otherFamilyMembers = $this->getMemberFamilyDetails('',$memberIdArr);
        return view('admin.members.profile_view.index',
        compact('personalInformations','educationQualifications','workingExperiences','otherFamilyMembers'));
    }

    private function getMemberPersonalInfoOrderBy($memberId = null, $memberIdArr = [], $flag = null, $memberAutoIdArr = [], $orderBy = null)
    {
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

        if (is_null($flag)) {
            $query->where('members.is_active', 1);
        }

        if ($memberId) {
            $query->where('members.member_id', $memberId);
        } elseif (!empty($memberIdArr)) {
            $query->whereIn('members.id', $memberIdArr);
        } elseif (!empty($memberAutoIdArr)) {
            $query->whereIn('members.id', $memberAutoIdArr);
        }

        // Dynamic ordering
        if ($orderBy) {
            $allowedColumns = ['member_id', 'donar_member_id'];
            if (in_array($orderBy, $allowedColumns)) {
                $query->orderBy("members.$orderBy", 'ASC');
            }
        }

        // Default ordering (optional)
        // $query->orderBy('members.created_dt_tm', 'DESC');

        return $query->get(); // returns a collection of stdClass objects
    }
    private function getMemberEducationalDetails($memberId = null, $memberIdArr = [])
    {
        $query = DB::table('member_edu_qualification')
            ->select(
                'member_edu_qualification.*',
                'education_level_tb.element as education_level',
                'exam_title_tb.element as exam_title',
                'education_board_tb.element as education_board_name',
                'quali_result_tb.element as quali_result_name'
            )
            ->leftJoin('common_table as education_level_tb', 'education_level_tb.element_code', '=', 'member_edu_qualification.level_of_education')
            ->leftJoin('common_table as exam_title_tb', 'exam_title_tb.element_code', '=', 'member_edu_qualification.exam_degree')
            ->leftJoin('common_table as education_board_tb', 'education_board_tb.element_code', '=', 'member_edu_qualification.education_board')
            ->leftJoin('common_table as quali_result_tb', 'quali_result_tb.element_code', '=', 'member_edu_qualification.qualification_result');

        if ($memberId) {
            $query->where('member_edu_qualification.member_id', $memberId);
        } elseif (!empty($memberIdArr)) {
            $query->whereIn('member_edu_qualification.member_id', $memberIdArr);
        }

        return $query->get(); // Returns a collection of objects
    }

    function getMemberWorkingDetails($memberId = null, $memberArray = [])
    {
        $query = DB::table('member_working_experience');

        if ($memberId) {
            $query->where('member_id', $memberId);
        } elseif (!empty($memberArray)) {
            $query->whereIn('member_id', $memberArray);
        }

        $query->orderBy('from_date', 'desc');

        return $query->get(); // Returns a collection of objects
    }

    private function getMemberFamilyDetails($memberId = null, $memberArray = [])
    {
        $query = DB::table('member_family')
            ->select(
                'member_family.*',
                'relaton_tb.element as relation_name',
                'occupation_tb.element as occupation_name'
            )
            ->leftJoin('common_table as relaton_tb', 'relaton_tb.element_code', '=', 'member_family.relation')
            ->leftJoin('common_table as occupation_tb', 'occupation_tb.element_code', '=', 'member_family.occupation');

        if ($memberId) {
            $query->where('member_family.member_id', $memberId);
        } elseif (!empty($memberArray)) {
            $query->whereIn('member_family.member_id', $memberArray);
        }

        return $query->get(); // Returns collection
    }
}
