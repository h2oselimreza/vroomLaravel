<?php

namespace App\Models\Web;

use App\Traits\TracksUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WebNews extends Model
{
    use HasFactory, TracksUser;

    protected $primaryKey = 'id';

    const CREATED_AT = 'created_dt_tm';
    const UPDATED_AT = 'updated_dt_tm';

    protected $fillable = [
        'heading',
        'body',
        'is_active',
        'publish_date',
        'created_by',
        'updated_by',
        'created_dt_tm',
        'updated_dt_tm'
    ];
}
