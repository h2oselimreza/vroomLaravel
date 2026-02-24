<?php

namespace App\Models;

use App\Traits\TracksUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MemberEducation extends Model
{
    use HasFactory, TracksUser;
    protected $table = 'member_edu_qualification';
    const CREATED_AT = 'created_dt_tm';
    const UPDATED_AT = 'updated_dt_tm';

    protected $fillable = [
        'member_id',
        'level_of_education',
        'exam_degree',
        'institute_name',
        'education_board',
        'qualification_result',
        'cgpa_marks',
        'scale',
        'passing_year',
        'duration',
        'major_group',
        'is_active',
        'created_by',
        'updated_by',
        'created_dt_tm',
        'updated_dt_tm'
    ];
}
