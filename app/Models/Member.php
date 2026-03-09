<?php

namespace App\Models;

use App\Traits\TracksUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Member extends Model
{
    use HasFactory, TracksUser;
    
    const CREATED_AT = 'created_dt_tm';
    const UPDATED_AT = 'updated_dt_tm';

    protected $fillable = [
        'member_id',
        'member_name',
        'member_bangla_name',
        'member_image',
        'designation',
        'first_joining_date',
        'gender',
        'religion',
        'nationality',
        'dob',
        'blood_group',
        'marital_status',
        'spouse_name',
        'spouse_occupation',
        'spouse_contact',
        'spouse_office_address',
        'member_tnt_phone',
        'primary_mobile',
        'secendary_mobile',
        'emer_contact_name',
        'emer_contact_relation',
        'emer_contact_mobile',
        'emer_contact_address',
        'email',
        'present_address',
        'member_permanent_address',
        'national_id',
        'father_name',
        'father_occupation',
        'father_office_address',
        'father_contact',
        'mother_name',
        'mother_occupation',
        'mother_office_address',
        'mother_contact',
        'guardian_name',
        'guardian_contact',
        'guardian_relation',
        'guardian_house_address',
        'last_organization',
        'last_org_address',
        'last_org_designation',
        'last_org_from_date',
        'last_org_to_date',
        'passport_no',
        'passport_expiry_date',
        'driving_license_no',
        'driving_license_expiry_date',
        'anniversary',
        'society_block',
        'society_road',
        'society_plot',
        'society_flat',
        'member_type',
        'first_introduced_by',
        'second_introduced_by',
        'member_occupation',
        'institution_name',
        'is_active',
        'system_user',
        'birthday_sms_status',
        'anniversary_sms_status',
        'donar_member_id',
        'created_by',
        'updated_by',
        'created_dt_tm',
        'updated_dt_tm',
    ];

    public function block()
    {
        return $this->belongsTo(Block::class, 'society_block', 'block_code');
    }

    public function road()
    {
        return $this->belongsTo(Road::class, 'society_road', 'road_code');
    }
}
