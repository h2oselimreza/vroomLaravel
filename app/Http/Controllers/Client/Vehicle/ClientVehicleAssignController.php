<?php

namespace App\Http\Controllers\Client\Vehicle;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\Pool\VehicleAssign\AssignEmployeeRequest;
use App\Models\Client\Vehicle;
use App\Repositories\Client\EmployeeRepository;
use App\Repositories\Client\VehicleRepository;
use App\Services\VtsService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientVehicleAssignController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(VehicleRepository $vehicleRepository, EmployeeRepository $employeeRepository)
    {
        $arr = [
            'companyCode'  => auth()->user()?->customerEmployee?->company,
            'bulkFlag'    => 2,
            'isActiveFlag' => 1,
        ];
        $vehicles = $vehicleRepository->getVehicleInfo($arr);
        $drivers = $employeeRepository->getEmpPersonalInfo(null, null, 1, auth()->user()?->customerEmployee?->company);
        return view('client.pool.vehicle-assign.index',compact('vehicles','drivers'));
    }


    public function showEmployeeAssign(Request $request, VehicleRepository $vehicleRepository)
    {

        $vehicleId  = trim($request->get('vehicleId'));
        $assignType = trim($request->get('type'));
        $bookingNo  = trim($request->get('bookingNo'));

        $companyCode = auth()->user()?->customerEmployee?->company;

        // Get vehicle details
        $vehicleDetails = $vehicleRepository->getPullVehicleDetails($vehicleId, $companyCode);

        if (!$vehicleDetails) {
            return redirect()->back()->with('error', 'Vehicle not found');
        }

        // Business logic
        $vehicleFlag = 0;

        if ($vehicleDetails->assign_type == config('constants.ASSIGN_VACANT')) {
            $vehicleFlag = 1;
        }

        if (!$vehicleDetails->driver_id && $assignType == config('constants.ASSIGN_ENROUTE')) {
            $vehicleFlag = 0;
        }

        // Main condition
        if (
            $vehicleFlag &&
            in_array($assignType, [
                config('constants.ASSIGN_PERSON'),
                config('constants.ASSIGN_ENROUTE')
            ])
        ) {

            $data = [];

            $data['vehicleId'] = $vehicleId;
            $data['assignType'] = $assignType;
            $data['leftMenuModuleUrl'] = "client/VehicleAssign/employeeVehicleAssign";

            $data['locationBtnFlag'] = empty($vehicleDetails->communication_code) ? 0 : 1;
            $data['bookingNo'] = $bookingNo;

            // ✅ Booking logic
            if (!empty($bookingNo)) {
                $bookingSummary = $vehicleRepository->getVehicleBookingSummary($bookingNo, $companyCode);

                $data['bookingSummary'] = $bookingSummary;

                $routeStr = '';

                if (!empty($bookingSummary)) {
                    $routeInfos = json_decode($bookingSummary[0]->route, true);

                    $routeArr = [];
                    $fromFlag = true;

                    foreach ($routeInfos as $routeInfo) {
                        if ($fromFlag) {
                            $routeArr[] = $routeInfo['routeFrom'];
                            $fromFlag = false;
                        }
                        $routeArr[] = $routeInfo['routeTo'];
                    }

                    $routeStr = !empty($routeArr) ? implode(' -- ', $routeArr) : '';
                }

                $data['routeStr'] = $routeStr;
            }

            return view('client.pool.vehicle-assign.employee-assign',compact('data'));
        }

        return redirect()->back()->with('error', 'Vehicle already enroute');
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
    public function store(AssignEmployeeRequest $request, VehicleRepository $vehicleRepository)
    {
        $vehicleId  = $request->vehicle;
        $assignType = $request->assignType;
        $vehicle = Vehicle::where('vehicle_id', $vehicleId)
            ->where('company', auth()->user()?->customerEmployee?->company)
            ->firstOrFail();
    
        // Driver validation for ENROUTE
        if (!$vehicle->driver_id && $assignType == config('constants.ASSIGN_ENROUTE') ) {
            return back()->with('error', 'Assign enroute not found');
        }
    
        // Only allow vacant
        if ($vehicle->assign_type != config('constants.ASSIGN_VACANT')) {
            return back()->with('error', 'Assign vacant not found');
        }
        $data = [
            'pull_emp_name'        => $request->personName,
            'pull_designation'     => $request->designation,
            'pull_department'      => $request->department,
            'pull_id_no'           => $request->idNo,
            'pull_receive_date'    => Carbon::parse($request->receiveDate),
            'pull_route'           => $request->route,
            'pull_current_location'=> $request->location,
            'pull_remarks'         => $request->notes,
            'assign_type'          => $assignType,
            'pull_detail_ref_no'   => reference_no(),
            'updated_by'           => auth()->user()->user_id,
            'updated_dt_tm'        => now(),
            'booking_no'           => $request->bookingNo,
            'route_json'           => $request->route_json,
        ];

        // Date validation
        $dateCheckFlag = 1;
        if ($vehicle->pull_receive_date) {
            $receiveDateTime = Carbon::parse($data['pull_receive_date']);
            $pullReceiveDateTimeDb = Carbon::parse($vehicle->pull_receive_date);
            if ($pullReceiveDateTimeDb->gt($receiveDateTime)) {
                $dateCheckFlag = 0;
            }
        }

        // Final validation before insert
        if (
            empty($data['pull_emp_name']) ||
            empty($data['pull_receive_date']) ||
            !in_array($assignType, [config('constants.ASSIGN_PERSON'), config('constants.ASSIGN_ENROUTE')])
        ) {
            return back()->with('error', 'pull_emp_name or pull_receive_date not found');
        }

        DB::beginTransaction();
    
        try {
            $result = $vehicleRepository->assignEmployee($data, $vehicleId, auth()->user()?->customerEmployee?->company, $vehicle->driver_id);
            // Update trip status
            if (!empty($data['booking_no'])) {
                $vehicleRepository->updateTripStatusVehicleBooking($data['booking_no'], config('constants.TRIP_STATUS_START'));
            }
    
            DB::commit();
    
            return redirect()->route('client.pool.vehicle-assign.index', $result)
                ->with('success', 'Employee assigned successfully');
    
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return back()->with('error', 'Something went wrong', $e->getMessage());
        }
    }

    public function showVehicleVacant(Request $request)
    {
        // 2. Get input
        $vehicleId  = trim($request->get('vehicleId'));
        $assignType = trim($request->get('type'));

        // 3. Get vehicle details (replace model/service logic)
        $vehicleDetails = Vehicle::where('vehicle_id', $vehicleId)
            ->where('company', auth()->user()?->customerEmployee?->company)
            ->first();

        if (!$vehicleDetails) {
            return redirect()->route('client.pool.vehicle-employee-assign')
                    ->with('error', 'Invalid receive date');
        }

        // 4. Business condition logic
        if (
            $assignType == config('constants.ASSIGN_VACANT')  &&
            in_array($vehicleDetails->assign_type, [config('constants.ASSIGN_ENROUTE'), config('constants.ASSIGN_PERSON')])
        ) {

            $data = [
                'leftMenuModuleUrl' => "client/VehicleAssign/employeeVehicleAssign",
                'vehicleDetails'    => $vehicleDetails,
                'vehicleId'         => $vehicleId,
                'assignType'        => $assignType,
            ];

            return view('client.pool.vehicle-assign.vehicle-vacant-view', compact('data'));
        }
    }

    public function vacantEmpVehicle(Request $request, VehicleRepository $vehicleRepository)
    {
        try {

            $vehicleId  = trim($request->post('vehicle'));
            $vacantDate = $request->post('vacantDate');

            if (!$vehicleId || !$vacantDate) {
                return back()->with('error', 'Vehicle Id or vacant date not found');
            }

            $vacantDateTime = Carbon::parse($vacantDate);

            // Vehicle fetch
            $vehicleDetails = Vehicle::where('vehicle_id', $vehicleId)
                ->where('company', auth()->user()?->customerEmployee?->company)
                ->first();
            
            if (!$vehicleDetails) {
                return back()->with('error', 'Vehicle not found');
            }

            $receiveDateTime = Carbon::parse($vehicleDetails->pull_receive_date);

            // Date validation
            if ($receiveDateTime->gt($vacantDateTime)) {
                return back()->with('error', 'Invalid vacant date');
            }

            // Assign type validation
            if (!in_array($vehicleDetails->assign_type, [
                config('constants.ASSIGN_ENROUTE'),
                config('constants.ASSIGN_PERSON')
            ])) {
                return back()->with('error', 'Invalid assign type');
            }

            $result = DB::transaction(function () use (
                $request,
                $vehicleId,
                $vehicleDetails,
                $vacantDateTime,
                $vehicleRepository
            ) {

                $vehicleBookingSummary = $vehicleRepository
                    ->updateTripStatusVacantVehicleBooking(
                        $vehicleId,
                        auth()->user()?->customerEmployee?->company
                    );

                $bookingNo = $vehicleBookingSummary->booking_no ?? null;

                $data = [
                    'pull_current_location' => trim($request->post('location')) ?: null,
                    'pull_remarks'          => trim($request->post('notes')) ?: null,
                    'pull_emp_name'         => null,
                    'pull_designation'      => null,
                    'pull_department'       => null,
                    'pull_id_no'            => null,
                    'pull_receive_date'     => $vacantDateTime,
                    'pull_route'            => null,
                    'route_json'            => null,
                    'assign_type'           => config('constants.ASSIGN_VACANT'),
                    'pull_detail_ref_no'    => reference_no(),
                    'updated_by'            => auth()->user()->user_id,
                    'updated_dt_tm'         => now(),
                    'booking_no'            => $bookingNo,
                ];

                $driver = null;

                if ($vehicleDetails->assign_type == config('constants.ASSIGN_ENROUTE')) {
                    $driver = $vehicleDetails->driver_id;
                }

                $result = $vehicleRepository->assignEmployee(
                    $data,
                    $vehicleId,
                    auth()->user()?->customerEmployee?->company,
                    $driver
                );

                $vehicleRepository->updateTripStatusVacantVehicleBooking(
                    $vehicleId,
                    auth()->user()?->customerEmployee?->company
                );

                return $result;
            });

            return redirect()->route('client.pool.vehicle-assign.index', $result)
                ->with('success', 'Vacant done');

        } catch (\Throwable $e) {
            return back()->with('error', 'Something went wrong'.$e->getMessage());
        }
    }

    public function showCurrentLocation(Request $request, VehicleRepository $vehicleRepository)
    {
        // 2. Get input
        $vehicleId = $request->get('vehicleId');

        // 3. Prepare params
        $arr = [
            'companyCode' => auth()->user()?->customerEmployee?->company,
            'bulkFlag'    => 0,
        ];

        // 4. Get company info
        $companyInfo = $vehicleRepository->getCompanyGeneralInfo($arr);
        // 5. Pass data to view
        return view('client.pool.vehicle-assign.current-location', [
            'vehicleId'          => $vehicleId,
            'companyInfo'        => $companyInfo,
        ]);
    }

    public function getSingleVehicleLocationData(
        Request $request,
        VehicleRepository $vehicleRepository,
        VtsService $vtsService
    ) {
        // 2. Input validation
        $vehicleId = $request->post('vehicleId');

        if (!$vehicleId) {
            return response()->json(['success' => 0, 'message' => 'Vehicle ID required']);
        }

        // 3. Get company info
        $companyInfo = $vehicleRepository->getCompanyGeneralInfo([
            'companyCode' => auth()->user()?->customerEmployee?->company,
            'bulkFlag'    => 0
        ]);

        if (empty($companyInfo)) {
            return response()->json(['success' => 0, 'message' => 'Company not found']);
        }

        $company = $companyInfo[0];

        if (empty($company['vts_app_key'])) {
            return response()->json(['success' => 2]); // no vts key
        }

        // 4. Get vehicle info
        $vehicleInfo = $vehicleRepository->getVehicleInfo([
            'vehicleId'   => $vehicleId,
            'companyCode' => $company['company_code'],
            'isActiveFlag'=> 1,
            'bulkFlag'    => 0
        ]);

        if (empty($vehicleInfo)) {
            return response()->json(['success' => 3]); // no vehicle found
        }

        $vehicle = $vehicleInfo[0];

        // 5. VTS Provider check
        if ($company['vts_company'] === 'easy_trax') {

            // Get location data
            $response = $vtsService->getCurrentLocationETracks(
                $vehicle['communication_code'],
                $company['vts_app_key']
            );

            $jsonObj = json_decode($response, true);

            if (!$jsonObj || empty($jsonObj)) {
                return response()->json(['success' => 3]);
            }

            // Extract IMEI key
            $imei = array_key_first($jsonObj);

            if (!$imei) {
                return response()->json(['success' => 3]);
            }

            $lat = $jsonObj[$imei]['lat'] ?? null;
            $lng = $jsonObj[$imei]['lng'] ?? null;

            if (!$lat || !$lng) {
                return response()->json(['success' => 3]);
            }

            // Get address
            $address = $vtsService->getAddressETracks($lat, $lng, $company['vts_app_key']);

            // Info window HTML
            $infoContent = [[
                '<div class="info_content">
                    <h5 class="text-vroom-orange">' . $vehicle['registration_no'] . '</h5>
                    <p><b>Address:</b> ' . $address . '</p>
                    <p><b>Latitude:</b> ' . $lat . '</p>
                    <p><b>Longitude:</b> ' . $lng . '</p>
                    <p><b>Vehicle ID:</b> ' . $vehicle['vehicle_id'] . '</p>
                    <p><b>Driver:</b> ' . $vehicle['driver_name'] . '</p>
                    <p><b>Driver Mobile:</b> ' . $vehicle['driver_mobile_no'] . '</p>
                    <p><b>Speed:</b> ' . ($jsonObj[$imei]['speed'] ?? 0) . ' kph</p>
                    <p><b>Vehicle Type:</b> ' . ($vehicle['vehicle_type_name'] ?? '') . '</p>
                </div>'
            ]];

            $location = [[$lat, $lng]];

            return response()->json([
                'success'     => 1,
                'infoContent' => $infoContent,
                'location'    => $location
            ]);
        }

        return response()->json(['success' => 0, 'message' => 'Unsupported VTS provider']);
    }
}
