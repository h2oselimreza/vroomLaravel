<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MemberFamily;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MemberFamilyMemberController extends Controller
{
    public function index($id){
        $data = Member::where(['id'=>$id])->first();
        $memberFamilyDetails = $this->getMemberFamilyDetails($id);
        $commonTableElementArr = array('type' => 'family_relation');
        $relations = $this->getCommonTableElement($commonTableElementArr);
        $commonTableElementArr = array('type' => 'occupation');
        $occupations = $this->getCommonTableElement($commonTableElementArr);
        return view("admin.members.family_member.edit",
        compact('data','memberFamilyDetails','relations','occupations')
        );
    }
    
    public function update(Request $request, $memberId)
{
    DB::beginTransaction();

    try {

        // 2. Submitted family members
        $familyData = $request->input('family_members', []);
        $existingIds = [];

        foreach ($familyData as $family) {
            if (!empty($family['id'])) {
                // Update existing family member
                $memberFamily = MemberFamily::find($family['id']);
                if ($memberFamily) {
                    $memberFamily->update([
                        'name'       => $family['name'] ?? null,
                        'relation'   => $family['relation'] ?? null,
                        'dob'        => $family['dob'] ?? null,
                        'gender'     => $family['gender'] ?? null,
                        'mobile'     => $family['mobile'] ?? null,
                        'email'      => $family['email'] ?? null,
                        'occupation' => $family['occupation'] ?? null,
                    ]);
                    $existingIds[] = $memberFamily->id;
                }
            } else {
                // Insert new family member
                $newMemberFamily = MemberFamily::create([
                    'member_id'  => $memberId, // <-- correct parent ID
                    'name'       => $family['name'] ?? null,
                    'relation'   => $family['relation'] ?? null,
                    'dob'        => $family['dob'] ?? null,
                    'gender'     => $family['gender'] ?? null,
                    'mobile'     => $family['mobile'] ?? null,
                    'email'      => $family['email'] ?? null,
                    'occupation' => $family['occupation'] ?? null,
                    'is_active'  => 1,
                ]);
                $existingIds[] = $newMemberFamily->id;
            }
        }

        // 3. Delete removed members
        if ($request->filled('deleteMemberRow')) {
            $deleteIds = explode(',', $request->input('deleteMemberRow'));
            MemberFamily::whereIn('id', $deleteIds)->delete();
        }

        // 4. Delete family members not submitted
        MemberFamily::where('member_id', $memberId)
                    ->whereNotIn('id', $existingIds)
                    ->delete();

        DB::commit();

        return redirect()->back()->with('success', 'Member family details updated successfully.');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Error updating member family: ' . $e->getMessage());
    }
}

    private function getMemberFamilyDetails($memberId = null, $memberArray = [])
    {
        $query = \DB::table('member_family')
            ->select(
                'member_family.*',
                'relation_tb.element as relation_name',
                'occupation_tb.element as occupation_name'
            )
            ->leftJoin('common_table as relation_tb', 'relation_tb.element_code', '=', 'member_family.relation')
            ->leftJoin('common_table as occupation_tb', 'occupation_tb.element_code', '=', 'member_family.occupation');

        if ($memberId) {
            $query->where('member_family.member_id', $memberId);
        } else {
            $query->whereIn('member_family.member_id', $memberArray);
        }

        return $query->get()->toArray();
    }

   public function getCommonTableElement($commonTableElementArr = [])
    {
        $query = \DB::table('common_table');

        if (isset($commonTableElementArr['type'])) {
            $query->where('type', $commonTableElementArr['type']);
        }

        if (isset($commonTableElementArr['depend_on_element'])) {
            $query->where('depend_on_element', $commonTableElementArr['depend_on_element']);
        }

        return $query->get()->toArray();
    }

}
