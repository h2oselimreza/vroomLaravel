<?php

namespace App\Models\Client;

use App\Models\BaseModel;

class AccidentLog extends BaseModel
{
    protected $table = 'accidental_log';

    protected $fillable = [
        'vehicle',
        'driver',
        'accident_date_time',
        'place',
        'vehicle_affected_area',
        'company',
        'remarks',
        'file_name',
        'file_original_name',
        'created_by',
        'created_dt_tm',
        'updated_by',
        'updated_dt_tm',
    ];
}
