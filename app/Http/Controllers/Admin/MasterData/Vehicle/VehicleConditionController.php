<?php

namespace App\Http\Controllers\Admin\MasterData\Vehicle;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MasterData\Vehicle\VehicleTypeRequest;
use App\Models\CommonTable;
use App\Repositories\MasterData\VehicleRepository;
use App\Services\TokenService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VehicleConditionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(VehicleRepository $vehicleRepository)
    {
        $data = $vehicleRepository->getVehicleCondition();
        return view('admin.master-data.vehicle.vehicle-condition.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.master-data.vehicle.vehicle-condition.create-edit'); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VehicleTypeRequest $request, TokenService $tokenService)
    {
        $validated = $request->validated();
        $prefix = "VCLR-";
        $element_code  = $prefix . $tokenService->getTokenByCode('COMMON-');
        try {
            DB::beginTransaction();

            CommonTable::create([
                'element'       => $validated['element'],
                'element_code'  => $element_code,
                'type' => 'vehicle_cndtn',
                'element_order' => $validated['element_order'] ?? 0,
                'is_active'     => $validated['is_active'],
            ]);

            DB::commit();

            return redirect()
                ->route('admin.modules.master-data.vehicle-condition.index')
                ->with('success', 'Vehicle condition created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Vehicle condition Store Error: ' . $e->getMessage());
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
        $data = $vehicleRepository->getVehicleCondition($id);
        return view('admin.master-data.vehicle.vehicle-condition.show',compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id, VehicleRepository $vehicleRepository)
    {
        $data = $vehicleRepository->getVehicleCondition($id);
        return view('admin.master-data.vehicle.vehicle-condition.create-edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VehicleTypeRequest $request, $id)
    {
        $vehicleType = CommonTable::where('type', 'vehicle_cndtn')->find($id);

        if (!$vehicleType) {
            return redirect()
                ->route('admin.modules.master-data.vehicle-condition.index')
                ->with('error', 'Vehicle condition not found!');
        }

        $validated = $request->validated();
        try {
            DB::beginTransaction();

            $vehicleType->update([
                'element'       => $validated['element'],
                'element_order' => $validated['element_order'],
                'is_active'     => $validated['is_active'], 
            ]);

            DB::commit();

            return redirect()
                ->route('admin.modules.master-data.vehicle-condition.index')
                ->with('success', 'Vehicle condition updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Vehicle condition Update Failed (ID: $id): " . $e->getMessage());

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
