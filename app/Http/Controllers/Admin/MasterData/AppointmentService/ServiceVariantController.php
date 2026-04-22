<?php

namespace App\Http\Controllers\Admin\MasterData\AppointmentService;

use App\Http\Controllers\Controller;
use App\Models\Admin\MasterData\Service;
use App\Models\Admin\MasterData\ServiceVariant;
use App\Services\TokenService;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;

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
        $request->validate([
            'serviceCode' => 'required|string',
            'variantType'  => 'required|string',
            'variants'     => 'required',
        ]);
    
        $variants = $request->variants;
    
        if (is_string($variants)) {
            $variants = json_decode($variants, true) ?? [];
        }
    
        if (!is_array($variants) || empty($variants)) {
            return response('0');
        }
    
        try {
            FacadesDB::beginTransaction();
    
            $serviceCode = $request->serviceCode;
            $variantType = $request->variantType;
    
            if ($request->filled('deleteVariant')) {
                $idsToDelete = array_filter(explode(',', $request->deleteVariant));
                if (!empty($idsToDelete)) {
                    ServiceVariant::whereIn('id', $idsToDelete)->delete();
                }
            }
    
            foreach ($variants as $variant) {
                $name = trim($variant['name'] ?? '');
                $variantId = (int) ($variant['id'] ?? 0);
    
                if ($name === '') {
                    continue;
                }
    
                $data = [
                    'service'               => $serviceCode,
                    'variant_type'          => $variantType,
                    'service_variant_name'   => $name,
                ];
    
                if ($variantId > 0) {
                    ServiceVariant::where('id', $variantId)->update($data);
                } else {
                    $prefix = 'SRVCVR-';
                    $data['variant_code'] = $prefix . $tokenService->getTokenByCode($prefix);
                    ServiceVariant::create($data);
                }
            }
    
            FacadesDB::commit();
            return response('1');
    
        } catch (\Throwable $e) {
            FacadesDB::rollBack();
            return response('0');
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
            $result = FacadesDB::table('service_variants')
                ->where('service', $serviceCode)
                ->where('variant_type', $variantType)
                ->where('is_active', 1)
                ->get();

            return response()->json(['variants' => $result]);
    }
}
