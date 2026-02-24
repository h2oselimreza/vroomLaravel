<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MemberWrokingExperience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MemberWorkingExperieanceController extends Controller
{
     public function edit($id){
        $data = Member::findOrFail($id);
        $empWorkingDetails = $this->getEmpWorkingDetails($id);
        return view("admin.members.working_experience.edit",
        compact("data",'empWorkingDetails')
        );
    }
    
    public function update(Request $request, $id)
    {
        $workingExpCount = (int) $request->workingExpCount;
        // IDs of rows deleted by user
        $deleteRows = $request->deleteWorkingRow ? explode(',', $request->deleteWorkingRow) : [];

        DB::transaction(function () use ($request, $id, $workingExpCount, $deleteRows) {

            // 1️⃣ Delete removed rows
            if (!empty($deleteRows)) {
                MemberWrokingExperience::whereIn('id', $deleteRows)->delete();
            }

            // 2️⃣ Loop through each working experience block
            for ($i = 0; $i <= $workingExpCount; $i++) {
                // Skip if no institution name (empty row)
                if (!$request->has("institutionName{$i}") || empty($request->input("institutionName{$i}"))) {
                    continue;
                }

                // Determine From Date
                $fromYear  = $request->input("fYear{$i}") ?? '0000';
                $fromMonth = $request->input("fMonth{$i}") ?? '00';
                $fromDay   = $request->input("fDay{$i}") ?? '00';
                $fromDate  = "{$fromYear}-{$fromMonth}-{$fromDay}";

                // Determine To Date
                $isContinued = $request->input("currentWorkCheckbox{$i}") == '1' ? 1 : 0;

                if ($isContinued) {
                    $toDate = null; // still working
                } else {
                    $toYear  = $request->input("tYear{$i}") ?? '0000';
                    $toMonth = $request->input("tMonth{$i}") ?? '00';
                    $toDay   = $request->input("tDay{$i}") ?? '00';
                    $toDate  = "{$toYear}-{$toMonth}-{$toDay}";
                }

                // Check if existing row
                if ($request->filled("hiddenWorkingDiv{$i}")) {
                    $workingExp = MemberWrokingExperience::find($request->input("hiddenWorkingDiv{$i}"));
                } else {
                    $workingExp = new MemberWrokingExperience();
                    $workingExp->member_id = $id;
                }

                // Save/update values
                $workingExp->institution_name  = $request->input("institutionName{$i}");
                $workingExp->institution_type  = $request->input("institutionType{$i}");
                $workingExp->address           = $request->input("address{$i}");
                $workingExp->designation       = $request->input("designation{$i}");
                $workingExp->department        = $request->input("department{$i}");
                $workingExp->from_date         = $fromDate;
                $workingExp->to_date           = $toDate;
                $workingExp->is_continued      = $isContinued;
                $workingExp->responsibilites   = $request->input("responsibilites{$i}");
                $workingExp->save();
            }
        });

        return redirect()->back()->with('success', 'Working experience updated successfully!');
    
    }

    private function getEmpWorkingDetails($id = null, $employeeIdArray = [])
    {
        $query = MemberWrokingExperience::query();

        if ($id) {
            $query->where('member_id', $id);
        } elseif (!empty($employeeIdArray)) {
            $query->whereIn('member_id', $employeeIdArray);
        }

        $query->orderBy('from_date', 'desc');

        return $query->get(); // returns a Collection
    }
}
