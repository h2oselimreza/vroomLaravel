<?php

namespace App\Http\Controllers\Admin\MasterData;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MasterData\FuelRequest;
use App\Models\Admin\MasterData\Fuel;
use DB;
use Illuminate\Http\Request;
use Log;

class FuelController extends Controller
{
    public function index(){
        $data = Fuel::get();
        return view('admin.master-data.fuel.index',compact('data'));
    }

    public function edit($id){
        $data = Fuel::findOrFail($id);
        return view('admin.master-data.fuel.create-edit',compact('data'));
    }

    public function show($id){
        $data = Fuel::findOrFail($id);
        return view('admin.master-data.fuel.show',compact('data'));
    }

    public function update(FuelRequest $request, $id)
    {
        $data = Fuel::findOrFail($id);

        $validated = $request->validated();

        try {
            DB::beginTransaction();

            $data->update([
                'fuel_rate'    => $validated['fuel_rate'],
                'fuel_name' => $validated['fuel_name'], 
            ]);

            DB::commit();

            return redirect()
                ->route('admin.module.master-data.fuel.index')
                ->with('success', __('Fuel updated successfully!'));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Update Failed", [
                'id'    => $id,
                'error' => $e->getMessage(),
                'user'  => auth()->id()
            ]);

            return back()
                ->withInput()
                ->with('error', __('Something went wrong. Please try again.'));
        }
    }
}
