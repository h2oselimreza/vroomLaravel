<?php

namespace App\Models\Admin\Place;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class Place extends BaseModel
{
    protected $fillable = [
        'place_code',
        'place_type',
        'title',
        'title_bn',
        'address',
        'address_bn',
        'place_email',
        'website',
        'place_mobile',
        'place_land_phone',
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
        'place_display_code',
    ];
}
