<?php

namespace App\Http\Controllers\Admin\MasterData\Vehicle;

use App\Http\Controllers\Controller;
use App\Repositories\MasterData\VehicleRepository;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index(VehicleRepository $vehicleRepository){
        $vehicleCount = $vehicleRepository->getVehicleTypeCounts();
        return view('admin.master-data.vehicle.index',compact('vehicleCount'));
    }
}
