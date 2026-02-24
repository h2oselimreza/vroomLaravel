<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\TracksUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MemberWrokingExperience extends Model
{
    use HasFactory, TracksUser;
    protected $table = 'member_working_experience';

    const CREATED_AT = 'created_dt_tm';
    const UPDATED_AT = 'updated_dt_tm';

    protected $fillable = [
        'member_id',
        'institution_name',
        'institution_type',
        'address',
        'designation',
        'department',
        'from_date',
        'to_date',
        'is_continued',
        'responsibilites',
        'is_active',
        'created_by',
        'updated_by',
        'created_dt_tm',
        'updated_dt_tm'
    ];
}
