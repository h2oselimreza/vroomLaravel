<?php

namespace App\Http\Controllers\Admin\MasterData;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MasterData\MemberShipCardRequest;
use App\Models\Admin\MasterData\MembershipCard;
use App\Services\TokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MembershipCardController extends Controller
{
    public function index(){
        $data = MembershipCard::get();
        return view('admin.master-data.membership-card.index',compact('data'));
    }

    public function create(){
        return view('admin.master-data.membership-card.create-edit');
    }

    public function store(MemberShipCardRequest $request)
    {
        $data = $request->validated();

         $cardNumber = $request->card_number;
        if (strlen($cardNumber) != 8) {
            return back()
                ->withInput()
                ->with('error', __('Card number lenght will be 8'));
        } else {
            $packageCode = substr($cardNumber, 7, 1);
        }

        // if (!($packageCode == '1' || $packageCode == '2' || $packageCode == '3')) {
        //     return back()
        //         ->withInput()
        //         ->with('error', __('Package not match!'));
        // }

        DB::beginTransaction();
        try {
            // 3. Create Record
            MembershipCard::create([
                'card_id'      => reference_no(),
                'card_number'   => $request->card_number,
                'validity_month'     => $request->validity_month,
                'package_code' => 1,
                'updated_type'       => 'admin_employee',
                'created_type'     => 'admin_employee',
                'is_active'  => 1,
            ]);

            DB::commit();

            return redirect()
                ->route('admin.module.master-data.member-ship-card.index')
                ->with('success', __('Member ship card created successfully!'));

        } catch (\Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            Log::error("Creation Failed: " . $e->getMessage());

            return back()->withInput()->with('error', 'Something went wrong!');
        }
    }

    //  public function edit(string $category_code)
    // {
    //     $data = ServiceCategory::where('category_code', $category_code)->first();
    //     $categories = $this->getServiceCategory();
    //     //dd($data, $categories);
    //     return view('admin.master-data.appointment-service.service-category.create-edit',compact('categories','data'));
    // }

    // public function update(CostCategoryRequest $request, $category_code)
    // {
    //     $cost_category = ServiceCategory::where('category_code',$category_code)->first();
    //     $data = $request->validated();

    //     $exists = ServiceCategory::where('category_name', $data['category_name'])
    //         ->where('id', '!=', $cost_category->id)
    //         ->exists();

    //     if ($exists) {
    //         return back()
    //             ->withInput()
    //             ->with('error', __('Category name already exists!'));
    //     }

    //     DB::beginTransaction();
    //     try {

    //         $categoryCode = $cost_category->category_code;
    //         $parentCategoryStr = $categoryCode; 

    //         if ($data['parent_category'] != 1) {
    //             $parent = ServiceCategory::where('category_code', $data['parent_category'])->first();
    //             if ($parent) {
    //                 $parentCategoryStr = "{$parent->parent_category_str} / {$categoryCode}";
    //             }
    //         }

    //         // 3. Update Record
    //         $cost_category->update([
    //             'parent_category'     => $data['parent_category'],
    //             'parent_category_str' => $parentCategoryStr,
    //             'category_name'       => $data['category_name'],
    //         ]);

    //         DB::commit();

    //         return redirect()
    //             ->route('admin.modules.master-data.service-category.index')
    //             ->with('success', __('Cost category updated successfully!'));

    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         Log::error("Update Failed: " . $e->getMessage());

    //         return back()->withInput()->with('error', 'Something went wrong during update!');
    //     }
    // }

}
