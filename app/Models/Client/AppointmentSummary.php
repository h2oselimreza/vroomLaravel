<?php

namespace App\Models\Client;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class AppointmentSummary extends BaseModel
{
    protected $table = 'appointment_summary';

    protected $fillable = [
        'appointment_no',
        'company',
        'workshop',
        'date_1',
        'date_2',
        'time_slot_1',
        'time_slot_2',
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
