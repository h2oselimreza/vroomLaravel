<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\TracksUser;

class SentSmsDetail extends Model
{
    use HasFactory;

    protected $table = 'sent_sms_details';

    const CREATED_AT = 'created_dt_tm';

    protected $fillable = [
        'summary_ref_no',
        'mobile_no',
        'message_body',
        'template',
        'schedule_date',
        'schedule_time',
        'created_by',
        'created_dt_tm'
    ];

}
