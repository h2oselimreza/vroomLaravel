<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerEmployee extends BaseModel
{

    protected $table = 'customer_employee';
    protected $fillable = [
        'company', 'employee_id', 'employee_name', 'customer_type', 'emp_type',
        'employee_image', 'designation', 'first_joining_date', 'gender',
        'religion', 'nationality', 'dob', 'blood_group', 'marital_status',
        'spouse_name', 'spouse_occupation', 'spouse_contact', 'primary_mobile',
        'secendary_mobile', 'emer_contact_name', 'emer_contact_relation',
        'email', 'emer_conatct_mobile', 'emer_contact_address', 'present_address',
        'national_id', 'father_name', 'father_occupation', 'father_office_address',
        'father_contact', 'mother_name', 'mother_occupation', 'mother_office_address',
        'mother_contact', 'guardian_name', 'guardian_contact', 'guardian_relation',
        'guardian_house_address', 'spouse_office_address', 'employee_tnt_phone',
        'employee_permanent_address', 'last_organization', 'last_org_address',
        'last_org_designation', 'last_org_from_date', 'last_org_to_date',
        'passport_no', 'passposrt_expiry_date', 'driving_license_no',
        'driving_license_expiry_date', 'anniversary', 'ref_one_name',
        'ref_one_mobile', 'ref_one_email', 'ref_one_address', 'ref_two_name',
        'ref_two_mobile', 'ref_two_email', 'ref_two_address', 'is_active',
        'created_by', 'created_type', 'updated_by', 'updated_type', 'system_user',
        'created_dt_tm','updated_dt_tm'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'employee_id', 'user_id');
    }
}
