<?php

namespace App\Http\Controllers\Admin\Corporate_customer;

use App\Http\Controllers\Controller;
use App\Models\CompanyNotificationPermission;
use App\Models\CompanySetting;
use App\Models\CorporateCompany;
use App\Models\MetaData\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompanyOfficeController extends Controller
{
    public function index($company_code){
        $package = Package::get();
        $packageJson = json_encode([
            'packageDetails' => $this->getAllPackage()
        ]);
        $data = CorporateCompany::where('company_code',$company_code)->first();
        $notificationPermissionsMasterData = DB::table('company_settings')->where('setting_type', 'notification_permission')->get();
        $companyNotificationPermissions = CompanyNotificationPermission::where('company', $company_code)->pluck('notification_code')->toArray();
        $companySettings = CompanySetting::where('company', $company_code)->first();
        return view('admin.corporate_customer.officeEdit',compact('package','packageJson','company_code','data','notificationPermissionsMasterData','companyNotificationPermissions','companySettings'));
    }

    public function getAllPackage($packageCode = null)
    {
        $query = Package::query();

        if ($packageCode) {
            $query->where('package_code', $packageCode);
        }

        return $query->get()->toArray();
    }


    public function editCompanyOfficial(Request $request)
    {
        $data = $request->validate([
            'company_code' => 'required|exists:corporate_companies,company_code',
            'package'      => 'required|string|max:50',
        ]);

        $company = CorporateCompany::where('company_code', $data['company_code'])->firstOrFail();

        $company->update([
            'package'        => $data['package'],
        ]);

        return redirect()
            ->route('admin.company.office.edit', [$company->company_code, 1])
            ->with('success', 'Package updated successfully');
    }

    public function updateNotificationPermission(Request $request)
    {

        $companyCode = $request->company_code;
        $notificationPermissions = $request->notificationPermissions ?? []; // default empty array

        if (!$companyCode) {
            return redirect()->route('admin.company.office.edit', $companyCode)
                            ->with('error', 'Company code is missing.');
        }

        // Use DB transaction for safety
        DB::transaction(function () use ($companyCode, $notificationPermissions) {

            CompanyNotificationPermission::where('company', $companyCode)->delete();

            if (!empty($notificationPermissions)) {
                $insertArr = collect($notificationPermissions)->map(function ($code) use ($companyCode) {
                    return [
                        'company'        => $companyCode,
                        'notification_code' => $code,
                        'created_by'     => auth()->user()->user_id ?? 'system',
                        'created_dt_tm'  => now(),
                    ];
                })->toArray();

                CompanyNotificationPermission::insert($insertArr);
            }
        });

        return redirect()->route('admin.company.office.edit', $companyCode)
                        ->with('success', 'Notification permissions updated successfully!');
    }

    public function updateSmsSettings(Request $request)
    {
        $companyCode = $request->company_code;

        // ✅ Validate input
        $validated = $request->validate([
            'url'      => 'required|string|max:255|url',
            'senderId' => 'required|string|max:50',
            'username' => 'required|string|max:100',
            'password' => 'required|string|max:100',
        ]);

        // ✅ Update or create in one step
        CompanySetting::updateOrCreate(
            [
                'company'      => $companyCode,
                'setting_type' => 'sms_settings',
                'title' => "Need to be think"
            ],
            [
                'description' => json_encode($validated),
            ]
        );

        return redirect()->route('admin.company.office.edit', $companyCode)
                        ->with('success', 'Company setting updated successfully!');
    }
}
