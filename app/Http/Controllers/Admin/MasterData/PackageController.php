<?php

namespace App\Http\Controllers\Admin\MasterData;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MasterData\PackageRequest;
use App\Models\MetaData\Package;
use App\Services\TokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Package::select([
            'id',
            'package_name', 
            'package_details->user->count as max_user', 
            'package_details->vehicle->count as max_vehicle'
        ])->get();
        return view('admin.master-data.package.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.master-data.package.create-edit'); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PackageRequest $request, TokenService $tokenService)
    {
        $data = $request->validated();

        $packageDetails = [
            'user'    => ['count' => $data['maxUser']],
            'vehicle' => ['count' => $data['maxVehicle']],
        ];

        $prefix = "PCK-";
        $packageCode = $prefix . $tokenService->getTokenByCode($prefix);

        DB::beginTransaction();
        try {
            Package::create([
                'package_code'    => $packageCode,
                'package_name'    => $data['package_name'],
                'package_details' => json_encode($packageDetails),
            ]);

            DB::commit();

            return redirect()
                ->route('admin.module.master-data.package.index')
                ->with('success', __('Package created successfully!'));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Package Creation Failed", [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'payload' => $data
            ]);

            return back()
                ->withInput()
                ->with('error', 'Something went wrong! Please try again.');
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
        $data = Package::select([
            'id',
            'package_name', 
            'package_details->user->count as max_user', 
            'package_details->vehicle->count as max_vehicle'
        ])->findOrFail($id);
        return view('admin.master-data.package.create-edit',compact('data')); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PackageRequest $request, $id)
    {
        $package = Package::findOrFail($id);

        $validated = $request->validated();

        $packageDetails = [
            'user'    => ['count' => $validated['maxUser']],
            'vehicle' => ['count' => $validated['maxVehicle']],
        ];

        try {
            DB::beginTransaction();

            $package->update([
                'package_name'    => $validated['package_name'],
                'package_details' => $packageDetails, 
            ]);

            DB::commit();

            return redirect()
                ->route('admin.module.master-data.package.index')
                ->with('success', __('Package updated successfully!'));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Package Update Failed", [
                'id'    => $id,
                'error' => $e->getMessage(),
                'user'  => auth()->id()
            ]);

            return back()
                ->withInput()
                ->with('error', __('Something went wrong. Please try again.'));
        }
    }

     /* Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
