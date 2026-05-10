<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;

class DefaultReminder extends Model
{
    protected $table = 'default_reminders';

    protected $fillable = [
        'company',
        'reminder_for',
        'reminder_channel_type',
        'reminder_no',
        'created_by',
        'updated_by',
        'created_dt_tm',
        'updated_dt_tm'
    ];
}
