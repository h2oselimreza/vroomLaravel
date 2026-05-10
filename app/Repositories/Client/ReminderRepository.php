<?php

namespace App\Repositories\Client;

use App\Models\Client\Vehicle;
use App\Models\Client\VehicleAssignDetail;
use App\Models\Client\VehicleBookingSummary;
use App\Models\Company;
use App\Models\CustomerEmployee;
use Illuminate\Support\Facades\DB;

class ReminderRepository
{
    public function getDefaultReminderTo($companyCode, $reminderFor)
    {
        return DB::table('default_reminders')
            ->where('company', $companyCode)
            ->where('reminder_for', $reminderFor)
            ->get();
    }

    public function addReminder($arr)
    {
        DB::table('reminder')->insert($arr);
        return 1;
    }

    public function getReminderList($companyCode)
    {
        return DB::table('reminder')
            ->where('company', $companyCode)
            ->orderBy('created_dt_tm', 'DESC')
            ->get();
    }

    public function removeReminder($reminderNo, $companyCode)
    {
        DB::table('reminder')
            ->where('reminder_no', $reminderNo)
            ->where('company', $companyCode)
            ->delete();

        return 1;
    }

    public function getDefaultReminder($companyCode)
    {
        return DB::table('default_reminders')
            ->where('company', $companyCode)
            ->get();
    }
}