<?php

namespace App\Models\Client;

use App\Models\BaseModel;

class VehicleFile extends BaseModel
{
    protected $table = 'vehicle_files';

    protected $fillable = [
        'vehicle',
        'original_name',
        'file_name',
        'file_type',
        'is_active',
        'created_by',
        'created_dt_tm',
        'updated_by',
        'updated_dt_tm',
    ];
}
