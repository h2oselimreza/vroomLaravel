<?php

namespace App\Http\Controllers\Admin\Place;

use App\Http\Controllers\Controller;
use App\Models\Admin\Place\Place;
use App\Models\Admin\Place\PlaceTimeSchedule;
use App\Repositories\CommonRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlaceTimeScheduleController extends Controller
{
     public function edit($placeCode, CommonRepository $commonRepository){
        $arr = ['bulkFlag'=>0,'placeCode'=>$placeCode];

        $timeSchedules = PlaceTimeSchedule::where('is_active', 1)
        ->when($arr['bulkFlag'] == 0, function ($query) use ($arr) {
            return $query->where('place', $arr['placeCode']);
        })
        ->get();

        $commonTableElementArr = array('type' => 'week_day');
        $weekdays = $commonRepository->getCommonTableElement($commonTableElementArr);
        $data = Place::where('place_code',$placeCode)->first();
        return view('admin.place.time-schedule-create-update',compact('timeSchedules','weekdays','placeCode','data'));
    }

    public function update(Request $request)
    {
        $placeCode = $request->input('placeCode');
        $place = Place::where('place_code', $placeCode)->first();
        if (!$place) {
            return redirect()->route('admin.place.place-info.index')->with('error', 'Place not found');
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

                PlaceTimeSchedule::updateOrCreate(
                    [
                        'id' => $request->input('timeScheduleId' . $i), // Search criteria
                    ],
                    [
                        'place'       => $placeCode,
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
