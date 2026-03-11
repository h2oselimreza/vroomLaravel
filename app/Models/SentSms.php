<?php

namespace App\Models;

use App\Traits\TracksUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class SentSms extends Model
{
    use HasFactory, TracksUser;
    protected $table = 'sent_sms';

    const CREATED_AT = 'created_dt_tm';
    const UPDATED_AT = 'updated_dt_tm';

    protected $fillable = [
        'reference_number',
        'sms_template',
        'sms_count',
        'custom_sms',
        'channel_type',
        'module_type',
        'mobile_number',
        'created_by',
        'created_dt_tm',
        'updated_by',
        'updated_dt_tm',
        'job_status'
    ];

    public static function memberBirthdayStatusChange($memberIdArr)
    {
        // 1. Update selected members
        DB::table('members')
            ->whereIn('id', $memberIdArr)
            ->update([
                'birthday_sms_status' => 1
            ]);

        // 2. Reset others whose birthday is not today
        DB::table('members')
            ->whereNotIn('id', $memberIdArr)
            ->where(function ($query) {
                $query->whereDay('dob', '!=', date('d'))
                    ->orWhereMonth('dob', '!=', date('m'));
            })
            ->update([
                'birthday_sms_status' => 0
            ]);
    }
}
