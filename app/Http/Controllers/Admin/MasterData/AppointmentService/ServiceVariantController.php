<?php

namespace App\Http\Controllers\Admin\MasterData\AppointmentService;

use App\Http\Controllers\Controller;
use App\Models\Admin\MasterData\Service;
use App\Models\Admin\MasterData\ServiceVariant;
use App\Services\TokenService;
use DB;
use Illuminate\Http\Request;

class ServiceVariantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = ServiceVariant::with('serviceDetails.category')->get();
        return view('admin.master-data.appointment-service.service-variant.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $serviceListData = Service::with('category')->get();
        return view('admin.master-data.appointment-service.service-variant.create',compact('serviceListData'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function saveServiceVariant(Request $request, TokenService $tokenService) 
    {
        if (is_string($request->variants)) {
            $request->merge([
                'variants' => json_decode($request->variants, true)
            ]);
        }

        $request->validate([
            'serviceCode' => 'required|string',
            'variantType' => 'required|string',
            'variants'    => 'required|array',
        ]);

        try {
            DB::beginTransaction();

            $serviceCode = $request->serviceCode;
            $variantType = $request->variantType;

            if ($request->filled('deleteVariant')) {
                $idsToDelete = explode(',', $request->deleteVariant);
                ServiceVariant::whereIn('id', $idsToDelete)->delete();
            }

            // --- FIX LOGIC START ---
            // We collapse the array into one single associative array if they are related,
            // or iterate carefully. 
            
            // If your request is ALWAYS one variant split into two array items:
            $inputData = [];
            foreach ($request->variants as $v) {
                if (is_array($v)) {
                    $inputData = array_merge($inputData, $v);
                }
            }

            $name = $inputData['name'] ?? null;
            $variantId = $inputData['id'] ?? null;

            if ($name) {
                $data = [
                    'service'              => $serviceCode,
                    'variant_type'         => $variantType,
                    'service_variant_name' => $name,
                ];

                if ($variantId && $variantId > 0) {
                    ServiceVariant::where('id', $variantId)->update($data);
                } else {
                    $prefix = "SRVCVR-";
                    $data['variant_code'] = $prefix . $tokenService->getTokenByCode($prefix);
                    ServiceVariant::create($data);
                }
            }
            // --- FIX LOGIC END ---

            DB::commit();
            return back()->with('success', 'Successfully Saved!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function setServiceVariant(Request $request)
    {

            $serviceCode = $request->serviceCode;
            $variantType = $request->variantType;
            $result = DB::table('service_variants')
                ->where('service', $serviceCode)
                ->where('variant_type', $variantType)
                ->where('is_active', 1)
                ->get();

            return response()->json(['variants' => $result]);
    }
}
