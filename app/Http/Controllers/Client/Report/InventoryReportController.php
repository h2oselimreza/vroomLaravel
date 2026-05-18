<?php

namespace App\Http\Controllers\Client\Report;

use App\Http\Controllers\Controller;
use App\Repositories\Client\ReportRepository;
use App\Repositories\Client\VehicleRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryReportController extends Controller
{

    public function index(
        VehicleRepository $vehicleRepository
    ) {

            $arr = [];

            $arr['isActiveFlag'] = 1;
            // 2 means all vehicle without vehicle array
            $arr['bulkFlag'] = 2;
            $arr['companyCode'] = Auth::user()->customerEmployee->company;
            $vehicles = $vehicleRepository->getVehicleInfo($arr);

            return view(
                'client.report.inventory.index',compact('vehicles')
            );

    }

    public function getVehicleLastProduct(
        Request $request,
        ReportRepository $reportRepository
    ) {

            $arr = [];
            $arr['vehicle'] = trim($request->get('vehicle'));
            $arr['company'] = Auth::user()->customerEmployee->company;  
            $results = $reportRepository->getVehicleLastProduct($arr);
;
            $i = 1;

            $response = [];

            foreach ($results as $result) {

                $x = [];

                $x = [
                    $i,
                    $result->category_name,
                    $result->product_name,
                    $result->variant_name,
                    $result->quantity,
                    $result->unit_name,
                    get_date_format1($result->stock_date)
                ];

                $response[] = $x;

                $i++;
            }

            return response()->json([
                'data' => $response
            ]);
    }
}
