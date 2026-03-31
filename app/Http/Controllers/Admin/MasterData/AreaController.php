<?php

namespace App\Http\Controllers\Admin\MasterData;

use App\Http\Controllers\Controller;
use App\Repositories\MasterData\AreaRepository;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    
    public function index(AreaRepository $areaRepository){
        $areaCount = $areaRepository->getAreaCounts();
        return view('admin.master-data.index',compact('areaCount'));
    }

    public function division(AreaRepository $areaRepository){
        $divisions = $areaRepository->getDivision();
        //dd($division);
        return view('admin.master-data.division',compact('divisions'));
    }

    public function district(AreaRepository $areaRepository){
        $districts = $areaRepository->getDistrict();
        return view('admin.master-data.district',compact('districts'));
    }

    public function upazila(AreaRepository $areaRepository){
        $upazila = $areaRepository->getUpozilla();
        return view('admin.master-data.upazila',compact('upazila'));
    }

}
