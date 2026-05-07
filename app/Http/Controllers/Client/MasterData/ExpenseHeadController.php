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

class ExpenseHeadController extends Controller
{

    public function index(
        Request $request,
        CommonRepository $commonRepository,
    ) {

        $data = [];
        $isActiveFlag = 1;
        $isActiveFlag =  $request->query('statusDropDown');

        if ($isActiveFlag) {
            $isActiveFlag = $isActiveFlag;
        }

        $categories = $commonRepository->getCostCategory(1);

        $costHeads = $commonRepository
            ->getCostHead($isActiveFlag);

        return view(
            'client.master-data.expense-head.index',
            compact('categories','costHeads','isActiveFlag')
        );
    }

    public function store(Request $request, MasterDataRepository $masterDataRepository, TokenService $tokenService)
    {
        try {

            // Validation rules
            $validator = Validator::make($request->all(), [
                'category'      => 'required|string|max:255',
                'costHeadName'  => 'required|string|max:255',
                'unitName'      => 'nullable|string|max:255',
                'unitPrice'     => 'nullable|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $inputArr = [];

            $inputArr['cost_category'] = $request->post('category');
            $inputArr['cost_head']     = $request->post('costHeadName');
            $inputArr['unit_name']     = $request->post('unitName');

            $inputArr['unit_price'] = $request->post('unitPrice')
                ? trim($request->post('unitPrice'))
                : '0.00';

            $inputArr['company'] = Auth::user()->customerEmployee->company;

            if (Auth::user()->customerEmployee->customer_type == config('constants.INDIVIDUAL_CUST')) {
                $inputArr['company'] = config('constants.INDIVIDUAL_EXP');
            }

            $inputArr['created_by']    = Auth::user()->user_id;
            $inputArr['created_dt_tm'] = Carbon::now();
            $inputArr['updated_by']    = Auth::user()->user_id;
            $inputArr['updated_dt_tm'] = Carbon::now();

            // extra safety check
            if (!empty($inputArr['cost_category']) && !empty($inputArr['cost_head'])) {

                $response = $masterDataRepository->insertExpenseHead($inputArr, $tokenService);

                return response()->json([
                    'status' => true,
                    'data'   => $response
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Required fields missing'
            ], 400);

        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function update($id, Request $request, MasterDataRepository $masterDataRepository)
    {
        try {
            // 2. Extract Serial and dynamic inputs
            $serial = $request->get('serial');
            
            $inputArr = [];
            $inputArr['cost_category']  = $request->input('category' . $serial);
            $inputArr['cost_head']      = $request->input('costHeadName' . $serial);
            $inputArr['cost_head_code'] = $request->input('costHeadCode' . $serial);

            $inputArr['unit_price'] = $request->filled('unitPrice' . $serial) 
                ? trim($request->input('unitPrice' . $serial)) 
                : '0.00';

            $costHeadId = $request->input('costHeadId' . $serial);

            // 3. Metadata and User Logic
            $inputArr['updated_by']    = Auth::user()->user_id; // Replacing $this->createUpdateUser
            $inputArr['updated_dt_tm'] = Carbon::now();         // Replacing $this->dateTime

            // 4. Company Logic (Logic Preserved)
            $inputArr['company'] = Auth::user()->customerEmployee->company; 
            if (Auth::user()->customerEmployee->customer_type == config('constants.INDIVIDUAL_CUST')) {
                $inputArr['company'] = config('constants.INDIVIDUAL_EXP');
            }

            // 5. Flag Check for Unit Name Update (Logic Preserved)
            // Check if unit_name should be included in the update
            $flag = $masterDataRepository->checkExpHead($inputArr);
            if ($flag == 1) {
                $inputArr['unit_name'] = $request->input('unitName' . $serial);
            }

            // 6. Execution
            if ($inputArr['cost_category'] && $inputArr['cost_head'] && $costHeadId) {
                
                $response = $masterDataRepository->editExpenseHead($inputArr, $costHeadId);

                if ($response == 1) {
                    // If update was successful, determine if it was a full or partial update
                    return response()->json($flag == 1 ? '1' : '3');
                }
                
                // Returns 2 if duplicate entry found in repository
                return response()->json((string)$response);
            }

            return response()->json('0'); // Fallback for invalid input

        } catch (\Exception $e) {
            // Future error-free: catch unexpected issues
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
