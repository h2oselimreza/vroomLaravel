<?php

namespace App\Models\Web;

use App\Traits\TracksUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
     use HasFactory, TracksUser;

    protected $table = 'web_events';

    // Disable default Laravel timestamps (since you use custom datetime columns)
    const CREATED_AT = 'created_dt_tm';
    const UPDATED_AT = 'updated_dt_tm';

    protected $fillable = [
        'image',
        'heading',
        'short_description',
        'details',
        'date',
        'is_active',
        'created_by',
        'created_dt_tm',
        'updated_by',
        'updated_dt_tm',
    ];

    // protected $casts = [
    //     'date' => 'date',
    //     'is_active' => 'boolean',
    //     'created_dt_tm' => 'datetime',
    //     'updated_dt_tm' => 'datetime',
    // ];
}
