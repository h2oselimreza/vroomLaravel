<?php

namespace App\Http\Controllers\Client\Report;

use App\Http\Controllers\Controller;
use App\Repositories\Client\ReportRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseHistoryReportController extends Controller
{

    public function index(
        Request $request
    ) {

        $fromDate = $request->post('fromDate')
            ? $request->post('fromDate')
            : Carbon::now()->startOfMonth()->format('Y-m-d');

        $toDate = $request->post('toDate')
            ? $request->post('toDate')
            : Carbon::now()->endOfMonth()->format('Y-m-d');

        validateDate($fromDate);

        validateDate($toDate);

        $company = Auth::user()->customerEmployee->company;

        return view(
            'client.report.expense-history.index',
            compact(
                'company',
                'fromDate',
                'toDate'
            )
        );
    }

    public function getExpenseHistoryData(
        Request $request,
        ReportRepository $reportRepository
    ) {

            $fromDate = $request->post('fromDate')
                ? $request->post('fromDate')
                : Carbon::now()->startOfMonth()->format('Y-m-d');

            $toDate = $request->post('toDate')
                ? $request->post('toDate')
                : Carbon::now()->endOfMonth()->format('Y-m-d');

            validateDate($fromDate);
            validateDate($toDate);

            $iterationNumber = $request->post('iterationNumber')
                ? $request->post('iterationNumber')
                : 0;

            $end = config('constants.SHOW_MORE_ITEM');
            $start = ($iterationNumber * ($end - 1));

            $company = Auth::user()->customerEmployee->company;

            $expenses = $reportRepository->getExpenseHistoryData(
                $fromDate,
                $toDate,
                $start,
                $end,
                $company
            );

            return response()->json([
                'expenses' => $expenses
            ]);

        }
}
