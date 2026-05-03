<?php

namespace App\Models\Admin\Place;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class PlaceFile extends BaseModel
{
    protected $table = 'place_file';

    protected $fillable = [
        'place',
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
