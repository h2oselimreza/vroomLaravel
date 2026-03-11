<?php

namespace App\Models;

use App\Traits\TracksUser;
use Carbon\Carbon;
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

    public static function EmployeeBirthdayStatusChange($IdArr, $msgType)
    {
        // 1. Update selected members
        DB::table('employee')
            ->whereIn('id', $IdArr)
            ->update([
                'birthday_sms_status' => 1
            ]);

        // 2. Reset others whose birthday is not today
        DB::table('employee')
            ->whereNotIn('id', $IdArr)
            ->where(function ($query) {
                $query->whereDay('dob', '!=', date('d'))
                    ->orWhereMonth('dob', '!=', date('m'));
            })
            ->update([
                'birthday_sms_status' => 0
            ]);
    }

    public static function updateSmsStatus(array $idArr, string $msgType)
    {
        $today = Carbon::now();

        // Determine table and column dynamically
        switch ($msgType) {
            case 'employeeBirthday':
                $table = 'employee';
                $column = 'birthday_sms_status';
                break;

            case 'employeeAnniversary':
                $table = 'employee';
                $column = 'anniversary_sms_status';
                break;

            case 'memberBirthday':
                $table = 'members';
                $column = 'birthday_sms_status';
                break;

            case 'memberAnniversary':
                $table = 'members';
                $column = 'anniversary_sms_status';
                break;

            default:
                throw new \InvalidArgumentException("Invalid message type: $msgType");
        }

        DB::table($table)
            ->whereIn('id', $idArr)
            ->update([
                $column => 1
            ]);

        DB::table($table)
            ->whereNotIn('id', $idArr)
            ->where(function ($query) use ($today, $msgType) {
                if (str_contains($msgType, 'Birthday')) {
                    $query->whereDay('dob', '!=', $today->day)
                        ->orWhereMonth('dob', '!=', $today->month);
                } else { // Anniversary
                    $query->whereDay('anniversary', '!=', $today->day)
                        ->orWhereMonth('anniversary', '!=', $today->month);
                }
            })
            ->update([
                $column => 0
            ]);
    }
}
