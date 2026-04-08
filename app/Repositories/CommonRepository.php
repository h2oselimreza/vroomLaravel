<?php

namespace App\Repositories;

use App\Models\Company;
use App\Models\CustomerEmployee;
use DB;

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
      

}