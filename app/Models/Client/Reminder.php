<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    protected $table = 'reminder';

    protected $primaryKey = 'id';

    protected $fillable = [
        'reminder_no',
        'reminder_for',
        'reminder_for_value',
        'reminder_type',
        'company',
        'heading',
        'body',
        'reminder_on_dt_tm',
        'next_show_dt_tm',
        'repeat_every',
        'repeat_type',
        'before_reminder_count',
        'before_reminder_type',
        'default_mobile_flag',
        'default_email_flag',
        'default_mobile_no',
        'default_email',
        'mobile_no',
        'email',
        'created_by',
        'updated_by',
        'created_dt_tm',
        'updated_dt_tm'
    ];

}
