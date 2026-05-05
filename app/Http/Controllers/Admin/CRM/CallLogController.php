<?php

namespace App\Http\Controllers\Admin\CRM;

use App\Http\Controllers\Controller;
use App\Models\Admin\CRM\CallCenterLog;
use App\Repositories\CommonRepository;
use App\Repositories\CRMCallLogRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CallLogController extends Controller
{
    public function index(Request $request, CRMCallLogRepository $cRMCallLogRepository)
    {
        // Default dates (today)
        $historyDate = $request->input('historyDate', Carbon::today()->toDateString());
        $taskDate    = $request->input('taskDate', Carbon::today()->toDateString());

        /*
        * Get Call History Data
        */
        $callHistories = $cRMCallLogRepository->getCallLog([
            'historyDate' => $historyDate,
            'taskDate'    => null,
            'logId'       => null,
        ]);

        /*
        * Get Call Task Data
        */
        $callTasks = $cRMCallLogRepository->getCallLog([
            'historyDate' => null,
            'taskDate'    => $taskDate,
            'logId'       => null,
        ]);

        return view('admin.crm.call-log.index', [
            'callHistories'   => $callHistories,
            'callTasks'       => $callTasks,
            'callHistoryDate' => $historyDate,
            'callTaskDate'    => $taskDate,
        ]);
    }

    public function create(Request $request, CRMCallLogRepository $crmCallLogRepository, CommonRepository $commonRepository)
    {

        $data = [];

        $data['logDetails'] = [];
        $logId = $request->query('logId', '');
        $data['logId'] = '';
        $data['disableFlag'] = '';

        // if (!empty($logId)) {
        //     $arr = [
        //         'historyDate' => '',
        //         'taskDate'    => '',
        //         'logId'       => $logId,
        //     ];

        //     /*
        //     * get existing call log details with log id
        //     */
        //     $logDetails = $crmCallLogRepository->getCallLog($arr);

        //     if (!empty($logDetails)) {
        //         $data['disableFlag'] = 'readonly';
        //         $data['logId'] = $logId;
        //         $data['logDetails'] = $logDetails;

        //         $nextCallStatus = $logDetails[0]['next_call_status'];
        //         $nextCallFlagDtTm = $logDetails[0]['next_call_flag_dt_tm'];

        //         /*
        //         * if call is in HOLD_NEXT_CALL status and is not ended in CALL_UNLOCK_MINUITE
        //         * specified time, free up the flag and release
        //         */
        //         if ($nextCallStatus == config('constants.HOLD_NEXT_CALL')) {
        //             $nextCallFlagDtTm = strtotime($nextCallFlagDtTm);
        //             $currentTime = strtotime(date('y-m-d H:i:s'));
        //             $minute = round(abs($nextCallFlagDtTm - $currentTime) / 60, 2);

        //             if ($minute > config('constants.CALL_UNLOCK_MINUITE')) {
        //                 $updateArr = [
        //                     'next_call_status'     => config('constants.HAVE_NEXT_CALL'),
        //                     'next_call_flag_dt_tm' => null,
        //                     'updated_by'           => Auth::user()->user_id,
        //                     //'updated_dt_tm'        => $this->dateTime,
        //                 ];

        //                 $crmCallLogRepository->changeNextCallStatus($updateArr, $logId);
        //             } else {
        //                 return redirect()->route('admin.crm.call-log.index')->with('error','Next call status not match');
        //             }
        //         }
        //     }
        // }

        // $callerType = $request->query('callerType', null);
        // $data['callerType'] = $callerType;
        // $data['customerInfo'] = [];
        // $data['leadCode'] = '';

        // if (!empty($callerType)) {
        //     if ($callerType == 'customer') {
        //         $customerId = $request->query('customerId', '');

        //         /*
        //         * get customer data
        //         */
        //         $data['customerInfo'] = $crmCallLogRepository->getIndividualCustomer($customerId);

        //         if (empty($data['customerInfo'])) {
        //             return redirect('admin/Crm/addCallLogShow');
        //         }
        //     } elseif ($callerType == 'leads') {
        //         $leadCode = $request->query('leadCode', '');

        //         /*
        //         * get leads data
        //         */
        //         $data['customerInfo'] = $crmCallLogRepository->getCallLeads($leadCode);
        //         $data['leadCode'] = $leadCode;

        //         if (empty($data['customerInfo'])) {
        //             return redirect('admin/Crm/addCallLogShow');
        //         }
        //     } else {
        //         return redirect('admin/Crm/addCallLogShow');
        //     }
        // }

        /*
        * call type data
        */
        $commonTableElementArr = ['type' => 'call_type'];
        $data['callTypes'] = $commonRepository->getCommonTableElement($commonTableElementArr);

        /*
        * call reasons data
        */
        $data['reasons'] = [
            'reasonData' => $commonRepository->getCallReason(null, 1)
        ];

        /*
        * call feedbacks data
        */
        $data['feedbacks'] = [
            'feedbackData' => $crmCallLogRepository->getCustomerFeedback(null, 1)
        ];

        /*
        * customer list modal data
        */
        $data['companies'] = $commonRepository->getIndividualAccList($data['isActiveFlag'] ?? null, config('constants.INDIVIDUAL_CUST'));

        return view('admin.crm.call-log.create',compact('data'));
    }

    public function setCurrentTime(Request $request, CRMCallLogRepository $crmCallLogRepository)
    {
        $currentDtTm = Carbon::now();
        $nextCallResponse = "";
        if ($request->input('fieldId') == 'Start') {

            $logId = $request->logId;
            /*
            * if call is in HOLD_NEXT_CALL status, start the next call if start button is clicked
            */
            if ($logId) {

                $updateArr = [
                    'next_call_status'   => config('constants.HOLD_NEXT_CALL'),
                    'next_call_flag_dt_tm' => $currentDtTm,
                    'updated_by'         => Auth::user()->user_id,
                    //'updated_dt_tm'      => $currentDtTm,
                ];

                $nextCallResponse = $crmCallLogRepository->updateForNextCallLog($updateArr, $logId);
            }
        }

        /*
        * return current date time
        */
        return response()->json([
            'dateTime'  => $currentDtTm,
            'time'      => Carbon::now()->format('h:i A'),
            'response'  => $nextCallResponse
        ]);
    }


    public function store(Request $request, CRMCallLogRepository $crmCallLogRepository)
    {
        // Laravel Validation (added, does NOT change logic)
        $request->validate([
            'callType' => 'required',
            'customerMobile' => 'required',
            'reason' => 'required',
            'feedback' => 'required',
            'startDtTm' => 'required|date',
            'endDtTm' => 'required|date',
        ]);

        $insertArr = [];

        $insertArr['call_type'] = $request->callType;
        $insertArr['log_id'] = reference_no();

        $insertArr['company'] = $request->companyCode ?? null;
        $insertArr['customer_name'] = $request->customerName ?? null;

        $insertArr['customer_mobile_no'] = $request->customerMobile;
        $insertArr['customer_address'] = $request->customerAddress ?? null;

        /*
        |--------------------------------------------------
        | Reason (SAFE EXPLODE)
        |--------------------------------------------------
        */
        $reason = explode('|', $request->reason ?? '');
        $insertArr['call_reason'] = $reason[0] ?? null;
        $insertArr['call_reason_text'] = $reason[1] ?? null;

        /*
        |--------------------------------------------------
        | Feedback (SAFE EXPLODE)
        |--------------------------------------------------
        */
        $feedback = explode('|', $request->feedback ?? '');
        $insertArr['customer_feedback'] = $feedback[0] ?? null;
        $insertArr['customer_feedback_text'] = $feedback[1] ?? null;

        $insertArr['call_start_dt_tm'] = \Carbon\Carbon::parse($request->startDtTm)->format('Y-m-d H:i:s');
        $insertArr['call_end_dt_tm'] = \Carbon\Carbon::parse($request->endDtTm)->format('Y-m-d H:i:s');

        /*
        |--------------------------------------------------
        | Start > End validation (same logic)
        |--------------------------------------------------
        */
        if (strtotime($insertArr['call_start_dt_tm']) > strtotime($insertArr['call_end_dt_tm'])) {
            return redirect()->route('admin.crm.call-log.create')->with('error','Call start date time and next call date time not match');
        }

        /*
        |--------------------------------------------------
        | Next Call logic
        |--------------------------------------------------
        */
        $nextCallDate = $request->nextCallDate;
        $nextCallTimeRaw = $request->nextCallTime;

        $nextCallTime = $nextCallTimeRaw
            ? date("H:i:s", strtotime($nextCallTimeRaw))
            : null;

        if ($nextCallDate != '' && $nextCallTime != '') {
            $insertArr['next_call_dt_tm'] = $nextCallDate . ' ' . $nextCallTime;
            $insertArr['next_call_status'] = config('constants.HAVE_NEXT_CALL');
        } else {
            $insertArr['next_call_status'] = config('constants.NO_NEXT_CALL');
        }

        $insertArr['remarks'] = $request->remarks ?? null;

        $insertArr['created_by'] = Auth::user()->user_id;
        $insertArr['created_dt_tm'] = Carbon::now();
        $insertArr['updated_by'] = Auth::user()->user_id;
        $insertArr['updated_dt_tm'] = Carbon::now();

        /*
        |--------------------------------------------------
        | Main validation (same logic preserved)
        |--------------------------------------------------
        */

        $previousLogUpdate = [];
        $previousLogId = $request->query('logId', '');
        if ($previousLogId) {

            $arr = [
                'historyDate' => "",
                'taskDate' => "",
                'logId' => $previousLogId
            ];

            $logDetails = $crmCallLogRepository->getCallLog($arr);
            //dd($logDetails);
            $nextCallStatus = $logDetails[0]->next_call_status ?? null;
            if ($nextCallStatus != config('constants.HOLD_NEXT_CALL')) {
                return redirect()->route('admin.crm.call-log.create')
                ->with('error','Call start date time and next call date time not match');

            }

            $nextCallDtTm = $logDetails[0]->next_call_flag_dt_tm ?? null;
            // dd($nextCallDtTm, $insertArr['call_start_dt_tm']);
            // if ($insertArr['call_start_dt_tm'] != $nextCallDtTm) {
            //     return redirect()->route('admin.crm.call-log.create')
            //     ->with('error','Call start date time not match');
            // }

            $previousLogUpdate['next_call_status'] = config('constants.DONE_NEXT_CALL');
            $previousLogUpdate['next_call_flag_dt_tm'] = null;
            $leadUpdateArr['updated_by'] = Auth::user()->user_id;
            $leadUpdateArr['updated_dt_tm'] = Carbon::now();
        }

        $insertArr['ref_log_id'] = $previousLogId;

        /*
        |--------------------------------------------------
        | Lead update
        |--------------------------------------------------
        */
        $leadCode = $request->leadCode;
        $leadUpdateArr = [];

        if ($leadCode) {
            $leadUpdateArr['call_status'] = 1;
            $leadUpdateArr['last_call_dt_tm'] = $insertArr['call_start_dt_tm'];
            $leadUpdateArr['updated_by'] = Auth::user()->user_id;
            $leadUpdateArr['updated_dt_tm'] = Carbon::now();
        }

        /*
        |--------------------------------------------------
        | Save
        |--------------------------------------------------
        */
        //dd($insertArr,$previousLogUpdate,$previousLogId,$leadCode,$leadUpdateArr);
        $response = $crmCallLogRepository->addCallLog(
            $insertArr,
            $previousLogUpdate,
            $previousLogId,
            $leadCode,
            $leadUpdateArr
        );

        return redirect()->route('admin.crm.call-log.create')->with('success','Call log added successfully.');

    }

    public function edit($logId, CommonRepository $commonRepository, CRMCallLogRepository $crmCallLogRepository){

        $commonTableElementArr = ['type' => 'call_type'];
        $data['callTypes'] = $commonRepository->getCommonTableElement($commonTableElementArr);

        /*
        * call reasons data
        */
        $data['reasons'] = [
            'reasonData' => $commonRepository->getCallReason(null, 1)
        ];

        /*
        * call feedbacks data
        */
        $data['feedbacks'] = [
            'feedbackData' => $crmCallLogRepository->getCustomerFeedback(null, 1)
        ];

        /*
        * customer list modal data
        */
        $data['companies'] = $commonRepository->getIndividualAccList($data['isActiveFlag'] ?? null, config('constants.INDIVIDUAL_CUST'));

        $data['log_data'] = CallCenterLog::where('log_id',$logId)->first();
        return view('admin.crm.call-log.create',compact('data'));
    }

    public function update(Request $request, CRMCallLogRepository $crmCallLogRepository)
    {
        $logId = $request->input('logId');

        /*
        |--------------------------------------------------------------------------
        | Get existing log
        |--------------------------------------------------------------------------
        */
        $callLog = $crmCallLogRepository->getEditCallLog($logId);

        if (!$callLog || !isset($callLog)) {
            return redirect()->route('admin.crm.call-log.index')->with('error','Call Log not found.');
        }

        /*
        |--------------------------------------------------------------------------
        | Only today's call can be edited (same logic)
        |--------------------------------------------------------------------------
        */
        if (date('Y-m-d', strtotime($callLog->call_start_dt_tm)) != date('Y-m-d')) {
            return redirect()->route('admin.crm.call-log.index')->with('error','Call start date time not match');
        }

        /*
        |--------------------------------------------------------------------------
        | Build update array
        |--------------------------------------------------------------------------
        */
        $updateArr = [];
        $updateArr['customer_name'] = $request->customerName;
        $updateArr['customer_mobile_no'] = $request->customerMobile;
        $updateArr['customer_address'] = $request->customerAddress;
        $updateArr['call_type'] = $request->callType;
        $updateArr['log_id'] = $logId;
        $nextCallTime = $request->nextCallTime 
        ? date("H:i:s", strtotime($request->nextCallTime)) 
        : null;

        // 2. Only concatenate if the DATE exists, otherwise set to NULL
        if ($request->nextCallDate && trim($request->nextCallDate) !== '') {
            $updateArr['next_call_dt_tm'] = $request->nextCallDate . ' ' . ($nextCallTime ?? '00:00:00');
            $updateArr['next_call_status'] = config('constants.HAVE_NEXT_CALL');
        } else {
            $updateArr['next_call_dt_tm'] = null;
            $updateArr['next_call_status'] = config('constants.NO_NEXT_CALL');
        }

        /*
        |--------------------------------------------------------------------------
        | Reason (safe explode)
        |--------------------------------------------------------------------------
        */
        $reason = explode('|', trim($request->input('reason', '')));
        $updateArr['call_reason'] = $reason[0] ?? null;
        $updateArr['call_reason_text'] = $reason[1] ?? null;

        /*
        |--------------------------------------------------------------------------
        | Feedback (safe explode)
        |--------------------------------------------------------------------------
        */
        $feedback = explode('|', trim($request->input('feedback', '')));
        $updateArr['customer_feedback'] = $feedback[0] ?? null;
        $updateArr['customer_feedback_text'] = $feedback[1] ?? null;

        $updateArr['remarks'] = $request->input('remarks')
            ? trim($request->input('remarks'))
            : null;

        /*
        |--------------------------------------------------------------------------
        | Audit fields
        |--------------------------------------------------------------------------
        */
        $updateArr['updated_by'] = Auth::user()->user_id ?? null;
        $updateArr['updated_dt_tm'] = now();

        /*
        |--------------------------------------------------------------------------
        | Validation (same condition logic)
        |--------------------------------------------------------------------------
        */
        if (
            $updateArr['call_type'] &&
            $updateArr['call_reason'] &&
            $updateArr['customer_feedback']
        ) {
            $response = $crmCallLogRepository->editCallLog($updateArr);

            return redirect()
                ->route('admin.crm.call-log.edit',$logId)
                ->with('success', "Call Log update success");
        }

        return redirect()
            ->route('admin.crm.call-log.index');
    }

    public function destroy($logId, CRMCallLogRepository $crmCallLogRepository)
    {
        $response = $crmCallLogRepository->removeCallLog($logId);

        return response()->json($response);
    }

    public function makeCall(Request $request, CommonRepository $commonRepository, CRMCallLogRepository $crmCallLogRepository){
        $data = [];
        
        // 2. Flash Messages
        $data['msg'] = $request->query('msg') == 1 ? 'Save Successfully...!' : '';
        $data['msgFlag'] = $request->query('msg') == 1 ? 'success' : '';

        // 3. Log Details Logic
        $logId = $request->query('logId');
        $data['logId'] = "";
        $data['disableFlag'] = '';
        $data['logDetails'] = [];

        if ($logId) {
            // Fetch using Eloquent or Repository
            $logDetails = CallCenterLog::where('log_id', $logId)->get();

            if ($logDetails->isNotEmpty()) {
                $log = $logDetails->first();
                $data['disableFlag'] = 'readonly';
                $data['logId'] = $logId;
                $data['logDetails'] = $logDetails;

                // Handle the "HOLD" status logic
                if ($log->next_call_status == config('constants.HOLD_NEXT_CALL')) {
                    $flagTime = Carbon::parse($log->next_call_flag_dt_tm);
                    $diffInMinutes = $flagTime->diffInMinutes(now());

                    if ($diffInMinutes > config('constants.CALL_UNLOCK_MINUITE')) {
                        $log->update([
                            'next_call_status' => config('constants.HAVE_NEXT_CALL'),
                            'next_call_flag_dt_tm' => null,
                            'updated_by' => Auth::user()->user_id,
                            'updated_dt_tm' => now(),
                        ]);
                    } else {
                        return redirect()->route('admin.crm.call-log.index')->with('error','One user has engaged with this call...!');
                    }
                }
            }
        }

        // 4. Caller Type Logic (Customer vs Leads)
        $callerType = $request->query('callerType');
        $data['callerType'] = $callerType;
        $data['customerInfo'] = [];
        $data['leadCode'] = "";

        if ($callerType) {
            if ($callerType == 'customer') {
                $customerId = $request->query('customerId');
                $data['customerInfo'] = $crmCallLogRepository->getIndividualCustomer($customerId);
            } elseif ($callerType == 'leads') {
                $leadCode = $request->query('leadCode');
                $data['customerInfo'] = $crmCallLogRepository->getCallLeads($leadCode);
                $data['leadCode'] = $leadCode;
            }

            if (!$data['customerInfo']) {
                return redirect()->route('admin.crm.call-log.create');
            }
        }

        $logId = $request->query('logId', '');
      
        $commonTableElementArr = ['type' => 'call_type'];
        $data['callTypes'] = $commonRepository->getCommonTableElement($commonTableElementArr);

        /*
        * call reasons data
        */
        $data['reasons'] = [
            'reasonData' => $commonRepository->getCallReason(null, 1)
        ];

        /*
        * call feedbacks data
        */
        $data['feedbacks'] = [
            'feedbackData' => $crmCallLogRepository->getCustomerFeedback(null, 1)
        ];

        /*
        * customer list modal data
        */
        $data['companies'] = $commonRepository->getIndividualAccList($data['isActiveFlag'] ?? null, config('constants.INDIVIDUAL_CUST'));

        $data['log_data'] = CallCenterLog::where('log_id',$logId)->first();
        return view('admin.crm.call-log.make-call',compact('data'));
    }

    public function truncateCallLog()
    {
        try {
            DB::table('call_center_log')->truncate();

            return response()->json([
                'status' => true,
                'message' => 'Call log truncated successfully'
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage() // remove in production
            ], 500);
        }
    }


    public function removeCallLogPanel(Request $request)
    {
        try {

            // Convert dates (same logic as CI)
            $fromDateInput = trim($request->input('fromDate'));
            $toDateInput   = trim($request->input('toDate'));

            // Validate required fields
            if (!$fromDateInput || !$toDateInput) {
                return response('0'); // optional: invalid input
            }

            // Append time (same behavior)
            $fromDate = Carbon::parse($fromDateInput)->startOfDay(); // 00:00:00
            $toDate   = Carbon::parse($toDateInput)->endOfDay();     // 23:59:59

            // Compare dates
            if ($fromDate->gt($toDate)) {
                return response('2'); // same as your CI logic
            }

            // Delete logs between dates
            DB::table('call_center_log')
                ->where('created_dt_tm', '>=', $fromDate)
                ->where('created_dt_tm', '<=', $toDate)
                ->delete();

            return response('1'); // success (same as CI)

        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage() // remove in production
            ], 500);
        }
    }
}
