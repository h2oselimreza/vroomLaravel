<?php

namespace App\Models\Admin\Workshop;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class WorkshopFile extends BaseModel
{
    protected $table = 'workshop_file';

    protected $fillable = [
        'workshop',
        'original_name',
        'file_name',
        'file_type',
        'is_active',
        'created_by',
        'created_dt_tm',
        'updated_by',
        'updated_dt_tm',
    ];
}
