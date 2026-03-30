<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyNotificationPermission extends Model
{
    protected $table = 'company_notification_permissions';

    public $timestamps = false; // ❗ Important

    protected $fillable = [
        'company',
        'notification_code',
        'created_by',
        'created_dt_tm',
    ];
}
