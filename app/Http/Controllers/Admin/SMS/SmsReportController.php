<?php

namespace App\Http\Controllers\Admin\SMS;

use App\Http\Controllers\Controller;
use App\Services\SmsService;
use Illuminate\Http\Request;

class SmsReportController extends Controller
{
    public function index(SmsService $smsService){
        $smsBalance = $smsService->getBalance();
        $smsBalance = $smsBalance['statusInfo']['availablebalance'] ?? 0;
        return view('admin.sms.sms-report',compact('smsBalance'));
    }
}
