<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;

class HomeServiceAppSummaryGen extends Model
{
    protected $table = 'home_service_app_summary_gen';

    protected $fillable = [
        'appointment_no',
        'company',
        'name',
        'mobile',
        'address',
        'service_date',
        'service_time',
        'final_date',
        'appointment_time',
        'grand_total',
        'additional_bill',
        'total_additional_bill',
        'discount',
        'remarks',
        'admin_remarks',
        'reject_reason',
        'leads_by',
        'status',
        'assign_emp',
        'assign_emp_dt_tm',
        'transaction_channel',
        'created_by',
        'created_type',
        'created_dt_tm',
        'updated_by',
        'updated_type',
        'updated_dt_tm',
    ];
}
