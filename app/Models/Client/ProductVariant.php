<?php

namespace App\Models\Client;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends BaseModel
{
    protected $table = 'product_variants';

    protected $fillable = [
        'company',
        'product',
        'variant_name',
        'variant_code',
        'unit_name',
        'model',
        'display_code',
        'details',
        'variant_type',
        'default_variant',
        'is_active',
        'created_by',
        'created_dt_tm',
        'updated_by',
        'updated_dt_tm',
    ];
}
