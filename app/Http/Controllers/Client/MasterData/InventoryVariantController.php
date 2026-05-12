<?php

namespace App\Http\Controllers\Client\MasterData;

use App\Http\Controllers\Controller;
use App\Repositories\CommonRepository;
use App\Repositories\MasterData\MasterDataRepository;
use App\Services\Client\GenerateMonthlyToken;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class InventoryVariantController extends Controller
{

    public function index(Request $request, CommonRepository $commonRepository)
    {

        $data = [];

        $isActiveFlag = 1;
        $postStatus = $request->statusDropDown;

        if ($postStatus) {
            $isActiveFlag = $postStatus;
        }

        $arr = [];

        $arr['categoryType'] = config('constants.PURCHASE');
        $arr['company'] = Auth::user()->customerEmployee->company;
        $arr['variantType'] = config('constants.PURCHASE');

        $categories = $commonRepository
            ->getProductCategoryList($arr, 1);

        $variants = $commonRepository
            ->getProductVariants($arr, $isActiveFlag);

        return view('client.master-data.inventory.variant.index', compact('categories','variants','isActiveFlag'));
    }

    public function create(CommonRepository $commonRepository)
    {

            $arr = [];
            $arr['categoryType'] = config('constants.PURCHASE');
            $arr['company'] = Auth::user()->customerEmployee->company;
            $arr['productType'] = config('constants.PURCHASE');

            $categories = $commonRepository
                ->getProductCategoryList($arr, 1);

            $products = $commonRepository
                ->getProduts($arr, 1);

            return view('client.master-data.inventory.variant.create', compact('categories','products'));

    }


    public function setProductVariant(Request $request, MasterDataRepository $masterDataRepository)
    {
            $arr = [];
            $arr['company'] = Auth::user()->customerEmployee->company;
            $arr['productCode'] = $request->productCode;
            $arr['variantType'] = $request->variantType;

            $result = $masterDataRepository
                ->getProductVariant($arr);

            return response()->json([
                'variants' => $result
            ]);
    }

    public function checkDupVariant(Request $request, MasterDataRepository $masterDataRepository)
    {
        try {

                $variantNameJson = trim($request->post('variantNameJson'));
                $productCode = trim($request->post('productCode'));
                $variantType = trim($request->post('variantType'));

                $variantNameArr = json_decode($variantNameJson);

                $result = $masterDataRepository->checkDupProductVariant(
                    $variantNameArr,
                    $productCode,
                    $variantType,
                    Auth::user()->customerEmployee->company
                );

                return response()->json($result);


        } catch (\Exception $e) {

            Log::error('Check Duplicate Variant Error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return response()->json([
                'status' => 500,
                'message' => 'Something went wrong.'
            ], 500);
        }
    }

    public function store(
        Request $request,
        MasterDataRepository $masterDataRepository,
        GenerateMonthlyToken $generateMonthlyToken
    ) {

        try {
            $totalVariant = (int) $request->totalVariant;

            $productCode = $request->productCode;

            $deleteVariantIdStr = $request->deleteVariant;

            $contentVariantId = $request->contenCheckVariantId;

            $contentCheckUpdateDtTm = $request->contenCheckUpdateDtTm;

            $contengencyFlag = $masterDataRepository
                ->checkVariantContengency($contentVariantId, $contentCheckUpdateDtTm);

            $checkDuplicateArr = [];

            if ($contengencyFlag == 1) {

                $updateArr = [];
                $insertArr = [];
                $msg = 0;

                for ($i = 1; $i < $totalVariant; $i++) {

                    $variantId = $request->post('variantAutoIdHidden' . $i);
                    $variantName = $request->post('variantNameHidden' . $i);
                    $model = $request->post('modelHidden' . $i);
                    $displayCode = $request->post('displayCodeHidden' . $i);
                    $unitName = $request->post('unitNameHidden' . $i);
                    $details = $request->post('detailsHidden' . $i);
                    //dd($variantId, $variantName, $model, $displayCode, $unitName, $details);
                    if ($variantName && $unitName) {

                        $checkDuplicateArr[] = $variantName;

                        // UPDATE
                        if ($variantId > 0) {

                            $updateArr[] = [
                                'id' => $variantId,
                                'variant_name' => $variantName,
                                'unit_name' => $unitName,
                                'model' => $model,
                                'display_code' => $displayCode,
                                'details' => $details,
                                'updated_by' => Auth::user()->user_id,
                                'updated_dt_tm' => Carbon::now(),
                            ];

                        // INSERT
                        } elseif ($variantId == 0) {
                            $insertArr[] = [
                                'product' => $productCode,
                                'company' => Auth::user()->customerEmployee->company,
                                'default_variant' => 0,
                                'variant_name' => $variantName,
                                'unit_name' => $unitName,
                                'variant_code' => config('constants.VARIANT') .
                                    $generateMonthlyToken->get_month_token(config('constants.VARIANT')),
                                'model' => $model,
                                'display_code' => $displayCode,
                                'details' => $details,
                                'variant_type' => config('constants.PURCHASE'),
                                'created_by' => Auth::user()->user_id,
                                'created_dt_tm' => Carbon::now(),
                                'updated_by' => Auth::user()->user_id,
                                'updated_dt_tm' => Carbon::now(),
                            ];
                        }
                    }
                }

                // duplicate check (same logic)
                if (count($checkDuplicateArr) == count(array_unique($checkDuplicateArr))) {

                    $msg = $masterDataRepository
                        ->saveProductVariant($updateArr, $insertArr, $deleteVariantIdStr);
                }

                if ($msg == 1) {

                    return redirect()
                        ->route('client.master-data.inventory-product-variant.create')
                        ->with('success', 'Product Variant save successfully');
                }

            } else {

                return redirect()
                    ->route('client.master-data.inventory-product-variant.create')
                    ->with('error', 'Product already update');
            }

        } catch (\Exception $e) {
            dd($e->getMessage());
            Log::error('Product Variant Store Error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function activeProductVariant(Request $request, MasterDataRepository $masterDataRepository)
    {
        try {

                $variantId = $request->variantId;

                if ($variantId) {

                    $response = $masterDataRepository
                        ->activeVariant(
                            $variantId,
                            Auth::user()->customerEmployee->company
                        );

                    return response()->json($response);

                } else {

                    return response()->json(3);
                }

        } catch (\Throwable $e) {

            Log::error('Active Product Variant Error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return response()->json(0);
        }
    }
}
