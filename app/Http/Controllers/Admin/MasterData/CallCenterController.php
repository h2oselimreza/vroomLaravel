<?php

namespace App\Http\Controllers\Admin\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Admin\MasterData\CallReason;
use App\Models\Admin\MasterData\CustomerFeedback;
use Illuminate\Http\Request;

class CallCenterController extends Controller
{
    public function index(){
        $reasonCount = CallReason::count();
        $customerFeedBack = CustomerFeedback::count();
        return view('admin.master-data.call-center.index',compact('reasonCount','customerFeedBack'));
    }
}
