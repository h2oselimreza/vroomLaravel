<?php

namespace App\Models\Admin\MasterData;

use App\Models\BaseModel;

class MembershipCard extends BaseModel
{
    // By default, Laravel looks for "membership_cards"
    protected $table = 'membership_card';

    protected $fillable = [
        'card_id',
        'card_number',
        'package_code',
        'validity_month',
        'company',
        'activation_dt_tm',
        'valid_dt_tm',
        'status',
        'is_active',
        'created_by',
        'created_dt_tm',
        'created_type',
        'updated_by',
        'updated_dt_tm',
        'updated_type',
    ];
}
