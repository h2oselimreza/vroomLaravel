<?php

namespace App\Http\Controllers\Client\Report;

use App\Http\Controllers\Controller;
use App\Repositories\Client\ReportRepository;
use App\Repositories\Client\VehicleRepository;
use App\Repositories\CommonRepository;
use App\Repositories\MasterData\MasterDataRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseDetailsHistoryReportController extends Controller
{
    public function index(
        VehicleRepository $vehicleRepository,
        CommonRepository $commonRepository,
        MasterDataRepository $masterDataRepository
    ) {

        $arr = [];

        $arr['isActiveFlag'] = 1;

        $arr['bulkFlag'] = 2; // 2 means all vehicle without vehicle array

        $arr['companyCode'] = Auth::user()->customerEmployee->company;

        $vehicles = $vehicleRepository->getVehicleInfo($arr);

        $costHeads = $commonRepository->getCostHead(1);

        $vendors = $masterDataRepository->getVendorList(
            1,
            Auth::user()->customerEmployee->company
        );

        return view(
            'client.report.expense-history-details.index',
            compact(
                'vehicles',
                'costHeads',
                'vendors'
            )
        );
    }

    public function showExpenseDetailsHistory(
        Request $request
    ) {

        $arr = [];

        $arr['vehicleIdStr'] = trim(
            $request->post('vehicleIdStr', '')
        );

        $arr['expenseHeadCode'] = trim(
            $request->post('costHeadStr', '')
        );

        $arr['vendorCode'] = trim(
            $request->post('vendorStr', '')
        );

        $fromDate = trim(
            $request->post('fromDate', '')
        );

        $toDate = trim(
            $request->post('toDate', '')
        );

        validateDate($fromDate);

        validateDate($toDate);

        $company = Auth::user()->customerEmployee->company;

        $vehicleIdStr = $arr['vehicleIdStr'];

        $expenseHeadCode = $arr['expenseHeadCode'];

        $vendorCode = $arr['vendorCode'];

        return view(
            'client.report.expense-history-details.expenseDataDetailsHistoryView',
            compact(
                'company',
                'fromDate',
                'toDate',
                'vehicleIdStr',
                'expenseHeadCode',
                'vendorCode'
            )
        );
    }

    public function getExpenseDetailsHistoryData(
        Request $request,
        ReportRepository $reportRepository
    ) {

            $fromDate = $request->fromDate
                ? $request->fromDate
                : Carbon::now()->startOfMonth()->format('Y-m-d');

            $toDate = $request->toDate
                ? $request->toDate
                : Carbon::now()->endOfMonth()->format('Y-m-d');

            validateDate($fromDate);

            validateDate($toDate);

            $arr = [];

            $arr['vehicleStr'] = $request->vehicleStr
                ? $request->vehicleStr
                : "";

            $arr['expenseHeadCode'] = $request->expenseHeadCode
                ? $request->expenseHeadCode
                : "";

            $arr['vendorCode'] = $request->vendorCode
                ? $request->vendorCode
                : "";

            $iterationNumber = $request->iterationNumber
                ? $request->iterationNumber
                : 0;

            $end = config('constants.SHOW_MORE_ITEM');

            $start = ($iterationNumber * ($end - 1));

            $company = Auth::user()->customerEmployee->company;

            $expenses = $reportRepository->getExpenseDetailsHistoryData(
                $fromDate,
                $toDate,
                $start,
                $end,
                $company,
                $arr
            );

            return response()->json([
                'expenses' => $expenses
            ]);
    }
}
