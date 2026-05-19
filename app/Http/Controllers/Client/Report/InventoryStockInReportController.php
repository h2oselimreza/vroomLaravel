<?php

namespace App\Http\Controllers\Client\Report;

use App\Http\Controllers\Controller;
use App\Repositories\Client\ReportRepository;
use App\Repositories\Client\VehicleRepository;
use App\Repositories\CommonRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryStockInReportController extends Controller
{

    public function index( VehicleRepository $vehicleRepository, CommonRepository $commonRepository)
    {

        $vehicleArr = [
            'isActiveFlag' => 1,
            'bulkFlag'     => 2, // 2 means all vehicle without vehicle array
            'companyCode'  => Auth::user()->customerEmployee->company,
        ];

        $vehicles = $vehicleRepository->getVehicleInfo($vehicleArr);

        $variantArr = [
            'company'     => Auth::user()->customerEmployee->company,
            'variantType' => config('constants.PURCHASE'),
        ];

        $variants = $commonRepository->getProductVariants($variantArr, 1);

        return view(
            'client.report.inventory.stock-in.stockInReportView',
            compact('vehicles', 'variants')
        );
    }

    public function stockInReportDetails(
        Request $request,
        ReportRepository $reportRepository
    ) {

            $arr = [];

            $arr['variantStr'] = $request->variantStr;
            $arr['fromDate']   = $request->fromDate;
            $arr['toDate']     = $request->toDate;
            $arr['company']    = Auth::user()->customerEmployee->company;

            if (
                $arr['variantStr'] != "" &&
                ($arr['fromDate'] && $arr['toDate'])
            ) {

                $company = $arr['company'];
                $fromDate = $arr['fromDate'];
                $toDate = $arr['toDate'];

                $stockInDetails = $reportRepository
                    ->getStockInProductDetails($arr);


                return view(
                    'client.report.inventory.stock-in.stockInProDetailReportView',
                    compact(
                        'company',
                        'fromDate',
                        'toDate',
                        'stockInDetails'
                    )
                );

            } else {
                return redirect()->route('client.report.inventory-stock-in-list.index')->with('error','Date is not found');
            }
    }
}
