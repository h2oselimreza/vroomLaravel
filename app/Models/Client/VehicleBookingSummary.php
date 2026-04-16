<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;

class VehicleBookingSummary extends Model
{
     protected $table = 'vehicle_booking_summary';

    protected $fillable = [
        'company',
        'booking_no',
        'person_name',
        'person_emp_id',
        'department',
        'designation',
        'display_emp_code',
        'from_dt_tm',
        'to_dt_tm',
        'from_dt_tm_confirmed',
        'to_dt_tm_confirmed',
        'route',
        'vehicle',
        'participant',
        'remarks',
        'comment',
        'status',
        'first_processing_by',
        'forward_to',
        'trip_status',
        'created_by',
        'created_type',
        'created_dt_tm',
        'updated_by',
        'updated_type',
        'updated_dt_tm',
    ];
}
