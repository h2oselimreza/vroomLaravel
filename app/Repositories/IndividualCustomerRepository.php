<?php

namespace App\Repositories;

use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IndividualCustomerRepository
{
    public function changeCompanyStatus($companyCode, $statusFlag)
    {
        return DB::transaction(function () use ($companyCode, $statusFlag) {

            $authUserId = Auth::user()->user_id ?? null;

            // ================= ACTIVE =================
            if ($statusFlag == 1) {

                DB::table('corporate_companies')
                    ->where('company_code', $companyCode)
                    ->update([
                        'is_active'      => 1,
                        'updated_by'     => $authUserId,
                        'updated_dt_tm'  => Carbon::now(),
                    ]);

                return 1;
            }

            // ================= INACTIVE =================
            DB::table('corporate_companies')
                ->where('company_code', $companyCode)
                ->update([
                    'is_active'      => 0,
                    'updated_by'     => $authUserId,
                    'updated_dt_tm'  => Carbon::now(),
                ]);

            // Get system users
            $systemUserArr = DB::table('customer_employee')
                ->where('system_user', 1)
                ->where('company', $companyCode)
                ->pluck('employee_id')
                ->toArray();

            if (!empty($systemUserArr)) {

                DB::table('customer_employee')
                    ->where('company', $companyCode)
                    ->update([
                        'updated_by'     => $authUserId,
                        'updated_dt_tm'  => Carbon::now(),
                        'system_user'    => 0,
                    ]);

                DB::table('users')
                    ->whereIn('user_id', $systemUserArr)
                    ->update([
                        'is_active'      => 0,
                        'updated_by'     => $authUserId,
                        'updated_dt_tm'  => Carbon::now(),
                    ]);
            }

            return 1;
        });
    }
}