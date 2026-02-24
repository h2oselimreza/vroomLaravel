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
                    $viewUrl   = route('admin.member.module.show', $members->id);
                    $editUrl   = route('admin.member.module.edit', $members->id);
                    $deleteUrl = route('admin.member.module.destroy', $members->id);
                    return '
                        <a href="'.$viewUrl.'" 
                        class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary action_button_modify">
                            <span class="ui-button-text">&nbsp;View</span>
                        </a>

                        <a href="'.$editUrl.'" 
                        class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary action_button_modify">
                            <span class="ui-button-text">&nbsp;Edit</span>
                        </a>

                        <a onclick="deleteRecord(\''.$deleteUrl.'\')" 
                        href="javascript:void(0)" 
                        class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary action_button_modify">
                            <span class="ui-button-text">&nbsp;Delete</span>
                        </a>
                    ';
                })

                ->rawColumns(['is_active', 'action'])
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
}
