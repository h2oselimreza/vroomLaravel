<?php

namespace App\Models\Admin\Workshop;

use Illuminate\Database\Eloquent\Model;
use App\Models\BaseModel;

class WorkshopVehicleType extends BaseModel
{
    protected $table = 'workshop_vehicle_type';

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
