<?php

namespace App\Models\Admin\MasterData;

use App\Models\BaseModel;

class Service extends BaseModel
{
    protected $table = 'services';

    protected $fillable = [
        'service_category',
        'service_name',
        'service_code',
        'service_type',
        'is_active',
        'created_by',
        'created_dt_tm',
        'updated_by',
        'updated_dt_tm',
    ];
}
