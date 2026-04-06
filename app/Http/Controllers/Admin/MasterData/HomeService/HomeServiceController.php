<?php

namespace App\Http\Controllers\Admin\MasterData\HomeService;

use App\Http\Controllers\Controller;
use App\Models\Admin\MasterData\Service;
use App\Models\Admin\MasterData\ServiceCategory;
use App\Models\Admin\MasterData\ServiceVariant;
use Illuminate\Http\Request;

class HomeServiceController extends Controller
{
    public function index(){
        $serviceCategoryCount = ServiceCategory::count();
        $serviceListCount = Service::count();
        $serviceVariantCount = ServiceVariant::count();
        return view('admin.master-data.home-service.index',compact('serviceCategoryCount','serviceListCount','serviceVariantCount'));
    }
}
