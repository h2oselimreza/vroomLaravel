<?php
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
