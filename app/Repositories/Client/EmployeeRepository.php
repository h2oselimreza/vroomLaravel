<?php

namespace App\Repositories\Client;

use App\Models\Company;
use App\Models\CustomerEmployee;
use Illuminate\Support\Facades\DB;

class EmployeeRepository
{

public function getClientEmployeeProfile($employeeId = null, array $employeeIdArr = [], $flag = null, $companyCode)
{
    return DB::table('customer_employee')
        // Joins with Aliases
        ->leftJoin('common_table as occupation_father_tb', 'occupation_father_tb.element_code', '=', 'customer_employee.father_occupation')
        ->leftJoin('common_table as occupation_mother_tb', 'occupation_mother_tb.element_code', '=', 'customer_employee.mother_occupation')
        ->leftJoin('common_table as occupation_spouse_tb', 'occupation_spouse_tb.element_code', '=', 'customer_employee.spouse_occupation')
        ->leftJoin('common_table as emer_con_rel_tb', 'emer_con_rel_tb.element_code', '=', 'customer_employee.emer_contact_relation')
        ->leftJoin('common_table as guardian_rel_tb', 'guardian_rel_tb.element_code', '=', 'customer_employee.guardian_relation')
        ->leftJoin('common_table as emp_type_tb', 'emp_type_tb.element_code', '=', 'customer_employee.emp_type')
        ->leftJoin('users', 'users.user_id', '=', 'customer_employee.employee_id')
        ->leftJoin('user_group', 'user_group.id', '=', 'users.user_group')

        // Selections
        ->select([
            'customer_employee.*',
            'occupation_father_tb.element as father_occupation_name',
            'occupation_mother_tb.element as mother_occupation_name',
            'occupation_spouse_tb.element as spouse_occupation_name',
            'emer_con_rel_tb.element as emer_contact_relation_name',
            'emp_type_tb.element as emp_type_name',
            'guardian_rel_tb.element_code as guardian_relation_name',
            'user_group.group_name',
            'users.is_reset'
        ])

        // Mandatory Filtering
        ->where('customer_employee.company', $companyCode)

        // Conditional Filtering (The Laravel Expert Way)
        ->when($flag === null, function ($query) {
            return $query->where('customer_employee.is_active', 1);
        })
        ->when($employeeId, function ($query) use ($employeeId) {
            return $query->where('customer_employee.employee_id', $employeeId);
        }, function ($query) use ($employeeIdArr) {
            // This runs if $employeeId is null/false (the 'else' case)
            return $query->when(!empty($employeeIdArr), function ($q) use ($employeeIdArr) {
                return $q->whereIn('customer_employee.id', $employeeIdArr);
            });
        })
        ->get()
        ->toArray();
    }

}