<?php

namespace App\Http\Controllers\Client\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MasterDataController extends Controller
{
    public function expense(){

        $data['leftMenuModuleUrl'] = "client/MasterData/expense";
        //$this->data['subDashboardValues'] = $this->MasterData_model->getCostDashboardValue();
        return view('client.master-data.expense',compact('data'));
    }
}
