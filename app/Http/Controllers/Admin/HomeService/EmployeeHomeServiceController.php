<?php

namespace App\Http\Controllers\Admin\HomeService;

use App\Http\Controllers\Controller;
use App\Repositories\Client\AppointmentRepository;
use App\Repositories\Client\HomeServiceRepository;
use App\Repositories\CommonRepository;
use Illuminate\Http\Request;

class EmployeeHomeServiceController extends Controller
{
    public function index(HomeServiceRepository $homeServiceRepository){
        $employees = $homeServiceRepository->getHomeServiceEmployee(null, [], 1);
        return view('admin.home-service.employee-home-service.index',compact('employees'));
    }

    public function show($empId, HomeServiceRepository $homeServiceRepository)
    {

        $inputArr = [
            'empId' => $empId
        ];

        if (empty($empId)) {
            return redirect()->route('admin.emp-home-service.list')
                ->with('error', 'Invalid Employee ID.');
        }

        $empPersonalInfo = $homeServiceRepository->getEmpPerInfo($inputArr);
        $empHomeSerLists = $homeServiceRepository->getEmpHomeServiceList($inputArr);
        return view('admin.home-service.employee-home-service.employee-home-service-list', compact('empPersonalInfo','empHomeSerLists'));
    }

    public function empHomeServiceDetails($appointmentNo, $empId, HomeServiceRepository $homeServiceRepository, CommonRepository $commonRepository)
    {

            $reqArr = [];
            $reqArr['appointmentNo'] = $appointmentNo;
            $reqArr['empId'] = $empId;

            $appointmentSummary = $homeServiceRepository->getEmpAppointmentSummary($reqArr);

            if ($appointmentSummary) {
                $homeServiceDetails = $homeServiceRepository->getAppoinmentDetail($reqArr['appointmentNo']);

                $variantArr = [];
                $variantArr['variantType'] = config('constants.HOME_SER');

                $distinctServices = $homeServiceRepository->getDistinctService($variantArr);
                $serviceVariants = $homeServiceRepository->getHomeService($variantArr, 1);

                $commonTableElementArr = ['type' => 'transaction_channel'];
                $transactionChannels = $commonRepository->getCommonTableElement($commonTableElementArr);

                return view('admin.home-service.employee-home-service.employee-home-service-details', compact(
                    'appointmentSummary',
                    'homeServiceDetails',
                    'distinctServices',
                    'serviceVariants',
                    'appointmentNo',
                    'empId',
                    'transactionChannels'
                ));
            }
    }

    public function startEmpHomeService(Request $request, HomeServiceRepository $homeServiceRepository)
    {

        // Input
        $reqArr = [
            'appointmentNo' => $request->appointmentNo,
            'empId'         => $request->empId,
        ];

        $inputArr = [
            'status' => config('constants.APPOINTMENT_START'),
        ];

        // Get summary (same logic)
        $homeServiceSummary = $homeServiceRepository->getEmpAppointmentSummary($reqArr);

        if ($homeServiceSummary) {

            if ($homeServiceSummary->status == config('constants.APPOINTMENT_ACCEPT')) {

                //$inputArr['updated_by']    = Auth::id();
                //$inputArr['updated_dt_tm'] = Carbon::now();
                $inputArr['updated_type']  = config('constants.P_ADMIN');

                $homeServiceRepository->startEmpHomeService($inputArr, $reqArr);

                return response('2');

            } else {
                return response('3'); // not accepted status
            }

        } else {
            return response('4'); // no data found
        }
    }
}
