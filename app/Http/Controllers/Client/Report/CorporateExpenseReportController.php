<?php

namespace App\Http\Controllers\Client\Report;

use App\Http\Controllers\Controller;
use App\Repositories\Client\ReportRepository;
use App\Repositories\Client\VehicleRepository;
use App\Repositories\CommonRepository;
use App\Repositories\MasterData\MasterDataRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CorporateExpenseReportController extends Controller
{
    public function index(VehicleRepository $vehicleRepository, CommonRepository $commonRepository, MasterDataRepository $masterDataRepository)
    {

        $isActiveFlag = 1;
        $arr = [
            'isActiveFlag' => 1,
            'bulkFlag'     => 2, // 2 means all vehicle without vehicle array
            'companyCode'  => Auth::user()->customerEmployee->company,
        ];

        // Vehicle List
        $vehicles = $vehicleRepository->getVehicleInfo($arr);

        // Cost Head List
        $costHeads = $commonRepository->getCostHead(1);

        // Vendor List
        $vendors = $masterDataRepository
            ->getVendorList(
                $isActiveFlag ?? 1,
                Auth::user()->customerEmployee->company,
            );

        return view('client.report.corporate-expense-report.index', compact(
            'vehicles',
            'costHeads',
            'vendors'
        ));
    }

    public function expenseReportDetails(
        Request $request,
        ReportRepository $reportRepository
    ) {
        $arr = [];

        $arr['vehicleIdStr']   = $request->vehicleIdStr;
        $arr['expenseHeadCode'] = $request->costHeadStr;
        $arr['vendorCode']     = $request->vendorStr;

        $arr['fromDate'] = $request->fromDate;
        $arr['toDate']   = $request->toDate;

        $arr['company'] = Auth::user()->customerEmployee->company;

        if (
            (
                $arr['vehicleIdStr'] != "" ||
                $arr['expenseHeadCode'] != "" ||
                $arr['vendorCode'] != ""
            )
            &&
            ($arr['fromDate'] && $arr['toDate'])
        ) {

            $data = [];

            $data['company']  = $arr['company'];
            $data['fromDate'] = $arr['fromDate'];
            $data['toDate']   = $arr['toDate'];

            // Vehicle Wise Report

            if (
                $arr['vehicleIdStr'] != "" &&
                $arr['expenseHeadCode'] == "" &&
                $arr['vendorCode'] == ""
            ) {

                $data['reportType'] = 'vechiclewise';

                $data['expenseDetails'] =
                    $reportRepository->expenseReportDetails($arr);

                return view(
                    'client.report.corporate-expense-report.expVehicleSummaryReportView',
                    compact('data')
                );
            }

            // Head Wise Report
            else if (
                $arr['vehicleIdStr'] == "" &&
                $arr['expenseHeadCode'] != "" &&
                $arr['vendorCode'] == ""
            ) {

                $data['expenseDetails'] =
                    $reportRepository->expenseReportDetails($arr);
                $data['reportType'] = 'headwise';

                $arr['flag'] = "headwise";

                $data['categoryDetails'] =
                    $reportRepository->getExpCategoryWiseDetails($arr);
                
                return view(
                    'client.report.corporate-expense-report.expHeadSummaryReportView',
                    compact('data')
                );
            }

            /*
            | Vehicle and Head Wise Report
            */
            else if (
                $arr['vehicleIdStr'] != "" &&
                $arr['expenseHeadCode'] != "" &&
                $arr['vendorCode'] == ""
            ) {

                $data['expenseDetails'] =
                    $reportRepository->expenseReportDetails($arr);

                $arr['flag'] = "headvehiclewise";

                $data['categoryDetails'] =
                    $reportRepository->getExpCategoryWiseDetails($arr);

                return view(
                    'client.report.corporate-expense-report.expDetailReportView',
                    compact('data')
                );
            }

            /*
            | Vendor Wise Report
            */
            else if (
                $arr['vehicleIdStr'] == "" &&
                $arr['expenseHeadCode'] == "" &&
                $arr['vendorCode'] != ""
            ) {
                $arr['flag'] = "vendorwise";

                $data['vendorDetails'] =
                    $reportRepository->getExpVendorWiseDetails($arr);

                $data['reportType'] = 'vendorwise';

                return view(
                    'client.report.corporate-expense-report.expVendorSummaryReportView',
                    compact('data')
                );
            }

            /*
            | Head and Vendor Wise Report
            */
            else if (
                $arr['vehicleIdStr'] == "" &&
                $arr['expenseHeadCode'] != "" &&
                $arr['vendorCode'] != ""
            ) {
                //dd($data['expenseDetails'],'eee');
                $data['expenseDetails'] =
                    $reportRepository->getExpHeadVendorWiseDetails($arr);

                $data['categoryDetails'] =
                    $reportRepository->getExpCategoryVendorDetails($arr);

                return view(
                    'client.report.corporate-expense-report.expHeadVendorReportView',
                    compact('data')
                );
            }

            /*
            | Vehicle and Vendor Wise Report
            */
            else if (
                $arr['vehicleIdStr'] != "" &&
                $arr['expenseHeadCode'] == "" &&
                $arr['vendorCode'] != ""
            ) {

                $data['expenseDetails'] =
                    $reportRepository->getExpVehicleVendorWiseDetails($arr);

                $data['vendorDetails'] =
                    $reportRepository->getExpVehicleVendorWiseSummary($arr);

                $data['reportType'] = 'vendorwise';

                return view(
                    'client.report.corporate-expense-report.expVehicleVendorReportView',
                    compact('data')
                );
            }

            /*
            | Vehicle + Vendor + Head Wise Report
            */
            else if (
                $arr['vehicleIdStr'] != "" &&
                $arr['expenseHeadCode'] != "" &&
                $arr['vendorCode'] != ""
            ) {

                $reportGroup = $request->post('reportGroup');
                /*
                | Vehicle Wise Group
                */
                if ($reportGroup == 'vehicleWise') {

                    $arr['reportType'] = 'vehicleWise';

                    $data['expenseDetails'] =
                        $reportRepository->getHeadVendorVehicleWiseDetails($arr);

                    $data['vehicleDetails'] =
                        $reportRepository->getHeadVendorVehicleWiseSummary($arr);

                    return view(
                        'client.report.corporate-expense-report.expHeadVehiVendReportView',
                        compact('data')
                    );
                }

                /*
                | Vendor Wise Group
                */
                else if ($reportGroup == 'vendorWise') {

                    $arr['reportType'] = 'vendorWise';

                    $data['expenseDetails'] =
                        $reportRepository->getHeadVendorVehicleWiseDetails($arr);
                    $data['vendorDetails'] =
                        $reportRepository->getHeadVendorVehicleWiseSummary($arr);

                    return view(
                        'client.report.corporate-expense-report.expHeadVehiVendReportView',
                        compact('data')
                    );
                }
            }

        } else {
        
            return redirect()->route('client.report.corporate-expense-report')->with('error','Data is missing');
        }
    }
}
