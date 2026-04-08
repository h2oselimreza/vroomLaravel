<?php

namespace App\Http\Controllers\Admin\Workshop;

use App\Http\Controllers\Controller;
use App\Models\Admin\Workshop\Workshop;
use App\Models\Admin\Workshop\WorkshopVehicleType;
use App\Repositories\CommonRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkshopVehicleTypeController extends Controller
{
    public function edit($workshopCode, CommonRepository $commonRepository){
        $data = Workshop::where('workshop_code', $workshopCode)->first();

        $commonTableElementArr = array('type' => 'vehicle_type');
        $vehicleTypes = $commonRepository->getCommonTableElement($commonTableElementArr);

        $arr = ['bulkFlag'=>0,'workshopCode'=>$workshopCode];
        $workshopVehicleTypes = WorkshopVehicleType::where('is_active', 1)
        ->when($arr['bulkFlag'] == 0, function ($query) use ($arr) {
            return $query->where('workshop', $arr['workshopCode']);
        })
        ->get();

        return view('admin.work-shop.vehicle-type.create-edit',compact('data', 'vehicleTypes', 'workshopVehicleTypes', 'workshopCode'));
    }

    public function update(Request $request, $workshopCode)
    {

        $workshopCode = $request->input('workshopCode');
        $vehicleTypeSerial = (int) $request->input('vehicleTypeSerial');
        $removeIdsStr = $request->input('removeVehicleTypeIdStr');

        // 2. Validate Workshop Existence
        $workshopExists = Workshop::where('workshop_code', $workshopCode)->exists();
        if (!$workshopExists) {
            return redirect()->route('admin.workshop.list')->with('error', 'Workshop not found.');
        }

        try {
            DB::beginTransaction();

            $hasSelections = false;

            // 3. Handle Deletions first (IDs tracked by JS)
            if (!empty($removeIdsStr)) {
                $removeIds = explode(',', $removeIdsStr);
                WorkshopVehicleType::whereIn('id', $removeIds)->delete();
            }

            // 4. Process Loop
            // Note: Use <= $vehicleTypeSerial if your serial starts at 1 and includes the last count
            for ($i = 1; $i <= $vehicleTypeSerial; $i++) {
                if ($request->has('vehicleTypeCheckBox' . $i)) {
                    $hasSelections = true;
                    $existingId = $request->input('vehicleTypeId' . $i);

                    WorkshopVehicleType::updateOrCreate(
                        ['id' => $existingId], // Match by ID
                        [
                            'workshop'      => $workshopCode,
                            'vehicle_type'  => $request->input('vehicleTypeCode' . $i),
                        ]
                    );
                }
            }

            DB::commit();

            $status = $hasSelections ? 1 : 0;
            return redirect()->route('admin.workshop-vehicle-type.edit', [$workshopCode, $status])
                            ->with('success', 'Vehicle types updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
}
