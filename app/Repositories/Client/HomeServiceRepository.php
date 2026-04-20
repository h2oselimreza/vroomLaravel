<?php

namespace App\Repositories\Client;

use Illuminate\Support\Facades\DB;

class HomeServiceRepository
{
    public function getHomeServiceList($arr)
    {
        return DB::table('home_service_app_summary as hs')
            ->select(
                'hs.*',
                'cc.title as company_name',
                'cc.company_type'
            )
            ->join('corporate_companies as cc', 'cc.company_code', '=', 'hs.company')

            // company filter
            ->when(!empty($arr['companyCode']), function ($query) use ($arr) {
                $query->where('hs.company', $arr['companyCode']);
            })

            // status filter
            ->when($arr['status'] != config('constants.APPOINTMENT_ALL'), function ($query) use ($arr) {
                $query->where('hs.status', $arr['status']);
            })

            ->orderBy('hs.created_dt_tm', 'DESC')
            ->get()
            ->toArray();
    }

}