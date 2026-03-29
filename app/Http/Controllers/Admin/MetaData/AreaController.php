<?php

namespace App\Http\Controllers\Admin\MetaData;

use App\Http\Controllers\Controller;
use App\Repositories\MetaData\AreaRepository;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    
    public function index(AreaRepository $areaRepository){
        $areaCount = $areaRepository->getAreaCounts();
        return view('admin.metadata.index',compact('areaCount'));
    }

    public function division(AreaRepository $areaRepository){
        $divisions = $areaRepository->getDivision();
        //dd($division);
        return view('admin.metadata.division',compact('divisions'));
    }

    public function district(AreaRepository $areaRepository){
        $districts = $areaRepository->getDistrict();
        return view('admin.metadata.district',compact('districts'));
    }

    public function upazila(AreaRepository $areaRepository){
        $upazila = $areaRepository->getUpozilla();
        return view('admin.metadata.upazila',compact('upazila'));
    }

}
