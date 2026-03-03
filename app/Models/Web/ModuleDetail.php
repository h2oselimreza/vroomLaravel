<?php

namespace App\Models\Web;

use App\Traits\TracksUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModuleDetail extends Model
{
    use HasFactory, TracksUser;

    // Table name (optional if it follows Laravel naming convention)
    protected $table = 'web_module_description';

     const CREATED_AT = 'created_dt_tm';
    const UPDATED_AT = 'updated_dt_tm';

    // Mass assignable fields
    protected $fillable = [
        'module_code',
        'heading',
        'short_description',
        'image',
        'description',
        'created_by',
        'updated_by',
        'updated_dt_tm',
        'created_dt_tm',
    ];
}
