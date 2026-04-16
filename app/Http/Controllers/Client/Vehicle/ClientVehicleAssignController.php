<?php

namespace App\Http\Controllers\Client\Vehicle;

use App\Http\Controllers\Controller;
use App\Repositories\Client\EmployeeRepository;
use App\Repositories\Client\VehicleRepository;
use Illuminate\Http\Request;

class ClientVehicleAssignController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(VehicleRepository $vehicleRepository, EmployeeRepository $employeeRepository)
    {
        $arr = [
            'companyCode'  => auth()->user()?->customerEmployee?->company,
            'bulkFlag'    => 2,
            'isActiveFlag' => 1,
        ];
        $vehicles = $vehicleRepository->getVehicleInfo($arr);
        $drivers = $employeeRepository->getEmpPersonalInfo(null, null, 1, auth()->user()?->customerEmployee?->company);
        return view('client.pool.vehicle-assign.index',compact('vehicles','drivers'));
    }


    public function showEmployeeAssign(Request $request, VehicleRepository $vehicleRepository)
    {

        $vehicleId  = trim($request->get('vehicleId'));
        $assignType = trim($request->get('type'));
        $bookingNo  = trim($request->get('bookingNo'));

        $companyCode = auth()->user()?->customerEmployee?->company;

        // Get vehicle details
        $vehicleDetails = $vehicleRepository->getPullVehicleDetails($vehicleId, $companyCode);

        if (!$vehicleDetails) {
            return redirect()->back()->with('error', 'Vehicle not found');
        }

        // Business logic
        $vehicleFlag = 0;

        if ($vehicleDetails->assign_type == config('constants.ASSIGN_VACANT')) {
            $vehicleFlag = 1;
        }

        if (!$vehicleDetails->driver_id && $assignType == config('constants.ASSIGN_ENROUTE')) {
            $vehicleFlag = 0;
        }

        // Main condition
        if (
            $vehicleFlag &&
            in_array($assignType, [
                config('constants.ASSIGN_PERSON'),
                config('constants.ASSIGN_ENROUTE')
            ])
        ) {

            $data = [];

            $data['vehicleId'] = $vehicleId;
            $data['assignType'] = $assignType;
            $data['leftMenuModuleUrl'] = "client/VehicleAssign/employeeVehicleAssign";

            $data['locationBtnFlag'] = empty($vehicleDetails->communication_code) ? 0 : 1;

            $data['bookingNo'] = $bookingNo;

            dd($bookingNo);
            // ✅ Booking logic
            if (!empty($bookingNo)) {
dd('ok');
                $bookingSummary = $vehicleRepository->getVehicleBookingSummary($bookingNo, $companyCode);

                $data['bookingSummary'] = $bookingSummary;

                $routeStr = '';

                if (!empty($bookingSummary)) {
                    $routeInfos = json_decode($bookingSummary[0]->route, true);

                    $routeArr = [];
                    $fromFlag = true;

                    foreach ($routeInfos as $routeInfo) {
                        if ($fromFlag) {
                            $routeArr[] = $routeInfo['routeFrom'];
                            $fromFlag = false;
                        }
                        $routeArr[] = $routeInfo['routeTo'];
                    }

                    $routeStr = !empty($routeArr) ? implode(' -- ', $routeArr) : '';
                }

                $data['routeStr'] = $routeStr;
            }

            return view('client.pool.vehicle-assign.employee-assign',compact('data'));
        }

        return redirect()->back()->with('error', 'Vehicle already enroute');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
