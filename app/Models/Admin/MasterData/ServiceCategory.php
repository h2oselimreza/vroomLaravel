<?php

namespace App\Models\Admin\MasterData;

use App\Models\BaseModel;

class ServiceCategory extends BaseModel
{
    protected $table = 'service_categories';


    protected $fillable = [
        'parent_category',
        'parent_category_str',
        'category_name',
        'category_code',
        'category_type',
        'is_active',
        'created_by',
        'updated_by',
        'created_dt_tm',
        'updated_dt_tm',
    ];
}
