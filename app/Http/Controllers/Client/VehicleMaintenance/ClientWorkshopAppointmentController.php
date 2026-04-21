<?php

namespace App\Http\Controllers\Client\VehicleMaintenance;

use App\Http\Controllers\Controller;
use App\Repositories\Client\AppointmentRepository;
use App\Repositories\Client\HomeServiceRepository;
use App\Repositories\Client\VehicleRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Pest\Support\Str;

class ClientWorkshopAppointmentController extends Controller
{
    public function index(AppointmentRepository $appointmentRepository){
        $arr['companyCode'] = auth()->user()?->customerEmployee?->company;
        $arr['status'] = config('constants.APPOINTMENT_ALL');
        $appointmentLists = $appointmentRepository->getAppointmentList($arr);
        return view('client.vehicle-maintenance.workshop-appointment.index',compact('appointmentLists'));
    }

    public function getWorkshopInfo(Request $request, AppointmentRepository $appointmentRepository){
        $workshopCode = $request->workshopCode;
        $result = $appointmentRepository->getWorkshopInfo($workshopCode);
        return $result;
    }

    public function createAppointment(Request $request, VehicleRepository $vehicleRepository){
            $workshop = $request->workshop;
            $arr['isActiveFlag'] = 1;
            $arr['bulkFlag'] = 2;  // 2 means all vehicle without vehicle array
            $arr['companyCode'] = auth()->user()?->customerEmployee?->company;
            $vehicles = $vehicleRepository->getVehicleInfo($arr);
            $variantArr['variantType'] = config('constants.APPOINTMENT_SER');
            $variantArr['workshopCode'] = $workshop;
            $distinctServices = $this->getDistinctService($variantArr);
            $serviceVariants = $this->getWorkshopService($variantArr, 1);
            dd($distinctServices);
            $workshopDetails = $this->singleWorkshopDetails($workshop);

            if ($workshopDetails) {
                $workshop = $workshop;
                return view('client.vehicle-maintenance.workshop-appointment.create',compact('workshop','vehicles','distinctServices','serviceVariants','workshopDetails'));
            } else {
                return redirect()->route('client.vehicle-maintenance.workshop-service-list.index')
                ->with('error', 'Work shop details not found');
            }
    }

