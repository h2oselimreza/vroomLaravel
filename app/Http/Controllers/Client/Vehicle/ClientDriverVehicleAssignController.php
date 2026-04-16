<?php

namespace App\Http\Controllers\Client\Vehicle;

use App\Http\Controllers\Controller;
use App\Repositories\Client\EmployeeRepository;
use App\Repositories\Client\VehicleDriverAssignRepository;
use App\Repositories\Client\VehicleRepository;
use Illuminate\Http\Request;

class ClientDriverVehicleAssignController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(VehicleRepository $vehicleRepository, EmployeeRepository $employeeRepository)
    {
        $arr = [
            'companyCode'  => auth()->user()?->customerEmployee?->company,
            'vehicleId'    => '',
            'vehicleIdArr' => [],
            'isActiveFlag' => '',
        ];
        $vehicles = $vehicleRepository->getVehicleInfo($arr);
        $drivers = $employeeRepository->getEmpPersonalInfo(null, null, 1, auth()->user()?->customerEmployee?->company);
        return view('client.pool.driver-assign.index',compact('vehicles','drivers'));
    }

    public function assignDriver(Request $request, VehicleDriverAssignRepository $vehicleDriverAssignRepository)
    {
        $request->validate([
            'vehicle' => 'required',
            'driver' => 'required',
            'contengencyDtTm' => 'required',
        ]);

        $vehicle = $request->vehicle;
        $driver = $request->driver;
        $contengencyDtTm = $request->contengencyDtTm;

        // company from auth (recommended)
        $companyCode = auth()->user()?->customerEmployee?->company;

        $result = $vehicleDriverAssignRepository->assignDriver($vehicle, $driver, $companyCode, $contengencyDtTm);

        return response()->json($result);
    }

    public function removeDriver(Request $request, VehicleDriverAssignRepository $vehicleDriverAssignRepository)
    {

        $vehicle = $request->vehicle;
        $contengencyDtTm = $request->contengencyDtTm;

        $companyCode = auth()->user()?->customerEmployee?->company;

        $result = $vehicleDriverAssignRepository
            ->removeDriver($vehicle, $companyCode, $contengencyDtTm);

        return response()->json($result);
    }
}
