<?php

namespace App\Http\Controllers\Client\MasterData;

use App\Http\Controllers\Controller;
use App\Repositories\CommonRepository;
use App\Repositories\MasterData\MasterDataRepository;
use App\Services\Client\GenerateMonthlyToken;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryCategoryController extends Controller
{

    public function index(Request $request, CommonRepository $commonRepository)
    {

        $isActiveFlag = 1;

        $statusDropDown = $request->statusDropDown;

        if ($statusDropDown) {
            $isActiveFlag = $statusDropDown;
        }

        $arr = [];
        $arr['categoryType'] = config('constants.PURCHASE');
        $arr['company']      = Auth::user()->customerEmployee->company;

        $categories = $commonRepository->getProductCategoryList($arr, $isActiveFlag);

        $activeCategories = $commonRepository->getProductCategoryList($arr, 1);

        $allCategories = $commonRepository->getProductCategoryList($arr, 3);

        return view('client.master-data.inventory.category.index', compact('categories','activeCategories','allCategories','isActiveFlag'));
    }

    public function store(Request $request, MasterDataRepository $masterDataRepository, GenerateMonthlyToken $generateMonthlyToken)
    {
        $inputArr = [];

        $inputArr['company'] = Auth::user()->customerEmployee->company;
        $inputArr['parent_category'] = $request->post('parentCode');
        $inputArr['category_name'] = $request->post('categoryName');
        $inputArr['category_type'] = config('constants.PURCHASE');
        $inputArr['created_by'] = Auth::user()->user_id;
        $inputArr['created_dt_tm'] = Carbon::now();
        $inputArr['updated_by'] = Auth::user()->user_id;
        $inputArr['updated_dt_tm'] = Carbon::now();

        if (
            $inputArr['parent_category'] &&
            $inputArr['category_name'] &&
            $inputArr['category_type']
        ) {
            $response = $masterDataRepository->insertCategory($inputArr, $generateMonthlyToken);

            return response((string) $response);

        } else {

            return response('3');
            }
    }

    public function update(Request $request, MasterDataRepository $masterDataRepository)
    {
        $serial = $request->get('serial');

        $inputArr = [];
        $inputArr['company'] = Auth::user()->customerEmployee->company;
        $inputArr['parent_category'] = $request->post('parentCode' . $serial);
        $inputArr['category_name'] = $request->post('categoryName' . $serial);
        $inputArr['category_code'] = $request->post('categoryCode' . $serial);
        $inputArr['updated_by'] = Auth::user()->user_id;
        $inputArr['updated_dt_tm'] = Carbon::now();

        if ($inputArr['parent_category'] && $inputArr['category_name']) {

            $response = $masterDataRepository->editProductCategory($inputArr, config('constants.PURCHASE'));

            return response((string) $response);

        } else {

            return response('3');
        }

    }

    public function destroy($categoryId, MasterDataRepository $masterDataRepository)
    {


        if ($categoryId) {

            $response = $masterDataRepository
                ->removeCategory($categoryId, Auth::user()->customerEmployee->company);

            return response((string) $response);

        } else {

            return response('4');
        }

    }

    public function activeInventoryCategory(Request $request, MasterDataRepository $masterDataRepository)
    {

        $categoryId = $request->categoryId;

        if ($categoryId) {

            $response = $masterDataRepository
                ->activeCategory($categoryId, Auth::user()->customerEmployee->company);

            return response((string) $response);

        } else {

            return response('3');
        }
    }

    
}
