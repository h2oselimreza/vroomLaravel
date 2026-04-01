<?php

namespace App\Models\Admin\MasterData;

use App\Models\BaseModel;

class CustomerFeedback extends BaseModel
{
    protected $table = 'customer_feedback';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'feedback_code',
        'call_type',
        'title',
        'description',
        'feedback_order',
        'is_active',
        'created_by',
        'updated_by',
        'created_dt_tm',
        'updated_dt_tm',
    ];
}
