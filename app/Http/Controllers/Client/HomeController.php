<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Repositories\Client\HomeRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

    public function index(
        Request $request,
        HomeRepository $homeRepository
    ) {

        $expensecheckFlag = "client/Expense/expenseList";

        $vehicleId = $request->get('vehicleId');

        $companyCode = Auth::user()->customerEmployee->company;

        $vehicleInfo = $homeRepository->getSingleVehicleInfo(
            $vehicleId,
            $companyCode
        );

        /*
        | Default Variables
        */

        $costCategotyGraph = json_encode([]);
        $costMonthGraph = json_encode([]);

        $yearLists = [];
        $categoryWiseYear = (int) Carbon::now()->format('Y');
        $monthWiseYear = (int) Carbon::now()->format('Y');

        /*
        | Expense Graph Data
        */
        // if ($expensecheckFlag) {

            $categoryWiseYear = (int) $request->post(
                'categoryWiseYear',
                Carbon::now()->format('Y')
            );

            $monthWiseYear = (int) $request->post(
                'monthWiseYear',
                Carbon::now()->format('Y')
            );

            /*
            | Category Wise Expense
            */

            $costCategotyValue = $homeRepository->getVehicleCostCategory(
                $companyCode,
                $categoryWiseYear,
                $vehicleId
            );

            $jsonCategoryArr = [];

            $totalExpense = 0;

            foreach ($costCategotyValue as $costCategoty) {

                $arr = [];
                $arr['name'] = $costCategoty->category_name;
                $arr['y'] = floatval($costCategoty->total_expense);
                $jsonCategoryArr[] = $arr;
                $totalExpense += $costCategoty->total_expense;
            }

            $costCategotyGraph = json_encode($jsonCategoryArr);

            /*
            | Month Wise Expense
            */

            $jsonMonthArr = [];

            $monthArr = [
                '1', '2', '3', '4', '5', '6',
                '7', '8', '9', '10', '11', '12'
            ];

            $costMonthValue = $homeRepository->getVehicleCostMonth(
                $companyCode,
                $monthWiseYear,
                $vehicleId
            );

            for ($i = 0; $i < 12; $i++) {

                $totalExpense = 0;

                foreach ($costMonthValue as $costMonth) {

                    if ($monthArr[$i] == $costMonth->month) {

                        $totalExpense = floatval(
                            $costMonth->total_expense
                        );

                        break;
                    }
                }

                $jsonMonthArr[] = $totalExpense;
            }

            $yearLists = $homeRepository->getCostYear(
                $companyCode
            );

            $costMonthGraph = json_encode($jsonMonthArr);
        // }

        /*
        | View Load
        */
        if ($vehicleInfo) {

            return view(
                'client.home.vehicleDashBoardView',
                compact(
                    'vehicleInfo',
                    'vehicleId',
                    'expensecheckFlag',
                    'costCategotyGraph',
                    'costMonthGraph',
                    'yearLists',
                    'categoryWiseYear',
                    'monthWiseYear'
                )
            );

        } else {
            return redirect()->route('client.home');
        }
    }
}
