<?php

namespace App\Http\Controllers\Client\Vehicle;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\Vehicle\ClientVehicleRequest;
use App\Models\Client\Vehicle;
use App\Repositories\Client\VehicleRepository;
use App\Repositories\CommonRepository;
use App\Services\TokenService;
use Illuminate\Http\Request;

class ClientVehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(VehicleRepository $vehicleRepository)
    {
        $data = [
            'companyCode'  => auth()->user()?->customerEmployee?->company,
            'vehicleId'    => '',
            'vehicleIdArr' => [],
            'isActiveFlag' => '',
        ];
        $data = $vehicleRepository->getVehicleInfo($data);
        return view('client.vehicle.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(CommonRepository $commonRepository)
    {
        $commonTableElementArr = array('type' => 'vehicle_type');
        $vehicleTypes = $commonRepository->getCommonTableElement($commonTableElementArr);
        $commonTableElementArr = array('type' => 'vehicle_brand');
        $brands = $commonRepository->getCommonTableElement($commonTableElementArr);
        $commonTableElementArr = array('type' => 'v_brand_model');
        $brandModels = array('brandModelsData' => $commonRepository->getCommonTableElement($commonTableElementArr));

        $commonTableElementArr = array('type' => 'vehicle_class');
        $vehicleClasses = $commonRepository->getCommonTableElement($commonTableElementArr);
        $commonTableElementArr = array('type' => 'vehicle_color');
        $colors = $commonRepository->getCommonTableElement($commonTableElementArr);
        $commonTableElementArr = array('type' => 'vehicle_group','company'=> auth()->user()?->customerEmployee?->company);
        $groups = $commonRepository->getCommonTableElement($commonTableElementArr);

        return view('client.vehicle.create-edit',compact('vehicleTypes','brands','vehicleClasses','colors','groups','brandModels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClientVehicleRequest $request, TokenService $tokenService)
    {
        $validated = $request->validated();
        $validated['company'] = auth()->user()?->customerEmployee?->company;  
        $validated['vehicle_id'] = config('constants.VEHICLE_CODE') . $tokenService->getTokenByCode(config('constants.VEHICLE_CODE'));        
        try {
            Vehicle::create($validated);

            return redirect()->route('client.vehicle.index')
                ->with('success', 'Vehicle created successfully');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Something went wrong'. $e->getMessage());
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
    public function edit(Vehicle $vehicle, CommonRepository $commonRepository)
    {
        $data = $vehicle;
        $commonTableElementArr = array('type' => 'vehicle_type');
        $vehicleTypes = $commonRepository->getCommonTableElement($commonTableElementArr);
        $commonTableElementArr = array('type' => 'vehicle_brand');
        $brands = $commonRepository->getCommonTableElement($commonTableElementArr);
        $commonTableElementArr = array('type' => 'v_brand_model');
        $brandModels = array('brandModelsData' => $commonRepository->getCommonTableElement($commonTableElementArr));

        $commonTableElementArr = array('type' => 'vehicle_class');
        $vehicleClasses = $commonRepository->getCommonTableElement($commonTableElementArr);
        $commonTableElementArr = array('type' => 'vehicle_color');
        $colors = $commonRepository->getCommonTableElement($commonTableElementArr);
        $commonTableElementArr = array('type' => 'vehicle_group','company'=> auth()->user()?->customerEmployee?->company);
        $groups = $commonRepository->getCommonTableElement($commonTableElementArr);

        return view('client.vehicle.create-edit',compact('data','vehicleTypes','brands','vehicleClasses','colors','groups','brandModels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClientVehicleRequest $request, Vehicle $vehicle)
    {
        try {
            $vehicle->update($request->validated());

            return redirect()->route('vehicle.index')
                ->with('success', 'Vehicle updated successfully');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Something went wrong');
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
