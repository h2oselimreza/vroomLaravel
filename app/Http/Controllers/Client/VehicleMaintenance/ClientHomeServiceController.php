<?php

namespace App\Http\Controllers\Client\VehicleMaintenance;

use App\Http\Controllers\Controller;
use App\Models\Client\HomeServiceAppSummary;
use App\Repositories\Client\HomeServiceRepository;
use Illuminate\Http\Request;

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
}
