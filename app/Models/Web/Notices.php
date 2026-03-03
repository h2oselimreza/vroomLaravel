<?php

namespace App\Models\Web;

use App\Traits\TracksUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notices extends Model
{
    use HasFactory, TracksUser;

    protected $table = 'web_notices';

    const CREATED_AT = 'created_dt_tm';
    const UPDATED_AT = 'updated_dt_tm';


    // Mass assignable fields
    protected $fillable = [
        'heading',
        'body',
        'is_active',
        'publish_date',
        'created_by',
        'created_dt_tm',
        'updated_by',
        'updated_dt_tm',
    ];

    // Casts
    protected $casts = [
        'publish_date' => 'date',
        'is_active' => 'boolean',
        'created_dt_tm' => 'datetime',
        'updated_dt_tm' => 'datetime',
    ];
}
