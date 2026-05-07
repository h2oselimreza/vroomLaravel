<?php

namespace App\Http\Controllers\Client\MasterData;

use App\Http\Controllers\Controller;
use App\Repositories\CommonRepository;
use App\Repositories\MasterData\MasterDataRepository;
use App\Services\TokenService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ExpenseCategoryController extends Controller
{
    public function index(Request $request, CommonRepository $commonRepository){
        $isActiveFlag = 1;
        $statusDropDown = $request->query('statusDropDown');
        if ($statusDropDown) {
            $isActiveFlag = $statusDropDown;
        }
        $categories = $commonRepository->getCostCategory($isActiveFlag);
        $allCategories = $commonRepository->getCostCategory(3);
        return view('client.master-data.expense.index',compact('categories','allCategories','isActiveFlag'));
    }

    public function store(Request $request, MasterDataRepository $masterDataRepository, TokenService $tokenService)
    {
        $validator = Validator::make($request->all(), [
            'parentCode'   => 'required|string',
            'categoryName' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $companyCode = Auth::user()->customerEmployee;

        // Prepare data array
        $inputArr = [
            'parent_category' => $request->parentCode,
            'category_name'   => $request->categoryName,
            'company'         => $companyCode->company,
            'created_by' => Auth::user()->user_id,
            'created_dt_tm' => Carbon::now(),
            'updated_by' => Auth::user()->user_id,
            'updated_dt_tm' => Carbon::now(),
        ];

        // Keep your business rule unchanged
        if ($companyCode->customer_type == config('constants.INDIVIDUAL_CUST')) {
            $inputArr['company'] = config('constants.INDIVIDUAL_EXP');
        }

        $response = $masterDataRepository->insertExpenseCategory($inputArr, $tokenService);

        return response()->json([
            'status' => true,
            'data'   => $response
        ]);
    }

    public function update($id, Request $request, MasterDataRepository $masterDataRepository)
    {
        $serial = $request->get('serial');

        $inputArr = [];

        $inputArr['parent_category'] = $request->post('parentCode' . $serial);
        $inputArr['category_name']   = $request->post('categoryName' . $serial);
        $inputArr['category_code']   = $request->post('categoryCode' . $serial);

        $categoryId = $request->post('categoryId' . $serial);

        $inputArr['updated_by']    = Auth::user()->user_id;
        $inputArr['updated_dt_tm'] = Carbon::now();

        $inputArr['company'] = Auth::user()->customerEmployee->company;

        if (Auth::user()->customerEmployee->customer_type == config('constants.INDIVIDUAL_CUST')) {
            $inputArr['company'] = config('constants.INDIVIDUAL_EXP');
        }

        if (!empty($inputArr['parent_category']) && !empty($inputArr['category_name']) && $categoryId) {

            $response = $masterDataRepository->editExpenseCategory($inputArr, $categoryId);

            return response()->json($response);
        }

        return response()->json(0);
    }

    public function destroy(
        $id,
        Request $request,
        MasterDataRepository $masterDataRepository
    ) {

            $categoryId = $id;  

            if ($categoryId) {

                $company = Auth::user()->customerEmployee->company;

                // Keep existing business logic unchanged
                if (Auth::user()->customerEmployee->customer_type == config('constants.INDIVIDUAL_CUST')) {
                    $company = config('constants.INDIVIDUAL_EXP');
                }

                $response = $masterDataRepository
                    ->removeExpenseCategory($categoryId, $company);

                return response()->json($response);
            }

            return response()->json(0);
    }

    public function activeExpenseCategory(
        Request $request,
        MasterDataRepository $masterDataRepository
    ) {
        $categoryId = $request->categoryId;

        if ($categoryId) {

            $company = Auth::user()->customerEmployee->company;

            // Keep existing business logic unchanged
            if (Auth::user()->customerEmployee->customer_type == config('constants.INDIVIDUAL_CUST')) {
                $company = config('constants.INDIVIDUAL_EXP');
            }

            $response = $masterDataRepository
                ->activeExpenseCategory($categoryId, $company);

            return response()->json($response);
        }

        return response()->json(0);
    }
}
