<?php

namespace App\Models\Admin\Place;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class PlaceTimeSchedule extends BaseModel
{
    protected $table = 'place_time_schedule';

    protected $fillable = [
        'place',
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
