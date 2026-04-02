<?php

namespace App\Http\Controllers\Admin\MasterData;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MasterData\CallCenterRequest;
use App\Http\Requests\Admin\MasterData\CustomerFeedBackRequest;
use App\Models\Admin\MasterData\CustomerFeedback;
use App\Services\TokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomerFeedBackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = CustomerFeedback::get();
        return view('admin.master-data.call-center.customer-feedback.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.master-data.call-center.customer-feedback.create-edit');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerFeedBackRequest $request, TokenService $tokenService)
    {
        $validated = $request->validated();
        $prefix = "CFB";
        $code = $prefix . $tokenService->getTokenByCode($prefix);

        DB::beginTransaction();
        try {
            CustomerFeedback::create([
                'feedback_code'    => $code,
                'call_type'    => $validated['call_type'],
                'title'    => $validated['title'],
                'feedback_order' => $validated['feedback_order'],
                'description' => $validated['description'],
            ]);

            DB::commit();

            return redirect()
                ->route('admin.module.master-data.customer-feedback.index')
                ->with('success', __('Customer feedback created successfully!'));

        } catch (\Exception $e) {
            dd($e->getMessage());
            Log::error("Creation Failed", [
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
        $data = CustomerFeedback::findOrFail($id);
        return view('admin.master-data.call-center.customer-feedback.show',compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $code)
    {
        $data = CustomerFeedback::where('feedback_code',$code)->first();
        return view('admin.master-data.call-center.customer-feedback.create-edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerFeedBackRequest $request, string $code)
    {
        $data = CustomerFeedback::where('feedback_code',$code)->first();
        $validated = $request->validated();

        try {
            DB::beginTransaction();

            $data->update([
                'call_type'    => $validated['call_type'],
                'title'    => $validated['title'],
                'feedback_order' => $validated['feedback_order'],
                'description' => $validated['description'],
            ]);

            DB::commit();

            return redirect()
                ->route('admin.module.master-data.customer-feedback.index')
                ->with('success', __('Customer feedback updated successfully!'));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Update Failed", [
                'id'    => $code,
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

    public function toggle($code)
    {
        $feedback = CustomerFeedback::where('feedback_code', $code)->firstOrFail();
        $feedback->update(['is_active' => !$feedback->is_active]);

        return back()->with('success', 'Status updated successfully!');
    }
}
