<?php

namespace App\Models\Admin\MasterData;

use Illuminate\Database\Eloquent\Model;
use App\Models\BaseModel;

class Fuel extends BaseModel
{
    protected $table = 'fuel';
    protected $fillable = [
        'fuel_code',
        'fuel_name',
        'fuel_rate',
        'created_by',
        'updated_by',
        'created_dt_tm',
        'updated_dt_tm',
    ];
    protected $casts = [
        'fuel_rate' => 'decimal:2',
        'created_dt_tm' => 'datetime',
        'updated_dt_tm' => 'datetime',
    ];
}
