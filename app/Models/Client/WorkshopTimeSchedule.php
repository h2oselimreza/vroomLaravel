<?php

namespace App\Models\Client;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class WorkshopTimeSchedule extends BaseModel
{
    protected $table = 'workshop_time_schedule';

    public $timestamps = false;

    protected $fillable = [
        'workshop',
        'start_time',
        'end_time',
        'total_time',
        'week_day',
        'weekend_status',
        'is_active',
        'created_by',
        'created_dt_tm',
        'updated_by',
        'updated_dt_tm',
    ];
}
