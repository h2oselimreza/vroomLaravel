<?php

namespace App\Http\Controllers\Admin\Corporate_customer;

use App\Http\Controllers\Controller;
use App\Models\CorporateCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanyProfileController extends Controller
{
    public function edit($company_code){
        $data = CorporateCompany::where('company_code', $company_code)->first();
        return view("admin.corporate_customer.profile-pic",compact("data"));
    }

    public function update(Request $request, $company_code)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);
        
        $company = CorporateCompany::where('company_code',$company_code)->first();

        if ($request->hasFile('image')) {

            if ($company->profile_image &&
                Storage::disk('public')->exists('images/company/' . $company->profile_image)) {

                Storage::disk('public')->delete('images/company/' . $company->profile_image);
            }

            $imageName = time() . '.' . $request->file('image')->extension();

            $request->file('image')
                    ->storeAs('company', $imageName, 'public');

            $company->update([
                'profile_image' => $imageName
            ]);
        }

        return redirect()
            ->route('admin.company.profile-image.edit', $company_code)
            ->with('success', 'Photo updated successfully');
    }
}
