<?php

namespace App\Models\Client;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class StockSummary extends BaseModel
{
    protected $table = 'stock_summary';

    protected $fillable = [
        'company',
        'stock_summary_id',
        'title',
        'stock_date',
        'reference_no',
        'stock_type',
        'is_active',
        'created_by',
        'created_dt_tm',
        'updated_by',
        'updated_dt_tm',
    ];
}
