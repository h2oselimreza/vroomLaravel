<?php

namespace App\Models\Client;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class ExpenseFile extends BaseModel
{
    protected $table = 'expense_files';

    protected $fillable = [
        'expense_no',
        'original_name',
        'file_name',
        'created_by',
        'created_dt_tm',
        'updated_by',
        'updated_dt_tm',
    ];
}
