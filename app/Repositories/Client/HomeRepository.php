<?php

namespace App\Repositories\Client;

use App\Models\Admin\MasterData\ServiceVariant;
use App\Models\Client\HomeServiceAppDetail;
use App\Models\Client\HomeServiceAppSummaryGen;
use App\Models\CorporateCompany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeRepository
{

    public function getSingleVehicleInfo(
        $vehicleId,
        $companyCode
    ) {

        $query = DB::table('vehicles')

            ->select(
                'vehicles.*',

                DB::raw('vehicle_type_tb.element as vehicle_type_name'),

                DB::raw('brand_tb.element as brand_name'),

                DB::raw('driver_tb.employee_name as driver_name'),

                DB::raw('driver_tb.primary_mobile as driver_mobile'),

                DB::raw('driver_tb.employee_id as driver_id'),

                DB::raw('brand_model_tb.element as brand_model_name'),

                DB::raw('vehicle_class_tb.element as vehicle_class_name'),

                DB::raw('vehicle_color_tb.element as color_name'),

                'driver_tb.driving_license_no',

                'driver_tb.driving_license_expiry_date'
            )

            ->leftJoin(
                'common_table as vehicle_type_tb',
                'vehicle_type_tb.element_code',
                '=',
                'vehicles.vehicle_type'
            )

            ->leftJoin(
                'common_table as brand_tb',
                'brand_tb.element_code',
                '=',
                'vehicles.brand'
            )

            ->leftJoin(
                'common_table as brand_model_tb',
                'brand_model_tb.element_code',
                '=',
                'vehicles.brand_model'
            )

            ->leftJoin(
                'common_table as vehicle_class_tb',
                'vehicle_class_tb.element_code',
                '=',
                'vehicles.vehicle_class'
            )

            ->leftJoin(
                'common_table as vehicle_color_tb',
                'vehicle_color_tb.element_code',
                '=',
                'vehicles.color'
            )

            ->leftJoin(
                'customer_employee as driver_tb',
                'driver_tb.employee_id',
                '=',
                'vehicles.driver_id'
            )

            ->where(
                'vehicles.vehicle_id',
                $vehicleId
            )

            ->where(
                'vehicles.company',
                $companyCode
            );

        /*
        | Existing Logic Preserved
        */
        if ($query->exists()) {
            return $query->first();
        }

        return $query->first();
    }

    public function getVehicleCostCategory(
        $companyCode,
        $year,
        $vehicleId
    ): array {

        return DB::table('expense_detail')

            ->select(
                DB::raw('SUM(expense_detail.amount) as total_expense'),

                'cost_heads.cost_category',

                'cost_categories.category_name'
            )
            ->join(
                'expense_summary',
                'expense_summary.expense_no',
                '=',
                'expense_detail.expense_no'
            )
            ->join(
                'cost_heads',
                'cost_heads.cost_head_code',
                '=',
                'expense_detail.expense_head'
            )
            ->join(
                'cost_categories',
                'cost_categories.category_code',
                '=',
                'cost_heads.cost_category'
            )
            ->where(
                'expense_detail.vehicle',
                $vehicleId
            )
            ->whereYear(
                'expense_summary.expense_date',
                $year
            )
            ->where(
                'expense_summary.company',
                $companyCode
            )
            ->groupBy(
                'cost_categories.category_code',
                'cost_heads.cost_category',
                'cost_categories.category_name'
            )
            ->orderBy(
                'cost_categories.category_name',
                'ASC'
            )

            ->get()
            ->toArray();
    }

    public function getVehicleCostMonth(
        $companyCode,
        $year,
        $vehicleId
    ): array {

        return DB::table('expense_detail')

            ->select(
                DB::raw('SUM(expense_detail.amount) as total_expense'),

                DB::raw('MONTH(expense_summary.expense_date) as month')
            )

            ->join(
                'expense_summary',
                'expense_summary.expense_no',
                '=',
                'expense_detail.expense_no'
            )
            ->where(
                'expense_detail.vehicle',
                $vehicleId
            )
            ->whereYear(
                'expense_summary.expense_date',
                $year
            )
            ->where(
                'expense_summary.company',
                $companyCode
            )
            ->groupBy(
                DB::raw('MONTH(expense_summary.expense_date)')
            )
            ->orderBy(
                DB::raw('MONTH(expense_summary.expense_date)'),
                'ASC'
            )
            ->get()
            ->toArray();
    }

    public function getCostYear($companyCode): array
    {

        return DB::table('expense_summary')
            ->select(
                DB::raw('YEAR(expense_summary.expense_date) as year')
            )
            ->where(
                'expense_summary.company',
                $companyCode
            )
            ->distinct()
            ->orderBy(
                DB::raw('YEAR(expense_summary.expense_date)'),
                'DESC'
            )
            ->get()
            ->toArray();
    }

}