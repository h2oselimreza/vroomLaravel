<?php

namespace App\Models\Client;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class QuotationResDetail extends BaseModel
{
    protected $table = 'quotation_res_detail';

    protected $fillable = [
        'quotation_no',
        'request_no',
        'req_detail_no',
        'unit_price',
        'is_active',
        'created_by',
        'created_dt_tm',
        'updated_by',
        'updated_dt_tm',
    ];
}
