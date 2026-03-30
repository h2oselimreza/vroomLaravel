<?php

namespace App\Models;
use App\Traits\TracksUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorporateCompany extends BaseModel
{
    protected $table = 'corporate_companies';
    
    protected $fillable = [
        'company_code',
        'company_type',
        'enrolled_apprv_code',
        'title',
        'package',
        'membership_card',
        'address',
        'company_email',
        'website',
        'company_mobile',
        'company_land_phone',
        'profile_image',
        'division',
        'district',
        'upozilla',
        'postal_code',
        'latitude',
        'longitude',
        'primary_contact_person',
        'primary_contact_designation',
        'primary_contact_mobile',
        'primary_contact_email',
        'second_contact_person',
        'second_contact_designation',
        'second_contact_mobile',
        'second_contact_email',
        'vts_company',
        'vts_app_key',
        'map_api_key',
        'rm_id',
        'is_active',
        'status',
        'created_by',
        'updated_by',
        'created_dt_tm',
        'updated_dt_tm'
    ];
}
