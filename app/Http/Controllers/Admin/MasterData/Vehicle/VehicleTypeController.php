<?php

namespace App\Http\Controllers\Admin\MasterData\Vehicle;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MasterData\Vehicle\VehicleTypeRequest;
use App\Models\CommonTable;
use App\Repositories\MasterData\VehicleRepository;
use App\Services\TokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VehicleTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(VehicleRepository $vehicleRepository)
    {
        $data = $vehicleRepository->getVehicleType();
        return view('admin.master-data.vehicle.vehicle-type.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.master-data.vehicle.vehicle-type.create-edit'); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VehicleTypeRequest $request, TokenService $tokenService)
    {
        $validated = $request->validated();
        $prefix = "VTYP-";
        $element_code  = $prefix . $tokenService->getTokenByCode('COMMON-');
        try {
            DB::beginTransaction();

            CommonTable::create([
                'element'       => $validated['element'],
                'element_code'  => $element_code,
                'type' => 'vehicle_type',
                'element_order' => $validated['element_order'] ?? 0,
                'is_active'     => $validated['is_active'],
            ]);

            DB::commit();

            return redirect()
                ->route('admin.modules.master-data.vehicle-type.index')
                ->with('success', 'Vehicle Type created successfully!');

        } catch (\Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            \Log::error('Vehicle Type Store Error: ' . $e->getMessage());
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
        $data = $vehicleRepository->getVehicleType($id);
        return view('admin.master-data.vehicle.vehicle-type.show',compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id, VehicleRepository $vehicleRepository)
    {
        $data = $vehicleRepository->getVehicleType($id);
        return view('admin.master-data.vehicle.vehicle-type.create-edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VehicleTypeRequest $request, $id)
    {
        $vehicleType = CommonTable::where('type', 'vehicle_type')->find($id);

        if (!$vehicleType) {
            return redirect()
                ->route('admin.modules.master-data.vehicle-type.index')
                ->with('error', 'Vehicle Type not found!');
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
                ->route('admin.modules.master-data.vehicle-type.index')
                ->with('success', 'Vehicle Type updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Vehicle Type Update Failed (ID: $id): " . $e->getMessage());

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
