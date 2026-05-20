<?php

namespace App\Http\Controllers\Client\Quotation;

use App\Http\Controllers\Controller;
use App\Repositories\Client\HomeServiceRepository;
use App\Repositories\Client\QuotationRepository;
use App\Repositories\Client\VehicleRepository;
use App\Repositories\WorkshopServiceRepository;
use App\Services\Client\GenerateMonthlyToken;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class QuotationController extends Controller
{
    public function index(QuotationRepository $quotationRepository)
    {
        $arr = [
            'companyCode' => Auth::user()->customerEmployee->company,
            'customerType' => config('constants.CORPORATE_CUST'),
            'status'       => config('constants.REQ_QUOT_ALL_STATUS'),
        ];

        $quotationReqLists = $quotationRepository->getQoutationReqest($arr);

        return view(
            'client.quotation.index',
            compact('quotationReqLists')
        );
    }

    public function create(
        VehicleRepository $vehicleRepository,
        HomeServiceRepository $homeServiceRepository,
        WorkshopServiceRepository $workshopServiceRepository
    ) {

        $data = [];

        $arr = [
            'isActiveFlag' => 1,
            'bulkFlag'     => 2, // 2 means all vehicle without vehicle array
            'companyCode'  => Auth::user()->customerEmployee->company,
        ];

        $vehicles = $vehicleRepository
            ->getVehicleInfo($arr);

        $variantArr = [
            'variantType' => config('constants.APPOINTMENT_SER'),
        ];

        $distinctServices = $homeServiceRepository
            ->getDistinctService($variantArr);

        $serviceVariants = $workshopServiceRepository
            ->getWorkshopService($variantArr, 1);

        return view(
            'client.quotation.create',
            compact('vehicles','distinctServices','serviceVariants')
        );
    }

    public function store(
        Request $request,
        GenerateMonthlyToken $generateMonthlyToken,
        QuotationRepository $quotationRepository
    ) {
        $vehicleCount = (int) $request->input('vehicleCount');

        if (!$vehicleCount) {
            return back()->with('error', 'Vehicle not found');
        }

        $quotationDetailInsertArr = [];
        $quantityFlag = 1;

        $requestNo =
            config('constants.QUOTATION_REQ') .
            $generateMonthlyToken->get_month_token(config('constants.QUOTATION_REQ'));

        $userId = Auth::id();
        $now = now();

        for ($i = 1; $i <= $vehicleCount; $i++) {

            $vehicleId = $request->input("vehicleId{$i}");

            if (!$vehicleId) {
                continue;
            }

            /*
            | SERVICE DATA
            */
            $takenServiceVarCount = (int) $request->input("takenServiceVarCount{$i}");

            for ($j = 1; $j <= $takenServiceVarCount; $j++) {

                $serviceVarCode = trim($request->input("takenServiceVarCode{$i}{$j}"));

                if (!$serviceVarCode) {
                    continue;
                }

                $quantity = trim($request->input("srviceQuantity{$i}{$j}"));

                if ($quantity === "" || $quantity <= 0) {
                    $quantityFlag = 0;
                }

                $quotationDetailInsertArr[] = [
                    'vehicle'              => $vehicleId,
                    'request_no'           => $requestNo,
                    'request_details_no'   => reference_no(),
                    'service_veriant'      => $serviceVarCode,
                    'quantity'             => $quantity,
                    'request_type'         => config('constants.REQ_TYPE_SERVICE'),
                    'product_variant'      => null,
                    'product_display_name'  => null,
                    'unit_name'            => null,
                    'created_by'           => $userId,
                    'created_dt_tm'        => $now,
                    'updated_by'           => $userId,
                    'updated_dt_tm'        => $now,
                ];
            }

            /*
            | PRODUCT DATA
            */
            $takenProductCount = (int) $request->input("takenProductCount{$i}");

            for ($j = 1; $j <= $takenProductCount; $j++) {

                $productName = $request->input("productName{$i}{$j}");

                if (!$productName) {
                    continue;
                }

                $quantity = trim($request->input("productQuantity{$i}{$j}"));

                if ($quantity === "" || $quantity <= 0) {
                    $quantityFlag = 0;
                }

                $quotationDetailInsertArr[] = [
                    'vehicle'              => $vehicleId,
                    'request_no'           => $requestNo,
                    'request_details_no'   => reference_no(),
                    'service_veriant'      => null,
                    'quantity'             => $quantity,
                    'request_type'         => config('constants.REQ_TYPE_PRODUCT'),
                    'product_variant'      => null,
                    'product_display_name' => $productName,
                    'unit_name'            => trim($request->input("productUnitName{$i}{$j}")),
                    'created_by'           => $userId,
                    'created_dt_tm'        => $now,
                    'updated_by'           => $userId,
                    'updated_dt_tm'        => $now,
                ];
            }
        }

        /*
        | VALIDATION CHECKS
        */
        if (!$quantityFlag) {
            return back()->with('error', 'Quantity flag is not valid');
        }

        if (empty($quotationDetailInsertArr)) {
            return back()->with('error', 'Quotation detail insert arr is not valid');
        }

        /*
        | SUMMARY DATA
        */
        $status = (int) $request->input('saveStatusFlag');

        $quotationSummaryArr = [
            'customer'                 => Auth::user()->customerEmployee->company,
            'customer_type'           => config('constants.CORPORATE_CUST'),
            'request_no'              => $requestNo,
            'approved_quotation_no'   => null,
            'remarks'                 => $request->input('remarks'),
            'status'                  => $status,
            'quotation_submitted_date'=> $request->input('lastsubmitDate'),
            'created_by'              => $userId,
            'created_dt_tm'           => $now,
            'created_by_type'         => config('constants.CLIENT'),
            'updated_by'              => $userId,
            'updated_dt_tm'           => $now,
            'updated_by_type'         => config('constants.CLIENT'),
            'req_sending_date'       => null,
        ];

        if ($status == config('constants.REQ_PENDING_STATUS')) {
            $quotationSummaryArr['req_sending_date'] = $now;
        }

        /*
        | SAVE DATA
        */
        $quotationRepository->addQuotationRequest(
            $quotationSummaryArr,
            $quotationDetailInsertArr
        );

        return redirect()->route('client.quotation.quotation-list.index')->with('success', 'Quotation added successfully');
    }

    public function edit(
        $requestNo,
        Request $request,
        QuotationRepository $quotationRepository,
        VehicleRepository $vehicleRepository,
        WorkshopServiceRepository $workshopRepository,
        HomeServiceRepository $homeServiceRepository
    ) {

        if (!$requestNo) {
            return redirect()->route('')->with('error','Request no not found');
        }

        /*
        | CHECK REQUEST EXIST
        */
        $arr = [
            'customerType' => config('constants.CORPORATE_CUST'),
            'companyCode'  => Auth::user()->customerEmployee->company,
            'requestNo'    => $requestNo,
        ];

        $requestFlag = $quotationRepository->checkFirstQuotReqExist($arr);

        if (!$requestFlag) {
            return redirect()->route('')->with('error','Request flag not found');
        }

        /*
        | DATA LOAD (QUOTATION)
        */
        $data = [];

        $data['requestSummaries'] = $quotationRepository->getQuotationReqSummary($requestNo);
        $data['requestDetails'] = $quotationRepository->getQuotationReqDetails($requestNo);
        $data['requestedVehicles'] = $quotationRepository->getRequestedVehicle($requestNo);


        /*
        | VEHICLES
        */
        $vehicleArr = [
            'isActiveFlag' => 1,
            'bulkFlag'     => 2,
            'companyCode'  => Auth::user()->customerEmployee->company,
        ];

        $data['vehicles'] = $vehicleRepository->getVehicleInfo($vehicleArr);

        /*
        |
        | WORKSHOP SERVICES
        |
        */
        $variantArr = [
            'variantType' => config('constants.APPOINTMENT_SER'),
        ];

        $data['distinctServices'] = $homeServiceRepository->getDistinctService($variantArr);
        $data['serviceVariants'] = $workshopRepository->getWorkshopService($variantArr, 1);

        /*
        |
        | REQUEST NO
        |
        */
        $data['requestNo'] = $requestNo;
        return view('client.quotation.edit', compact('data'));
    }

    public function update($requestNo, Request $request, QuotationRepository $quotationRepository)
    {

        $vehicleCount = (int) $request->input('vehicleCount', 0);
        $requestNo = trim($request->input('requestNo', ''));

        $arr = [
            'customerType' => config('constants.CORPORATE_CUST'),
            'companyCode'  => Auth::user()->customerEmployee->company, // Example Auth mapping
            'requestNo'    => $requestNo
        ];

        // 2. Core Validation Checks via Repository/Model
        $requestFlag = $quotationRepository->checkFirstQuotReqExist($arr);
        
        if (!$requestFlag) {
            return redirect()->route('client.quotation.quotation-list.index')->with('error','Request flag is not found');
        }

        $requestStatus = $quotationRepository->checkQuotReqStatus($requestNo);
        
        $allowedStatuses = [config('constants.REQ_DRAFT_STATUS'), config('constants.REQ_PENDING_STATUS'), config('constants.REQ_REJECT_STATUS')];
        if (!in_array($requestStatus, $allowedStatuses)) {
            return redirect()->route('client.quotation.quotation-list.index')->with('error','Request status is not in array');
        }

        // 3. Process Deletions
        $deleteArr = [
            'productDelteStr' => trim($request->input('productDelteStr', ''))
        ];

        $dbVehicleStr = trim($request->input('dbVehicleStr', ''));
        $takenVehicleStr = trim($request->input('takenVehicleStr', ''));
        
        $dbVehicleArr = $dbVehicleStr !== '' ? explode(',', $dbVehicleStr) : [];
        $takenVehicleArr = $takenVehicleStr !== '' ? explode(',', $takenVehicleStr) : [];

        $deleteVehicleArr = array_diff($dbVehicleArr, $takenVehicleArr);
        $deleteArr['vehicleDeleteStr'] = !empty($deleteVehicleArr) ? implode(',', $deleteVehicleArr) : "";

        if (!$vehicleCount) {
            return redirect()->route('client.quotation.quotation-list.index')->with('error','Vehicle not found');

        }

        $quotationDetailInsertArr = [];
        $quotationDetailUpdateArr = [];
        $quantityFlag = true;
        $deleteServiceDetailsNoArr = [];

        // 4. Processing Loops
        for ($i = 1; $i <= $vehicleCount; $i++) {
            $vehicleId = trim($request->input('vehicleId' . $i, ''));
            
            if (!$vehicleId) {
                continue;
            }

            // ---------- SERVICE PROCESSING ----------------//
            $vehicleIdForVehicleCount = trim($request->input('vehicleIdForVehicleCount' . $vehicleId, ''));
            $dbServiceVarReqDetailStr = $request->input('dbServiceVarReqDetailStr' . $vehicleIdForVehicleCount, '');

            $takenServiceVarDetailNoArr = [];
            $takenServiceVarCount = (int) trim($request->input('takenServiceVarCount' . $i, 0));

            for ($j = 1; $j <= $takenServiceVarCount; $j++) {
                $serviceVarCode = trim($request->input('takenServiceVarCode' . $i . $j, ''));
                
                if ($serviceVarCode) {
                    $quantity = trim($request->input('srviceQuantity' . $i . $j, ''));
                    if ($quantity === "" || (float)$quantity <= 0) {
                        $quantityFlag = false;
                    }

                    $detailArr = [
                        'vehicle'              => $vehicleId,
                        'service_veriant'      => $serviceVarCode,
                        'quantity'             => $quantity,
                        'request_type'         => config('constants.REQ_TYPE_SERVICE'),
                        'product_variant'      => null,
                        'product_display_name' => null,
                        'unit_name'            => null,
                        'updated_by'           => Auth::user()->user_id,
                        'updated_dt_tm'        => Carbon::now(),
                    ];

                    $reqServiceDetailsNo = $request->input('reqServiceDetailsNo' . $i . $j);

                    if ($reqServiceDetailsNo) {
                        $takenServiceVarDetailNoArr[] = $reqServiceDetailsNo;
                        $detailArr['request_details_no'] = $reqServiceDetailsNo;
                        $quotationDetailUpdateArr[] = $detailArr;
                    } else {
                        $detailArr['request_no']         = $requestNo;
                        $detailArr['request_details_no'] = reference_no(); // Keeps your custom tracking helper
                        $detailArr['created_by']         = Auth::user()->user_id;
                        $detailArr['created_dt_tm']      = Carbon::now();
                        $quotationDetailInsertArr[]      = $detailArr;
                    }
                }
            }

            if ($dbServiceVarReqDetailStr) {
                $dbServiceVarReqDetailArr = explode(',', $dbServiceVarReqDetailStr);
                $deleteServiceVarArr = array_diff($dbServiceVarReqDetailArr, $takenServiceVarDetailNoArr);
                if (!empty($deleteServiceVarArr)) {
                    $deleteServiceDetailsNoArr[] = implode(',', $deleteServiceVarArr);
                }
            }

            // ------------- PRODUCT PROCESSING ------------------//
            $takenProductCount = (int) trim($request->input('takenProductCount' . $i, 0));

            for ($j = 1; $j <= $takenProductCount; $j++) {
                $productName = trim($request->input('productName' . $i . $j, ''));
                
                if ($productName) {
                    $pQuantity = trim($request->input('productQuantity' . $i . $j, ''));
                    if ($pQuantity === "" || (float)$pQuantity <= 0) {
                        $quantityFlag = false;
                    }

                    $detailArr = [
                        'vehicle'              => $vehicleId,
                        'service_veriant'      => null,
                        'quantity'             => $pQuantity,
                        'request_type'         => config('constants.REQ_TYPE_PRODUCT'),
                        'product_variant'      => null,
                        'product_display_name' => $productName,
                        'unit_name'            => trim($request->input('productUnitName' . $i . $j, '')),
                        'updated_by'           => Auth::user()->user_id,
                        'updated_dt_tm'        => Carbon::now(),
                    ];

                    $reqProductDetailsNo = $request->input('reqProductDetailsNo' . $i . $j);
                    if ($reqProductDetailsNo) {
                        $detailArr['request_details_no'] = $reqProductDetailsNo;
                        $quotationDetailUpdateArr[] = $detailArr;
                    } else {
                        $detailArr['request_no']         = $requestNo;
                        $detailArr['request_details_no'] = reference_no();
                        $detailArr['created_by']         = Auth::user()->user_id;
                        $detailArr['created_dt_tm']      = Carbon::now();
                        $quotationDetailInsertArr[]      = $detailArr;
                    }
                }
            }
        }

        // 5. Save and Redirect Engine
        if ($quantityFlag && (!empty($quotationDetailInsertArr) || !empty($quotationDetailUpdateArr))) {
            
            $resquestStaus = (int) $request->input('saveStatusFlag', 0);
            $quotationSummaryArr = [];

            if ($resquestStaus ==  config('constants.REQ_DRAFT_STATUS')) {
                $quotationSummaryArr['req_sending_date'] = null;
            } elseif ($resquestStaus == config('constants.REQ_PENDING_STATUS')) {
                $quotationSummaryArr['req_sending_date'] = Carbon::now();
            }

            $quotationSummaryArr['remarks']                  = $request->filled('remarks') ? (string) trim($request->input('remarks')) : null;
            $quotationSummaryArr['status']                   = $resquestStaus;
            $quotationSummaryArr['quotation_submitted_date'] = $request->filled('lastsubmitDate') ? (string) trim($request->input('lastsubmitDate')) : null;
            $quotationSummaryArr['updated_by']               = Auth::user()->user_id;
            $quotationSummaryArr['updated_dt_tm']            = Carbon::now();
            $quotationSummaryArr['updated_by_type']          = config('constants.CLIENT');
            
            $updateDtTm = trim($request->input('updateDtTm', ''));

            $serviceDeleteStr = "";

            if (!empty($deleteServiceDetailsNoArr)) {
                // Filter removes empty trailing commas or values cleanly
                $serviceDeleteStr = implode(',', array_filter($deleteServiceDetailsNoArr));
            }
            $deleteArr['serviceDeleteStr'] = $serviceDeleteStr;

            // DB Transaction added for stability across batch arrays
            try {
                $result = DB::transaction(function () use ($quotationSummaryArr, $quotationDetailInsertArr, $quotationDetailUpdateArr, $requestNo, $deleteArr, $updateDtTm, $quotationRepository) {
                    return $quotationRepository->editQuotationRequest(
                        $quotationSummaryArr, 
                        $quotationDetailInsertArr, 
                        $quotationDetailUpdateArr, 
                        $requestNo, 
                        $deleteArr, 
                        $updateDtTm
                    );
                });

                return redirect()->route('client.quotation.quotation-list.index')->with('success','Quotation update successfully');

            } catch (Exception $e) {
                // Fallback route handling if DB fails cleanly
                return redirect()->back()->withErrors(['error' => 'Database operation failed: ' . $e->getMessage()]);
            }
        }
    }
}
