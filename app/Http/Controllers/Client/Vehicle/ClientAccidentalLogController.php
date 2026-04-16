<?php

namespace App\Http\Controllers\Client\Vehicle;

use App\Http\Controllers\Controller;
use App\Models\Client\AccidentLog;
use App\Repositories\Client\AccidentLogRepository;
use App\Repositories\Client\EmployeeRepository;
use App\Repositories\Client\VehicleRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ClientAccidentalLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(AccidentLogRepository $accidentLogRepository)
    {
        $data = $accidentLogRepository->getAccidentalLog(auth()->user()?->customerEmployee?->company);
        return view('client.vehicle.accidental-log.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(VehicleRepository $vehicleRepository, EmployeeRepository $employeeRepository)
    {
        $data = [
            'companyCode'  => auth()->user()?->customerEmployee?->company,
            'vehicleId'    => '',
            'vehicleIdArr' => [],
            'isActiveFlag' => '',
        ];
        $vehicles = $vehicleRepository->getVehicleInfo($data);
        $drivers = $employeeRepository->getEmpPersonalInfo(null, null, 1, auth()->user()?->customerEmployee?->company);
        return view('client.vehicle.accidental-log.create-edit',compact('vehicles','drivers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'vehicleId' => 'required',
            'driverId' => 'required',
            'accidentDate' => 'required|date',
            'accidentTime' => 'required',
            'place' => 'required|string|max:200',
            'affectedAreas' => 'required|string|max:200',
            'accidentFile.*' => 'nullable|file|max:2048',
        ]);

        try {
            DB::beginTransaction();

            // Combine Date + Time
            $dateTime = Carbon::parse(
                $request->accidentDate . ' ' . $request->accidentTime
            )->format('Y-m-d H:i:s');

            $fileNameArr = [];
            $fileOrgNameArr = [];

            // Multiple File Upload
            if ($request->hasFile('accidentFile')) {
                foreach ($request->file('accidentFile') as $file) {
                    if ($file->isValid()) {

                        $fileName = Str::random(20);
                        $extension = $file->getClientOriginalExtension();

                        $storedName = $fileName . '.' . $extension;

                        $file->move(public_path('assets/client/files/accidental-log'), $storedName);

                        $fileNameArr[] = $storedName;
                        $fileOrgNameArr[] = $file->getClientOriginalName();
                    }
                }
            }

            // Insert Data
            AccidentLog::create([
                'vehicle' => $request->vehicleId,
                'driver' => $request->driverId,
                'accident_date_time' => $dateTime,
                'place' => $request->place,
                'vehicle_affected_area' => $request->affectedAreas,
                'remarks' => $request->remarks ?? null,
                'company' => auth()->user()?->customerEmployee?->company, // adjust as needed
                'file_name' => $fileNameArr ? json_encode($fileNameArr) : null,
                'file_original_name' => $fileOrgNameArr ? json_encode($fileOrgNameArr) : null,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Accident log created successfully');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Something went wrong'.$e->getMessage());
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
    public function edit(string $id, AccidentLogRepository $accidentLogRepository, VehicleRepository $vehicleRepository, EmployeeRepository $employeeRepository)
    {
        $data = [
            'companyCode'  => auth()->user()?->customerEmployee?->company,
            'vehicleId'    => '',
            'vehicleIdArr' => [],
            'isActiveFlag' => '',
        ];
        $vehicles = $vehicleRepository->getVehicleInfo($data);
        $drivers = $employeeRepository->getEmpPersonalInfo(null, null, 1, auth()->user()?->customerEmployee?->company);
        $data =  $accidentLogRepository->getAccidentalLog(auth()->user()?->customerEmployee?->company, $id);
        return view('client.vehicle.accidental-log.create-edit',compact('data', 'vehicles','drivers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // Validation
        $request->validate([
            'id' => 'required|exists:accidental_log,id',
            'vehicleId' => 'required',
            'driverId' => 'required',
            'accidentDate' => 'required|date',
            'accidentTime' => 'required',
            'place' => 'required|string|max:200',
            'affectedAreas' => 'required|string|max:200',
            'accidentFile.*' => 'nullable|file|max:2048',
        ]);

        try {
            DB::beginTransaction();

            // Find existing record
            $accident = AccidentLog::findOrFail($request->id);

            // Combine date + time
            $dateTime = Carbon::parse(
                $request->accidentDate . ' ' . $request->accidentTime
            )->format('Y-m-d H:i:s');

            // Existing files
            $fileNameArr = $accident->file_name ? json_decode($accident->file_name, true) : [];
            $fileOrgNameArr = $accident->file_original_name ? json_decode($accident->file_original_name, true) : [];

            // Upload new files
            if ($request->hasFile('accidentFile')) {
                foreach ($request->file('accidentFile') as $file) {
                    if ($file->isValid()) {

                        $fileName = Str::random(20);
                        $extension = $file->getClientOriginalExtension();
                        $storedName = $fileName . '.' . $extension;

                        $file->move(public_path('assets/client/files/accidental-log'), $storedName);

                        $fileNameArr[] = $storedName;
                        $fileOrgNameArr[] = $file->getClientOriginalName();
                    }
                }
            }

            // Update data
            $accident->update([
                'vehicle' => $request->vehicleId,
                'driver' => $request->driverId,
                'accident_date_time' => $dateTime,
                'place' => $request->place,
                'vehicle_affected_area' => $request->affectedAreas,
                'remarks' => $request->remarks,
                'company' => auth()->user()?->customerEmployee?->company,
                'file_name' => $fileNameArr ? json_encode($fileNameArr) : null,
                'file_original_name' => $fileOrgNameArr ? json_encode($fileOrgNameArr) : null,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Accident updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Something went wrong'.$e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $companyCode = auth()->user()?->customerEmployee?->company;

        // Get record
        $log = AccidentLog::where('id', $id)
            ->where('company', $companyCode)
            ->first();

        if (!$log) {
            return redirect()->back()->with('error', 'Data not found');
        }

        // Decode file list
        $fileLists = $log->file_name ? json_decode($log->file_name, true) : [];

        // Delete physical files
        foreach ($fileLists as $file) {
            $filePath = public_path('assets/client/files/accidental-log/' . $file);

            if (File::exists($filePath)) {
                File::delete($filePath);
            }
        }

        // Delete DB record
        $log->delete();

        return response()->json([
            'status' => true,
            'message' => 'Accidental log deleted successfully'
        ]);
    }

    public function deleteAccidentalLogFile(Request $request)
    {
        $fileName = $request->fileNameHidden;
        $id = $request->id;

        $companyCode = auth()->user()?->customerEmployee?->company;

        $accident = AccidentLog::where('id', $id)
            ->where('company', $companyCode)
            ->first();

        if (!$accident) {
            return redirect()->back()->with('error', 'Data not found');
        }

        // Delete physical file
        $filePath = public_path('assets/client/files/accidental-log/' . $fileName);

        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Decode arrays
        $fileArr = $accident->file_name ? json_decode($accident->file_name, true) : [];
        $orgArr  = $accident->file_original_name ? json_decode($accident->file_original_name, true) : [];

        $newFileArr = [];
        $newOrgArr = [];

        foreach ($fileArr as $index => $file) {
            if ($file != $fileName) {
                $newFileArr[] = $file;
                $newOrgArr[] = $orgArr[$index] ?? '';
            }
        }

        // Update DB
        $accident->update([
            'file_name' => !empty($newFileArr) ? json_encode($newFileArr) : null,
            'file_original_name' => !empty($newOrgArr) ? json_encode($newOrgArr) : null,
            'updated_by' => auth()->id(),
            'updated_dt_tm' => now(),
        ]);

        return redirect()->back()->with('success', 'File deleted successfully');
    }
}
