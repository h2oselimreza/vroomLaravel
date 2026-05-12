<?php

namespace App\Models\Client;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class Stock extends BaseModel
{
    protected $table = 'stock';

    protected $fillable = [
        'company',
        'variant',
        'quantity',
        'status',
        'created_by',
        'created_dt_tm',
        'updated_by',
        'updated_dt_tm',
    ];
}
