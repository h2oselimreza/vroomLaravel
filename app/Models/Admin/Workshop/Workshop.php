<?php

namespace App\Models\Admin\Workshop;

use Illuminate\Database\Eloquent\Model;
use App\Models\BaseModel;

class Workshop extends BaseModel
{
    protected $table = 'workshops';

    protected $fillable = [
        'workshop_code', 'title', 'address', 'workshop_email', 'website',
        'workshop_mobile', 'workshop_land_phone', 'profile_image', 'division',
        'district', 'upozilla', 'postal_code', 'latitude', 'longitude',
        'primary_contact_person', 'primary_contact_designation', 'primary_contact_mobile',
        'primary_contact_email', 'second_contact_person', 'second_contact_designation',
        'second_contact_mobile', 'second_contact_email', 'is_active', 'status',
        'created_by', 'updated_by', 'created_dt_tm', 'updated_dt_tm'
    ];
}
