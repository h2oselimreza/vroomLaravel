<?php

namespace App\Models\Web;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\TracksUser;


class WebAchievement extends Model
{
    use HasFactory, TracksUser;

    protected $table = 'web_achievements';

    // Disable default timestamps (since you use custom fields)
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
}
