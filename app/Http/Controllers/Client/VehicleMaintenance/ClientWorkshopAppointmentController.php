<?php

namespace App\Http\Controllers\Client\VehicleMaintenance;

use App\Http\Controllers\Controller;
use App\Repositories\Client\AppointmentRepository;
use App\Repositories\Client\HomeServiceRepository;
use App\Repositories\Client\VehicleRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            $workshopDetails = $this->singleWorkshopDetails($workshop);

            if ($workshopDetails) {
                $workshop = $workshop;
                return view('client.vehicle-maintenance.workshop-appointment.create',compact('workshop','vehicles','distinctServices','serviceVariants','workshopDetails'));
            } else {
                return redirect()->route('client.vehicle-maintenance.workshop-service-list.index')
                ->with('error', 'Work shop details not found');
            }
    }

    // $arr['isActiveFlag'] = 1;
    //     $arr['bulkFlag'] = 2; 
    //     $arr['companyCode'] = auth()->user()?->customerEmployee?->company;
    //     $vehicles = $vehicleRepository->getVehicleInfo($arr);
    //     $variantArr['variantType'] = config('constants.HOME_SER');

    //     $distinctServices = $homeServiceRepository->getDistinctService($variantArr);
    //     $serviceVariants = $homeServiceRepository->getHomeService($variantArr, 1);
    //     $companyInfo = $homeServiceRepository->getSingleCompanyInfo($arr['companyCode']);
    

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
