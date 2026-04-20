<?php

namespace App\Repositories\Client;

use App\Models\Admin\MasterData\ServiceVariant;
use App\Models\Client\HomeServiceAppDetail;
use App\Models\Client\HomeServiceAppSummaryGen;
use App\Models\CorporateCompany;
use Illuminate\Support\Facades\DB;

class HomeServiceRepository
{
    public function getHomeServiceList($arr)
    {
        return DB::table('home_service_app_summary_gen as hs')
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

    public function getDistinctService($variantArr)
    {
        return DB::table('service_variants as sv')
            ->select('sv.service', 's.service_name')
            ->join('services as s', 's.service_code', '=', 'sv.service')
            ->where('sv.variant_type', $variantArr['variantType'] ?? null)
            ->where('sv.is_active', 1)
            ->where('s.is_active', 1)
            ->distinct()
            ->get();
    }

    public function getHomeService($arr, $isActiveFlag = 1)
    {
        return ServiceVariant::query()
        ->select(
            'service_variants.variant_code',
            'service_variants.service_variant_name',
            'service_variants.variant_type',
            'service_variants.default_variant',
            'service_variants.service',
            'service_variants.unit_price',
            'service_variants.unit_name',
            'services.service_name'
        )
        ->join('services', 'services.service_code', '=', 'service_variants.service')
        ->where('service_variants.variant_type', $arr['variantType'] ?? null)
        ->where(['service_variants.is_active' => 1, 'services.is_active' => 1])
        ->orderBy('service_variants.service_variant_name', 'ASC')
        ->get();
    }

    public function getSingleCompanyInfo($companyCode)
    {
        if (!$companyCode) {
            return null;
        }
        return CorporateCompany::where('company_code', $companyCode)->first();
    }

    public function deleteHomeService($appointmentNo, $company)
    {
        if (!$appointmentNo) {
            return 2;
        }

        $summary = HomeServiceAppSummaryGen::where('appointment_no', $appointmentNo)
            ->where('company', $company)
            ->first();

        if (!$summary) {
            return 2;
        }

        if ($summary->status != config('constants.APPOINTMENT_PENDING')) {
            return 2;
        }
        DB::beginTransaction();
        try {

            // ✅ Delete summary
            HomeServiceAppSummaryGen::where('appointment_no', $appointmentNo)
                ->where('company', $company)
                ->delete();

            // ✅ Delete details
            HomeServiceAppDetail::where('appointment_no', $appointmentNo)
                ->delete();

            DB::commit();

            return 1;

        } catch (\Exception $e) {
            DB::rollBack();
            return 2;
        }
    }

}