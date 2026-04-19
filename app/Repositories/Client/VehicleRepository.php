<?php

namespace App\Repositories\Client;

use App\Models\Client\Vehicle;
use App\Models\Client\VehicleAssignDetail;
use App\Models\Company;
use App\Models\CustomerEmployee;
use Illuminate\Support\Facades\DB;

class VehicleRepository
{

    public function getVehicleInfo($arr)
    {
        $query = DB::table('vehicles')
            ->select(
                'vehicles.*',
                'vehicle_type_tb.element as vehicle_type_name',
                'brand_tb.element as brand_name',
                'driver_tb.employee_name as driver_name',
                'driver_tb.employee_id as driver_id',
                'driver_tb.primary_mobile as driver_mobile_no',
                'brand_model_tb.element as brand_model_name',
                'vehicle_class_tb.element as vehicle_class_name',
                'vehicle_color_tb.element as color_name',
                'vehicle_group_tb.element as vehicle_group_name'
            )
            ->leftJoin('common_table as vehicle_type_tb', 'vehicle_type_tb.element_code', '=', 'vehicles.vehicle_type')
            ->leftJoin('common_table as brand_tb', 'brand_tb.element_code', '=', 'vehicles.brand')
            ->leftJoin('common_table as brand_model_tb', 'brand_model_tb.element_code', '=', 'vehicles.brand_model')
            ->leftJoin('common_table as vehicle_class_tb', 'vehicle_class_tb.element_code', '=', 'vehicles.vehicle_class')
            ->leftJoin('common_table as vehicle_color_tb', 'vehicle_color_tb.element_code', '=', 'vehicles.color')
            ->leftJoin('common_table as vehicle_group_tb', 'vehicle_group_tb.element_code', '=', 'vehicles.vehicle_group')
            ->leftJoin('customer_employee as driver_tb', 'driver_tb.employee_id', '=', 'vehicles.driver_id')
            ->where('vehicles.company', $arr['companyCode']);

        if (isset($arr['bulkFlag']) && $arr['bulkFlag'] == 0) {
            $query->where('vehicles.vehicle_id', $arr['vehicleId']);
        }

        if (isset($arr['isActiveFlag']) && $arr['isActiveFlag'] == 1) {
            $query->where('vehicles.is_active', 1);
        } elseif (isset($arr['isActiveFlag']) && $arr['isActiveFlag'] == 2) {
            $query->where('vehicles.is_active', 0);
        }

        return $query->get();
    }

    public function getVehicleFiles(array $arr)
    {
        $query = DB::table('vehicle_files')
            ->where('is_active', 1)
            ->orderByDesc('id');

        // Apply condition only when bulkFlag = 0
        if (isset($arr['bulkFlag']) && $arr['bulkFlag'] == 0) {
            $query->where('vehicle', $arr['vehicleId']);
        }

        return $query->get()->toArray();
    }

    public function getPullVehicleDetails($vehicleId, $company)
    {
        return Vehicle::where('vehicle_id', $vehicleId)
            ->where('company', $company)
            ->where('is_active', 1)
            ->first();
    }

    public function getVehicleBookingSummary($bookingNo, $companyCode)
    {
        return DB::table('vehicle_booking_summary')
            ->where('company', $companyCode)
            ->where('booking_no', $bookingNo)
            ->get();
    }

    public function assignEmployee(array $data, string $vehicleId, string $company, $driver = null): bool
    {
        return DB::transaction(function () use ($data, $vehicleId, $company, $driver) {

            $bookingNo = $data['booking_no'] ?? null;

            // Remove unwanted fields
            unset($data['booking_no']);

            // 1. Update Vehicle
            $vehicle = Vehicle::where('vehicle_id', $vehicleId)->first();
            $vehicle->update($data);

            // 2. Prepare Assign Details
            $assignDetails = [
                'reference_no'        => $data['pull_detail_ref_no'] ?? null,
                'company'             => $company,
                'vehicle'             => $vehicleId,
                'driver'              => $driver ?? null,
                'assign_dt_tm'        => $data['pull_receive_date'] ?? null,
                'assign_type'         => $data['assign_type'] ?? null,
                'emp_name'            => $data['pull_emp_name'] ?? null,
                'emp_designation'     => $data['pull_designation'] ?? null,
                'emp_department'      => $data['pull_department'] ?? null,
                'emp_id_no'           => $data['pull_id_no'] ?? null,
                'route'               => $data['pull_route'] ?? null,
                'current_location'    => $data['pull_current_location'] ?? null,
                'booking_no'          => $bookingNo,
                'remarks'             => $data['pull_remarks'] ?? null,
                'created_by'          => $data['updated_by'] ?? null,
                'created_dt_tm'       => $data['updated_dt_tm'] ?? now(),
            ];

            // 3. Insert Assign Details
            VehicleAssignDetail::create($assignDetails);

            return true;
        });
    }

    public function updateTripStatusVehicleBooking($bookingNo, $status)
    {
        DB::table('vehicle_booking_summary')
            ->where('booking_no', $bookingNo)
            ->update([
                'trip_status'   => $status,
                'updated_by'    => auth()->user_id() ?? null,
                'updated_dt_tm' => now(),
            ]);
    }

}