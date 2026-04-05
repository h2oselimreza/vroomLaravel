<?php

namespace App\Http\Controllers\Admin\MasterData\AppointmentService;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MasterData\ServiceListRequest;
use App\Models\Admin\MasterData\Service;
use App\Models\Admin\MasterData\ServiceCategory;
use App\Services\TokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ServiceListController extends Controller
{
   /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Service::with('category')->get();
        return view('admin.master-data.appointment-service.service-list.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = $this->getCostCategory();
        return view('admin.master-data.appointment-service.service-list.create-edit',compact('categories'));
    }

    public function getCostCategory($isActiveFlag = 1) 
    {
        return \DB::table('service_categories')
            ->when($isActiveFlag == 1, fn($q) => $q->where('is_active', 1))
            ->when($isActiveFlag == 2, fn($q) => $q->where('is_active', 0))
            // ->when($this->customerType == 'indv_customer', 
            //     fn($q) => $q->where('company', '999999'),
            //     fn($q) => $q->where('company', $this->companyCode)
            // )
            ->orderBy('parent_category_str', 'asc')
            ->get()
            ->toArray(); // Converts to array to match your old return type
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ServiceListRequest $request, TokenService $tokenService)
    {
        $data = $request->validated();
        DB::beginTransaction();
        try {
            $prefix = "SRVC-";
            $serviceCode = $prefix . $tokenService->getTokenByCode($prefix);
        
            Service::create([
                'service_category'  => $data['service_category'],
                'service_name'     => $data['service_name'],
                'service_code'       => $serviceCode,
                'service_type' => 'APPOINMENT',
                'is_active'           => 1,
            ]);

            DB::commit();

            return redirect()
                ->route('admin.modules.master-data.service-list.index')
                ->with('success', __('Service category created successfully!'));

        } catch (\Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            Log::error("Creation Failed: " . $e->getMessage());

            return back()->withInput()->with('error', 'Something went wrong!');
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
    public function edit(string $code)
    {
        $data = Service::where('service_code', $code)->first();
        $categories = $this->getCostCategory();
        return view('admin.master-data.appointment-service.service-list.create-edit',compact('categories','data'));
    }

    /**
     * Update the specified resource in storage.
     */
    /**
 * Update the specified cost head in storage.
 */
    public function update(ServiceListRequest $request, $code)
    {
        $service = Service::where('service_code',$code)->first();
        // 1. Get validated data from your Request class
        $data = $request->validated();

        DB::beginTransaction();
        try {

            $service->update([
                'service_category'  => $data['service_category'],
                'service_name'     => $data['service_name'],
            ]);

            DB::commit();

            return redirect()
                ->route('admin.modules.master-data.service-list.index')
                ->with('success', __('Cost head updated successfully!'));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Update Failed", [
                'id'    => $service->id,
                'error' => $e->getMessage()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Something went wrong during the update.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function toggle($code)
    {
        $data = Service::where('service_code', $code)->firstOrFail();
        $data->update(['is_active' => !$data->is_active]);

        return back()->with('success', 'Status updated successfully!');
    }
}
