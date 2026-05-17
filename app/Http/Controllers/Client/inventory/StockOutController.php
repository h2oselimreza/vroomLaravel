<?php

namespace App\Http\Controllers\Client\inventory;

use App\Http\Controllers\Controller;
use App\Repositories\Client\InventoryRepository;
use App\Repositories\Client\VehicleRepository;
use App\Services\Client\GenerateMonthlyToken;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StockOutController extends Controller
{
    public function index(InventoryRepository $inventoryRepository){

        $arr = [
            'company'   => Auth::user()->customerEmployee->company,
            'stockType' => 'stock_out',
        ];

        $stocks = $inventoryRepository->getStockSummary($arr);

        return view('client.inventory.stock-out.index',compact('stocks'));
    }

    public function create(VehicleRepository $vehicleRepository, InventoryRepository $inventoryRepository)
    {

        $data = [];

        /*
        |--------------------------------------------------------------------------
        | Vehicle Information
        |--------------------------------------------------------------------------
        */

        $vehicleArr = [];
        $vehicleArr['isActiveFlag'] = 1;
        $vehicleArr['bulkFlag'] = 2; // 2 means all vehicle without vehicle array
        $vehicleArr['companyCode'] = Auth::user()->customerEmployee->company;

        $vehicles = $vehicleRepository->getVehicleInfo($vehicleArr);

        /*
        |--------------------------------------------------------------------------
        | Variant Information
        |--------------------------------------------------------------------------
        */

        $variantArr = [];
        $variantArr['company'] = Auth::user()->customerEmployee->company;
        $variantArr['variantType'] = config('constants.PURCHASE');

        $variants = $inventoryRepository->getStockVaiant($variantArr);

        return view('client.inventory.stock-out.create',compact('vehicles','variants'));
    }

    public function store(Request $request, GenerateMonthlyToken $generateMonthlyToken, InventoryRepository $inventoryRepository)
    {

        try {

            $stockSummaryArr = [];
            $stockDetailsArr = [];
            $variantArr = [];

            $vehicleCount = (int) $request->post('vehicleCount');

            if ($vehicleCount) {

                $tempTableInsetArr = [];

                $stockId = config('constants.STOCK_ID') . $generateMonthlyToken->get_month_token(config('constants.STOCK_ID'));

                for ($i = 1; $i <= $vehicleCount; $i++) {

                    $vehicleId = trim($request->post('vehicleId' . $i));

                    if ($vehicleId) {

                        $takenVariantCount = $request->post('takenVariantCount' . $i);

                        for ($j = 1; $j <= $takenVariantCount; $j++) {

                            if ($request->post('variantCode' . $i . $j)) {

                                $variantTempArr = [];
                                $arr = [];

                                $arr['stock_summary_id'] = $stockId;
                                $arr['stock_detail_id'] = reference_no();
                                $arr['company'] = Auth::user()->customerEmployee->company;
                                $arr['vehicle'] = $vehicleId;
                                $arr['variant'] = trim($request->post('variantCode' . $i . $j));
                                $arr['debit_quantity'] = trim($request->post('quantity' . $i . $j));
                                $arr['credit_quantity'] = 0.00;
                                $arr['trasaction_type'] = config('constants.DEBIT');
                                $arr['status'] = 1; // debit from stock out panel

                                $arr['remarks'] = trim($request->post('remarks' . $i . $j))
                                    ? trim($request->post('remarks' . $i . $j))
                                    : null;

                                $arr['created_by'] = Auth::user()->user_id;
                                $arr['created_dt_tm'] = Carbon::now();
                                $arr['updated_by'] = Auth::user()->user_id;
                                $arr['updated_dt_tm'] = Carbon::now();

                                $stockDetailsArr[] = $arr;

                                /*
                                |--------------------------------------------------------------------------
                                | Temp Table Array
                                |--------------------------------------------------------------------------
                                */

                                $variantTempArr['company'] = Auth::user()->customerEmployee->company;
                                $variantTempArr['variant_temp'] = $arr['variant'];
                                $variantTempArr['quantity_temp'] = $arr['debit_quantity'];

                                $tempTableInsetArr[] = $variantTempArr;

                                $variantArr[] = $arr['variant'];
                            }
                        }
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | Stock Summary
                |--------------------------------------------------------------------------
                */

                $stockSummaryArr['company'] = Auth::user()->customerEmployee->company;
                $stockSummaryArr['stock_summary_id'] = $stockId;
                $stockSummaryArr['title'] = null;
                $stockSummaryArr['stock_date'] = trim($request->post('stockOutDate'));
                $stockSummaryArr['reference_no'] = null;
                $stockSummaryArr['stock_type'] = 'stock_out';
                $stockSummaryArr['is_active'] = 1;
                $stockSummaryArr['created_by'] = Auth::user()->user_id;
                $stockSummaryArr['created_dt_tm'] = Carbon::now();
                $stockSummaryArr['updated_by'] = Auth::user()->user_id;
                $stockSummaryArr['updated_dt_tm'] = Carbon::now();

                /*
                |--------------------------------------------------------------------------
                | Company Stock Validation
                |--------------------------------------------------------------------------
                */

                $arr = [];
                $companyVariantArr = [];

                $arr['company'] = Auth::user()->customerEmployee->company;
                $arr['variantArr'] = $variantArr;

                $companyStocks = $inventoryRepository->getCompanyStock($arr);

                foreach ($companyStocks as $companyStock) {

                    $companyVariantArr[] = $companyStock->variant;

                    if ($companyStock->status == 2) {

                        return redirect()->route('client.inventory.stock-out.create')->with('error','Stock variant status is invalid');
                    }
                }

                if (
                    !array_diff($variantArr, $companyVariantArr)
                    && $stockSummaryArr['stock_date']
                    && $stockDetailsArr
                ) {

                    $statusData = [];

                    $statusData['status'] = 2;
                    $statusData['updated_by'] = Auth::user()->customerEmployee->company;
                    $statusData['updated_dt_tm'] = Carbon::now();

                    // 2 is processing state
                    $inventoryRepository->changeStockStatus(
                        $variantArr,
                        $statusData
                    );

                    $result = $inventoryRepository->addStockOut(
                        $stockSummaryArr,
                        $stockDetailsArr,
                        $tempTableInsetArr,
                        Auth::user()->customerEmployee->company,
                    );

                    $statusData['status'] = 1;

                    // 1 is processing complete
                    $inventoryRepository->changeStockStatus(
                        $variantArr,
                        $statusData
                    );

                    return redirect()->route('client.inventory.stock-out.create')->with('success','Stock out add successfully');


                } else {

                    return redirect()->route('client.inventory.stock-out.create')->with('error','Variant array data is invalid');
                }

            } else {

                return redirect()->route('client.inventory.stock-out.create')->with('error','Variant array data is invalid');
            }

        } catch (\Throwable $e) {
            dd($e->getMessage());
            Log::error('Add New Stock Out Error', [
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile(),
            ]);

            return redirect()->route('client.inventory.stock-out.create')->with('error',$e->getMessage());

        }
    }

    public function checkStockQuantity(Request $request, InventoryRepository $inventoryRepository)
    {
        $variantQuantityStr = trim($request->post('variantQuantityStr'));

        if ($variantQuantityStr) {

            $variantQuantityArr = explode(',', $variantQuantityStr);

            $tempTableInsetArr = [];

            for ($i = 0; $i < count($variantQuantityArr); $i++) {

                $arr = [];
                $variantArr = [];

                $arr = explode(':', $variantQuantityArr[$i]);

                $variantArr['company'] = Auth::user()->customerEmployee->company;
                $variantArr['variant_temp'] = $arr[0];
                $variantArr['quantity_temp'] = $arr[1];

                $tempTableInsetArr[] = $variantArr;
            }

            $response = $inventoryRepository->checkStockQuantity(
                $tempTableInsetArr,
                Auth::user()->customerEmployee->company,
            );

            return response($response);

        } else {

            return response(1);
        }
    }

    public function edit($summaryId, Request $request, InventoryRepository $inventoryRepository, VehicleRepository $vehicleRepository)
    {
        try {
                $arr = [];

                $arr['company'] = Auth::user()->customerEmployee->company;
                $arr['stockType'] = 'stock_out';
                $arr['summaryId'] = $summaryId;

                $stockSummary = $inventoryRepository->getStockSummary($arr);

                $editedVehicles = $inventoryRepository->getStockOutVehicle($arr);

                $stockDetails = $inventoryRepository->getCalculatedStockOutDetail($arr);

                $summaryId = $summaryId;

                $arr['isActiveFlag'] = 1;

                $arr['bulkFlag'] = 2;

                $arr['companyCode'] = Auth::user()->customerEmployee->company;

                $vehicles = $vehicleRepository->getVehicleInfo($arr);

                $arr['company'] = Auth::user()->customerEmployee->company;
                $arr['variantType'] = config('constants.PURCHASE');

                $variants = $inventoryRepository->getStockVaiant($arr);

                return view('client.inventory.stock-out.edit',compact('summaryId','stockSummary','editedVehicles','stockDetails','vehicles','variants'));

        } catch (\Throwable $e) {

            Log::error('Show Edit Stock Out Error', [
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile(),
            ]);
        }
    }

    public function update($stockSummaryId, Request $request, InventoryRepository $inventoryRepository)
    {
        try {

            $stockSummaryId = $request->stockId;
            $variantDeleteStr = $request->variantDeleteStr;

            $stockSummaryArr = [
                'title' => null,
                'stock_date' => trim($request->post('stockOutDate')),
                'reference_no' => null,
                'updated_by' => Auth::user()->user_id,
                'updated_dt_tm' => Carbon::now(),
                'stock_summary_id' => $stockSummaryId,
            ];

            $vehicleCount = (int) $request->post('vehicleCount');

            $variantArr = [];
            $quantityArr = [];
            $stockDetailInsertArr = [];
            $deleteStockDetails = [];
            $tempTableInsertArr = [];
            $quantityFlag = 1;

            /*
            |--------------------------------------------------------------------------
            | Existing Stock Details
            |--------------------------------------------------------------------------
            */

            $arr = [
                'company' => Auth::user()->customerEmployee->company,
                'stockType' => 'stock_out',
                'summaryId' => $stockSummaryId,
            ];

            $stockDetails = $inventoryRepository
                ->getCalculatedStockDetail($arr);

            if (
                $stockSummaryArr['stock_date']
                && $vehicleCount
                && $stockDetails
            ) {

                $companyVariantArr = [];

                $arr = [
                    'company' => Auth::user()->customerEmployee->company,
                    'variantArr' => $variantArr
                ];

                $companyStocks = $inventoryRepository
                    ->getCompanyStock($arr);

                foreach ($companyStocks as $companyStock) {

                    $companyVariantArr[] = $companyStock->variant;

                    if ($companyStock->status == 2) {
                        return redirect()->route('client.inventory.stock-out.edit',$stockSummaryId)->with('error','Stock variant status is invalid');
                    }
                }

                //Deleted Variants Handling

                if ($variantDeleteStr) {
                    $deleteStockDetails = $inventoryRepository
                        ->getDeleteStockOutDetails(
                            $variantDeleteStr,
                            Auth::user()->customerEmployee->company,
                            $stockSummaryId
                        );
                }
                //dd($deleteStockDetails);
                if ($deleteStockDetails) {

                    foreach ($deleteStockDetails as $deleteStockDetail) {
                        //dd($deleteStockDetail);
                        $stockDetailArr = [];
                        $stockDetailArr['stock_detail_id'] = reference_no();
                        $stockDetailArr['stock_summary_id'] = $stockSummaryId;
                        $stockDetailArr['company'] = Auth::user()->customerEmployee->company;
                        $stockDetailArr['variant'] = $deleteStockDetail->variant;
                        $stockDetailArr['vehicle'] = $deleteStockDetail->vehicle;
                        $stockDetailArr['remarks'] = null;
                        $stockDetailArr['credit_quantity'] =
                            $deleteStockDetail->debit_quantity
                            - $deleteStockDetail->credit_quantity;

                        $stockDetailArr['debit_quantity'] = 0.00;
                        $stockDetailArr['trasaction_type'] = config('constants.CREDIT');
                        $stockDetailArr['status'] = 3;

                        $stockDetailArr['created_by'] = Auth::user()->user_id;
                        $stockDetailArr['created_dt_tm'] = Carbon::now();
                        $stockDetailArr['updated_by'] = Auth::user()->user_id;
                        $stockDetailArr['updated_dt_tm'] = Carbon::now();

                        $stockDetailInsertArr[] = $stockDetailArr;

                        $tempArr = [];
                        $tempArr['company'] = Auth::user()->customerEmployee->company;
                        $tempArr['variant_temp'] = $deleteStockDetail->variant;
                        $tempArr['debit_quantity_temp'] = 0.00;
                        $tempArr['credit_quantity_temp'] =
                            $stockDetailArr['credit_quantity'];

                        $tempTableInsertArr[] = $tempArr;

                        $variantArr[] = $deleteStockDetail->variant;
                    }
                }
                // New Insert Variants
                //dd('ddddd');
                $variantNewInsertArr = [];

                for ($i = 1; $i <= $vehicleCount; $i++) {

                    $vehicleId = $request->post('vehicleId' . $i);

                    if ($vehicleId) {

                        $takenVariantCount =
                            $request->post('takenVariantCount' . $i);

                        for ($j = 1; $j <= $takenVariantCount; $j++) {

                            $stockDetailAutoId = (int) $request->post(
                                'stockDetailAutoId' . $i . $j
                            );

                            $variantCode = trim(
                                $request->post('variantCode' . $i . $j)
                            );

                            if ($variantCode && $stockDetailAutoId == 0) {

                                $quantity = trim(
                                    $request->post('quantity' . $i . $j)
                                );

                                $variantArr[] = $variantCode;
                                $variantNewInsertArr[] = $variantCode;
                                $quantityArr[] = $quantity;

                                $stockDetailArr = [];

                                $stockDetailArr['stock_detail_id'] = reference_no();
                                $stockDetailArr['stock_summary_id'] = $stockSummaryId;
                                $stockDetailArr['company'] = Auth::user()->customerEmployee->company;
                                $stockDetailArr['variant'] = $variantCode;
                                $stockDetailArr['vehicle'] = $vehicleId;

                                $stockDetailArr['remarks'] =
                                    ($request->post('remarks' . $i))
                                        ? trim($request->post('remarks' . $i . $j))
                                        : null;

                                $stockDetailArr['credit_quantity'] = 0.00;
                                $stockDetailArr['debit_quantity'] = $quantity;
                                $stockDetailArr['trasaction_type'] = config('constants.DEBIT');
                                $stockDetailArr['status'] = 1;

                                $stockDetailArr['created_by'] = Auth::user()->user_id;
                                $stockDetailArr['created_dt_tm'] = Carbon::now();
                                $stockDetailArr['updated_by'] = Auth::user()->user_id;
                                $stockDetailArr['updated_dt_tm'] = Carbon::now();

                                $stockDetailInsertArr[] = $stockDetailArr;
                            }
                        }
                    }
                }

                // Final Validation

                if (!array_diff($variantArr, $companyVariantArr)) {

                    if ($variantNewInsertArr) {

                        $variantArrCount = count($variantNewInsertArr);

                        for ($i = 0; $i < $variantArrCount; $i++) {

                            $tempArr = [];

                            $tempArr['company'] = Auth::user()->customerEmployee->company;
                            $tempArr['variant_temp'] =
                                $variantNewInsertArr[$i];

                            $tempArr['debit_quantity_temp'] =
                                $quantityArr[$i];

                            $tempArr['credit_quantity_temp'] = 0.00;

                            $tempTableInsertArr[] = $tempArr;
                        }
                    }

                    $inventoryRepository->editStockOut(
                        $stockSummaryArr,
                        $stockDetailInsertArr,
                        $stockSummaryId,
                        Auth::user()->customerEmployee->company,
                        $tempTableInsertArr,
                        $variantArr
                    );

                    return redirect()->route('client.inventory.stock-out.edit',$stockSummaryId)->with('success','Stock out update successfully');

                } else {

                    return redirect()->route('client.inventory.stock-out.edit',$stockSummaryId)->with('error','Variant array is invalid');
                    
                }

            } else {

                return redirect()->route('client.inventory.stock-out.edit', $stockSummaryId)->with('error','Stock details or stock date or vehicle count is invalid');
            }

        } catch (\Throwable $e) {

            Log::error('Edit Stock Out Error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);
        }
    }


    public function checkStockQuantityEdit(Request $request, InventoryRepository $inventoryRepository)
    {
        try {

            $variantQuantityStr = $request->variantQuantityStr;
            $variantDeleteStr = $request->variantDeleteStr;
            $stockSummaryId = $request->summaryId;

            $tempTableInsertArr = [];
            $deleteStockDetails = [];

            // New Variant Quantity Processing

            $variantQuantityArr = explode(',', $variantQuantityStr);

            if ($variantQuantityStr) {

                for ($i = 0; $i < count($variantQuantityArr); $i++) {

                    $arr = explode(':', $variantQuantityArr[$i]);

                    $tempArr = [];

                    $tempArr['company'] = Auth::user()->customerEmployee->company;
                    $tempArr['variant_temp'] = $arr[0];
                    $tempArr['debit_quantity_temp'] = $arr[1];
                    $tempArr['credit_quantity_temp'] = 0.00;

                    $tempTableInsertArr[] = $tempArr;
                }
            }

            // Deleted Variants Processing

            if ($variantDeleteStr) {

                $deleteStockDetails =
                    $inventoryRepository->getDeleteStockDetails(
                        $variantDeleteStr,
                        Auth::user()->customerEmployee->company,
                        $stockSummaryId
                    );
            }

            if (!empty($deleteStockDetails)) {

                foreach ($deleteStockDetails as $deleteStockDetail) {

                    $tempArr = [];

                    $tempArr['company'] = Auth::user()->customerEmployee->company;
                    $tempArr['variant_temp'] = $deleteStockDetail['variant'];
                    $tempArr['debit_quantity_temp'] = 0.00;
                    $tempArr['credit_quantity_temp'] =
                        $deleteStockDetail['debit_quantity'];

                    $tempTableInsertArr[] = $tempArr;
                }
            }

            // Final Check
            if (!empty($tempTableInsertArr)) {

                $response = $inventoryRepository
                    ->checkStockQuantityEdit(
                        $tempTableInsertArr,
                        //Auth::user()->customerEmployee->company,
                    );

                return response($response);

            } else {

                return response(1);
            }

        } catch (\Throwable $e) {

            Log::error('Check Stock Quantity Edit Error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return response(1);
        }
    }

    public function removeStockOutSummary(Request $request, InventoryRepository $inventoryRepository)
    {
        try {
            $stockSummaryId = $request->stockSummaryId;

            $arr = [];

            $arr['stockSummaryId'] = $stockSummaryId;
            $arr['company'] = Auth::user()->customerEmployee->company;
            $arr['transactionType'] = config('constants.DEBIT');

            $stockDetailInsertArr = [];

            $stockDetails = $inventoryRepository->getStockDetails($arr);

            if (!empty($stockDetails)) {

                $stockDetailvariantArr = [];
                $stockDetailQuantityArr = [];
                $stockIdArr = [];

                $quantityStrArr = [];
                $updatedByStrArr = [];
                $updatedDtTmStrArr = [];

                $stockUpdateQuery = "";

                foreach ($stockDetails as $stockDetail) {

                    $stockDetailArr = [];

                    $stockDetailvariantArr[] = $stockDetail->variant;

                    $stockDetailQuantityArr[] = $stockDetail->debit_quantity;

                    $stockDetailArr['stock_detail_id'] = reference_no();

                    $stockDetailArr['stock_summary_id'] = $stockSummaryId;

                    $stockDetailArr['company'] = Auth::user()->customerEmployee->company;
                    $stockDetailArr['variant'] = $stockDetail->variant;
                    $stockDetailArr['vehicle'] = $stockDetail->vehicle;
                    $stockDetailArr['remarks'] = null;
                    $stockDetailArr['credit_quantity'] = $stockDetail->debit_quantity;
                    $stockDetailArr['debit_quantity'] = 0.00;
                    $stockDetailArr['trasaction_type'] = config('constants.CREDIT');
                    // delete from list
                    $stockDetailArr['status'] = 2;

                    $stockDetailArr['created_by'] = Auth::user()->user_id;
                    $stockDetailArr['created_dt_tm'] = Carbon::now();
                    $stockDetailArr['updated_by'] = Auth::user()->user_id;
                    $stockDetailArr['updated_dt_tm'] = Carbon::now();
                    $stockDetailInsertArr[] = $stockDetailArr;
                }

                $arr['variantArr'] = $stockDetailvariantArr;

                $stocks = $inventoryRepository->getCompanyStock($arr);

                $stockDetailvarArrCount = count(
                    $stockDetailvariantArr
                );

                for ($i = 0; $i < $stockDetailvarArrCount; $i++) {

                    foreach ($stocks as $stock) {

                        if (
                            $stockDetailvariantArr[$i]
                            == $stock->variant
                        ) {

                            $stockIdArr[] = $stock->id;

                            $userId   = Auth::user()->user_id;
                            $dateTime = Carbon::now()->format('Y-m-d H:i:s');

                            $quantityStrArr[] =
                                "WHEN `id` = {$stock->id}
                                THEN `quantity` + {$stockDetailQuantityArr[$i]}";

                            $updatedByStrArr[] =
                                "WHEN `id` = {$stock->id}
                                THEN '{$userId}'";

                            $updatedDtTmStrArr[] =
                                "WHEN `id` = {$stock->id}
                                THEN '{$dateTime}'";
                        }
                    }
                }

                if (!empty($stockIdArr)) {

                    $stockUpdateQuery = "
                        UPDATE `stock`
                        SET
                            `quantity` = CASE
                                " . implode(' ', $quantityStrArr) . "
                                ELSE `quantity`
                            END,

                            `updated_by` = CASE
                                " . implode(' ', $updatedByStrArr) . "
                                ELSE `updated_by`
                            END,

                            `updated_dt_tm` = CASE
                                " . implode(' ', $updatedDtTmStrArr) . "
                                ELSE `updated_dt_tm`
                            END

                        WHERE `id` IN (" . implode(',', $stockIdArr) . ")
                    ";

                    $stockSummaryArr = [];

                    $stockSummaryArr['is_active'] = 0;
                    $stockSummaryArr['updated_by'] = Auth::user()->user_id;
                    $stockSummaryArr['updated_dt_tm'] = Carbon::now();

                    $response = $inventoryRepository
                        ->removeStockSummary(
                            $stockSummaryId,
                            Auth::user()->customerEmployee->company,
                            $stockSummaryArr,
                            $stockDetailInsertArr,
                            $stockUpdateQuery
                        );

                    return response($response);

                } else {

                    return response(2);
                }

            } else {

                return response(2);
            }

        } catch (\Throwable $e) {

            Log::error('Remove Stock Out Summary Error', [
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile(),
            ]);

            return response(2);
        }
    }
}
