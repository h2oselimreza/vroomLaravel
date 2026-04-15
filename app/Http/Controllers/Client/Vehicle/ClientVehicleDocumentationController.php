<?php

namespace App\Http\Controllers\Client\Vehicle;

use App\Http\Controllers\Controller;
use App\Models\Client\Vehicle;
use App\Models\Client\VehicleFile;
use App\Repositories\Client\VehicleRepository;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ClientVehicleDocumentationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function edit(string $vehicleId, VehicleRepository $vehicleRepository)
    {
        $data = [
            'companyCode'  => auth()->user()?->customerEmployee?->company,
            'vehicleId'    => $vehicleId,
            'vehicleIdArr' => [],
            'isActiveFlag' => 1,
            'bulkFlag' => 0
        ];
        $vehicleDetails = $vehicleRepository->getVehicleInfo($data);
        $vehicleFiles = $vehicleRepository->getVehicleFiles($data);
        return view('client.vehicle.vehicle-doc-create-edit', compact('vehicleDetails','vehicleFiles','vehicleId'));
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

    public function updateRegistration(Request $request)
    {

        $vehicleId = $request->vehicleId;

        // =========================
        // VEHICLE EXIST CHECK
        // =========================
        $vehicle = Vehicle::where('vehicle_id', $vehicleId)
            ->where('company', auth()->user()?->customerEmployee?->company)
            ->first();

        if (!$vehicle) {
            return redirect()->route('client.documentation.edit',$request->vehicleId)
                ->with('error', 'Vehicle not exist');
        }

        DB::beginTransaction();

        try {

            $insertFiles = [];

            if ($request->hasFile('vehicleFile')) {

                foreach ($request->file('vehicleFile') as $file) {

                    if ($file) {

                        $fileName = reference_no(); // your helper

                        $extension = $file->getClientOriginalExtension();
                        $finalFileName = $fileName . '_' . now()->format('Ymd_His') . '.' . $extension;

                        $file->move(public_path('assets/client/vehicle/files'), $finalFileName);

                        $insertFiles[] = [
                            'vehicle'       => $vehicleId,
                            'original_name' => $file->getClientOriginalName(),
                            'file_name'     => $finalFileName,
                            'file_type'     => config('constants.REGISTRATION_FILE'),
                            'is_active'     => 1,
                            'created_by'    => auth()->user()->name ?? 'system',
                            'created_dt_tm' => now(),
                            'updated_by'    => auth()->user()->name ?? 'system',
                            'updated_dt_tm' => now(),
                        ];
                    }
                }
            }

            // =========================
            // REGISTRATION UPDATE DATA
            // =========================
            $registrationArr = [
                'registration_date' => $request->registrationDate
                ? optional(Carbon::parse($request->registrationDate))->format('Y-m-d')
                : null,

                'seat_capacity' => $request->seatCapacity,
            ];

            // =========================
            // INSERT FILES
            // =========================
            if (!empty($insertFiles)) {
                VehicleFile::insert($insertFiles);
            }

            // =========================
            // UPDATE VEHICLE
            // =========================
            $vehicle->update($registrationArr);

            DB::commit();

            return redirect()->route('client.documentation.edit',$request->vehicleId)
                ->with('success', 'Updated successfully');

        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()->route('client.documentation.edit',$request->vehicleId)
                ->with('error', 'error'.$e->getMessage());

        }
    }


    public function removeFile(Request $request)
    {

        $vehicleFileId = (int) $request->vehicleFileId;
        $vehicleId     = (string) $request->vehicleId;

        // =========================
        // VEHICLE EXIST CHECK
        // =========================
        $vehicle = Vehicle::where('vehicle_id', $vehicleId)
            ->where('company', auth()->user()?->customerEmployee?->company)
            ->first();

        if (!$vehicle) {
            return response()->json(['status' => 3, 'message' => 'Vehicle not found']);
        }

        // =========================
        // FILE DELETE
        // =========================
        if ($vehicleFileId) {

            $file = VehicleFile::find($vehicleFileId);

            if ($file) {

                // Delete physical file
                $filePath = public_path('assets/client/vehicle/files/' . $file->file_name);

                if (File::exists($filePath)) {
                    File::delete($filePath);
                }

                // Delete DB record
                $file->delete();

                return response()->json(['status' => 1, 'message' => 'Deleted successfully']);
            }

            return response()->json(['status' => 2, 'message' => 'File not found']);
        }

        return response()->json(['status' => 2, 'message' => 'Invalid file id']);
    }
}
