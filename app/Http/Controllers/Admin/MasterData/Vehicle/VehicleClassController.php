<?php

namespace App\Http\Controllers\Admin\MasterData\Vehicle;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MasterData\Vehicle\VehicleClassRequest;
use App\Models\CommonTable;
use App\Repositories\MasterData\VehicleRepository;
use App\Services\TokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VehicleClassController extends Controller
{
    public function index(VehicleRepository $vehicleRepository)
    {
        $data = $vehicleRepository->getVehicleClass();
        return view('admin.master-data.vehicle.vehicle-class.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.master-data.vehicle.vehicle-class.create-edit'); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VehicleClassRequest $request, TokenService $tokenService)
    {
        $validated = $request->validated();
        $prefix = "VCLSS-";
        $element_code  = $prefix . $tokenService->getTokenByCode('COMMON-');
        try {
            DB::beginTransaction();

            CommonTable::create([
                'element'       => $validated['element'],
                'element_code'  => $element_code,
                'type' => 'vehicle_class',
                'element_order' => $validated['element_order'] ?? 0,
                'is_active'     => $validated['is_active'],
                'element_bn' => $validated['element_bn'],
            ]);

            DB::commit();

            return redirect()
                ->route('admin.modules.master-data.vehicle-class.index')
                ->with('success', 'Vehicle class created successfully!');

        } catch (\Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            Log::error('Vehicle class Store Error: ' . $e->getMessage());
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
        $data = $vehicleRepository->getVehicleClass($id);
        return view('admin.master-data.vehicle.vehicle-class.show',compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id, VehicleRepository $vehicleRepository)
    {
        $data = $vehicleRepository->getVehicleClass($id);
        return view('admin.master-data.vehicle.vehicle-class.create-edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VehicleClassRequest $request, $id)
    {
        $vehicleType = CommonTable::where('type', 'vehicle_class')->find($id);

        if (!$vehicleType) {
            return redirect()
                ->route('admin.modules.master-data.vehicle-class.index')
                ->with('error', 'Vehicle Class not found!');
        }

        $validated = $request->validated();
        try {
            DB::beginTransaction();

            $vehicleType->update([
                'element'       => $validated['element'],
                'element_order' => $validated['element_order'],
                'is_active'     => $validated['is_active'],
                'element_bn' => $validated['element_bn'], 
            ]);

            DB::commit();

            return redirect()
                ->route('admin.modules.master-data.vehicle-class.index')
                ->with('success', 'Vehicle class updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Vehicle class Update Failed (ID: $id): " . $e->getMessage());

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
