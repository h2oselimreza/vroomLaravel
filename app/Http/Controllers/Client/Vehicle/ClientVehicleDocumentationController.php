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
        $data = DB::table('vehicles')->where('vehicle_id', $vehicleId)->first();
        $arr = [
            'companyCode'  => auth()->user()?->customerEmployee?->company,
            'vehicleId'    => $vehicleId,
            'vehicleIdArr' => [],
            'isActiveFlag' => 1,
            'bulkFlag' => 0
        ];
        $vehicleDetails = $vehicleRepository->getVehicleInfo($arr);
        $vehicleFiles = $vehicleRepository->getVehicleFiles($arr);
        return view('client.vehicle.vehicle-doc-create-edit', compact('data','vehicleDetails','vehicleFiles','vehicleId'));
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

    public function updateFitness(Request $request)
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

            // =========================
            // FILE UPLOAD
            // =========================
            if ($request->hasFile('vehicleFile')) {

                foreach ($request->file('vehicleFile') as $file) {

                    if ($file && $file->isValid()) {

                        $fileName = reference_no();
                        $extension = $file->getClientOriginalExtension();

                        // SAFE filename (no colon, no spaces)
                        $finalFileName = $fileName . '_' . now()->format('Ymd_His') . '.' . $extension;

                        $file->move(public_path('assets/client/vehicle/files'), $finalFileName);

                        $insertFiles[] = [
                            'vehicle'       => $vehicleId,
                            'original_name' => $file->getClientOriginalName(),
                            'file_name'     => $finalFileName,
                            'file_type'     => config('constants.FITNESS_FILE'),
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
            // FITNESS DATA UPDATE
            // =========================
            $fitnessData = [
                'fitness_issue_date' => $request->fitnessIssueDate
                    ? Carbon::parse($request->fitnessIssueDate)->format('Y-m-d')
                    : null,

                'fitness_validity_from_date' => $request->validityFromDate
                    ? Carbon::parse($request->validityFromDate)->format('Y-m-d')
                    : null,

                'fitness_validity_todate' => $request->validityToDate
                    ? Carbon::parse($request->validityToDate)->format('Y-m-d')
                    : null,

                'fitness_renew_fee' => $request->fitnessRenewFee ?? null,
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
            $vehicle->update($fitnessData);

            DB::commit();

            return redirect()
                ->route('client.documentation.edit', $vehicleId)
                ->with('success', 'Fitness updated successfully');

        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()
                ->route('client.documentation.edit', $vehicleId)
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function updateTaxToken(Request $request)
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

            // =========================
            // FILE UPLOAD
            // =========================
            if ($request->hasFile('vehicleFile')) {

                foreach ($request->file('vehicleFile') as $file) {

                    if ($file && $file->isValid()) {

                        $fileName = reference_no();
                        $extension = $file->getClientOriginalExtension();

                        // Safe filename
                        $finalFileName = $fileName . '_' . now()->format('Ymd_His') . '.' . $extension;

                        $file->move(public_path('assets/client/vehicle/files'), $finalFileName);

                        $insertFiles[] = [
                            'vehicle'       => $vehicleId,
                            'original_name' => $file->getClientOriginalName(),
                            'file_name'     => $finalFileName,
                            'file_type'     => config('constants.TAXTOKEN_FILE'),
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
            // TAX TOKEN DATA UPDATE
            // =========================
            $taxData = [
                'tax_fee_issue_date' => $request->taxIssueDate
                    ? Carbon::parse($request->taxIssueDate)->format('Y-m-d')
                    : null,

                'tax_period_from_date' => $request->texPeriodFromDate
                    ? Carbon::parse($request->texPeriodFromDate)->format('Y-m-d')
                    : null,

                'tax_period_to_date' => $request->texPeriodToDate
                    ? Carbon::parse($request->texPeriodToDate)->format('Y-m-d')
                    : null,

                'tax_fee' => $request->taxFee ?? null,
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
            $vehicle->update($taxData);

            DB::commit();

            return redirect()
                ->route('client.documentation.edit', $vehicleId)
                ->with('success', 'Tax token updated successfully');

        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()
                ->route('client.documentation.edit', $vehicleId)
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function updateInsurance(Request $request)
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

            // =========================
            // FILE UPLOAD
            // =========================
            if ($request->hasFile('vehicleFile')) {

                foreach ($request->file('vehicleFile') as $file) {

                    if ($file && $file->isValid()) {

                        $fileName  = reference_no();
                        $extension = $file->getClientOriginalExtension();

                        // Safe filename (no colon, no space)
                        $finalFileName = $fileName . '_' . now()->format('Ymd_His') . '.' . $extension;

                        $file->move(public_path('assets/client/vehicle/files'), $finalFileName);

                        $insertFiles[] = [
                            'vehicle'       => $vehicleId,
                            'original_name' => $file->getClientOriginalName(),
                            'file_name'     => $finalFileName,
                            'file_type'     => config('constants.INSURANCE_FILE'),
                            'is_active'     => 1,
                            'created_by'    => auth()->user()->user_id ?? 'system',
                            'created_dt_tm' => now(),
                            'updated_by'    => auth()->user()->user_id ?? 'system',
                            'updated_dt_tm' => now(),
                        ];
                    }
                }
            }

            // =========================
            // INSURANCE DATA UPDATE
            // =========================
            $insuranceData = [
                'insurance_issue_date' => $request->insuranceIssueDate
                    ? Carbon::parse($request->insuranceIssueDate)->format('Y-m-d')
                    : null,

                'insurance_pre_amount' => $request->preAmount ?? null,
                'insurance_nature'     => $request->insuranceNature ?? null,
                'insurance_company'    => $request->companyName ?? null,
                'insurance_contact_person' => $request->contactPerson ?? null,
                'insurance_mobile'     => $request->mobileNo ?? null,

                'insurance_form_date' => $request->validityFromDate
                    ? Carbon::parse($request->validityFromDate)->format('Y-m-d')
                    : null,

                'insurance_to_date' => $request->validityToDate
                    ? Carbon::parse($request->validityToDate)->format('Y-m-d')
                    : null,
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
            $vehicle->update($insuranceData);

            DB::commit();

            return redirect()
                ->route('client.documentation.edit', $vehicleId)
                ->with('success', 'Insurance updated successfully');

        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()
                ->route('client.documentation.edit', $vehicleId)
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function updateRoutePermit(Request $request)
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

            // =========================
            // FILE UPLOAD
            // =========================
            if ($request->hasFile('vehicleFile')) {

                foreach ($request->file('vehicleFile') as $file) {

                    if ($file && $file->isValid()) {

                        $fileName = reference_no();
                        $extension = $file->getClientOriginalExtension();
                        $finalFileName = $fileName . '.' . $extension;

                        // Store file (recommended way)
                        $file->move(public_path('assets/files/vehicle'), $finalFileName);

                        $insertFiles[] = [
                            'vehicle'       => $vehicleId,
                            'original_name' => $file->getClientOriginalName(),
                            'file_name'     => $finalFileName,
                            'file_type'     => config('constants.ROUTE_FILE'),
                            'is_active'     => 1,
                            'created_by'    => auth()->user()->user_id ?? 'system',
                            'created_dt_tm' => now(),
                            'updated_by'    => auth()->user()->user_id ?? 'system',
                            'updated_dt_tm' => now(),
                        ];
                    }
                }
            }

            // =========================
            // DATA PREPARATION
            // =========================
            $data = [
                'route_issue_date' => $request->routeIssueDate
                    ? Carbon::parse($request->routeIssueDate)->format('Y-m-d')
                    : null,

                'permit_no' => $request->permitNo,
                'permit_fee' => $request->permitFee,
                'route_area' => $request->routeArea,
                'tyre_number' => $request->tyreNumber ?? 0,

                'route_form_date' => $request->routeValidityFromDate
                    ? Carbon::parse($request->routeValidityFromDate)->format('Y-m-d')
                    : null,

                'route_to_date' => $request->routeValidityToDate
                    ? Carbon::parse($request->routeValidityToDate)->format('Y-m-d')
                    : null,
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
            $vehicle->update($data);

            DB::commit();

            return redirect()
                ->route('client.documentation.edit', $vehicleId)
                ->with('success', 'Route Permit updated successfully');

        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()
                ->route('client.documentation.edit', $vehicleId)
                ->with('error', $e->getMessage());
        }
    }

    public function updateOtherInfo(Request $request)
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

            // =========================
            // FILE UPLOAD
            // =========================
            if ($request->hasFile('vehicleFile')) {

                foreach ($request->file('vehicleFile') as $file) {

                    if ($file && $file->isValid()) {

                        $fileName = reference_no();
                        $extension = $file->getClientOriginalExtension();
                        $finalFileName = $fileName . '.' . $extension;

                        // Move file (make sure folder exists)
                        $file->move(public_path('assets/files/vehicle'), $finalFileName);

                        $insertFiles[] = [
                            'vehicle'       => $vehicleId,
                            'original_name' => $file->getClientOriginalName(),
                            'file_name'     => $finalFileName,
                            'file_type'     => config('constants.OTHER_INFO_FILE'),
                            'is_active'     => 1,
                            'created_by'    => auth()->user()->user_id ?? 'system',
                            'created_dt_tm' => now(),
                            'updated_by'    => auth()->user()->user_id ?? 'system',
                            'updated_dt_tm' => now(),
                        ];
                    }
                }
            }

            // =========================
            // DATA PREPARATION
            // =========================
            $data = [
                'purchase_date' => $request->otherPurchaseDate
                    ? Carbon::parse($request->otherPurchaseDate)->format('Y-m-d')
                    : null,

                'purchase_cost' => $request->otherPurchaseCost ?? 0.00,
                'dpy'           => $request->otherDpy ?? 0.00,
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
            $vehicle->update($data);

            DB::commit();

            return redirect()
                ->route('client.documentation.edit', $vehicleId)
                ->with('success', 'Other information updated successfully');

        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()
                ->route('client.documentation.edit', $vehicleId)
                ->with('error', $e->getMessage());
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
