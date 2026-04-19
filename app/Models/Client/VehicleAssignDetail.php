<?php

namespace App\Models\Client;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class VehicleAssignDetail extends Model
{
    protected $table = 'vehicle_assign_details';

    public $timestamps = false;

    protected $fillable = [
        'reference_no',
        'booking_no',
        'route_lat_long',
        'map_image',
        'company',
        'vehicle',
        'driver',
        'assign_dt_tm',
        'assign_type',
        'emp_name',
        'emp_designation',
        'emp_department',
        'emp_id_no',
        'route',
        'current_location',
        'remarks',
        'created_by',
        'created_dt_tm',
    ];
}
