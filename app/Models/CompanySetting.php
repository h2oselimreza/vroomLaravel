<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanySetting extends BaseModel
{
     protected $table = 'company_settings';

    protected $primaryKey = 'id';

    protected $fillable = [
        'company',
        'setting_type',
        'title',
        'code',
        'description',
        'is_active',
        'created_by',
        'created_dt_tm',
        'updated_by',
        'updated_dt_tm',
    ];
}
