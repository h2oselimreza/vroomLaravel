<?php

namespace App\Http\Controllers\Client\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Client\CorporateVendor;
use App\Repositories\CommonRepository;
use App\Repositories\MasterData\AreaRepository;
use App\Repositories\MasterData\MasterDataRepository;
use App\Services\TokenService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VendorController extends Controller
{
    public function index(Request $request, MasterDataRepository $masterDataRepository){
        $isActiveFlag = 1;
        $statusDropDown = (int) $request->statusDropDown;
        if ($statusDropDown) {
            $isActiveFlag = $statusDropDown;
        }
        $vendors = $masterDataRepository->getVendorList($isActiveFlag, Auth::user()->customerEmployee->company);

        return view('client.vendor.index',compact('vendors','isActiveFlag'));
    }

    public function create(AreaRepository $areaRepository){
        $data['disableFlag'] = 1;
        $divisions = $areaRepository->getDivision();
        $districts = $areaRepository->getDistrict();
        $upozillas = $areaRepository->getUpozilla();
        return view('client.vendor.create-update',compact('divisions','districts','upozillas'));
    }

    public function store(Request $request, TokenService $tokenService)
    {
        try {
            // ✅ Validation
            $request->validate([
            'title' => 'required|string|max:250',
            'vendor_mobile' => 'required|string|max:20',
        ]);
            DB::beginTransaction();

            $now = Carbon::now();
            $userId = Auth::user()->user_id ?? Auth::id();
            $company = Auth::user()->customerEmployee->company;

            // ✅ 🔥 DUPLICATE CHECK
            $exists = DB::table('corporate_vendor')
                ->where('title', $request->title)
                ->where('vendor_mobile', $request->vendor_mobile)
                ->exists();

            if ($exists) {
                DB::rollBack(); // important

                return back()
                    ->withInput()
                    ->with('error', 'You have already inserted this vendor...!');
            }

            // ✅ Generate vendor code (same logic)
            $vendorCode = config('constants.VENDOR_CODE') . $tokenService->getTokenByCode(config('constants.VENDOR_CODE'));

            // ✅ Prepare data
            $data = [
                'vendor_code' => $vendorCode,
                'company' => $company,

                'title' => $request->title,
                'address' => $request->address,

                'vendor_email' => $request->email,
                'website' => $request->website,

                'vendor_mobile' => $request->vendor_mobile,
                'vendor_land_phone' => $request->vendor_land_phone,

                'division' => $request->division ?? 0,
                'district' => $request->district ?? 0,
                'upozilla' => $request->upozilla ?? 0,
                'postal_code' => $request->postalCode ?? 0,

                'latitude' => $request->latitude,
                'longitude' => $request->longitude,

                // Primary contact
                'primary_contact_person' => $request->primary_contact_person,
                'primary_contact_designation' => $request->primary_contact_designation,
                'primary_contact_mobile' => $request->primary_contact_mobile,
                'primary_contact_email' => $request->primary_contact_email,

                // Secondary contact
                'second_contact_person' => $request->second_contact_person,
                'second_contact_designation' => $request->second_contact_designation,
                'second_contact_mobile' => $request->second_contact_mobile,
                'second_contact_email' => $request->second_contact_email,

                'is_active' => 1,
                'status' => 1,

                'created_by' => $userId,
                'created_dt_tm' => $now,
                'updated_by' => $userId,
                'updated_dt_tm' => $now,
            ];

            // ✅ Insert
            DB::table('corporate_vendor')->insert($data);

            DB::commit();

            return redirect('/client/vendor/info')
                ->with('success', 'Vendor added successfully');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function edit($vendorCode, AreaRepository $areaRepository){
        $data['disableFlag'] = 1;
        $divisions = $areaRepository->getDivision();
        $districts = $areaRepository->getDistrict();
        $upozillas = $areaRepository->getUpozilla();
        $vendor = CorporateVendor::where('vendor_code', $vendorCode)->first();
        return view('client.vendor.create-update',compact('divisions','districts','upozillas','vendor'));
    }

    public function update($vendorCode, Request $request, TokenService $tokenService)
    {
        try {

            // ✅ Validation (same as store)
            $request->validate([
                'title' => 'required|string|max:250',
                'vendor_mobile' => 'required|string|max:20',
            ]);

            DB::beginTransaction();

            $now = Carbon::now();
            $userId = Auth::user()->user_id ?? Auth::id();
            $company = Auth::user()->customerEmployee->company;

            // ✅ Find existing vendor
            $vendor = DB::table('corporate_vendor')->where('vendor_code', $vendorCode)->first();

            if (!$vendor) {
                DB::rollBack();
                return back()->with('error', 'Vendor not found');
            }

            // ✅ DUPLICATE CHECK (exclude current record)
            $exists = DB::table('corporate_vendor')
                ->where('title', $request->title)
                ->where('vendor_mobile', $request->vendor_mobile)
                ->where('vendor_code', '!=', $vendorCode)
                ->exists();

            if ($exists) {
                DB::rollBack();

                return back()
                    ->withInput()
                    ->with('error', 'You have already inserted this vendor...!');
            }

            // ✅ Prepare update data (same structure as store)
            $data = [
                'company' => $company,

                'title' => $request->title,
                'address' => $request->address,

                'vendor_email' => $request->email,
                'website' => $request->website,

                'vendor_mobile' => $request->vendor_mobile,
                'vendor_land_phone' => $request->vendor_land_phone,

                'division' => $request->division ?? 0,
                'district' => $request->district ?? 0,
                'upozilla' => $request->upozilla ?? 0,
                'postal_code' => $request->postalCode ?? 0,

                'latitude' => $request->latitude,
                'longitude' => $request->longitude,

                // Primary contact
                'primary_contact_person' => $request->primary_contact_person,
                'primary_contact_designation' => $request->primary_contact_designation,
                'primary_contact_mobile' => $request->primary_contact_mobile,
                'primary_contact_email' => $request->primary_contact_email,

                // Secondary contact
                'second_contact_person' => $request->second_contact_person,
                'second_contact_designation' => $request->second_contact_designation,
                'second_contact_mobile' => $request->second_contact_mobile,
                'second_contact_email' => $request->second_contact_email,

                // status remains same
                'is_active' => $vendor->is_active ?? 1,
                'status' => $vendor->status ?? 1,

                // update tracking
                'created_by' => $vendor->created_by,
                'created_dt_tm' => $vendor->created_dt_tm,
                'updated_by' => $userId,
                'updated_dt_tm' => $now,
            ];

            // ✅ Update query
            DB::table('corporate_vendor')
                ->where('vendor_code', $vendorCode)
                ->update($data);

            DB::commit();

            return redirect()->route('client.vendor.venor-list.edit', $vendorCode)
                ->with('success', 'Vendor updated successfully');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }
}
