<?php

namespace App\Repositories\Client;

use App\Models\Client\AppointmentSummary;
use Illuminate\Support\Facades\DB;

class AppointmentRepository
{

   
    public function getAppointmentList($arr)
    {
        $query = AppointmentSummary::query()
            ->select(
                'appointment_summary.*',
                'workshops.title as workshop_name',
                'corporate_companies.title as company_name',
                'corporate_companies.company_type'
            )
            ->join('workshops', 'workshops.workshop_code', '=', 'appointment_summary.workshop')
            ->join('corporate_companies', 'corporate_companies.company_code', '=', 'appointment_summary.company');

        // ✅ company filter
        if (!empty($arr['companyCode'])) {
            $query->where('appointment_summary.company', $arr['companyCode']);
        }

        // ✅ status filter
        if (!empty($arr['status']) && $arr['status'] != config('constants.APPOINTMENT_ALL')) {
            $query->where('appointment_summary.status', $arr['status']);
        }

        return $query
            ->orderBy('appointment_summary.created_dt_tm', 'DESC')
            ->get();
    }

}