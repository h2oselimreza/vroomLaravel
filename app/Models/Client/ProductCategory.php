<?php

namespace App\Models\Client;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends BaseModel
{
    protected $table = 'product_categories';

    protected $fillable = [
        'company',
        'parent_category',
        'parent_category_str',
        'category_name',
        'category_code',
        'category_type',
        'is_active',
        'created_by',
        'created_dt_tm',
        'updated_by',
        'updated_dt_tm',
    ];
}
