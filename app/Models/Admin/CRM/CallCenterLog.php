<?php

namespace App\Models\Admin\CRM;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class CallCenterLog extends BaseModel
{
    protected $table = 'call_center_log';

    protected $fillable = [
        'log_id',
        'ref_log_id',
        'call_type',
        'company',
        'customer_name',
        'customer_mobile_no',
        'customer_address',
        'call_reason',
        'call_reason_text',
        'customer_feedback',
        'customer_feedback_text',
        'call_start_dt_tm',
        'call_end_dt_tm',
        'next_call_dt_tm',
        'next_call_status',
        'next_call_flag_dt_tm',
        'remarks',
        'created_by',
        'created_dt_tm',
        'updated_by',
        'updated_dt_tm',
    ];
}
