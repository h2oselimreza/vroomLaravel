<?php

namespace App\Models\Client;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class StockDetail extends BaseModel
{
    protected $table = 'stock_details';

    protected $fillable = [
        'stock_detail_id',
        'stock_summary_id',
        'company',
        'vehicle',
        'variant',
        'remarks',
        'credit_quantity',
        'debit_quantity',
        'trasaction_type',
        'status',
        'created_by',
        'created_dt_tm',
        'updated_by',
        'updated_dt_tm',
    ];
}
