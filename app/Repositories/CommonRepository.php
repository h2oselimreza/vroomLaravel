<?php

namespace App\Repositories;

use App\Models\Company;
use App\Models\CustomerEmployee;
use Illuminate\Support\Facades\DB;

class CommonRepository
{

    public function getCommonTableElement($commonTableElementArr)
    {
        return DB::table('common_table')
            ->when(isset($commonTableElementArr['type']), function ($q) use ($commonTableElementArr) {
                $q->where('type', $commonTableElementArr['type']);
            })
            ->when(isset($commonTableElementArr['depend_on_element']), function ($q) use ($commonTableElementArr) {
                $q->where('depend_on_element', $commonTableElementArr['depend_on_element']);
            })
            ->get();
    }

    public function getCompanyList($isActiveFlag = 1, $companyType = null)
    {
        $query = DB::table('corporate_companies')
            ->select(
                'corporate_companies.*',
                'employee.employee_name as rm_name',
                'package.package_name'
            )
            ->leftJoin('employee', 'employee.employee_id', '=', 'corporate_companies.rm_id')
            ->leftJoin('package', 'package.package_code', '=', 'corporate_companies.package');

        // ACTIVE FILTER (same logic)
        if ($isActiveFlag == 1) {
            $query->where('corporate_companies.is_active', 1);
        } elseif ($isActiveFlag == 2) {
            $query->where('corporate_companies.is_active', 0);
        }

        // COMPANY TYPE FILTER (same logic)
        if ($companyType) {
            $query->where('corporate_companies.company_type', $companyType);
        }

        return $query->get();
    }
      
    public function getIndividualAccList($isActiveFlag = 1, $companyType = null)
    {
        $query = DB::table('corporate_companies')
            ->select(
                'corporate_companies.*',
                'employee.employee_name as rm_name',
                'package.package_name',
                'user_group.group_name as user_group_name'
            )
            ->leftJoin('employee', 'employee.employee_id', '=', 'corporate_companies.rm_id')
            ->leftJoin('package', 'package.package_code', '=', 'corporate_companies.package')
            ->join('customer_employee', 'customer_employee.company', '=', 'corporate_companies.company_code')
            ->join('users', 'users.user_id', '=', 'customer_employee.employee_id')
            ->join('user_group', 'user_group.id', '=', 'users.user_group');

        if ($isActiveFlag == 1) {
            $query->where('corporate_companies.is_active', 1);
        } elseif ($isActiveFlag == 2) {
            $query->where('corporate_companies.is_active', 0);
        }

        $query->where('corporate_companies.company_type', $companyType);

        return $query->get();
    }

}