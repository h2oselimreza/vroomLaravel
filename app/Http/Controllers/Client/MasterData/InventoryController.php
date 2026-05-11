<?php

namespace App\Http\Controllers\Client\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(){
        return view('client.master-data.inventory.index');
    }
}
