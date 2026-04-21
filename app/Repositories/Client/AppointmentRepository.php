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

    public function getDistinctService(array $variantArr)
    {
        return DB::table('service_variants')
        ->select('service_variants.service', 'services.service_name')
        ->join('services', 'services.service_code', '=', 'service_variants.service')
        ->where('service_variants.variant_type', $variantArr['variantType'])
        ->where('service_variants.is_active', 1)
        ->where('services.is_active', 1)
        ->distinct()
        ->get()
        ->toArray();
    }


    public function getWorkshopService($arr, $isActiveFlag = 1)
    {
        $query = DB::table('service_variants')
            ->select(
                'service_variants.*',
                'services.service_name',
                'service_categories.category_name',
                'service_categories.parent_category_str'
            )
            ->join('services', 'services.service_code', '=', 'service_variants.service')
            ->join('service_categories', 'service_categories.category_code', '=', 'services.service_category')
            ->where('service_variants.variant_type', $arr['variantType'])
            ->where('services.is_active', 1)
            ->where('service_categories.is_active', 1);

        if ($isActiveFlag == 1) {
            $query->where('service_variants.is_active', 1);
        } elseif ($isActiveFlag == 2) {
            $query->where('service_variants.is_active', 0);
        }

        return $query
            ->orderBy('service_variants.service', 'ASC')
            ->get()
            ->toArray();
    }

    public function getWorkshopList($searchArr, $serviceVarArr)
    {
        $workshopArr = [];

        /*
        |---------------------------------------
        | STEP 1: Filter workshops by service variants
        |---------------------------------------
        */

        if (!empty($serviceVarArr)) {

            $results = DB::table('workshop_service')
                ->selectRaw('COUNT(id) as id_count, workshop')
                ->whereIn('service_variant', $serviceVarArr)
                ->groupBy('workshop')
                ->havingRaw('COUNT(id) = ?', [count($serviceVarArr)])
                ->get();

            foreach ($results as $result) {
                $workshopArr[] = $result->workshop;
            }
        }

        /*
        |---------------------------------------
        | STEP 2: If filter exists but no match
        |---------------------------------------
        */

        if (!empty($serviceVarArr) && empty($workshopArr)) {
            return collect(); // empty result
        }

        /*
        |---------------------------------------
        | STEP 3: Main workshop query
        |---------------------------------------
        */

        $query = DB::table('workshops')
            ->select(
                'workshops.*',
                'divisions.division_en_name',
                'divisions.division_bn_name',
                'districts.district_en_name',
                'districts.district_bn_name',
                'upazilas.upozilla_en_name',
                'upazilas.upozilla_bn_name'
            )
            ->leftJoin('divisions', 'divisions.id', '=', 'workshops.division')
            ->leftJoin('districts', 'districts.id', '=', 'workshops.district')
            ->leftJoin('upazilas', 'upazilas.id', '=', 'workshops.upozilla');

        /*
        |---------------------------------------
        | STEP 4: Filters (area-wise)
        |---------------------------------------
        */

        if (!empty($searchArr['division'])) {
            $query->where('workshops.division', $searchArr['division']);
        }

        if (!empty($searchArr['district'])) {
            $query->where('workshops.district', $searchArr['district']);
        }

        if (!empty($searchArr['upozilla'])) {
            $query->where('workshops.upozilla', $searchArr['upozilla']);
        }

        /*
        |---------------------------------------
        | STEP 5: Workshop filter from service match
        |---------------------------------------
        */

        if (!empty($workshopArr)) {
            $query->whereIn('workshops.workshop_code', $workshopArr);
        }

        /*
        |---------------------------------------
        | STEP 6: Active only
        |---------------------------------------
        */

        $query->where('workshops.is_active', 1);

        return $query->get();
    }


    

}