<?php

namespace App\Http\Controllers\Admin\Workshop;

use App\Http\Controllers\Controller;
use App\Models\Admin\Workshop\Workshop;
use App\Models\Client\WorkshopService;
use App\Repositories\Client\AppointmentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    public function edit($workshopCode,AppointmentRepository $appointmentRepository)
    {
        $arr['workshopCode'] = $workshopCode;
        $arr['bulkFlag'] = 0;
        $variantArr['variantType'] = config('constants.APPOINTMENT_SER');
        $variantArr['workshopCode'] = $workshopCode;

        $distinctServices = $appointmentRepository->getDistinctService($variantArr);
        $serviceVariants = $appointmentRepository->getAssignWorkshopService($variantArr, 1);
        $data = Workshop::where('workshop_code', $workshopCode)->first();
        return view('admin.work-shop.service.create-edit',compact('data','distinctServices','serviceVariants','workshopCode'));
            
    }

    public function store(Request $request)
    {
        $workshopCode = $request->input('workshopCode');
        $count = (int) $request->input('serviceVariantCount');
        $removeIds = $request->input('removeServiceVarIdStr');
    
        if (!$workshopCode) {
            return back()->with('error', 'Invalid workshop code');
        }
    
        // Check workshop exists
        $workshopExists = Workshop::where('workshop_code', $workshopCode)->exists();
    
        if (!$workshopExists) {
            return redirect()->route('admin.workshop.list');
        }
    
        $insertArr = [];
        $updateArr = [];
    
        DB::beginTransaction();
    
        try {
    
            // Delete removed records
            if (!empty($removeIds)) {
                $ids = array_filter(explode(',', $removeIds));
    
                WorkshopService::whereIn('id', $ids)->delete();
            }
    
            // Loop variants
            for ($i = 1; $i < $count; $i++) {
    
                if ($request->has('serviceVarCheckBox' . $i)) {
    
                    $id = $request->input('serviceVariantId' . $i);
                    $code = $request->input('serviceVariantCode' . $i);
    
                    if ($id) {
                        // UPDATE DATA
                        $updateArr[] = [
                            'id' => (int) $id,
                            'workshop' => $workshopCode,
                            'service_variant' => $code,
                            // 'updated_by' => $userId,
                            // 'updated_dt_tm' => $now,
                        ];
                    } else {
                        // INSERT DATA
                        $insertArr[] = [
                            'workshop' => $workshopCode,
                            'service_variant' => $code,
                             'created_by' => auth()->user()->user_id,
                            'created_dt_tm' => now(),
                             'updated_by' => auth()->user()->user_id,
                            'updated_dt_tm' => now(),
                        ];
                    }
                }
            }
    
            // Batch Insert
            if (!empty($insertArr)) {
                WorkshopService::insert($insertArr);
            }
    
            // Batch Update (manual loop because Laravel has no native updateBatch)
            foreach ($updateArr as $row) {
                WorkshopService::where('id', $row['id'])->update($row);
            }
    
            DB::commit();
    
            return redirect()->route('admin.workshop-service.edit', $workshopCode)
            ->with('success', 'Service saved successfully');
    
        } catch (\Exception $e) {
    
            DB::rollBack();
    
            return back()->with('error', $e->getMessage());
        }
    }
}
