<?php

namespace App\Http\Controllers\Admin\Workshop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Wrokshop\GeneralInfoRequest;
use App\Models\Admin\Workshop\Workshop;
use App\Repositories\MasterData\AreaRepository;
use App\Services\TokenService;
use Illuminate\Http\Request;

class GeneralInfoController extends Controller
{
    public function create(AreaRepository $areaRepository){
        $divisions = $areaRepository->getDivision();
        $districts = ['districtData' => $areaRepository->getDistrict()];
        $upozillas = ['upozillaData' => $areaRepository->getUpozilla()];
        return view('admin.work-shop.create-edit',compact('divisions','districts','upozillas'));
    }

    public function store(GeneralInfoRequest $request, TokenService $tokenService)
    {
        $prefix = "WRKSHP-";
        try {

            $data = $request->validated();
            // Add extra fields
            $data['status'] = 1;
            $data['is_active'] = 1;
            $data['workshop_code']  = $prefix . $tokenService->getTokenByCode($prefix);
            Workshop::create($data);

            return redirect()
                ->route('admin.workshop-general-info.create')
                ->with('success', 'Workshop created successfully');

        } catch (\Exception $e) {

            return back()
                ->withInput()
                ->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function edit($workshopCode, AreaRepository $areaRepository){
        $data = Workshop::where('workshop_code',$workshopCode)->first();
        $divisions = $areaRepository->getDivision();
        $districts = ['districtData' => $areaRepository->getDistrict()];
        $upozillas = ['upozillaData' => $areaRepository->getUpozilla()];
        return view('admin.work-shop.create-edit',compact('data','divisions'));
    }

    public function update(GeneralInfoRequest $request, $workshopCode)
    {
        try {
            $workshop = Workshop::where('workshop_code', $workshopCode)->first();

            $data = $request->validated();

            $workshop->update($data);

            return redirect()
                ->route('admin.workshop-general-info.edit', $workshopCode)
                ->with('success', 'Workshop updated successfully');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Update failed: ' . $e->getMessage());
        }
    }

}
