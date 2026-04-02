<?php

namespace App\Models\Admin\MasterData;

use App\Models\BaseModel;

class ServiceVariant extends BaseModel
{
    protected $table = 'service_variants';


    protected $fillable = [
        'service',
        'service_variant_name',
        'variant_code',
        'variant_type',
        'default_variant',
        'unit_name',
        'unit_price',
        'is_active',
        'created_by',
        'updated_by',
        'created_dt_tm',
        'updated_dt_tm',
    ];
}
