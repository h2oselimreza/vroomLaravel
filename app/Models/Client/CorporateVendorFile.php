<?php

namespace App\Models\Client;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class CorporateVendorFile extends BaseModel
{
    protected $table = 'corporate_vendor_file';

    protected $primaryKey = 'id';

    protected $fillable = [
        'vendor',
        'original_name',
        'file_name',
        'file_type',
        'is_active',
        'created_by',
        'created_dt_tm',
        'updated_by',
        'updated_dt_tm',
    ];
}
