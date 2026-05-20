<?php

namespace App\Models\Client;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class QuotationReqDetail extends BaseModel
{
    protected $table = 'quotation_req_detail';

    protected $fillable = [

        'request_no',
        'request_details_no',
        'vehicle',
        'service_veriant',
        'product_variant',
        'product_display_name',
        'request_type',
        'quantity',
        'unit_name',
        'created_by',
        'created_dt_tm',
        'updated_by',
        'updated_dt_tm',
    ];
}
