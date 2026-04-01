<?php

namespace App\Models\Admin\MasterData;

use App\Models\BaseModel;

class CallReason extends BaseModel
{
    protected $table = 'call_reason'; 

    protected $fillable = [
        'reason_code',
        'call_type',
        'title',
        'description',
        'reason_order',
        'is_active',
        'created_by',
        'updated_by',
        'created_dt_tm',
        'updated_dt_tm',
    ];
}
