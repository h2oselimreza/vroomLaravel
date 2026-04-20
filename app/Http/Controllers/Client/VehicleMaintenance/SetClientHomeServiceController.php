<?php

namespace App\Http\Controllers\Client\VehicleMaintenance;

use App\Http\Controllers\Controller;
use App\Models\Client\HomeServiceAppDetail;
use App\Models\Client\HomeServiceAppSummaryGen;
use App\Repositories\Client\HomeServiceRepository;
use App\Repositories\Client\VehicleRepository;
use App\Services\Client\GenerateMonthlyToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SetClientHomeServiceController extends Controller
{
    public function setHomeService(HomeServiceRepository $homeServiceRepository, VehicleRepository $vehicleRepository){
        $arr['isActiveFlag'] = 1;
        $arr['bulkFlag'] = 2; 
        $arr['companyCode'] = auth()->user()?->customerEmployee?->company;
        $vehicles = $vehicleRepository->getVehicleInfo($arr);
        $variantArr['variantType'] = config('constants.HOME_SER');

        $distinctServices = $homeServiceRepository->getDistinctService($variantArr);
        $serviceVariants = $homeServiceRepository->getHomeService($variantArr, 1);
        $companyInfo = $homeServiceRepository->getSingleCompanyInfo($arr['companyCode']);
        return view('client.vehicle-maintenance.set-home-service.create-edit',compact('vehicles', 'distinctServices','serviceVariants','companyInfo'));
    }

    public function addNewHomeService(Request $request, GenerateMonthlyToken $generateMonthlyToken)
    {
        $serviceTime = $request->input('serviceTime');
        //dd($request->input('takenServiceVarCount'));
        $summaryArr = [
            'name'         => trim($request->input('name')),
            'mobile'       => trim($request->input('mobile')),
            'service_date' => trim($request->input('serviceDate')),
            'service_time' => $serviceTime ? date("H:i:s", strtotime($serviceTime)) : null,
            'address'      => trim($request->input('address')),
            'remarks'      => $request->filled('remarks') ? trim($request->input('remarks')) : null,
        ];

        if (
            $serviceTime &&
            $summaryArr['name'] &&
            $summaryArr['mobile'] &&
            $summaryArr['service_date'] &&
            $summaryArr['address']
        ) {

            $appointmentNo = config('constants.HOMESERVICE_NO') . $generateMonthlyToken->get_month_token(config('constants.HOMESERVICE_NO'));
            $detailArr = [];
            $grandTotal = 0;

            $count = (int) $request->input('takenServiceVarCount', 0);

            for ($j = 1; $j <= $count; $j++) {

                $serviceVarCode = trim($request->input('takenServiceVarCode' . $j));

                if ($serviceVarCode) {

                    $unitPrice = (float) $request->input('takenServiceUnitPrice' . $j);
                    $qty       = (float) $request->input('quantity' . $j);

                    $total = $unitPrice * $qty;
                    $grandTotal += $total;

                    $detailArr[] = [
                        'appointment_no'  => $appointmentNo,
                        'service_variant' => $serviceVarCode,
                        'unit_price'      => $unitPrice,
                        'quantity'        => $qty,
                        'total_amount'    => $total,

                        'created_by'      => auth()->user()->user_id,
                        'created_type'    => config('constants.CLIENT'),
                        'created_dt_tm'   => now(),
                        'updated_by'      => auth()->user()->user_id,
                        'updated_type'    => config('constants.CLIENT'),
                        'updated_dt_tm'   => now(),
                    ];
                }
            }

            // ✅ Summary Data
            $summaryArr['grand_total']   = $grandTotal;
            $summaryArr['appointment_no'] = $appointmentNo;
            $summaryArr['company']        = auth()->user()?->customerEmployee?->company;
            $summaryArr['status']         = config('constants.APPOINTMENT_PENDING');

            $summaryArr['created_type']   = config('constants.CLIENT');
            $summaryArr['updated_type']   = config('constants.CLIENT');

            // ✅ Transaction (VERY IMPORTANT)
            DB::beginTransaction();

            try {
                HomeServiceAppSummaryGen::create($summaryArr);
                if (!empty($detailArr)) {
                    HomeServiceAppDetail::insert($detailArr);
                }

                DB::commit();

                // SMS / Email trigger
                //$this->smsMailSend($summaryArr);
                return redirect()->route('client.vehicle-maintenance.home-service.homeServiceList')
                ->with('success', 'Home service created successfully');
            } catch (\Exception $e) {

                DB::rollBack();

                return redirect()->back()->with('error', 'Insert error'.$e->getMessage());
            }

        }
    }
}
