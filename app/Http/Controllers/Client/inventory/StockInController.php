<?php

namespace App\Http\Controllers\Client\inventory;

use App\Http\Controllers\Controller;
use App\Repositories\Client\InventoryRepository;
use App\Repositories\CommonRepository;
use App\Services\Client\GenerateMonthlyToken;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StockInController extends Controller
{

    public function index(InventoryRepository $inventoryRepository)
    {
        $data = [];

        $arr = [];
        $arr['company'] = Auth::user()->customerEmployee->company;
        $arr['stockType'] = 'stock_in';

        $stocks = $inventoryRepository
            ->getStockSummary($arr);

        return view(
            'client.inventory.stock-in.index',compact('stocks'),
        );
    }

    public function create(
        CommonRepository $commonRepository
    ) {
        $arr = [];

        $arr['company'] = Auth::user()
            ->customerEmployee
            ->company;

        $arr['variantType'] = config('constants.PURCHASE');

        $variants = $commonRepository
            ->getProductVariants($arr, 1);

        return view(
            'client.inventory.stock-in.create',compact('variants')
        );

    }

    public function store(
        Request $request,
        InventoryRepository $inventoryRepository,
        GenerateMonthlyToken $generateMonthlyToken
    ) {

        try {

            $stockSummaryArr = [];

            $stockSummaryArr['title'] = $request->post('title')
                ? trim($request->post('title'))
                : null;

            $stockSummaryArr['stock_date'] = trim(
                $request->post('sockInDate')
            );

            $stockSummaryArr['reference_no'] = $request->post('referenceNo')
                ? trim($request->post('referenceNo'))
                : null;

            $stockSummaryArr['created_by'] = Auth::user()->user_id;
            $stockSummaryArr['created_dt_tm'] = Carbon::now();
            $stockSummaryArr['updated_by'] = Auth::user()->user_id;
            $stockSummaryArr['updated_dt_tm'] = Carbon::now();
            $stockSummaryArr['stock_summary_id'] =
                config('constants.STOCK_ID') .
                $generateMonthlyToken->get_month_token(
                    config('constants.STOCK_ID')
                );

            $stockSummaryArr['company'] = Auth::user()->customerEmployee->company;
            $stockSummaryArr['is_active'] = 1;
            $stockSummaryArr['stock_type'] = 'stock_in';

            $counter = (int) $request->post('counterHidden');

            $variantArr = [];
            $quantityArr = [];
            $stockInsertArr = [];
            $stockDetailInsertArr = [];
            $stockIdArr = [];

            $quantityStrArr = [];
            $updatedByStrArr = [];
            $updatedDtTmStrArr = [];

            $stockUpdateQuery = "";

            $quantityFlag = 1;

            if ($stockSummaryArr['stock_date'] && $counter) {

                $arr = [];

                $arr['company'] = Auth::user()
                    ->customerEmployee
                    ->company;

                $arr['variantType'] = config('constants.PURCHASE');

                $companyVariantArr = $inventoryRepository
                    ->getCompanyVariant($arr);

                for ($i = 1; $i <= $counter; $i++) {

                    $variantCode = $request->post('variantCode' . $i);

                    $quantity = $request->post('quantity' . $i);

                    if ($variantCode) {

                        $stockDetailArr = [];

                        $variantArr[] = $variantCode;

                        $quantityArr[] = $quantity;

                        if ($quantity <= 0) {
                            $quantityFlag = 0;
                        }

                        $stockDetailArr['stock_detail_id'] =reference_no();

                        $stockDetailArr['stock_summary_id'] = $stockSummaryArr['stock_summary_id'];

                        $stockDetailArr['company'] = Auth::user()
                            ->customerEmployee
                            ->company;

                        $stockDetailArr['variant'] = $variantCode;

                        $stockDetailArr['remarks'] =
                            $request->post('remarks' . $i)
                            ? trim($request->post('remarks' . $i))
                            : null;

                        $stockDetailArr['credit_quantity'] = $quantity;
                        $stockDetailArr['debit_quantity'] = 0.00;
                        $stockDetailArr['trasaction_type'] = config('constants.CREDIT');
                        $stockDetailArr['status'] = 1;
                        $stockDetailArr['created_by'] =Auth::user()->user_id;
                        $stockDetailArr['created_dt_tm'] = Carbon::now();
                        $stockDetailArr['updated_by'] =Auth::user()->user_id;
                        $stockDetailArr['updated_dt_tm'] = Carbon::now();
                        $stockDetailInsertArr[] = $stockDetailArr;
                    }
                }

                if (
                    !array_diff($variantArr, $companyVariantArr)
                    && $variantArr
                    && $quantityFlag
                ) {

                    $arr['variantArr'] = $variantArr;

                    $stocks = $inventoryRepository->getCompanyStock($arr);

                    $variantArrCount = count($variantArr);

                    for ($i = 0; $i < $variantArrCount; $i++) {

                        $insertFlag = 1;

                        $stockArr = [];

                        foreach ($stocks as $stock) {

                            if (
                                $variantArr[$i] == $stock->variant
                            ) {

                                $stockIdArr[] = $stock->id;

                                $quantityStrArr[] ="WHEN `id` = " .$stock->id ." THEN `quantity`+" .$quantityArr[$i];

                                $updatedByStrArr[] ="WHEN `id` = " .$stock->id ." THEN '" .Auth::user()->user_id ."'";

                                $updatedDtTmStrArr[] ="WHEN `id` = " .$stock->id ." THEN '" . Carbon::now() ."'";

                                $insertFlag = 0;
                            }
                        }

                        if ($insertFlag) {

                            $stockArr['company'] = Auth::user()->customerEmployee->company;
                            $stockArr['variant'] = $variantArr[$i];
                            $stockArr['quantity'] = $quantityArr[$i];
                            $stockArr['status'] = 1;
                            $stockArr['created_by'] = Auth::user()->user_id;
                            $stockArr['created_dt_tm'] = Carbon::now();
                            $stockArr['updated_by'] = Auth::user()->user_id;
                            $stockArr['updated_dt_tm'] = Carbon::now();
                            $stockInsertArr[] = $stockArr;
                        }
                    }

                    if ($stockIdArr) {

                        $stockUpdateQuery = "UPDATE `stock`
                            SET `quantity` = CASE "
                            . implode(' ', $quantityStrArr) .
                            " ELSE `quantity` END,

                            `updated_by` = CASE "
                            . implode(' ', $updatedByStrArr) .
                            " ELSE `updated_by` END,

                            `updated_dt_tm` = CASE "
                            . implode(' ', $updatedDtTmStrArr) .
                            " ELSE `updated_dt_tm` END

                            WHERE `id` IN("
                            . implode(',', $stockIdArr) .
                            ")";
                    }

                    $response = $inventoryRepository
                        ->addStockIn(
                            $stockSummaryArr,
                            $stockDetailInsertArr,
                            $stockUpdateQuery,
                            $stockInsertArr
                        );

                    return redirect()->route('client.inventory.stock-in.create')->with('success', 'Stock In add successfully');

                } else {

                    return redirect()->route('client.inventory.stock-in.create')->with('error', 'Variant not valid');
                }

            } else {

                return redirect()->route('client.inventory.stock-in.create')->with('error', 'Stock summary not found');
            }

        } catch (\Throwable $e) {

            Log::error('Add New Stock In Error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('client.inventory.stock-in.create')->with('error', $e->getMessage());
        }
    }

    public function edit($summaryId, Request $request, InventoryRepository $inventoryRepository, CommonRepository $commonRepository)
    {
        try {

            $data = [];

            $arr = [];
            $arr['company'] = Auth::user()->customerEmployee->company;

            $arr['stockType'] = 'stock_in';
            $arr['summaryId'] = $summaryId;

            // stock summary
            $data['stockSummary'] =
                $inventoryRepository->getStockSummary($arr);

            // stock details
            $data['stockDetails'] =
                $inventoryRepository->getCalculatedStockDetail($arr);
            //dd($data['stockDetails']);
            if (!empty($data['stockSummary']) && !empty($data['stockDetails'])) {

                $arr['variantType'] = config('constants.PURCHASE');

                $data['summaryId'] = $summaryId;

                $data['variants'] =
                    $commonRepository->getProductVariants($arr, 1);
                //dd($data['variants']);
                return view("client.inventory.stock-in.edit",compact('data'));

            } else {
                return redirect()->route('client.inventory.stock-in.index')->with('error','Stock summary and stock details not found');
            }

        } catch (\Throwable $e) {
            Log::error('Show Edit Stock In Error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
            ]);

            return redirect()->route('client.inventory.stock-in.index')
                ->with('error', 'Something went wrong');
        }
    }

    public function editStockIn(Request $request, InventoryRepository $inventoryRepository)
    {
        try {

            $stockSummaryId = $request->stockId;
            $variantDeleteStr = $request->variantDeleteStr;
            
            $stockSummaryArr = [];

            $stockSummaryArr['title'] = $request->title
                ? $request->title
                : null;

            $stockSummaryArr['stock_date'] =$request->sockInDate;

            $stockSummaryArr['reference_no'] = $request->referenceNo
                ? $request->referenceNo
                : null;

            $stockSummaryArr['updated_by'] = Auth::user()->user_id;
            $stockSummaryArr['updated_dt_tm'] = Carbon::now();
            $stockSummaryArr['stock_summary_id'] = $stockSummaryId;
            $counter = (int) trim($request->post('counterHidden'));

            $variantArr = [];
            $quantityArr = [];
            $stockDetailInsertArr = [];
            $deleteStockDetails = [];
            $tempTableInsertArr = [];
            $quantityFlag = 1;

            $arr = [];
            $arr['company'] = Auth::user()->customerEmployee->company;
            $arr['stockType'] = 'stock_in';
            $arr['summaryId'] = $stockSummaryId;

            $stockDetails = $inventoryRepository->getCalculatedStockDetail($arr);

            if (
                $stockSummaryArr['stock_date'] &&
                $counter &&
                $stockDetails
            ) {

                $arr['variantType'] = config('constants.PURCHASE');

                $companyVariantArr =
                    $inventoryRepository->getCompanyVariant($arr);
                // DELETE ITEMS
                if ($variantDeleteStr) {
                    $deleteStockDetails =
                        $inventoryRepository->getDeleteStockDetailsNew(
                            $variantDeleteStr,
                            Auth::user()->customerEmployee->company,
                            $stockSummaryId
                        );
                }
                if ($deleteStockDetails) {

                    foreach ($deleteStockDetails as $deleteStockDetail) {

                        $stockDetailArr = [];
                        $stockDetailArr['stock_detail_id'] = reference_no();
                        $stockDetailArr['stock_summary_id'] = $stockSummaryId;
                        $stockDetailArr['company'] = Auth::user()->customerEmployee->company;
                        $stockDetailArr['variant'] = $deleteStockDetail->variant;
                        $stockDetailArr['remarks'] = null;
                        $stockDetailArr['credit_quantity'] = 0.00;

                        $stockDetailArr['debit_quantity'] =
                            $deleteStockDetail->credit_quantity
                            - $deleteStockDetail->debit_quantity;

                        $stockDetailArr['trasaction_type'] = config('constants.DEBIT');
                        $stockDetailArr['status'] = 3;

                        $stockDetailArr['created_by'] = Auth::user()->user_id;
                        $stockDetailArr['created_dt_tm'] = Carbon::now();
                        $stockDetailArr['updated_by'] = Auth::user()->user_id;
                        $stockDetailArr['updated_dt_tm'] = Carbon::now();

                        $stockDetailInsertArr[] = $stockDetailArr;

                        $tempArr = [];
                        $tempArr['company'] = Auth::user()->customerEmployee->company;
                        $tempArr['variant_temp'] = $deleteStockDetail->variant;
                        $tempArr['debit_quantity_temp'] =
                            $stockDetailArr['debit_quantity'];
                        $tempArr['credit_quantity_temp'] = 0.00;

                        $tempTableInsertArr[] = $tempArr;

                        $variantArr[] = $deleteStockDetail->variant;
                    }
                }

                $variantNewInsertArr = [];

                for ($i = 1; $i <= $counter; $i++) {

                    $variantCode = $request->post('variantCode' . $i);
                    $quantity = $request->post('quantity' . $i);
                    $stockDetailAutoId = (int) $request->post('stockDetailsAutoId' . $i);

                    if ($variantCode && $stockDetailAutoId == 0) {

                        $stockDetailArr = [];

                        $variantArr[] = $variantCode;
                        $variantNewInsertArr[] = $variantCode;
                        $quantityArr[] = $quantity;

                        if ($quantity <= 0) {
                            $quantityFlag = 0;
                        }

                        $stockDetailArr['stock_detail_id'] = reference_no();
                        $stockDetailArr['stock_summary_id'] = $stockSummaryId;
                        $stockDetailArr['company'] = Auth::user()->customerEmployee->company;
                        $stockDetailArr['variant'] = $variantCode;
                        $stockDetailArr['remarks'] = $request->post('remarks' . $i)
                            ? $request->post('remarks' . $i)
                            : null;

                        $stockDetailArr['credit_quantity'] = $quantity;
                        $stockDetailArr['debit_quantity'] = 0.00;
                        $stockDetailArr['trasaction_type'] = config('constants.CREDIT');
                        $stockDetailArr['status'] = 1;

                        $stockDetailArr['created_by'] = Auth::user()->user_id;
                        $stockDetailArr['created_dt_tm'] = Carbon::now();
                        $stockDetailArr['updated_by'] = Auth::user()->user_id;
                        $stockDetailArr['updated_dt_tm'] = Carbon::now();

                        $stockDetailInsertArr[] = $stockDetailArr;
                    }
                }

                if (
                    !array_diff($variantArr, $companyVariantArr)
                    && $quantityFlag
                ) {
                    if ($variantNewInsertArr) {

                        $count = count($variantNewInsertArr);

                        for ($i = 0; $i < $count; $i++) {

                            $tempArr = [];

                            $tempArr['company'] =
                                Auth::user()->customerEmployee->company;

                            $tempArr['variant_temp'] =
                                $variantNewInsertArr[$i];

                            $tempArr['debit_quantity_temp'] = 0.00;

                            $tempArr['credit_quantity_temp'] =
                                $quantityArr[$i];

                            $tempTableInsertArr[] = $tempArr;
                        }
                    }
                    //dd($tempTableInsertArr);
                    $response = $inventoryRepository->editStockIn(
                        $stockSummaryArr,
                        $stockDetailInsertArr,
                        $stockSummaryId,
                        Auth::user()->customerEmployee->company,
                        $tempTableInsertArr,
                        $variantArr
                    );

                    return redirect()->route('client.inventory.stock-in.edit',$stockSummaryId)->with('success','Stock in update successfully');

                } else {

                    return redirect()->route('client.inventory.stock-in.edit',$stockSummaryId)->with('error','Variant issue');
                }

            } else {

                return redirect()->route('client.inventory.stock-in.edit',$stockSummaryId)->with('error','Stock date and stock details not found');
            }

        } catch (\Throwable $e) {

            Log::error('Edit Stock In Error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
            ]);

            return redirect()->route('client.inventory.stock-in.edit',$stockSummaryId)->with('success','Stock in update successfully');
        }
    }

    public function destroy(
        $stockSummaryId,
        Request $request,
        InventoryRepository $inventoryRepository
    ) {

        try {
            $stockSummaryId = $request->stockSummaryId;
            $arr = [];
            $arr['summaryId'] = $stockSummaryId;
            $arr['company'] = Auth::user()
                ->customerEmployee
                ->company;

            $stockDetailInsertArr = [];

            $stockDetails = $inventoryRepository
                ->getCalculatedStockDetail($arr);

            if ($stockDetails) {

                $stockDetailvariantArr = [];
                $stockDetailQuantityArr = [];
                $stockIdArr = [];

                $quantityStrArr = [];
                $updatedByStrArr = [];
                $updatedDtTmStrArr = [];

                $stockUpdateQuery = "";

                foreach ($stockDetails as $stockDetail) {

                    $stockDetailvariantArr[] = $stockDetail->variant;

                    $stockDetailQuantityArr[] =
                        $stockDetail->credit_quantity -
                        $stockDetail->debit_quantity;

                    $stockDetailInsertArr[] = [
                        'stock_detail_id' => reference_no(),
                        'stock_summary_id' => $stockSummaryId,
                        'company' => Auth::user()
                            ->customerEmployee
                            ->company,
                        'variant' => $stockDetail->variant,
                        'remarks' => null,
                        'credit_quantity' => 0.00,
                        'debit_quantity' =>
                            $stockDetail->credit_quantity -
                            $stockDetail->debit_quantity,
                        'trasaction_type' => config('constants.DEBIT'),
                        'status' => 2,
                        'created_by' => Auth::user()->user_id,
                        'created_dt_tm' => Carbon::now(),
                        'updated_by' => Auth::user()->user_id,
                        'updated_dt_tm' => Carbon::now(),
                    ];
                }

                $arr['variantArr'] = $stockDetailvariantArr;

                $stocks = $inventoryRepository
                    ->getCompanyStock($arr);

                $stockSatusFlag = 1;
                $quantityFlag = 1;

                $count = count($stockDetailvariantArr);

                for ($i = 0; $i < $count; $i++) {

                    foreach ($stocks as $stock) {

                        if (
                            $stockDetailvariantArr[$i] ==
                            $stock->variant
                        ) {

                            if (
                                $stockDetailQuantityArr[$i] <=
                                $stock->quantity
                            ) {

                                $stockIdArr[] = $stock->id;

                                $quantityStrArr[] =
                                    "WHEN `id` = " . $stock->id .
                                    " THEN `quantity`-" .
                                    $stockDetailQuantityArr[$i];

                                $updatedByStrArr[] =
                                    "WHEN `id` = " . $stock->id .
                                    " THEN '" . Auth::user()->user_id . "'";

                                $updatedDtTmStrArr[] =
                                    "WHEN `id` = " . $stock->id .
                                    " THEN '" . now() . "'";

                            } else {
                                $quantityFlag = 0;
                            }

                            if ($stock->status == 2) {
                                $stockSatusFlag = 0;
                            }
                        }
                    }
                }

                if ($quantityFlag == 1 && $stockIdArr) {

                    if ($stockSatusFlag) {

                        $stockUpdateQuery =
                            "UPDATE `stock`
                            SET `quantity` = CASE "
                            . implode(' ', $quantityStrArr) .
                            " ELSE `quantity` END,

                            `updated_by` = CASE "
                            . implode(' ', $updatedByStrArr) .
                            " ELSE `updated_by` END,

                            `updated_dt_tm` = CASE "
                            . implode(' ', $updatedDtTmStrArr) .
                            " ELSE `updated_dt_tm` END

                            WHERE `id` IN("
                            . implode(',', $stockIdArr) .
                            ")";

                        $stockSummaryArr = [
                            'is_active' => 0,
                            'updated_by' => Auth::user()->user_id,
                            'updated_dt_tm' => now(),
                        ];

                        $statusData = [
                            'status' => 2,
                            'updated_by' => Auth::user()->user_id,
                            'updated_dt_tm' => now(),
                        ];

                        $inventoryRepository
                            ->changeStockStatus(
                                $stockDetailvariantArr,
                                $statusData
                            );

                        $response = $inventoryRepository
                            ->removeStockSummary(
                                $stockSummaryId,
                                Auth::user()
                                    ->customerEmployee
                                    ->company,
                                $stockSummaryArr,
                                $stockDetailInsertArr,
                                $stockUpdateQuery
                            );

                        $statusData['status'] = 1;

                        $inventoryRepository
                            ->changeStockStatus(
                                $stockDetailvariantArr,
                                $statusData
                            );

                        return response()->json($response);

                    } else {
                        return response()->json(4);
                    }

                } else {
                    return response()->json(3);
                }

            } else {
                return response()->json(2);
            }

        } catch (\Throwable $e) {
            dd($e->getMessage());
            Log::error('Remove Stock Summary Error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
            ]);

            return response()->json(0);
        }
    }
}
