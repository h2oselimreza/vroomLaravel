<?php

namespace App\Models\Client;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class HomeServiceAppDetail extends BaseModel
{
    protected $table = 'home_service_app_detail_gen';

    public $timestamps = false;

    protected $fillable = [
        'appointment_no',
        'service_variant',
        'unit_price',
        'quantity',
        'total_amount',
        'created_by',
        'created_type',
        'created_dt_tm',
        'updated_by',
        'updated_type',
        'updated_dt_tm',
    ];
}
