<?php

namespace App\Models\Admin\MasterData;

use Illuminate\Database\Eloquent\Model;
use App\Models\BaseModel;

class CostCategory extends BaseModel
{
    // Explicitly defining the table name
    protected $table = 'cost_categories';

    protected $fillable = [
        'company',
        'parent_category',
        'parent_category_str',
        'category_name',
        'category_code',
        'is_active',
        'created_by',
        'created_dt_tm',
        'updated_by',
        'updated_dt_tm',
    ];
}
