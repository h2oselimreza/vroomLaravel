<?php

namespace App\Models\Web;

use App\Traits\TracksUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Slider extends Model
{
    use HasFactory, TracksUser;
    protected $table = 'web_home_page_image';
    const CREATED_AT = 'created_dt_tm';
    const UPDATED_AT = 'updated_dt_tm';

    protected $fillable = [
        'image',
        'image_order',
        'created_by',
        'updated_by',
        'created_dt_tm',
        'updated_dt_tm'
    ];
}
