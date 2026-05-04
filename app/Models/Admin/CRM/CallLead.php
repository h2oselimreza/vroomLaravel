<?php

namespace App\Models\Admin\CRM;

use Illuminate\Database\Eloquent\Model;

class CallLead extends Model
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
