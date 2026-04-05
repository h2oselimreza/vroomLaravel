<?php

namespace App\Http\Controllers\Admin\MasterData\AppointmentService;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MasterData\CostCategoryRequest;
use App\Models\Admin\MasterData\ServiceCategory;
use App\Services\TokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ServiceCategoryController extends Controller
{
    public function index() {
        $data = ServiceCategory::with('parent')
        ->orderBy('parent_category_str', 'asc')
        ->get();
        return view('admin.master-data.appointment-service.service-category.index',compact('data'));
    }

    public function create()
    {
        $categories = $this->getServiceCategory();
        return view('admin.master-data.appointment-service.service-category.create-edit',compact('categories'));
    }

    public function getServiceCategory($isActiveFlag = 1) 
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

     public function store(CostCategoryRequest $request, TokenService $tokenService)
    {
        $data = $request->validated();

        // 1. Manual Duplicate Check (as per your request)
        // We check if category_name already exists for this company
        $exists = ServiceCategory::where('category_name', $data['category_name'])
            //->where('company', auth()->user()->company_code ?? '999999')
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->with('error', __('Category name already exists!'));
        }

        DB::beginTransaction();
        try {
            $prefix = "SRCATG-";
            $categoryCode = $prefix . $tokenService->getTokenByCode($prefix);
            
            // 2. Hierarchy String Logic
            $parentCategoryStr = $categoryCode; 
            if ($data['parent_category'] != 1) {
                $parent = ServiceCategory::where('category_code', $data['parent_category'])->first();
                if ($parent) {
                    $parentCategoryStr = "{$parent->parent_category_str} / {$categoryCode}";
                }
            }

            // 3. Create Record
            ServiceCategory::create([
                'parent_category'     => $data['parent_category'],
                'parent_category_str' => $parentCategoryStr,
                'category_name'       => $data['category_name'],
                'category_code'       => $categoryCode,
                'is_active'           => 1,
                'category_type'       => 'APPOINMENT'
            ]);

            DB::commit();

            return redirect()
                ->route('admin.modules.master-data.service-category.index')
                ->with('success', __('Service category created successfully!'));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Creation Failed: " . $e->getMessage());

            return back()->withInput()->with('error', 'Something went wrong!');
        }
    }

     public function edit(string $category_code)
    {
        $data = ServiceCategory::where('category_code', $category_code)->first();
        $categories = $this->getServiceCategory();
        //dd($data, $categories);
        return view('admin.master-data.appointment-service.service-category.create-edit',compact('categories','data'));
    }

    public function update(CostCategoryRequest $request, $category_code)
    {
        $cost_category = ServiceCategory::where('category_code',$category_code)->first();
        $data = $request->validated();

        $exists = ServiceCategory::where('category_name', $data['category_name'])
            ->where('id', '!=', $cost_category->id)
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->with('error', __('Category name already exists!'));
        }

        DB::beginTransaction();
        try {

            $categoryCode = $cost_category->category_code;
            $parentCategoryStr = $categoryCode; 

            if ($data['parent_category'] != 1) {
                $parent = ServiceCategory::where('category_code', $data['parent_category'])->first();
                if ($parent) {
                    $parentCategoryStr = "{$parent->parent_category_str} / {$categoryCode}";
                }
            }

            // 3. Update Record
            $cost_category->update([
                'parent_category'     => $data['parent_category'],
                'parent_category_str' => $parentCategoryStr,
                'category_name'       => $data['category_name'],
            ]);

            DB::commit();

            return redirect()
                ->route('admin.modules.master-data.service-category.index')
                ->with('success', __('Cost category updated successfully!'));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Update Failed: " . $e->getMessage());

            return back()->withInput()->with('error', 'Something went wrong during update!');
        }
    }

     public function toggle($code)
    {
        $data = ServiceCategory::where('category_code', $code)->firstOrFail();
        $data->update(['is_active' => !$data->is_active]);

        return back()->with('success', 'Status updated successfully!');
    }
}
