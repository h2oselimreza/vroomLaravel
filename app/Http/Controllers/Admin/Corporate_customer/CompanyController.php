<?php

namespace App\Http\Controllers\Admin\Corporate_customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\MetaData\CorporateCompanyRequest;
use App\Models\CorporateCompany;
use App\Models\MetaData\District;
use App\Models\MetaData\Upozilla;
use App\Repositories\MetaData\AreaRepository;
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
            $data['company_code']  = $prefix . $tokenService->getTokenByCode($prefix);;
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
}
