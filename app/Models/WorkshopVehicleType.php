<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkshopVehicleType extends BaseModel
{
    protected $table = 'workshop_vehicle_type';

    public $timestamps = false;

    protected $fillable = [
        'workshop',
        'vehicle_type',
        'is_active',
        'created_by',
        'created_dt_tm',
        'updated_by',
        'updated_dt_tm',
    ];
}
