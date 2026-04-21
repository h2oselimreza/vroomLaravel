<?php

namespace App\Models\Client;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class WorkshopService extends BaseModel
{
    protected $table = 'workshop_service';

    protected $fillable = [
        'workshop',
        'service_variant',
        'is_active',
        'created_by',
        'updated_by',
        'created_dt_tm',
        'updated_dt_tm',
    ];
}
