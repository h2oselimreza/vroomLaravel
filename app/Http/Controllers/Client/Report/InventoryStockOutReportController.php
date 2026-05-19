<?php

namespace App\Http\Controllers\Client\Report;

use App\Http\Controllers\Controller;
use App\Repositories\Client\ReportRepository;
use App\Repositories\Client\VehicleRepository;
use App\Repositories\CommonRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryStockOutReportController extends Controller
{
    public function index(
        VehicleRepository $vehicleRepository,
        CommonRepository $commonRepository
    ) {

        $company = Auth::user()->customerEmployee->company;

        $vehicleArr = [
            'isActiveFlag' => 1,
            'bulkFlag'     => 2, // 2 means all vehicle without vehicle array
            'companyCode'  => $company,
        ];

        $vehicles = $vehicleRepository
            ->getVehicleInfo($vehicleArr);

        $variantArr = [
            'company'     => $company,
            'variantType' => config('constants.PURCHASE'),
        ];

        $variants = $commonRepository
            ->getProductVariants($variantArr, 1);

        return view(
            'client.report.inventory.stock-out.stockOutReportView',
            compact(
                'vehicles',
                'variants'
            )
        );
    }

    public function stockOutReportDetails(
        Request $request,
        ReportRepository $reportRepository
    ) {
            $arr = [];

            $arr['vehicleIdStr'] = $request->vehicleIdStr;
            $arr['variantStr'] = $request->variantStr;
            $arr['fromDate'] = $request->fromDate;
            $arr['toDate'] = $request->toDate;
            $arr['dateFlag'] = $request->dateWiseFlag;
            $arr['reportGroup'] = $request->reportGroup
                ? $request->reportGroup
                : 'vehicleWise';

            $arr['company'] = Auth::user()->customerEmployee->company;

            if (
                $arr['variantStr'] &&
                $arr['fromDate'] &&
                $arr['toDate']
            ) {

                $stockOutDetails = $reportRepository
                    ->stockOutReportDetails($arr);

                $company = $arr['company'];
                $fromDate = $arr['fromDate'];
                $toDate = $arr['toDate'];

                if (
                    $arr['vehicleIdStr'] != "" &&
                    $arr['variantStr'] != ""
                ) {

                    if ($arr['reportGroup'] == 'vehicleWise') {

                        $reportType = 'vehicleWise';

                        if ($arr['dateFlag']) {

                            return view(
                                'client.report.inventory.stock-out.stkOutVehcleDateReportView',
                                compact(
                                    'stockOutDetails',
                                    'company',
                                    'fromDate',
                                    'toDate',
                                    'reportType'
                                )
                            );

                        } else {

                            return view(
                                'client.report.inventory.stock-out.stkOutVehcleDetailReportView',
                                compact(
                                    'stockOutDetails',
                                    'company',
                                    'fromDate',
                                    'toDate',
                                    'reportType'
                                )
                            );
                        }

                    } elseif ($arr['reportGroup'] == 'productWise') {

                        $reportType = 'productWise';

                        if ($arr['dateFlag']) {

                            return view(
                                'client.report.inventory.stock-out.stkOutPrdDateReportView',
                                compact(
                                    'stockOutDetails',
                                    'company',
                                    'fromDate',
                                    'toDate',
                                    'reportType'
                                )
                            );

                        } else {

                            return view(
                                'client.report.inventory.stock-out.stkOutPrdDetailReportView',
                                compact(
                                    'stockOutDetails',
                                    'company',
                                    'fromDate',
                                    'toDate',
                                    'reportType'
                                )
                            );
                        }
                    }

                } elseif (
                    $arr['vehicleIdStr'] == "" &&
                    $arr['variantStr'] != ""
                ) {

                    $reportType = 'vechiclewise';

                    return view(
                        'client.report.stkOutPrdSummReportView',
                        compact(
                            'stockOutDetails',
                            'company',
                            'fromDate',
                            'toDate',
                            'reportType'
                        )
                    );
                }

            } else {

                return redirect()->route('client.report.inventory-stock-out-list.index');

            }
    }
}
