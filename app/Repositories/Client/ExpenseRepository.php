<?php

namespace App\Repositories\Client;

use App\Models\Client\Vehicle;
use App\Models\Client\VehicleAssignDetail;
use App\Models\Client\VehicleBookingSummary;
use App\Models\Company;
use App\Models\CustomerEmployee;
use Illuminate\Support\Facades\DB;

class ExpenseRepository
{


    public function getExpenseSummary(array $arr, int $perPage = 10)
    {
        return DB::table('expense_summary as es')
        ->select('es.*', 'cv.title as vendor_title')
        ->leftJoin('corporate_vendor as cv', 'cv.vendor_code', '=', 'es.vendor')

        ->where('es.company', $arr['company'])

        ->when(!empty($arr['expenseType']), fn($q) =>
            $q->where('es.expense_type', $arr['expenseType'])
        )

        ->when(!empty($arr['expenseNo']), fn($q) =>
            $q->where('es.expense_no', $arr['expenseNo'])
        )

        ->when(!empty($arr['title']), fn($q) =>
            $q->where('es.expense_title', 'LIKE', '%' . $arr['title'] . '%')
        )

        ->when(!empty($arr['fromDate']), fn($q) =>
            $q->whereDate('es.expense_date', '>=', $arr['fromDate'])
        )

        ->when(!empty($arr['toDate']), fn($q) =>
            $q->whereDate('es.expense_date', '<=', $arr['toDate'])
        )

        ->orderByDesc('es.created_dt_tm')

        ->paginate($perPage);
    }

    public function getExpenseSummaryList(array $arr)
    {
        return DB::table('expense_summary as es')
        ->select('es.*', 'cv.title as vendor_title')
        ->leftJoin('corporate_vendor as cv', 'cv.vendor_code', '=', 'es.vendor')

        ->where('es.company', $arr['company'])

        ->when(!empty($arr['expenseType']), fn($q) =>
            $q->where('es.expense_type', $arr['expenseType'])
        )

        ->when(!empty($arr['expenseNo']), fn($q) =>
            $q->where('es.expense_no', $arr['expenseNo'])
        )

        ->when(!empty($arr['title']), fn($q) =>
            $q->where('es.expense_title', 'LIKE', '%' . $arr['title'] . '%')
        )

        ->when(!empty($arr['fromDate']), fn($q) =>
            $q->where('es.expense_date', '>=', $arr['fromDate'])
        )

        ->when(!empty($arr['toDate']), fn($q) =>
            $q->where('es.expense_date', '<=', $arr['toDate'])
        )

        ->orderByDesc('es.created_dt_tm')

        ->paginate(10);
    }

    public function addExpense($summaryArr, $detailsArr, $insertFileArr)
    {
        if (!$detailsArr || count($detailsArr) == 0) {
            return 2;
        }

        DB::table('expense_summary')->insert($summaryArr);

        DB::table('expense_detail')->insert($detailsArr);

        if ($insertFileArr && count($insertFileArr) > 0) {
            DB::table('expense_files')->insert($insertFileArr);
        }

        return 1;
    }

}