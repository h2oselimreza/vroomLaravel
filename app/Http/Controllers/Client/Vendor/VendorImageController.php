<?php

namespace App\Http\Controllers\Client\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Client\CorporateVendor;
use Illuminate\Http\Request;

class VendorImageController extends Controller
{
    public function edit($vendorCode){
        $vendor = CorporateVendor::where('vendor_code', $vendorCode)->first();
        return view('client.vendor.image.create-edit',compact('vendor'));
    }

    public function update(Request $request, $vendorCode)
    {
    
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);
        $vendor = CorporateVendor::where('vendor_code', $vendorCode)->firstOrFail();

        if ($request->hasFile('image')) {
            
            $destinationPath = 'assets/client/images/vendor/';

            if ($vendor->profile_image) {
                $oldFile = public_path($destinationPath . $vendor->profile_image);
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }

            $file = $request->file('image');
            
            $imageName = \Illuminate\Support\Str::random(10) . '-' . $file->getClientOriginalName();


            $file->move(public_path($destinationPath), $imageName);

            // 4. Update database
            $vendor->update([
                'profile_image' => $imageName
            ]);
        }

        return redirect()
            ->route('client.vendor.profile-image.edit', $vendorCode)
            ->with('success', 'Photo updated successfully');
    }
}
