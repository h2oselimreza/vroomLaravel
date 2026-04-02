<?php

namespace App\Http\Controllers\Admin\MasterData;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MasterData\CallCenterRequest;
use App\Models\Admin\MasterData\CallReason;
use App\Services\TokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CallReasonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = CallReason::get();
        return view('admin.master-data.call-center.call-reason.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.master-data.call-center.call-reason.create-edit');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CallCenterRequest $request, TokenService $tokenService)
    {
        $validated = $request->validated();
        $prefix = "CRS";
        $packageCode = $prefix . $tokenService->getTokenByCode($prefix);

        DB::beginTransaction();
        try {
            CallReason::create([
                'reason_code'    => $packageCode,
                'call_type'    => $validated['call_type'],
                'title'    => $validated['title'],
                'reason_order' => $validated['reason_order'],
                'description' => $validated['description'],
            ]);

            DB::commit();

            return redirect()
                ->route('admin.module.master-data.call-reason.index')
                ->with('success', __('Call reason created successfully!'));

        } catch (\Exception $e) {
            dd($e->getMessage());
            Log::error("Package Creation Failed", [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
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
        $data = CallReason::findOrFail($id);
        return view('admin.master-data.call-center.call-reason.show',compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $reason_code)
    {
        $data = CallReason::where('reason_code',$reason_code)->first();
        return view('admin.master-data.call-center.call-reason.create-edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CallCenterRequest $request, string $reason_code)
    {
        $data = CallReason::where('reason_code',$reason_code)->first();
        $validated = $request->validated();

        try {
            DB::beginTransaction();

            $data->update([
                'call_type'    => $validated['call_type'],
                'title'    => $validated['title'],
                'reason_order' => $validated['reason_order'],
                'description' => $validated['description'],
            ]);

            DB::commit();

            return redirect()
                ->route('admin.module.master-data.call-reason.index')
                ->with('success', __('Call reason updated successfully!'));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Package Update Failed", [
                'id'    => $reason_code,
                'error' => $e->getMessage(),
            ]);

            return back()
                ->withInput()
                ->with('error', __('Something went wrong. Please try again.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
