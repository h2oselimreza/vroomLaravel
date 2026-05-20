<?php

namespace App\Models\Client;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class QuotationReqSummary extends BaseModel
{
    protected $table = 'quotation_req_summary';

    protected $fillable = [
        'customer',
        'customer_type',
        'request_no',
        'approved_quotation_no',
        'remarks',
        'admin_remarks',
        'status',
        'quotation_submitted_date',
        'req_sending_date',
        'reject_reason',
        'rm_id',
        'reference_no',
        'is_active',
        'created_by',
        'created_dt_tm',
        'created_by_type',
        'updated_by',
        'updated_dt_tm',
        'updated_by_type',
    ];
}
