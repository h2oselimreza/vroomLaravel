<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;

class AppointmentDetail extends Model
{
    protected $table = 'appointment_detail';

    protected $fillable = [
        'appointment_no',
        'service_variant',
        'vehicle',
        'created_by',
        'created_type',
        'created_dt_tm',
        'updated_by',
        'updated_type',
        'updated_dt_tm',
    ];
}
