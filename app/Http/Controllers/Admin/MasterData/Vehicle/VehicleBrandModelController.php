<?php

namespace App\Http\Controllers\Admin\MasterData\Vehicle;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MasterData\Vehicle\VehicleBrandModelRequest;
use App\Http\Requests\Admin\MasterData\Vehicle\VehicleTypeRequest;
use App\Models\CommonTable;
use App\Repositories\MasterData\VehicleRepository;
use App\Services\TokenService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VehicleBrandModelController extends Controller
{/**
     * Display a listing of the resource.
     */
    public function index(VehicleRepository $vehicleRepository)
    {
        $data = $vehicleRepository->getVehicleBrandModel();
        return view('admin.master-data.vehicle.vehicle-brand-model.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vehicleBrand = CommonTable::where(['type'=>'vehicle_brand','is_active'=>1])
        ->oldest('element_order')
        ->oldest('element')
        ->get();
        return view('admin.master-data.vehicle.vehicle-brand-model.create-edit',compact('vehicleBrand')); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VehicleBrandModelRequest $request, TokenService $tokenService)
    {
        $validated = $request->validated();
        $prefix = "VBMDL-";
        $element_code  = $prefix . $tokenService->getTokenByCode('COMMON-');
        try {
            DB::beginTransaction();

            CommonTable::create([
                'element'       => $validated['element'],
                'depend_on_element' => $validated['depend_on_element'],
                'element_code'  => $element_code,
                'type' => 'v_brand_model',
                'element_order' => $validated['element_order'] ?? 0,
                'is_active'     => $validated['is_active'],
            ]);

            DB::commit();

            return redirect()
                ->route('admin.modules.master-data.vehicle-brand-model.index')
                ->with('success', 'Vehicle Brand Model created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Vehicle Brand Model Store Error: ' . $e->getMessage());
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
        $data = $vehicleRepository->getVehicleBrandModel($id);
        return view('admin.master-data.vehicle.vehicle-brand-model.show',compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id, VehicleRepository $vehicleRepository)
    {
        $data = $vehicleRepository->getVehicleBrandModel($id);
        $vehicleBrand = CommonTable::where(['type'=>'vehicle_brand','is_active'=>1])
        ->oldest('element_order')
        ->oldest('element')
        ->get();
        return view('admin.master-data.vehicle.vehicle-brand-model.create-edit',compact('data','vehicleBrand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VehicleBrandModelRequest $request, $id)
    {
        $vehicleType = CommonTable::where('type', 'v_brand_model')->find($id);

        if (!$vehicleType) {
            return redirect()
                ->route('admin.modules.master-data.vehicle-brand-model.index')
                ->with('error', 'Vehicle Brand Model not found!');
        }
        $validated = $request->validated();
        try {
            DB::beginTransaction();

            $vehicleType->update([
                'element'       => $validated['element'],
                'depend_on_element' => $validated['depend_on_element'],
                'element_order' => $validated['element_order'],
                'is_active'     => $validated['is_active'], 
            ]);

            DB::commit();

            return redirect()
                ->route('admin.modules.master-data.vehicle-brand-model.index')
                ->with('success', 'Vehicle Brand Model updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Vehicle Brand Model Update Failed (ID: $id): " . $e->getMessage());

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
