<?php

namespace App\Http\Controllers\Admin\Place;

use App\Http\Controllers\Controller;
use App\Repositories\PlaceRepository;
use Illuminate\Http\Request;

class PlaceController extends Controller
{
    public function index(Request $request, PlaceRepository $placeRepository){
            $isActiveFlag = 1;
            $statusDropDown = $request->statusDropDown;
            if ($statusDropDown) {
                $isActiveFlag = $statusDropDown;
            }
            $places = $placeRepository->getPlaceList($isActiveFlag);
            return view('admin.place.index',compact('places','isActiveFlag'));
    }
}
