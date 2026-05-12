<?php

namespace App\Http\Controllers\Client\inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClientInventoryController extends Controller
{
    public function index(){
        return view('client.inventory.index');
    }
}
