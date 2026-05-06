<?php

namespace App\Http\Controllers\Client\Expense;

use App\Http\Controllers\Controller;
use App\Repositories\Client\ExpenseRepository;
use App\Repositories\Client\VehicleRepository;
use App\Repositories\CommonRepository;
use App\Repositories\MasterData\MasterDataRepository;
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
        ExpenseRepository $expenseRepository
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
            $userId = Auth::id();
            $now = Carbon::now();

            $vehicles = $vehicleRepository->getVehicleInfo([
                'isActiveFlag' => 1,
                'bulkFlag' => 2,
                'companyCode' => $company,
            ]);

            $expenseNo = 'EXPENSE_NO' . date('Ym');
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
            $vendor = $request->vendor;
            $summaryArr = [
                'company' => $company,
                'expense_title' => $request->input('expenseTitle'),
                'vendor' => $vendor ?: null,
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
                $summaryArr['guest_name'] = trim($request->input('guestName'));
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
                    $fileNameWithExt = $fileName . '.' . $ext;

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


}
