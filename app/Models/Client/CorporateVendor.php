<?php

namespace App\Models\Client;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class CorporateVendor extends BaseModel
{
    protected $table = 'corporate_vendor';

    protected $primaryKey = 'id';

    protected $fillable = [
        'vendor_code',
        'company',
        'title',
        'address',
        'vendor_email',
        'website',
        'vendor_mobile',
        'vendor_land_phone',
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
        'is_active',
        'status',
        'created_by',
        'created_dt_tm',
        'updated_by',
        'updated_dt_tm',
    ];
}
