<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerEmployeeFile extends BaseModel
{
    protected $table = 'customer_emp_file';

    protected $fillable = [
        'employee',
        'original_name',
        'file_name',
        'file_type',
        'is_active',
        'created_by',
        'updated_by',
        'created_dt_tm',
        'updated_dt_tm',
    ];
}
