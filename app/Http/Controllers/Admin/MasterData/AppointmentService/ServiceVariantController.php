<?php

namespace App\Http\Controllers\Admin\MasterData\AppointmentService;

use App\Http\Controllers\Controller;
use App\Models\Admin\MasterData\Service;
use App\Models\Admin\MasterData\ServiceVariant;
use DB;
use Illuminate\Http\Request;

class ServiceVariantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = ServiceVariant::get();
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
    public function store(Request $request)
    {
        //
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
