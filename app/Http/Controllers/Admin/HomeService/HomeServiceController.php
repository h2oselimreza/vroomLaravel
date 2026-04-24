<?php

namespace App\Http\Controllers\Admin\HomeService;

use App\Http\Controllers\Controller;
use App\Repositories\Client\GenHomeServiceRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeServiceController extends Controller
{
     public function index(Request $request, GenHomeServiceRepository $genHomeServiceRepository) 
    {
            $statusLists = array(
                config('constants.APPOINTMENT_ALL'),
                config('constants.APPOINTMENT_PENDING'),
                config('constants.APPOINTMENT_PROCCESSING'),
                config('constants.APPOINTMENT_ACCEPT'),
                config('constants.APPOINTMENT_ALL'),
            );
            $arr['companyCode'] = Auth::user()?->customerEmployee?->company;
            if($request->get('status')){
                $arr['status'] = $request->get('status');
            }else{
                $arr['status'] = $request->get('status') ?? config('constants.APPOINTMENT_ALL');
            }
            $status = $arr['status'];
            $data = $genHomeServiceRepository->getHomeServiceList($arr);
            return view('admin.home-service.index',compact('data', 'status', 'statusLists'));
    }

    public function show($appointmentNo, $companyCode, GenHomeServiceRepository $genHomeServiceRepository)
    {

        if ($appointmentNo == '' || $companyCode == '') {
            return redirect()
                ->route('admin.home-service.module.index')
                ->with('error', 'Appointment number and company code are required.');
        }

        $appointmentSummary = $genHomeServiceRepository->getAppointmentSummary($appointmentNo, $companyCode);

        if (!$appointmentSummary) {
            return redirect()
                ->route('admin.home-service.module.index')
                ->with('error', 'Appointment not found.');
        }

        $variantArr = [
            'variantType' => config('constants.HOME_SER'),
        ];

        $homeServiceDetails   = $genHomeServiceRepository->getAppoinmentDetail($appointmentNo);
        $distinctServices     = $genHomeServiceRepository->getDistinctService($variantArr);
        $serviceVariants      = $genHomeServiceRepository->getHomeService($variantArr, 1);

        return view('admin.home-service.show', 
        compact('appointmentSummary','homeServiceDetails','distinctServices','serviceVariants','appointmentNo','companyCode'));
    }

    public function proccessHomeService(Request $request, GenHomeServiceRepository $genHomeServiceRepository){

        $appointmentNo = trim($request->post('appointmentNo'));

        $homeServiceSummary = $genHomeServiceRepository->getAppointmentSummary($appointmentNo);

        if (!$homeServiceSummary) {
            return response()->json(4); // no data found
        }

        // IMPORTANT: same logic as CI constant check
        if ($homeServiceSummary->status == config('constants.APPOINTMENT_PENDING')) {

            $arr = [
                'updated_type' => config('constants.P_ADMIN'),
                'status'       => config('constants.APPOINTMENT_PROCCESSING'),
            ];

            $genHomeServiceRepository->proccessHomeService($arr, $appointmentNo);

            return response()->json(2); // success processing
        }

        return response()->json(3); // not pending status

    }


    public function updateHomeService(Request $request, GenHomeServiceRepository $genHomeServiceRepository)
    {
        //dd($request->all());
        $appointmentNo = trim((string) $request->post('appointmentNo'));

        $summaryArr = [
            'name'            => $request->post('name'),
            'mobile'          => $request->post('mobile'),
            'address'         => $request->post('address'),
            'final_date'      => $request->filled('confirmDate') ? $request->post('confirmDate') : null,
            'appointment_time'=> $request->filled('confirmTime')
                ? date('H:i:s', strtotime(trim((string) $request->post('confirmTime'))))
                : null,
        ];

        if (
            !$summaryArr['name'] ||
            !$summaryArr['mobile'] ||
            !$summaryArr['address'] ||
            !$appointmentNo
        ) {
            return redirect()->route('admin.home-service.details')
            ->with('error','Name or mobile or address not found');
        }

        $homeServiceSummary = $genHomeServiceRepository->getAppointmentSummary($appointmentNo);
        $homeServiceDetails  = $genHomeServiceRepository->getAppoinmentDetail($appointmentNo);

        if (!$homeServiceSummary || empty($homeServiceDetails)) {
            return redirect()->route('admin.home-service.details')->with('error','Home service summary and home service details not found');
        }

        if (
            $homeServiceSummary->status != config('constants.APPOINTMENT_PROCCESSING') &&
            $homeServiceSummary->status != config('constants.APPOINTMENT_ACCEPT')
        ) {
            return redirect()->route('admin.home-service.module.index');
        }

        $createUpdateUser = Auth::user()->user_id;
        $dateTime = now();

        $updateDetailArr = [];
        $insertDetailArr = [];
        $takenHomeService = [];
        $dbHomeService = [];
        $dbHomeServiceFlag = 1;
        $grandTotal = 0.00;

        $takenServiceVarCount = $request->post('takenServiceVarCount');

        for ($j = 1; $j <= $takenServiceVarCount; $j++) {
            $serviceVarCode = $request->post('takenServiceVarCode' . $j);

            if (!$serviceVarCode) {
                continue;
            }

            $takenHomeService[] = $serviceVarCode;

            $insertFlag = 1;
            $currentRow = null;

            foreach ($homeServiceDetails as $homeServiceDetail) {
                if ($dbHomeServiceFlag == 1) {
                    $dbHomeService[] = $homeServiceDetail['service_variant'];
                }

                if ($homeServiceDetail['service_variant'] == $serviceVarCode) {
                    $insertFlag = 0;
                    $currentRow = $homeServiceDetail;

                    $unitPrice = (float) trim((string) $request->post('takenServiceUnitPrice' . $j));
                    $quantity  = (float) trim((string) $request->post('quantity' . $j));
                    $totalAmount = $unitPrice * $quantity;

                    $updateDetailArr[] = [
                        'id'            => $homeServiceDetail['id'],
                        'unit_price'    => $unitPrice,
                        'quantity'      => $quantity,
                        'total_amount'  => $totalAmount,
                        'updated_by'    => $createUpdateUser,
                        'updated_type'  => config('constants.P_ADMIN'),
                        'updated_dt_tm' => $dateTime,
                    ];
                }
            }

            $dbHomeServiceFlag = 0;

            $unitPrice = (float) trim((string) $request->post('takenServiceUnitPrice' . $j));
            $quantity  = (float) trim((string) $request->post('quantity' . $j));
            $totalAmount = $unitPrice * $quantity;
            $grandTotal += $totalAmount;

            if ($insertFlag == 1) {
                $insertDetailArr[] = [
                    'appointment_no' => $appointmentNo,
                    'service_variant' => $serviceVarCode,
                    'unit_price'      => $unitPrice,
                    'quantity'        => $quantity,
                    'total_amount'    => $totalAmount,
                    'created_by'      => $createUpdateUser,
                    'created_type'    => config('constants.P_ADMIN'),
                    'created_dt_tm'   => $dateTime,
                    'updated_by'      => $createUpdateUser,
                    'updated_type'    => config('constants.P_ADMIN'),
                    'updated_dt_tm'   => $dateTime,
                ];
            }
        }

        $summaryArr['grand_total'] = $grandTotal;
        $summaryArr['updated_by'] = $createUpdateUser;
        $summaryArr['updated_type'] = config('constants.P_ADMIN');
        $summaryArr['updated_dt_tm'] = $dateTime;

        $deleteHomeServiceArr = array_values(array_diff($dbHomeService, $takenHomeService));

        $finalArr = [
            'summaryArr'      => $summaryArr,
            'insertDetailArr'  => $insertDetailArr,
            'updateDetailArr'  => $updateDetailArr,
            'deleteDetailArr'  => $deleteHomeServiceArr,
            'company'          => $homeServiceSummary->company,
            'appointmentNo'    => $appointmentNo,
        ];

        $genHomeServiceRepository->editHomeService($finalArr);

        return redirect()->route('admin.home-service.details', [
            'appointmentNo' => $appointmentNo,
            'companyCode'   => $finalArr['company'],
        ])->with('msg', 1);
    }
}
