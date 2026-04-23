<?php

namespace App\Http\Controllers\Admin\Appointment;

use App\Http\Controllers\Controller;
use App\Repositories\Client\AppointmentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function index(Request $request, AppointmentRepository $appointmentRepository) 
    {
            $statusLists = array(
                config('constants.APPOINTMENT_ALL'),
                config('constants.APPOINTMENT_PENDING'),
                config('constants.APPOINTMENT_PROCCESSING'),
                config('constants.APPOINTMENT_ACCEPT'),
                config('constants.APPOINTMENT_ALL'),
            );
            $arr['companyCode'] = Auth::user()?->customerEmployee?->company;
            if($request->get('status')){
                $arr['status'] = $request->get('status');
            }else{
                $arr['status'] = $request->get('status') ?? config('constants.APPOINTMENT_ALL');
            }
            $status = $arr['status'];
            $data = $appointmentRepository->getAppointmentList($arr);
            return view('admin.appointment.index',compact('data', 'status', 'statusLists'));
    }

    public function show($appointmentNo, $companyCode, AppointmentRepository $appointmentRepository){

        // Get main appointment summary
        $appointmentSummary = $appointmentRepository->getAppointmentSummary($appointmentNo, $companyCode);
        //dd($appointmentSummary);
        // If not found, redirect safely
        if (!$appointmentSummary) {
            return redirect()->route('client.appointment.module.index')->with('error','Appointment summary not found');
        }

        // Load related data
        $appointmentedVehicles = $appointmentRepository->getAppoinmentedVehicle($appointmentNo);
        $appointmentDetails = $appointmentRepository->getAppoinmentDetail($appointmentNo);
        return view('admin.appointment.show', 
        compact('appointmentSummary','appointmentedVehicles','appointmentDetails','appointmentNo','companyCode'));
    }

    public function appointmentChangeStatus(Request $request, AppointmentRepository $appointmentRepository)
    {

        $appointmentNo = trim($request->get('appointmentNo'));
        $status        = trim($request->get('status'));

        // validation safety (recommended)
        if (!$appointmentNo || !$status) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request data'
            ]);
        }

        // update status via model/service
        $result = $appointmentRepository->appointmentChangeStatus($appointmentNo, $status);

        return response()->json($result);
    }

    public function setConfirmDateTime(Request $request, AppointmentRepository $appointmentRepository)
    {

        $appointmentNo = $request->input('appointmentNo');
        $confirmDate   = $request->input('confirmDate');

        $confirmTime = date(
            "H:i:s",
            strtotime(trim($request->input('confirmTime')))
        );

        $result = $appointmentRepository->setConfirmDateTime(
            $appointmentNo,
            $confirmDate,
            $confirmTime
        );

        return redirect()
            ->route('client.appointment.module.index')
            ->with('success', 'Confirmation date and time has been updated successfully.');
        return $result;

    }
}
