<?php

namespace App\Models;

use App\Traits\TracksUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WebPrayerTime extends Model
{
    use HasFactory, TracksUser;

    protected $table = 'web_prayer_time';

    const CREATED_AT = 'created_dt_tm';
    const UPDATED_AT = 'updated_dt_tm';

    protected $fillable = [
        'prayer_date',
        'fajr',
        'zuhor',
        'asor',
        'maghrib',
        'isha',
        'jumma',
        'sunrise',
        'sunset',
        'data_source',
        'created_by',
        'created_dt_tm',
        'updated_by',
        'updated_dt_tm',
    ];

    protected $dates = [
        'prayer_date',
        'created_dt_tm',
        'updated_dt_tm',
    ];
}
