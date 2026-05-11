<?php

namespace App\Http\Controllers\Client\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Client\Product;
use App\Repositories\CommonRepository;
use App\Repositories\MasterData\MasterDataRepository;
use App\Services\Client\GenerateMonthlyToken;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryProductController extends Controller
{

    public function index(Request $request, CommonRepository $commonRepository)
    {

        $isActiveFlag = 1;

        $statusDropDown = $request->statusDropDown;

        if ($statusDropDown) {
            $isActiveFlag = $statusDropDown;
        }

        $arr = [];
        $arr['productType'] = config('constants.PURCHASE');
        $arr['categoryType'] = config('constants.PURCHASE');
        $arr['company'] = Auth::user()->customerEmployee->company;

        $products = $commonRepository->getProduts($arr, $isActiveFlag);

        $categories = $commonRepository->getProductCategoryList($arr, 1);

        return view('client.master-data.inventory.product.index', compact('isActiveFlag','products','categories'));

    }

    public function create(CommonRepository $commonRepository)
    {

        $arr = [];

        $arr['categoryType'] = config('constants.PURCHASE');
        $arr['company'] = Auth::user()->customerEmployee->company;

        $categories = $commonRepository->getProductCategoryList($arr);

        return view('client.master-data.inventory.product.create', compact('categories'));

    }

    public function store(Request $request, MasterDataRepository $masterDataRepository, GenerateMonthlyToken $generateMonthlyToken)
    {

        $inputArr = [];

        $inputArr['company'] = Auth::user()->customerEmployee->company;
        $inputArr['category'] = $request->post('categoryCode');
        $inputArr['product_name'] = $request->post('productName');
        $inputArr['product_details'] = $request->post('productDetails');
        $inputArr['product_type'] = config('constants.PURCHASE');
        $inputArr['created_by'] = Auth::user()->user_id;
        $inputArr['created_dt_tm'] = Carbon::now();
        $inputArr['updated_by'] = Auth::user()->user_id;
        $inputArr['updated_dt_tm'] = Carbon::now();

        if ($inputArr['category'] && $inputArr['product_name']) {
            $insertVariantArr = [];

            $insertVariantArr['company'] = Auth::user()->customerEmployee->company;
            $insertVariantArr['variant_name'] = 'Default';
            $insertVariantArr['default_variant'] = 1;
            $insertVariantArr['variant_type'] = config('constants.PURCHASE');

            $insertVariantArr['created_by'] = Auth::user()->user_id;
            $insertVariantArr['created_dt_tm'] = Carbon::now();
            $insertVariantArr['updated_by'] = Auth::user()->user_id;
            $insertVariantArr['updated_dt_tm'] = Carbon::now();

            $response = $masterDataRepository->insertProduct($inputArr, $insertVariantArr, $generateMonthlyToken);

            return response($response);

        } else {
            return response('3');
        }

    }

    public function edit($productCode, CommonRepository $commonRepository){
        $arr = [];

        $arr['categoryType'] = config('constants.PURCHASE');
        $arr['company'] = Auth::user()->customerEmployee->company;

        $categories = $commonRepository->getProductCategoryList($arr);

        $product = Product::where('product_code', $productCode)->first();

        return view('client.master-data.inventory.product.create', compact('categories','product'));
    }

    public function update($productCode, Request $request, MasterDataRepository $masterDataRepository)
    {
        $inputArr = [];

        $inputArr['company'] = Auth::user()->customerEmployee->company;
        $inputArr['category'] = $request->categoryCode;
        $inputArr['product_name'] = $request->productName;
        $inputArr['product_details'] = $request->productDetails;
        $inputArr['product_code'] = $productCode;
        $inputArr['product_type'] = config('constants.PURCHASE');
        $inputArr['updated_by'] = Auth::user()->user_id;
        $inputArr['updated_dt_tm'] = Carbon::now();

        // Same validation logic preserved
        if (
            $inputArr['category'] &&
            $inputArr['product_name'] &&
            $inputArr['product_code']
        ) {

            $response = $masterDataRepository
                ->updateProduct($inputArr);

            return response((string) $response);

        } else {

            return response('3');
        }
    }
}
