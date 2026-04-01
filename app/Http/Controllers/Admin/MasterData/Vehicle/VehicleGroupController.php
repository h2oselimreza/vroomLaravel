<?php

namespace App\Http\Controllers\Admin\MasterData\Vehicle;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MasterData\Vehicle\VehicleGroupRequest;
use App\Models\CommonTable;
use App\Models\CorporateCompany;
use App\Repositories\MasterData\VehicleRepository;
use App\Services\TokenService;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VehicleGroupController extends Controller
{
     /* Display a listing of the resource.
     */
    public function index(VehicleRepository $vehicleRepository)
    {
        $data = $vehicleRepository->getVehicleGroup();
        return view('admin.master-data.vehicle.vehicle-group.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $company = CorporateCompany::where(['is_active'=>1])
        ->oldest('title')
        ->get();
        return view('admin.master-data.vehicle.vehicle-group.create-edit',compact('company')); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VehicleGroupRequest $request, TokenService $tokenService)
    {
        $validated = $request->validated();
        $prefix = "VGRP-";
        $element_code  = $prefix . $tokenService->getTokenByCode('COMMON-');
        try {
            DB::beginTransaction();

            CommonTable::create([
                'element'       => $validated['element'],
                'company' => $validated['company'],
                'element_code'  => $element_code,
                'type' => 'vehicle_group',
                'element_order' => $validated['element_order'] ?? 0,
                'is_active'     => $validated['is_active'],
            ]);

            DB::commit();

            return redirect()
                ->route('admin.modules.master-data.vehicle-group.index')
                ->with('success', 'Vehicle Group created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Vehicle Group Store Error: ' . $e->getMessage());
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Something went wrong! Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, VehicleRepository $vehicleRepository)
    {
        $data = $vehicleRepository->getVehicleGroup($id);
        return view('admin.master-data.vehicle.vehicle-group.show',compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id, VehicleRepository $vehicleRepository)
    {
        $data = $vehicleRepository->getVehicleGroup($id);
        $company = CorporateCompany::where(['is_active'=>1])
        ->oldest('title')
        ->get();
        return view('admin.master-data.vehicle.vehicle-group.create-edit',compact('data','company'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VehicleGroupRequest $request, $id)
    {
        $vehicleType = CommonTable::where('type', 'vehicle_group')->find($id);
        if (!$vehicleType) {
            return redirect()
                ->route('admin.modules.master-data.vehicle-group.index')
                ->with('error', 'Vehicle group not found!');
        }
        $validated = $request->validated();
        try {
            DB::beginTransaction();

            $vehicleType->update([
                'element'       => $validated['element'],
                'company' => $validated['company'],
                'element_order' => $validated['element_order'],
                'is_active'     => $validated['is_active'], 
            ]);

            DB::commit();

            return redirect()
                ->route('admin.modules.master-data.vehicle-group.index')
                ->with('success', 'Vehicle Group updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Vehicle Group Update Failed (ID: $id): " . $e->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Something went wrong while updating. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
