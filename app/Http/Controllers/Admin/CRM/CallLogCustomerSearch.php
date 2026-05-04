<?php

namespace App\Http\Controllers\Admin\CRM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CallLogCustomerSearch extends Controller
{
    public function index(){
        return view('admin.crm.call-log.customer-search');
    }
}
