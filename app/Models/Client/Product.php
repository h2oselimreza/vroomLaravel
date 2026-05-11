<?php

namespace App\Models\Client;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class Product extends BaseModel
{
    protected $table = 'products';

    protected $fillable = [
        'company',
        'product_name',
        'product_code',
        'category',
        'product_details',
        'product_type',
        'is_active',
        'created_by',
        'created_dt_tm',
        'updated_by',
        'updated_dt_tm',
    ];
}
