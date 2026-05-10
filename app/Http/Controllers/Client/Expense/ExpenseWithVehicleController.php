<?php

namespace App\Http\Controllers\Client\Expense;

use App\Http\Controllers\Controller;
use App\Repositories\Client\ExpenseRepository;
use App\Repositories\Client\VehicleRepository;
use App\Repositories\CommonRepository;
use App\Repositories\MasterData\MasterDataRepository;
use App\Services\TokenService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ExpenseWithVehicleController extends Controller
{
    public function index(Request $request, ExpenseRepository $expenseRepository)
    {
        $data = [];

        $data['leftMenuModuleUrl'] = "client/Expense/expenseList";
        $data['msg'] = "";
        $data['msgFlag'] = "";

        // Filters (same logic as CI)
        $arr = [
            'company'     => Auth::user()->customerEmployee->company,
            'expenseType' => config('constants.EXP_TYPE_VEHICLE'),
            'expenseNo'   => $request->get('expenseNo', ''),
            'title'       => $request->get('title', ''),
            'expenseDate' => '',
            'fromDate'    => $request->get('fromDate', ''),
            'toDate'      => $request->get('toDate', ''),
        ];

        $perPage = 10;

        // ✅ Single optimized query
        $expenses = $expenseRepository->getExpenseSummary($arr, $perPage);

        // Keep query string (same behavior)
        $expenses->appends([
            'expenseNo' => $arr['expenseNo'],
            'title'     => $arr['title'],
            'fromDate'  => $arr['fromDate'],
            'toDate'    => $arr['toDate'],
        ]);

        // Assign to view
        // $data["links"] = $expenses->links();
        // $data["expenseLists"] = $expenses->items();
        // $data["serial"] = ($expenses->currentPage() - 1) * $perPage;

        $data["expenseNo"] = $arr['expenseNo'];
        $data["title"] = $arr['title'];
        $data["expenseDate"] = $arr['expenseDate'];
        $data["fromDate"] = $arr['fromDate'];
        $data["toDate"] = $arr['toDate'];

        $data['expenseLists'] = $expenseRepository->getExpenseSummaryList($arr);


        return view('client.expense.expense-with-vehicle.index', $data);
    }

    public function create(VehicleRepository $vehicleRepository, CommonRepository $commonRepository, MasterDataRepository $masterDataRepository){
        $arr['isActiveFlag'] = 1;
        $arr['bulkFlag'] = 2;  // 2 means all vehicle without vehicle array
        $arr['companyCode'] = Auth::user()->customerEmployee->company;
        $data['vehicles'] = $vehicleRepository->getVehicleInfo($arr);

        $data['costHeads'] = $commonRepository->getCostHead(1);
        $data['vendors'] = array();
        if (Auth::user()->customerEmployee->customer_type == config('constants.CORPORATE_CUST')) {
            $arr['companyCode'] = $arr['companyCode'];
            $arr['bulkFlag'] = 1;
            $data['vendors'] = $masterDataRepository->getVendorGeneralInfo($arr);
        }

        return view('client.expense.expense-with-vehicle.create-edit',compact('data'));

    }

    public function store(
        Request $request,
        VehicleRepository $vehicleRepository,
        ExpenseRepository $expenseRepository,
        TokenService $tokenService
    ) {
        DB::beginTransaction();

        try {

            $vehicleCount = (int) $request->vehicleCount;
            $redirectFlagHidden = (int) $request->redirectFlagHidden;
            $detailsArr = [];
            $insertFileArr = [];
            $totalAmount = 0;

            if (!$vehicleCount) {

                DB::rollBack();

                return $redirectFlagHidden == 1
                    ? redirect('/client/expense/expense-with-vehicle')
                    : redirect('/client/expense/expense-with-vehicle/create');
            }

            $company = Auth::user()->customerEmployee->company;
            $userId = Auth::user()->user_id;
            $now = Carbon::now();

            $vehicles = $vehicleRepository->getVehicleInfo([
                'isActiveFlag' => 1,
                'bulkFlag' => 2,
                'companyCode' => $company,
            ]);

            $expenseNo = config('constants.EXPENSE_NO') . $tokenService->getTokenByCode(config('constants.EXPENSE_NO'));
            // 🔥 Vehicle loop
            for ($i = 1; $i <= $vehicleCount; $i++) {

                $vehicleId = $request->input('vehicleId' . $i);

                if (!$vehicleId) {
                    continue;
                }

                $vehicleGroup = collect($vehicles)
                    ->firstWhere('vehicle_id', $vehicleId)
                    ->vehicle_group ?? "";

                $takenExpenseCount = (int) $request->input('takenExpenseCount' . $i, 0);

                for ($j = 1; $j <= $takenExpenseCount; $j++) {

                    $expenseHead = trim($request->input('expenseHeadCode' . $i . $j));

                    if (!$expenseHead) {
                        continue;
                    }

                    $quantity = (float) $request->input('quantity' . $i . $j);
                    $unitPrice = (float) $request->input('unitPrice' . $i . $j);

                    $adjust = $request->input('adjust' . $i . $j)
                        ? trim($request->input('adjust' . $i . $j))
                        : '0.00';

                    $amount = ($quantity * $unitPrice) + $adjust;

                    $detailsArr[] = [
                        'expense_no' => $expenseNo,
                        'vehicle' => $vehicleId,
                        'vehicle_group' => $vehicleGroup,
                        'expense_head' => $expenseHead,
                        'odometer_mileage' => $request->input('mileage' . $i)
                            ? (float) $request->input('mileage' . $i)
                            : null,
                        'quantity' => $quantity,
                        'unit_name' => trim($request->input('unitName' . $i . $j)),
                        'unit_price' => $unitPrice,
                        'adjust' => $adjust,
                        'amount' => $amount,
                        'remarks' => $request->input('remarks' . $i . $j) ?: null,
                        'created_by' => $userId,
                        'created_dt_tm' => $now,
                        'updated_by' => $userId,
                        'updated_dt_tm' => $now,
                    ];

                    $totalAmount += $amount;
                }
            }

            // 🔥 Summary
            $summaryArr = [
                'company' => $company,
                'expense_title' => $request->input('expenseTitle'),
                'vendor' => $request->vendor ?: null,
                'expense_type' => 'vehicle',
                'expense_date' => $request->input('expenseDate'),
                'expense_no' => $expenseNo,
                'total_amount' => $totalAmount,
                'created_by' => $userId,
                'created_dt_tm' => $now,
                'updated_by' => $userId,
                'updated_dt_tm' => $now,
            ];
            if ($summaryArr['vendor']) {
                $summaryArr['guest_name'] = null;
                $summaryArr['guest_mobile'] = null;
                $summaryArr['is_guest'] = 0;
            } else {
                $summaryArr['guest_name'] = $request->input('guestName');
                $summaryArr['guest_mobile'] = $request->input('guestMobile')
                    ? $request->input('guestMobile')
                    : null;
                $summaryArr['is_guest'] = 1;
            }
            // validation (unchanged logic)
            if (!($summaryArr['expense_title'] && $summaryArr['expense_date'] && $detailsArr)) {

                DB::rollBack();

                return $redirectFlagHidden == 1
                    ? redirect('/client/expense/expense-with-vehicle')
                    : redirect('/client/expense/expense-with-vehicle/create');
            }

            if (!$summaryArr['vendor'] && !$summaryArr['guest_name']) {

                DB::rollBack();

                return redirect()->route('client.expense.expense-with-vehicle.create')
                    ->with('error', 'Vendor and guest name is not found.');
            }

            // 🔥 File upload
            if ($request->hasFile('expenseFile')) {

                foreach ($request->file('expenseFile') as $file) {

                    $fileName = Str::random(10);
                    $ext = $file->getClientOriginalExtension();
                    $fileNameWithExt = $fileName . '_' . Carbon::now()->format('YmdHis') . '.' . $ext;
                    $file->move(public_path('assets/client/files/expense'), $fileNameWithExt);

                    $insertFileArr[] = [
                        'expense_no' => $expenseNo,
                        'original_name' => $file->getClientOriginalName(),
                        'file_name' => $fileNameWithExt,
                        'created_by' => $userId,
                        'created_dt_tm' => $now,
                        'updated_by' => $userId,
                        'updated_dt_tm' => $now,
                    ];
                }
            }

            // 🔥 Save DB
            $result = $expenseRepository
                ->addExpense($summaryArr, $detailsArr, $insertFileArr);

            DB::commit();

            return redirect()
                ->route('client.expense.expense-with-vehicle.index')
                ->with('success', 'Expense add successfully');

        } catch (\Throwable $e) {
            dd($e->getMessage());
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function edit($expenseNo, Request $request, 
        VehicleRepository $vehicleRepository,
        ExpenseRepository $expenseRepository,
        CommonRepository $commonRepository,
        MasterDataRepository $masterDataRepository) {

        if (!$expenseNo) {
            return redirect()->route('client.expense.expense-with-vehicle.index')->with('error', 'Expense number not found');
        }
        $arr['expenseNo'] = $expenseNo;
        $arr['company'] =  Auth::user()->customerEmployee->company;
        $arr['expenseType'] = config('constants.EXP_TYPE_VEHICLE');
        $data['expenseSummary'] = $expenseRepository->getExpenseSummary($arr);
        if (empty($data['expenseSummary'])) {
            return redirect()->route('client.expense.expense-with-vehicle.index')->with('error', 'Expense number not found');
        }

        $data['takenVehicles'] = $expenseRepository->getExpenseTakenVehicle($arr);
        $data['expenseDetails'] = $expenseRepository->getExpenseDetails($arr);
        $data['expenseFiles'] = $expenseRepository->getExpenseFiles($arr);

        $arr = array();
        $arr['isActiveFlag'] = 1;
        $arr['bulkFlag'] = 2;
        $arr['companyCode'] = Auth::user()->customerEmployee->company;
        $data['vehicles'] = $vehicleRepository->getVehicleInfo($arr);
        $data['costHeads'] = $commonRepository->getCostHead(1);
        $data['expenseNo'] = $expenseNo;
        $data['vendors'] = array();
        if ( Auth::user()->customerEmployee->customer_type == config('constants.CORPORATE_CUST')) {
            $arr['companyCode'] = Auth::user()->customerEmployee->company;
            $arr['bulkFlag'] = 1;
            $data['vendors'] = $masterDataRepository->getVendorGeneralInfo($arr);
        }
        return view('client.expense.expense-with-vehicle.edit',compact('data','expenseNo'));
    }

   public function update($id, Request $request, ExpenseRepository $expenseRepository)
    {
        try {
            $vehicleCount = (int) $request->input('vehicleCount');
            if (!$vehicleCount) {
                return redirect()
                    ->route('client.expense.expense-with-vehicle.edit', $id)
                    ->with('error', 'Vehicle count not found');
            }

            $detailsUpdateArr = [];
            $detailsInsertArr = [];
            $detailTableIdArr = [];
            $totalAmount = 0;
            $expenseNo = $request->input('expenseNo');
            $updateDtTm = $request->input('updateDtTm');
            // 2. Process Nested Vehicle/Expense Logic
            for ($i = 1; $i <= $vehicleCount; $i++) {
                $vehicleId = $request->input('vehicleId' . $i);
                if ($vehicleId) {
                    $takenExpenseCount = (int)$request->input('takenExpenseCount' . $i);
                    for ($j = 1; $j <= $takenExpenseCount; $j++) {
                        
                        if ($request->has('expenseHeadCode' . $i . $j)) {
                            $detailTableId = (int)$request->input('detailTableId' . $i . $j);
                            
                            $quantity = (float)$request->input('quantity' . $i . $j, 0);
                            $unitPrice = (float)$request->input('unitPrice' . $i . $j, 0);
                            $adjust = (float)$request->input('adjust' . $i . $j, 0);
                            $calcAmount = ($quantity * $unitPrice) + $adjust;

                            $row = [
                                'odometer_mileage' => $request->input('mileage' . $i) ? floatval($request->input('mileage' . $i)) : null,
                                'expense_head'     => $request->input('expenseHeadCode' . $i . $j),
                                'quantity'         => $quantity,
                                'unit_name'        => $request->input('unitName' . $i . $j),
                                'unit_price'       => $unitPrice,
                                'adjust'           => $adjust,
                                'amount'           => $calcAmount,
                                'remarks'          => $request->input('remarks' . $i . $j) ?: null,
                                'updated_by'       => Auth::user()->user_id,
                                'updated_dt_tm'    => Carbon::now(),
                            ];

                            if ($detailTableId) {
                                $row['id'] = $detailTableId;
                                $detailsUpdateArr[] = $row;
                                $detailTableIdArr[] = $detailTableId;
                            } else {
                                $row['expense_no'] = $expenseNo;
                                $row['vehicle']    = $vehicleId;
                                $row['created_by'] = Auth::user()->user_id;
                                $row['created_dt_tm'] = Carbon::now();
                                $detailsInsertArr[] = $row;
                            }
                            $totalAmount += $calcAmount;
                        }
                    }
                }
            }

            // 3. Prepare Summary Data
            $summaryData = [
                'expense_title' => $request->input('expenseTitle'),
                'expense_date'  => $request->input('expenseDate'),
                'total_amount'  => $totalAmount,
                'updated_by'    => Auth::user()->user_id,
                'updated_dt_tm' => Carbon::now(),
            ];

            $vendor = $request->input('vendor');
            if ($vendor) {
                $summaryData['vendor'] = trim($vendor);
                $summaryData['is_guest'] = 0;
                $summaryData['guest_name'] = null;
                $summaryData['guest_mobile'] = null;
            } else {
                $summaryData['is_guest'] = 1;
                $summaryData['guest_name'] = $request->input('guestName');
                $summaryData['guest_mobile'] = $request->input('guestMobile') ?: null;
            }

            // Validation logic
            if (empty($summaryData['expense_title']) || empty($summaryData['expense_date']) || ($summaryData['vendor'] == null && $summaryData['guest_name'] == null)) {
                return redirect()
                    ->route('client.expense.expense-with-vehicle.edit', $id)
                    ->with('error', 'Expense title or expense_date or vendor not found');
            }

            // 4. File Upload Handling
            $insertFileArr = [];
            if ($request->hasFile('expenseFile')) {
                foreach ($request->file('expenseFile') as $file) {
                    if ($file->isValid()) {
                        $refNo = reference_no(); 
                        $extension = $file->getClientOriginalExtension();
                        $newFileName = $refNo . '_' . now()->format('Ymd_His') . '.' . $extension;
                        
                        $file->move(public_path('assets/client/files/expense'), $newFileName);

                        $insertFileArr[] = [
                            'expense_no'    => $expenseNo,
                            'original_name' => $file->getClientOriginalName(),
                            'file_name'     => $newFileName,
                            'created_by'    => Auth::user()->user_id,
                            'created_dt_tm' => Carbon::now(),
                            'updated_by'    => Auth::user()->user_id,
                            'updated_dt_tm' => Carbon::now(),
                        ];
                    }
                }
            }

            // 5. Database Transaction via Repository
            $deleteData = [
                'vehicleStr'     => $request->input('deleteVehicleStr'),
                'expenseHeadStr' => $request->input('deleteHeadStr'),
                'fileStr'        => $request->input('deleteFileStr'),
            ];

            // Perform the update
            $result = $expenseRepository->editExpense(
                $summaryData, $detailsUpdateArr, $detailsInsertArr, $detailTableIdArr, 
                $insertFileArr, $expenseNo, $deleteData, $updateDtTm, Auth::user()->customerEmployee->company
            );

            // Handle Return Logic based on Repository result
            if ($result == 3) {
                return redirect()->back()->with('error', 'Data has been modified by another user. Please refresh.');
            } elseif ($result == 4) {
                return redirect()->back()->with('error', 'One or more items do not exist.');
            }

            return redirect()
                    ->route('client.expense.expense-with-vehicle.edit', $id)
                    ->with('success', 'Data updated successfully');

        } catch (\Exception $e) {

            return redirect()
                ->route('client.expense.expense-with-vehicle.edit', $id)
                ->with('error', 'Something went wrong while updating: ' . $e->getMessage());
        }
    }

    public function show($expenseNo, ExpenseRepository $expenseRepository, VehicleRepository $vehicleRepository)
    {

        if(!$expenseNo){
            return redirect()
            ->route('client.expense.expense-with-vehicle.index')
            ->with('error', 'Expense number not found');
        }


        $arr = [];
        $arr['expenseNo']   = $expenseNo;
        $arr['company']     = Auth::user()->customerEmployee->company;
        $arr['expenseType'] = config('constants.EXP_TYPE_VEHICLE');

        $expenseSummary = $expenseRepository->getExpenseSummary($arr);

        if ($expenseSummary->isEmpty()) {
            return redirect()
                ->route('client.expense.expense-with-vehicle.index')
                ->with('error', 'Expense summary not found');
        }

        $takenVehicles = $expenseRepository->getExpenseTakenVehicle($arr);

        $expenseDetails = $expenseRepository->getExpenseDetails($arr);

        $expenseFiles = $expenseRepository->getExpenseFiles($arr);

        $vehicleArr = [];
        $vehicleArr['isActiveFlag'] = 1;
        $vehicleArr['bulkFlag']     = 2;
        $vehicleArr['companyCode']  = Auth::user()->customerEmployee->company;

        $vehicles = $vehicleRepository->getVehicleInfo($vehicleArr);

        $expenseNo = $expenseNo;

        //$this->data['vendors'] = [];

        return view('client.expense.expense-with-vehicle.show', 
            compact('takenVehicles','expenseDetails','expenseFiles','vehicles','expenseNo','expenseSummary')
        );      
    }

}
