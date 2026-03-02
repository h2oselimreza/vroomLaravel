<?php

namespace App\Models\Web;

use App\Traits\TracksUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WebsiteModule extends Model
{
     use HasFactory, TracksUser;
    protected $table = 'web_website_module';
    protected $primaryKey = 'id';

    const CREATED_AT = 'created_dt_tm';
    const UPDATED_AT = 'updated_dt_tm';

    protected $fillable = [
        'module_code',
        'web_module_name',
    ];
}
