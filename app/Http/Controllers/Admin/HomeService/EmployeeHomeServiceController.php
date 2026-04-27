<?php

namespace App\Http\Controllers\Admin\HomeService;

use App\Http\Controllers\Controller;
use App\Repositories\Client\AppointmentRepository;
use App\Repositories\Client\HomeServiceRepository;
use App\Repositories\CommonRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

            return redirect()->route('admin.home-service.my-home-service-list')
            ->with('error','Data not found');
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

    public function updateHomeService(Request $request, HomeServiceRepository $homeServiceRepository)
    {
        $appointmentNo = $request->appointmentNo;
        $empId         = $request->empId;

        if (!$appointmentNo) {
            return redirect()->route('admin.employee-home-service-details',[$appointmentNo,$empId])
            ->with('error','Appointment no not found');
        }

        $homeServiceSummary = $homeServiceRepository->getAppointmentSummary($appointmentNo);
        $homeServiceDetails  = $homeServiceRepository->getAppoinmentDetail($appointmentNo);

        if (!$homeServiceSummary || !$homeServiceDetails) {
            return redirect()->route('admin.employee-home-service-details',[$appointmentNo,$empId])
            ->with('error','Home service summary of home service details not found');
        }

        if ($homeServiceSummary->status != config('constants.APPOINTMENT_START')) {
            return redirect()->route('admin.employee-home-service-details',[$appointmentNo,$empId])
            ->with('error','Work not start');
        }

        $updateDetailArr = [];
        $insertDetailArr = [];
        $takenHomeService = [];
        $dbHomeService = [];
        $dbHomeServiceFlag = 1;
        $grandTotal = 0.00;
        $additionalBillArr = [];

        $takenServiceVarCount = (int) $request->takenServiceVarCount;

        for ($j = 1; $j <= $takenServiceVarCount; $j++) {
            $serviceVarCode = $request->input('takenServiceVarCode' . $j);

            if (!$serviceVarCode) {
                continue;
            }

            $takenHomeService[] = $serviceVarCode;

            $arr = [];
            $insertFlag = 1;

            foreach ($homeServiceDetails as $homeServiceDetail) {
                if ($dbHomeServiceFlag == 1) {
                    $dbHomeService[] = $homeServiceDetail->service_variant;
                }

                if ($homeServiceDetail->service_variant == $serviceVarCode) {
                    $insertFlag = 0;

                    $arr['id'] = $homeServiceDetail->id;
                    $arr['unit_price'] = (float) $request->input('takenServiceUnitPrice' . $j, 0);
                    $arr['quantity']   = (float) $request->input('quantity' . $j, 0);
                    $arr['total_amount'] = $arr['unit_price'] * $arr['quantity'];
                    //$arr['updated_by'] = $this->createUpdateUser;
                    $arr['updated_type'] = config('constants.P_ADMIN');
                    //$arr['updated_dt_tm'] = $this->dateTime;

                    $updateDetailArr[] = $arr;
                }
            }

            $dbHomeServiceFlag = 0;

            if ($insertFlag == 1) {
                $arr['appointment_no'] = $appointmentNo;
                $arr['service_variant'] = $serviceVarCode;
                $arr['unit_price'] = (float) $request->input('takenServiceUnitPrice' . $j);
                $arr['quantity'] = (float) $request->input('quantity' . $j);
                $arr['total_amount'] = $arr['unit_price'] * $arr['quantity'];
                $arr['created_by'] = Auth::user()->user_id;
                $arr['created_type'] = config('constants.P_ADMIN');
                $arr['created_dt_tm'] = Carbon::now();
                $arr['updated_by'] = Auth::user()->user_id;
                $arr['updated_type'] = config('constants.P_ADMIN');
                $arr['updated_dt_tm'] = Carbon::now();

                $insertDetailArr[] = $arr;
            }

            $grandTotal += $arr['total_amount'] ?? 0;
        }

        $summaryArr = [];
        $summaryArr['grand_total'] = $grandTotal;

        $additionalServiceCount = (int) $request->input('additionalServiceCount');

        $summaryArr['total_additional_bill'] = 0.00;
        for ($i = 1; $i < $additionalServiceCount; $i++) {
            $addServiceName  = $request->input('addServiceName' . $i);
            $addServicePrice = (float) $request->input('addServicePrice' . $i);

            if ($addServiceName != "" && $addServicePrice != "") {
                $additionalBillArr[] = [
                    'detail' => $addServiceName,
                    'amount' => $addServicePrice,
                ];
                $summaryArr['total_additional_bill'] += $addServicePrice;
            }
        }

        $summaryArr['additional_bill'] = json_encode($additionalBillArr);
        $summaryArr['discount'] = (float) $request->input('discount');
        //$summaryArr['updated_by'] = $this->createUpdateUser;
        $summaryArr['updated_type'] = config('constants.P_ADMIN');
        //$summaryArr['updated_dt_tm'] = $this->dateTime;

        $deleteHomeServiceArr = array_diff($dbHomeService, $takenHomeService);

        $finalArr = [
            'summaryArr'       => $summaryArr,
            'insertDetailArr'   => $insertDetailArr,
            'updateDetailArr'   => $updateDetailArr,
            'deleteDetailArr'   => $deleteHomeServiceArr,
            'company'           => $homeServiceSummary->company,
            'appointmentNo'     => $appointmentNo,
        ];

        $homeServiceRepository->editHomeService($finalArr);

        return redirect()->route('admin.employee-home-service-details',[$appointmentNo,$empId])->with('success','Data update successfully');
    }

    public function rejectEmpHomeService(Request $request, HomeServiceRepository $homeServiceRepository)
    {
        $inputArr = [];
        $reqArr = [];

        $reqArr['appointmentNo'] = $request->appointmentNo;
        $inputArr['reject_reason'] = $request->comment;
        $inputArr['status'] = config('constants.APPOINTMENT_REJECT');

        $homeServiceSummary = $homeServiceRepository
            ->getAppointmentSummary($reqArr['appointmentNo']);

        if ($homeServiceSummary 
            && ($homeServiceSummary->status == config('constants.APPOINTMENT_ACCEPT') 
            || $homeServiceSummary->status == config('constants.APPOINTMENT_START'))) {

            //$inputArr['updated_by']   = $this->createUpdateUser;
            //$inputArr['updated_dt_tm'] = $this->dateTime;
            $inputArr['updated_type'] = config('constants.P_ADMIN');

            $homeServiceRepository
                ->rejectEmpHomeService($inputArr, $reqArr);

            return response('2'); // same as echo 2
        } else {
            return response('4'); // same as echo 4
        }
    }

    public function completeEmpHomeService(Request $request, HomeServiceRepository $homeServiceRepository)
    {
        $inputArr = [];
        $reqArr = [];

        $reqArr['appointmentNo'] = $request->appointmentNo;
        $inputArr['status'] = config('constants.APPOINTMENT_COMPLETE');

        $homeServiceSummary = $homeServiceRepository
            ->getAppointmentSummary($reqArr['appointmentNo']);

        if ($homeServiceSummary && $homeServiceSummary->status == config('constants.APPOINTMENT_START')) {

            $inputArr['updated_by'] = Auth::user()->user_id;
            $inputArr['updated_dt_tm'] = Carbon::now();
            $inputArr['updated_type'] = config('constants.P_ADMIN');

            $homeServiceRepository
                ->completeEmpHomeService($inputArr, $reqArr);

            return response('2'); // same as echo 2

        } else {
            return response('4'); // same as echo 4
        }
    }

    public function cashCollectEmpHomeService(Request $request, HomeServiceRepository $homeServiceRepository)
    {
        $inputArr = [];
        $reqArr = [];

        $reqArr['appointmentNo'] = $request->appointmentNo;
        $reqArr['transactionChannel'] = $request->transactionChannel;

        $inputArr['status'] = config('constants.APPOINTMENT_CASH_COLLECT');
        $inputArr['transaction_channel'] = $reqArr['transactionChannel'];

        $homeServiceSummary = $homeServiceRepository
            ->getAppointmentSummary($reqArr['appointmentNo']);
        if ($homeServiceSummary && $homeServiceSummary->status == config('constants.APPOINTMENT_COMPLETE')) {

            $inputArr['updated_by'] = Auth::user()->user_id;
            $inputArr['updated_dt_tm'] = Carbon::now();
            $inputArr['updated_type'] = config('constants.P_ADMIN');

            $homeServiceRepository
                ->completeEmpHomeService($inputArr, $reqArr);

            return response('2'); // same as echo 2

        } else {
            return response('4'); // same as echo 4
        }
    }
}
