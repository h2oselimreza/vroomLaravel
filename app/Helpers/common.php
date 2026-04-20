<?php
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
function get_common_table_name_str($elementCodeStr, $type)
{
    return DB::table('common_table')
        ->where('type', $type)
        ->whereIn('element_code', explode(',', $elementCodeStr))
        ->pluck('element')
        ->implode(' , ');
}

if (!function_exists('get_block_name_str')) {

    function get_block_name_str($blockCodeStr)
    {
        if (!$blockCodeStr) {
            return "";
        }

        return DB::table('blocks')
            ->whereIn('block_code', explode(',', $blockCodeStr))
            ->pluck('block_name')
            ->implode(' , ');
    }

}

if (!function_exists('get_road_name_str')) {

    function get_road_name_str($roadCodeStr)
    {
        if (!$roadCodeStr) {
            return "";
        }

        return DB::table('roads')
            ->whereIn('road_code', explode(',', $roadCodeStr))
            ->pluck('road_name')
            ->implode(' , ');
    }

}

function reference_no(){
    return uniqid("ap", false);
}


if (!function_exists('get_module_group')) {
    function get_module_group($breadcrumbModuleUrl)
    {
        $row = DB::table('modules')
            ->where('module_url', $breadcrumbModuleUrl)
            ->first();

        return $row ? $row->module_group : "";
    }
}

if (!function_exists('get_modules')) {
    function get_modules($userGroup)
    {
        $row = DB::table('user_group')
            ->where('id', $userGroup)
            ->first();

        return $row ? $row->modules : "";
    }
}

if (!function_exists('get_distinct_rows')) {
    function get_distinct_rows($mList)
    {
        return DB::table('modules')
            ->distinct()
            ->select('modules.module_group', 'module_group.module_group_order')
            ->join('module_group', 'module_group.module_group_code', '=', 'modules.module_group')
            ->whereIn('modules.id', $mList)
            ->orderBy('module_group.module_group_order', 'ASC')
            ->get()
            ->toArray();
    }
}

if (!function_exists('get_module_group_name')) {
    function get_module_group_name($moduleGroupId)
    {
        $row = DB::table('module_group')
            ->where('module_group_code', $moduleGroupId)
            ->first();

        return $row ? $row->module_group_name : "";
    }
}

if (!function_exists('get_row_modules')) {
    function get_row_modules($moduleId, $moduleGroupId)
    {
        return DB::table('modules')
            ->where('id', $moduleId)
            ->where('module_group', $moduleGroupId)
            ->get()
            ->toArray();
    }
}


function getDateTimeFormat($datetime = null)
{
    if (!empty($datetime)) {
        return Carbon::parse($datetime)->format('F j, Y h:i A');
    }

    return '';
}

function get_date_format1($date = null)
{
    return $date ? Carbon::parse($date)->format('F j, Y') : '';
}

function get_time_format($time = null)
{
    return $time ? Carbon::parse($time)->format('h:i A') : null;
}

function get_date_time_format($datetime = null)
{
    return $datetime ? Carbon::parse($datetime)->format('F j, Y h:i A') : '';
}

function getVehicleAssignTypeName($assignType)
{
    return match ($assignType) {
        config('constants.ASSIGN_VACANT')  => 'Vacant',
        config('constants.ASSIGN_ENROUTE')  => 'En Route',
        config('constants.ASSIGN_PERSON')  => 'Assigned',
        default        => '',
    };
}

function get_appointment_status($status = 0, $flag = 'admin')
{
    $statuses = [
        config('constants.APPOINTMENT_PENDING')      => 'Pending',
        config('constants.APPOINTMENT_PROCCESSING')  => 'Processing',
        config('constants.APPOINTMENT_ACCEPT')       => 'Accepted',
        config('constants.APPOINTMENT_REJECT')       => 'Rejected',
        config('constants.APPOINTMENT_COMPLETE')     => 'Completed',
        config('constants.APPOINTMENT_START')        => 'Start',
        config('constants.APPOINTMENT_CASH_COLLECT') => 'Paid',
        config('constants.APPOINTMENT_ALL')          => 'All',
    ];

    return $statuses[$status] ?? '';
}


function get_create_update_by_name($userId, $userType = null)
{
    if (empty($userId)) {
        return '';
    }

    $userType = $userType ?? config('constants.USER_TYPE_CORP_EMP');

    $corpTypes = [
        config('constants.USER_TYPE_CORP_EMP'),
        config('constants.USER_TYPE_INDV_EMP'),
        config('constants.CLIENT'),
    ];

    $adminTypes = [
        config('constants.USER_TYPE_ADMIN_EMP'),
        config('constants.P_ADMIN'),
    ];

    if (in_array($userType, $corpTypes)) {
        return DB::table('customer_employee')
            ->where('employee_id', $userId)
            ->value('employee_name') ?? '';
    }

    if (in_array($userType, $adminTypes)) {
        return DB::table('employee')
            ->where('employee_id', $userId)
            ->value('employee_name') ?? '';
    }

    return '';
}