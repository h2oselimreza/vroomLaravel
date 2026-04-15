<?php

namespace App\Services\Client;

use Illuminate\Support\Facades\DB;

class PackageService
{
    const PACKAGE_VEHICLE_COUNT = 'vehicle';
    const PACKAGE_USER_COUNT    = 'user';

    public function getPackageInfo($type = null, $company = null)
    {
        $data = DB::table('corporate_companies')
            ->join('package', 'package.package_code', '=', 'corporate_companies.package')
            ->where('corporate_companies.company_code', $company)
            ->select('package.package_details', 'package.package_name')
            ->first();

        if (!$data) {
            return ['success' => 0];
        }

        $packageDetails = json_decode($data->package_details);

        if ($type == self::PACKAGE_VEHICLE_COUNT) {
            return [
                'success' => 1,
                'vehicleCount' => $packageDetails->vehicle->count ?? 0
            ];
        }

        if ($type == self::PACKAGE_USER_COUNT) {
            return [
                'success' => 1,
                'userCount' => $packageDetails->user->count ?? 0
            ];
        }

        return ['success' => 0];
    }
}