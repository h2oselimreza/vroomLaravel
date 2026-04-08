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

    // public function edit(CorporateCompany $company, AreaRepository $areaRepository){
    //     $data = $company;
    //     $divisions = $areaRepository->getDivision();
    //     $districts = ['districtData' => $areaRepository->getDistrict()];
    //     $upozillas = ['upozillaData' => $areaRepository->getUpozilla()];
    //     return view('admin.corporate_customer.createEdit',compact('data','divisions'));
    // }

    // public function update(CorporateCompanyRequest $request, $id)
    // {
    //     $data = $request->validated();

    //     $company = CorporateCompany::findOrFail($id);

    //     $company->update([
    //         'title'                     => $data['title'],
    //         'address'                   => $data['address'] ?? null,
    //         'company_email'             => $data['company_email'] ?? null,
    //         'website'                   => $data['website'] ?? null,
    //         'company_mobile'            => $data['company_mobile'] ?? null,
    //         'company_land_phone'        => $data['company_land_phone'] ?? null,

    //         'division'                  => $data['division'],
    //         'district'                  => $data['district'],
    //         'upozilla'                  => $data['upozilla'],

    //         'postal_code'               => $data['postal_code'] ?? null,
    //         'latitude'                  => $data['latitude'] ?? null,
    //         'longitude'                 => $data['longitude'] ?? null,

    //         'primary_contact_person'        => $data['primary_contact_person'] ?? null,
    //         'primary_contact_designation'   => $data['primary_contact_designation'] ?? null,
    //         'primary_contact_mobile'        => $data['primary_contact_mobile'] ?? null,
    //         'primary_contact_email'         => $data['primary_contact_email'] ?? null,

    //         'second_contact_person'         => $data['second_contact_person'] ?? null,
    //         'second_contact_designation'    => $data['second_contact_designation'] ?? null,
    //         'second_contact_mobile'         => $data['second_contact_mobile'] ?? null,
    //         'second_contact_email'          => $data['second_contact_email'] ?? null,

    //         'vts_company'               => $data['vts_company'] ?? null,
    //         'vts_app_key'               => $data['vts_app_key'] ?? null,
    //         'map_api_key'               => $data['map_api_key'] ?? null,
    //     ]);

    //     return redirect()
    //         ->route('admin.company-modules.index')
    //         ->with('success', 'Company updated successfully!');
    // }
}
