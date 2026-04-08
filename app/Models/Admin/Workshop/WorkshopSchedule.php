<?php

namespace App\Models\Admin\Workshop;

use Illuminate\Database\Eloquent\Model;
use App\Models\BaseModel;

class WorkshopSchedule extends BaseModel
{
    protected $table = 'workshop_time_schedule';

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
