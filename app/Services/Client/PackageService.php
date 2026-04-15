<?php

namespace App\Services\Client;

use Illuminate\Support\Facades\DB;

class PackageService
{
    const PACKAGE_VEHICLE_COUNT = 'vehicle';
    const PACKAGE_USER_COUNT    = 'user';

    public function check($type = null, $company = null): array
    {
        // =========================
        // Get Package Info
        // =========================
        $data = DB::table('corporate_companies')
            ->join('package', 'package.package_code', '=', 'corporate_companies.package')
            ->select('package.package_details', 'package.package_name')
            ->where('corporate_companies.company_code', $company)
            ->first();

        if (!$data) {
            return ['success' => 0];
        }

        $packageDetails = json_decode($data->package_details);

        // =========================
        // VEHICLE COUNT CHECK
        // =========================
        if ($type === self::PACKAGE_VEHICLE_COUNT) {

            $packageVehicleCount = $packageDetails->vehicle->count ?? 0;

            $companyVehicle = DB::table('vehicles')
                ->where('company', $company)
                ->where('is_active', 1)
                ->count();

            return [
                'success' => $packageVehicleCount > $companyVehicle ? 1 : 0
            ];
        }

        // =========================
        // USER COUNT CHECK
        // =========================
        if ($type === self::PACKAGE_USER_COUNT) {

            $packageUserCount = $packageDetails->user->count ?? 0;

            $companyUser = DB::table('customer_employee')
                ->where('company', $company)
                ->where('system_user', 1)
                ->count();

            return [
                'success' => $packageUserCount > $companyUser ? 1 : 0
            ];
        }

        return ['success' => 0];
    }
}