<?php

namespace App\Models\Admin\CRM;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class CallLead extends BaseModel
{
    protected $table = 'call_leads';

    protected $fillable = [
        'lead_code',
        'name',
        'mobile',
        'address',
        'call_status',
        'last_call_dt_tm',
        'created_by',
        'created_dt_tm',
        'updated_by',
        'updated_dt_tm',
    ];
}
