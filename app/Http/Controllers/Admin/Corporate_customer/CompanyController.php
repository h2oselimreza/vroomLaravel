<?php

namespace App\Http\Controllers\Admin\Corporate_customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MasterData\CorporateCompanyRequest;
use App\Models\CorporateCompany;
use App\Models\MetaData\District;
use App\Models\MetaData\Upozilla;
use App\Repositories\MasterData\AreaRepository;
use App\Services\TokenService;

class CompanyController extends Controller
{
    public function index(){
        $companies = CorporateCompany::where('is_active',1)->get();
        return view('admin.corporate_customer.index',compact('companies'));
    }

    public function create(AreaRepository $areaRepository){
        $divisions = $areaRepository->getDivision();
        $districts = ['districtData' => $areaRepository->getDistrict()];
        $upozillas = ['upozillaData' => $areaRepository->getUpozilla()];
        return view('admin.corporate_customer.createEdit',compact('divisions','districts','upozillas'));
    }

    public function getDistricts($division_id)
    {
        $districts = District::where('division', $division_id)
            ->where('is_active', 1)
            ->get();
        return response()->json($districts);
    }

    public function getUpazilas($district_id)
    {
        $upazilas = Upozilla::where('district', $district_id)
            ->where('is_active', 1)
            ->get();

        return response()->json($upazilas);
    }

    public function store(CorporateCompanyRequest $request, TokenService $tokenService)
    {
        $prefix = "FC";
        try {

            $data = $request->validated();
            // Add extra fields
            $data['status'] = 1;
            $data['company_code']  = $prefix . $tokenService->getTokenByCode($prefix);
            $data['company_type'] = 'corp_customer';
            CorporateCompany::create($data);

            return redirect()
                ->route('admin.company-modules.index')
                ->with('success', 'Company created successfully');

        } catch (\Exception $e) {

            return back()
                ->withInput()
                ->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function edit(CorporateCompany $company, AreaRepository $areaRepository){
        $data = $company;
        $divisions = $areaRepository->getDivision();
        $districts = ['districtData' => $areaRepository->getDistrict()];
        $upozillas = ['upozillaData' => $areaRepository->getUpozilla()];
        return view('admin.corporate_customer.createEdit',compact('data','divisions'));
    }

    public function update(CorporateCompanyRequest $request, $id)
    {
        $data = $request->validated();

        $company = CorporateCompany::findOrFail($id);

        $company->update([
            'title'                     => $data['title'],
            'address'                   => $data['address'] ?? null,
            'company_email'             => $data['company_email'] ?? null,
            'website'                   => $data['website'] ?? null,
            'company_mobile'            => $data['company_mobile'] ?? null,
            'company_land_phone'        => $data['company_land_phone'] ?? null,

            'division'                  => $data['division'],
            'district'                  => $data['district'],
            'upozilla'                  => $data['upozilla'],

            'postal_code'               => $data['postal_code'] ?? null,
            'latitude'                  => $data['latitude'] ?? null,
            'longitude'                 => $data['longitude'] ?? null,

            'primary_contact_person'        => $data['primary_contact_person'] ?? null,
            'primary_contact_designation'   => $data['primary_contact_designation'] ?? null,
            'primary_contact_mobile'        => $data['primary_contact_mobile'] ?? null,
            'primary_contact_email'         => $data['primary_contact_email'] ?? null,

            'second_contact_person'         => $data['second_contact_person'] ?? null,
            'second_contact_designation'    => $data['second_contact_designation'] ?? null,
            'second_contact_mobile'         => $data['second_contact_mobile'] ?? null,
            'second_contact_email'          => $data['second_contact_email'] ?? null,

            'vts_company'               => $data['vts_company'] ?? null,
            'vts_app_key'               => $data['vts_app_key'] ?? null,
            'map_api_key'               => $data['map_api_key'] ?? null,
        ]);

        return redirect()
            ->route('admin.company-modules.index')
            ->with('success', 'Company updated successfully!');
    }

    public function companyList(){
        $companies = CorporateCompany::where('is_active',1)->get();
        return view('admin.corporate_customer.employee.company-list',compact('companies'));
    }
}
