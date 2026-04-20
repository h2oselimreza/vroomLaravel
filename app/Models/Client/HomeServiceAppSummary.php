<?php

namespace App\Models\Client;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class HomeServiceAppSummary extends BaseModel
{
    protected $table = 'home_service_app_summary';

    protected $fillable = [
        'appointment_no',
        'company',
        'name',
        'mobile',
        'address',
        'service_date',
        'service_time',
        'final_date',
        'appointment_time',
        'remarks',
        'reject_reason',
        'status',
        'created_by',
        'created_type',
        'created_dt_tm',
        'updated_by',
        'updated_type',
        'updated_dt_tm',
    ];
}
