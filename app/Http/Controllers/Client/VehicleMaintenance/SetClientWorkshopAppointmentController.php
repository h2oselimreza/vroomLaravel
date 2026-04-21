<?php

namespace App\Http\Controllers\Client\VehicleMaintenance;

use App\Http\Controllers\Controller;
use App\Repositories\Client\AppointmentRepository;
use App\Repositories\MasterData\AreaRepository;
use Illuminate\Http\Request;

class SetClientWorkshopAppointmentController extends Controller
{

    public function setAppointment(Request $request, AreaRepository $areaRepository, AppointmentRepository $appointmentRepository)
    {
        $variantArr = [
            'variantType' => config('constants.APPOINTMENT_SER'),
        ];

        $distinctServices = $appointmentRepository->getDistinctService($variantArr);

        $serviceVariants = $appointmentRepository->getWorkshopService($variantArr, 1);

        $divisions = $areaRepository->getDivision();

        $districts = [
            'districtData' => $areaRepository->getDistrict()
        ];

        $upozillas = [
            'upozillaData' => $areaRepository->getUpozilla()
        ];

        // ==============================
        // 4. Selected Service Variants
        // ==============================
        $serviceVarArr = [];

        $searchArr = [
            'division'  => $request->input('division'),
            'district'  => $request->input('district'),
            'upozilla'  => $request->input('upozilla'),
        ];

        $serviceVariantCount = (int) $request->input('serviceVariantCount');

        for ($i = 1; $i <= $serviceVariantCount; $i++) {

            if ($request->has("serviceVarCheckBox$i")) {
                $serviceVarArr[] = $request->input("serviceVariantCode$i");
            }
        }

        // ==============================
        // 5. Workshop List
        // ==============================
        $searchVariantArr = $serviceVarArr;
        $searchArea = $searchArr;

        $workshops = $appointmentRepository->getWorkshopList($searchArr, $serviceVarArr);

        return view('client.vehicle-maintenance.workshop-appointment.set-appointment', 
        compact('workshops','distinctServices','serviceVariants', 'divisions', 'districts', 'upozillas', 'searchArea', 'searchVariantArr'));
    }
}
