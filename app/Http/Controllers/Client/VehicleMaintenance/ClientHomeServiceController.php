<?php

namespace App\Http\Controllers\Client\VehicleMaintenance;

use App\Http\Controllers\Controller;
use App\Models\Client\HomeServiceAppDetail;
use App\Models\Client\HomeServiceAppSummary;
use App\Models\Client\HomeServiceAppSummaryGen;
use App\Repositories\Client\HomeServiceRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientHomeServiceController extends Controller
{
    public function homeServiceList(HomeServiceRepository $homeServiceRepository)
    {

        $data = [];

        $arr['companyCode'] = auth()->user()?->customerEmployee?->company;
        $arr['status'] = config('constants.APPOINTMENT_ALL');

        $appointmentLists = $homeServiceRepository->getHomeServiceList($arr);

        return view('client.vehicle-maintenance.home-service.index', compact('appointmentLists'));
    }

    public function showHomeService($appointmentNo, HomeServiceRepository $homeServiceRepository)
    {

        if (!$appointmentNo) {
            return redirect()->route('client.vehicle-maintenance.home-service.homeServiceList')
                ->with('error', 'Appointment not found');
        }

        // Fetch summary
        $appointmentSummary = HomeServiceAppSummaryGen::where('appointment_no', $appointmentNo)
            ->where('company', auth()->user()?->customerEmployee?->company)
            ->first();

        if (!$appointmentSummary) {
            return redirect()->route('client.vehicle-maintenance.home-service.homeServiceList')
                ->with('error', 'appointment summary not found');
        }

        // Details
        $homeServiceDetails = HomeServiceAppDetail::where('appointment_no', $appointmentNo)->get();

        // ✅ Service variants
        $variantType = config('constants.HOME_SER');

        $distinctServices = $homeServiceRepository->getDistinctService([
            'variantType' => $variantType
        ]);

        $serviceVariants = $homeServiceRepository->getHomeService([
            'variantType' => $variantType
        ], 1);

        // ✅ Data array (Laravel style)
        $data = [
            'leftMenuModuleUrl'   => 'client/GenHomeService/homeServiceList',
            'appointmentSummary'  => $appointmentSummary,
            'homeServiceDetails'  => $homeServiceDetails,
            'distinctServices'    => $distinctServices,
            'serviceVariants'     => $serviceVariants,
            'appointmentNo'       => $appointmentNo,
        ];

        // ✅ View decision based on status
        if ($appointmentSummary->status == config('constants.APPOINTMENT_PENDING')) {
            return view('client.vehicle-maintenance.home-service.edit-home-service', compact('data'));
        }

        return view('client.genHomeService.showHomeServiceView', $data);
    }


    public function updateHomeService(Request $request)
    {
        // Input
        $appointmentNo = trim($request->input('appointmentNo'));
        $serviceTime   = trim($request->input('serviceTime'));

        $summaryArr = [
            'name'         => trim($request->input('name')),
            'mobile'       => trim($request->input('mobile')),
            'address'      => trim($request->input('address')),
            'service_date' => trim($request->input('serviceDate')),
            'service_time' => $serviceTime ? Carbon::parse($serviceTime)->format('H:i:s') : null,
            'remarks'      => $request->filled('remarks') ? trim($request->input('remarks')) : null,
        ];

        // ✅ Validation check (same logic preserved)
        if (
            !$serviceTime ||
            !$summaryArr['name'] ||
            !$summaryArr['mobile'] ||
            !$summaryArr['service_date'] ||
            !$summaryArr['address'] ||
            !$appointmentNo
        ) {
            return redirect()->route('client.genHomeService.homeServiceList');
        }

        // Fetch existing data
        $homeServiceSummary = HomeServiceAppSummaryGen::where('appointment_no', $appointmentNo)->first();
        $homeServiceDetails = HomeServiceAppDetail::where('appointment_no', $appointmentNo)->get();

        if (!$homeServiceSummary || $homeServiceDetails->isEmpty()) {
            return redirect()->route('client.vehicle-maintenance.home-service.homeServiceList')
                ->with('error', 'Home service summary not found');
        }

        // Status check
        if ($homeServiceSummary->status != config('constants.APPOINTMENT_PENDING')) {
            return redirect()->route('client.vehicle-maintenance.home-service.homeServiceList')
                ->with('error', 'Home service summary not found');
        }

        DB::beginTransaction();

        try {

            $updateDetailArr = [];
            $insertDetailArr = [];
            $takenHomeService = [];
            $dbHomeService = [];
            $dbHomeServiceFlag = 1;
            $grandTotal = 0.00;

            $takenServiceVarCount = (int) $request->input('takenServiceVarCount');

            for ($j = 1; $j <= $takenServiceVarCount; $j++) {

                $serviceVarCode = trim($request->input('takenServiceVarCode' . $j));

                if ($serviceVarCode) {

                    $takenHomeService[] = $serviceVarCode;
                    $insertFlag = 1;
                    $arr = [];

                    foreach ($homeServiceDetails as $detail) {

                        if ($dbHomeServiceFlag == 1) {
                            $dbHomeService[] = $detail->service_variant;
                        }

                        if ($detail->service_variant == $serviceVarCode) {

                            $insertFlag = 0;

                            $arr = [
                                'id'            => $detail->id,
                                'unit_price'    => (float) $request->input('takenServiceUnitPrice' . $j),
                                'quantity'      => (float) $request->input('quantity' . $j),
                                'total_amount'  => (float) $request->input('takenServiceUnitPrice' . $j) *
                                                (float) $request->input('quantity' . $j),
                                'updated_by'    => auth()->user()->user_id,
                                'updated_type'  => config('constants.CLIENT'),
                                'updated_dt_tm' => now(),
                            ];

                            $updateDetailArr[] = $arr;
                        }
                    }

                    $dbHomeServiceFlag = 0;

                    // Insert new
                    if ($insertFlag == 1) {

                        $arr = [
                            'appointment_no' => $appointmentNo,
                            'service_variant'=> $serviceVarCode,
                            'unit_price'     => (float) $request->input('takenServiceUnitPrice' . $j),
                            'quantity'       => (float) $request->input('quantity' . $j),
                            'total_amount'   => (float) $request->input('takenServiceUnitPrice' . $j) *
                                                (float) $request->input('quantity' . $j),
                            'created_by'     => auth()->user()->user_id,
                            'created_type'   => config('constants.CLIENT'),
                            'created_dt_tm'  => now(),
                            'updated_by'     => auth()->user()->user_id,
                            'updated_type'   => config('constants.CLIENT'),
                            'updated_dt_tm'  => now(),
                        ];

                        $insertDetailArr[] = $arr;
                    }

                    $grandTotal += $arr['total_amount'];
                }
            }

            // ✅ Summary update
            $summaryArr['grand_total'] = $grandTotal;
            $summaryArr['updated_by'] = auth()->user()->user_id;
            $summaryArr['updated_type'] = config('constants.CLIENT');
            $summaryArr['updated_dt_tm'] = now();

            // ✅ Delete logic
            $deleteHomeServiceArr = array_diff($dbHomeService, $takenHomeService);

            // ✅ Update summary
            HomeServiceAppSummaryGen::where('appointment_no', $appointmentNo)
                ->update($summaryArr);

            // ✅ Update details
            foreach ($updateDetailArr as $updateData) {
                HomeServiceAppDetail::where('id', $updateData['id'])->update($updateData);
            }

            // ✅ Insert new details
            if (!empty($insertDetailArr)) {
                HomeServiceAppDetail::insert($insertDetailArr);
            }

            // ✅ Delete removed services
            if (!empty($deleteHomeServiceArr)) {
                HomeServiceAppDetail::where('appointment_no', $appointmentNo)
                    ->whereIn('service_variant', $deleteHomeServiceArr)
                    ->delete();
            }

            DB::commit();

            return redirect()->route('client.vehicle-maintenance.home-service.homeServiceList')
                ->with('success', 'Home service update successfully');

        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()->route('client.vehicle-maintenance.home-service.homeServiceList')
                ->with('error', 'Something went wrong!'.$e->getMessage());
        }
    }
}