    public function addNewAppointment(Request $request, AppointmentRepository $appointmentRepository)
    {
        // Basic validation (important for safety)
        $validated = $request->validate([
            'vehicleCount' => 'required|integer|min:1',
            'workshop'     => 'required|string',
            'date1'        => 'required|date',
            'timeSlot1'    => 'required|string',
            'date2'        => 'nullable|date',
            'timeSlot2'    => 'nullable|string',
            'remarks'      => 'nullable|string',
        ]);

        $vehicleCount = (int) $validated['vehicleCount'];

        // Summary data
        $summaryArr = [
            'workshop'      => $request->workshop,
            'date_1'        => $request->date1,
            'time_slot_1'   => $request->timeSlot1,
            'date_2'        => $request->date2 ? $request->date2 : null,
            'time_slot_2'   => $request->timeSlot2 ? $request->timeSlot2 : null,
            'remarks'       => $request->remarks ? $request->remarks : null,
        ];

        // Required field check
        if (!$summaryArr['workshop'] || !$summaryArr['date_1'] || !$summaryArr['time_slot_1'] || !$vehicleCount) {
            return redirect('client/Appointment/setAppoinment');
        }

        // Generate appointment number (Laravel-safe alternative)
        $appoinmentNo = 'APPT' . now()->format('Ym') . strtoupper(Str::random(5));

        $detailArr = [];
        $serviceVarCodeArr = [];

        for ($i = 1; $i <= $vehicleCount; $i++) {

            $vehicleId = $request->input('vehicleId' . $i);

            if (!$vehicleId) {
                return redirect('client/Appointment/setAppoinment');
            }

            $takenServiceVarCount = (int) $request->input('takenServiceVarCount' . $i, 0);

            for ($j = 1; $j <= $takenServiceVarCount; $j++) {

                $serviceVarCode = trim($request->input('takenServiceVarCode' . $i . $j));

                if ($serviceVarCode) {
                    $detailArr[] = [
                        'vehicle'          => $vehicleId,
                        'appointment_no'   => $appoinmentNo,
                        'service_variant'  => $serviceVarCode,
                        'created_by'       => auth()->user()?->customerEmployee?->company ?? null,
                        'created_type'     => config('constants.CLIENT'),
                        'created_dt_tm'    => now(),
                        'updated_by'       => auth()->user()?->customerEmployee?->company ?? null,
                        'updated_type'     => config('constants.CLIENT'),
                        'updated_dt_tm'    => now(),
                    ];

                    $serviceVarCodeArr[] = $serviceVarCode;
                }
            }
        }

        // 🧾 Final summary
        $summaryArr['appointment_no'] = $appoinmentNo;
        $summaryArr['company']        = auth()->user()?->customerEmployee?->company ?? null;
        $summaryArr['status']         = 'PENDING';
        $summaryArr['created_by']     = auth()->user()->user_id;
        $summaryArr['created_type']   = config('constants.CLIENT');
        $summaryArr['created_dt_tm']  = now();
        $summaryArr['updated_by']     = auth()->user()->user_id;
        $summaryArr['updated_type']   = config('constants.CLIENT');
        $summaryArr['updated_dt_tm']  = now();

        // 💾 Transaction-safe insert (VERY IMPORTANT for production)
        DB::beginTransaction();

        try {
            $result = $appointmentRepository->addNewAppointment($summaryArr, $detailArr, $serviceVarCodeArr);

            DB::commit();

            return redirect('client/Appointment/setAppoinment/' . $result);

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Appointment creation failed: ' . $e->getMessage());
        }
    }
    

    function getDistinctService($variantArr)
    {
        return DB::table('service_variants')
            ->select('service_variants.service', 'services.service_name')
            ->join('workshop_service', 'workshop_service.service_variant', '=', 'service_variants.variant_code')
            ->join('services', 'services.service_code', '=', 'service_variants.service')
            ->where('service_variants.variant_type', $variantArr['variantType'])
            ->where('workshop_service.workshop', $variantArr['workshopCode'])
            ->where('service_variants.is_active', 1)
            ->where('services.is_active', 1)
            ->distinct()
            ->get()
            ->toArray();
    }

    public function getWorkshopService($arr, $isActiveFlag = 1)
    {
        return DB::table('workshop_service')
            ->select(
                'workshop_service.workshop',
                'workshop_service.service_variant as variant_code',
                'service_variants.service_variant_name',
                'service_variants.variant_type',
                'service_variants.default_variant',
                'service_variants.service',
                'services.service_name'
            )
            ->join('service_variants', 'service_variants.variant_code', '=', 'workshop_service.service_variant')
            ->join('services', 'services.service_code', '=', 'service_variants.service')
            ->where('workshop_service.workshop', $arr['workshopCode'])
            ->where('service_variants.variant_type', $arr['variantType'])
            ->where('service_variants.is_active', 1)
            ->where('services.is_active', 1)
            ->orderBy('service_variants.service', 'ASC')
            ->get()
            ->toArray();
    }

    function singleWorkshopDetails($workshopCode)
    {
        return DB::table('workshops')
            ->select(
                'workshops.*',
                'divisions.division_en_name',
                'divisions.division_bn_name',
                'districts.district_en_name',
                'districts.district_bn_name',
                'upazilas.upozilla_en_name',
                'upazilas.upozilla_bn_name'
            )
            ->join('divisions', 'divisions.id', '=', 'workshops.division')
            ->join('districts', 'districts.id', '=', 'workshops.district')
            ->join('upazilas', 'upazilas.id', '=', 'workshops.upozilla')
            ->where('workshops.workshop_code', $workshopCode)
            ->where('workshops.is_active', 1)
            ->first();
    }
}
