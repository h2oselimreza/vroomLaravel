<?php

namespace App\Http\Controllers\Admin\Place;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Place\PlaceRequest;
use App\Models\Admin\Place\Place;
use App\Repositories\MasterData\AreaRepository;
use App\Repositories\PlaceRepository;
use App\Services\TokenService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    public function create(AreaRepository $areaRepository){
        $divisions = $areaRepository->getDivision();
        $districts = ['districtData' => $areaRepository->getDistrict()];
        $upozillas = ['upozillaData' => $areaRepository->getUpozilla()];
        return view('admin.place.create-edit',compact('divisions','districts','upozillas'));
    }

    public function store(PlaceRequest $request, TokenService $tokenService)
    {
        try {
            DB::beginTransaction();

            $dateTime = Carbon::now();

            // ================= EXIST CHECK (same as CI) =================
            $exists = DB::table('places')
                ->where('title', $request->title)
                ->where('place_mobile', $request->place_mobile)
                ->exists();

            if ($exists) {
                DB::rollBack();
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Place already exists');
            }

            // ================= GENERATE PLACE CODE =================
            $placeCode = config('constants.PLACE_CODE') . $tokenService->getTokenByCode(config('constants.PLACE_CODE'));

            // ================= DATA PREPARE =================
            $data = [
                'place_code'                  => $placeCode,
                'place_type'                  => config('constants.PLACE_TYPE'),

                'title'                       => $request->title,
                'title_bn'                    => $request->title_bn,
                'address'                     => $request->address,
                'address_bn'                  => $request->address_bn,

                'place_email'                 => $request->workshop_email,
                'website'                     => $request->website,

                'place_mobile'                => $request->place_mobile,
                'place_land_phone'            => $request->place_land_phone,

                'division'                    => $request->division ?? 0,
                'district'                    => $request->district ?? 0,
                'upozilla'                    => $request->upozilla ?? 0,
                'postal_code'                 => $request->postal_code ?? 0,

                'latitude'                    => $request->latitude,
                'longitude'                   => $request->longitude,

                'place_display_code'          => $request->place_display_code,

                'primary_contact_person'      => $request->primary_contact_person,
                'primary_contact_designation'=> $request->primary_contact_designation,
                'primary_contact_mobile'      => $request->primary_contact_mobile,
                'primary_contact_email'       => $request->primary_contact_email,

                'second_contact_person'       => $request->second_contact_person,
                'second_contact_designation' => $request->second_contact_designation,
                'second_contact_mobile'       => $request->second_contact_mobile,
                'second_contact_email'        => $request->second_contact_email,

                // KEEP ORIGINAL LOGIC
                'status'                      => 1,
                'is_active'                   => 1,

                'created_by'                  => Auth::user()->user_id,
                'created_dt_tm'               => $dateTime,
                'updated_by'                  => Auth::user()->user_id,
                'updated_dt_tm'               => $dateTime,
            ];

            // ================= INSERT =================
            DB::table('places')->insert($data);

            DB::commit();

            return redirect()
                ->route('admin.place.place-info.edit', [$placeCode, 1])
                ->with('success', 'Place created successfully');

        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function edit($placeCode, AreaRepository $areaRepository){
        $data = Place::where('place_code',$placeCode)->first();
        //dd($data);
        $divisions = $areaRepository->getDivision();
        $districts = ['districtData' => $areaRepository->getDistrict()];
        $upozillas = ['upozillaData' => $areaRepository->getUpozilla()];
        return view('admin.place.create-edit',compact('data','divisions'));
    }

    public function update(PlaceRequest $request, $placeCode)
    {
        try {
            DB::beginTransaction();

            $dateTime = Carbon::now();

            // ================= EXIST CHECK (exclude current record) =================
            $exists = DB::table('places')
                ->where('title', $request->title)
                ->where('place_mobile', $request->place_mobile)
                ->where('place_code', '!=', $placeCode) // important
                ->exists();

            if ($exists) {
                DB::rollBack();

                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Place already exists');
            }

            // ================= DATA PREPARE =================
            $data = [
                'place_type'                  => config('constants.PLACE_TYPE'),

                'title'                       => $request->title,
                'title_bn'                    => $request->title_bn,
                'address'                     => $request->address,
                'address_bn'                  => $request->address_bn,

                'place_email'                 => $request->workshop_email,
                'website'                     => $request->website,

                'place_mobile'                => $request->place_mobile,
                'place_land_phone'            => $request->place_land_phone,

                'division'                    => $request->division ?? 0,
                'district'                    => $request->district ?? 0,
                'upozilla'                    => $request->upozilla ?? 0,
                'postal_code'                 => $request->postal_code ?? 0,

                'latitude'                    => $request->latitude,
                'longitude'                   => $request->longitude,

                'place_display_code'          => $request->place_display_code,

                'primary_contact_person'      => $request->primary_contact_person,
                'primary_contact_designation'=> $request->primary_contact_designation,
                'primary_contact_mobile'      => $request->primary_contact_mobile,
                'primary_contact_email'       => $request->primary_contact_email,

                'second_contact_person'       => $request->second_contact_person,
                'second_contact_designation' => $request->second_contact_designation,
                'second_contact_mobile'       => $request->second_contact_mobile,
                'second_contact_email'        => $request->second_contact_email,

                // KEEP ORIGINAL LOGIC
                'updated_by'                  => Auth::user()->user_id,
                'updated_dt_tm'               => $dateTime,
            ];

            // ================= UPDATE =================
            DB::table('places')
                ->where('place_code', $placeCode)
                ->update($data);

            DB::commit();

            return redirect()
                ->route('admin.place.place-info.edit', [$placeCode, 1])
                ->with('success', 'Place updated successfully');

        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }
}
