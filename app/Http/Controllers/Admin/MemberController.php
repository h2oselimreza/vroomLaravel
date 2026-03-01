<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MemberRequest;
use App\Models\Block;
use App\Models\Road;
use Illuminate\Http\Request;
use App\Models\Member;
use App\Services\TokenService;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("admin.members.index");
    }

    public function getMemberData(Request $request){
        if ($request->ajax()) {

            $members = $this->getMemberPersonalInfo('','','','');

            return DataTables::of($members)

                ->addIndexColumn()

                ->addColumn('action', content: function ($members) {
                    $editUrl   = route('admin.member.module.edit', $members->id);
                    $activeInactiveUrl   = route('admin.member.status', $members->id);
                    $statusText = $members->status == 1 ? 'Inactive' : 'Active';
                    return '
                            <div class="dropdown">
                            <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Actions
                            </button>

                            <ul class="dropdown-menu dropdown-menu-end">

                                <!-- Edit -->
                                <li>
                                    <a class="dropdown-item d-flex align-items-center" href="' . $editUrl . '">
                                        <i class="fa fa-edit me-2"></i> Edit
                                    </a>
                                </li>

                                <!-- Active / Inactive -->
                                <li>
                                    <form action="' . $activeInactiveUrl . '" method="POST">
                                        ' . csrf_field() . '
                                        <input type="hidden" name="_method" value="PATCH">
                                        <button type="submit" class="dropdown-item d-flex align-items-center">
                                            <i class="fa fa-toggle-on me-2"></i> ' . $statusText . '
                                        </button>
                                    </form>
                                </li>

                            </ul>
                        </div>
                    ';
                })

                ->addColumn('checkbox', function ($member) {
                    return '<input type="checkbox" class="rowCheckbox" value="'.$member->id.'">';
                })

                ->rawColumns(['is_active', 'action','checkbox'])
                ->make(true);

        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $blocks = Block::getSocietyBlock();
        $roads = Road::getSocietyRoad();
        $blockRoads = Block::getSocietyBlocksRoads();
        //dd($blockRoads);
        return view('admin.members.create-edit',compact('blocks','roads','blockRoads'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MemberRequest $request, TokenService $tokenService)
    {
        $prefix = "LM-";

        DB::transaction(function () use ($request, $tokenService, $prefix) {
            // Generate token inside the transaction
            $memberId = $prefix . $tokenService->getTokenByCode($prefix);
            //dd($memberId);
            $data = $request->validated();
            $data['member_id'] = $memberId;

            Member::create($data);
        });

        return redirect()
            ->route('admin.member.module.index')
            ->with('success', 'Member created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Member::findOrFail($id);
        //dd($data);
        $blocks = Block::getSocietyBlock();
        $roads = Road::getSocietyRoad();
        $blockRoads = Block::getSocietyBlocksRoads();
        return view('admin.members.create-edit', compact('data','blocks','roads','blockRoads'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MemberRequest $request, Member $member)
    {
        $member->update($request->validated());

        return redirect()
            ->route('admin.member.module.edit', $member->id)
            ->with('success', 'Member updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    private function getMemberPersonalInfo($memberId = null, $memberIdArr = [], $flag = null, $memberAutoIdArr = [])
    {
        $query = \DB::table('members')
            ->select(
                'members.id',
                'members.id',
                'members.member_id',
                'members.donar_member_id',
                'members.member_name',
                'members.member_type',
                'members.father_contact as contact_no',
                'blocks.block_name as block',
                'roads.road_name as road',
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

    public function showMemberInfo(Request $request){
        //dd($request->all());
        $memberIdArr = $array = explode(',', $request->input('member_ids'));
        $personalInformations = $this->getMemberPersonalInfoOrderBy('',$memberIdArr);
        $educationQualifications = $this->getMemberEducationalDetails('',$memberIdArr);
        $workingExperiences = $this->getMemberWorkingDetails('',$memberIdArr);
        $otherFamilyMembers = $this->getMemberFamilyDetails('',$memberIdArr);
        return view('admin.members.profile_view.index',
        compact('personalInformations','educationQualifications','workingExperiences','otherFamilyMembers')
        );
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

    public function updateStatus($id)
    {
        $member = Member::findOrFail($id);
        $member->is_active = $member->is_active == 1 ? 0 : 1;
        $member->save();
        return redirect()->back()->with('success', 'Status Updated Successfully');
    }
}
