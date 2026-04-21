<?php

namespace App\Http\Controllers\Client\VehicleMaintenance;

use App\Http\Controllers\Controller;
use App\Repositories\Client\AppointmentRepository;
use Illuminate\Http\Request;

class ClientWorkshopAppointmentController extends Controller
{
    public function index(AppointmentRepository $appointmentRepository){
        $arr['companyCode'] = auth()->user()?->customerEmployee?->company;
        $arr['status'] = config('constants.APPOINTMENT_ALL');
        $appointmentLists = $appointmentRepository->getAppointmentList($arr);
        return view('client.vehicle-maintenance.workshop-appointment.index',compact('appointmentLists'));
    }

    public function getWorkshopInfo(Request $request, AppointmentRepository $appointmentRepository){
        $workshopCode = $request->workshopCode;
        $result = $appointmentRepository->getWorkshopInfo($workshopCode);
        return $result;
    }
}
