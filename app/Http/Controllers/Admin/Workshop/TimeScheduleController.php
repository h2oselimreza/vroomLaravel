<?php

namespace App\Http\Controllers\Admin\Workshop;

use App\Http\Controllers\Controller;
use App\Models\Admin\Workshop\Workshop;
use App\Models\Admin\Workshop\WorkshopSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TimeScheduleController extends Controller
{
    public function edit($workshopCode){
        $arr = ['bulkFlag'=>0,'workshopCode'=>$workshopCode];
        $timeSchedules = WorkshopSchedule::where('is_active', 1)
        ->when($arr['bulkFlag'] == 0, function ($query) use ($arr) {
            return $query->where('workshop', $arr['workshopCode']);
        })
        ->get();
        $commonTableElementArr = array('type' => 'week_day');
        $weekdays = $this->getCommonTableElement($commonTableElementArr);
        return view('admin.work-shop.time-schedule.create-edit',compact('timeSchedules','weekdays','workshopCode'));
    }

    private function getCommonTableElement($commonTableElementArr)
    {
        return DB::table('common_table')
            ->when(isset($commonTableElementArr['type']), function ($q) use ($commonTableElementArr) {
                $q->where('type', $commonTableElementArr['type']);
            })
            ->when(isset($commonTableElementArr['depend_on_element']), function ($q) use ($commonTableElementArr) {
                $q->where('depend_on_element', $commonTableElementArr['depend_on_element']);
            })
            ->get()
            ->toArray();
    }

    public function update(Request $request)
    {

        $workshopCode = $request->input('workshopCode');
        $workshop = Workshop::where('workshop_code', $workshopCode)->first();
        if (!$workshop) {
            return redirect()->route('admin.workshop-list.index')->with('error', 'Workshop not found');
        }

        try {
            DB::beginTransaction();

            for ($i = 1; $i <= 7; $i++) {
                $isWeekend = $request->has('weekendCheckBox' . $i);
                
                $startTime = null;
                $endTime = null;
                $totalTime = null;
                $weekendStatus = 1;

                if (!$isWeekend) {
                    $regFromTime = $request->input('regFromTime' . $i);
                    $regToTime = $request->input('regToTime' . $i);
                    $regMinTime = $request->input('regMinTime' . $i); // Format H:i

                    // Validation: Total Time calculation
                    $totalMinutesAvailable = round(abs(strtotime($regToTime) - strtotime($regFromTime)) / 60, 2);
                    $timeParts = explode(':', $regMinTime);
                    $inputMinutes = ((int)$timeParts[0] * 60) + (int)($timeParts[1] ?? 0);

                    if ($inputMinutes > $totalMinutesAvailable) {
                        throw new \Exception("Invalid time duration for day $i");
                    }

                    $startTime = date("H:i:s", strtotime($regFromTime));
                    $endTime = date("H:i:s", strtotime($regToTime));
                    $totalTime = $regMinTime;
                    $weekendStatus = 0;
                }

                WorkshopSchedule::updateOrCreate(
                    [
                        'id' => $request->input('timeScheduleId' . $i), // Search criteria
                    ],
                    [
                        'workshop'       => $workshopCode,
                        'week_day'       => $request->input('weekDayCode' . $i),
                        'start_time'     => $startTime,
                        'end_time'       => $endTime,
                        'total_time'     => $totalTime,
                        'weekend_status' => $weekendStatus,
                        'is_active'      => 1,
                    ]
                );
            }

            DB::commit();
            return back()->with('success', 'Time schedule saved successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to save: ' . $e->getMessage());
        }
    }
}
