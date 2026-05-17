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
}
