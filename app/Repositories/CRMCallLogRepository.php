<?php

namespace App\Repositories;

use App\Models\Company;
use App\Models\CustomerEmployee;
use Illuminate\Support\Facades\DB;

class CRMCallLogRepository
{

    public function getCallLog($arr)
    {
        $query = DB::table('call_center_log')
            ->select(
                'call_center_log.*',
                'call_reason.title as reason_title',
                'customer_feedback.title as feedback_title',
                'common_table.element as call_type_title',
                //'employee.employee_name as created_by_name'
            )
            ->leftJoin('call_reason', 'call_reason.reason_code', '=', 'call_center_log.call_reason')
            ->leftJoin('customer_feedback', 'customer_feedback.feedback_code', '=', 'call_center_log.customer_feedback')
            ->join('common_table', 'common_table.element_code', '=', 'call_center_log.call_type');
            //->join('employee', 'employee.employee_id', '=', 'call_center_log.created_by');

        // historyDate condition
        if (!empty($arr['historyDate'])) {
            $query->whereDate('call_center_log.call_start_dt_tm', $arr['historyDate'])
                ->orderBy('call_center_log.call_start_dt_tm', 'DESC');
        }

        // taskDate condition
        if (!empty($arr['taskDate'])) {
            $query->whereIn('call_center_log.next_call_status', ['1', '3'])
                ->whereDate('call_center_log.next_call_dt_tm', $arr['taskDate'])
                ->orderBy('call_center_log.next_call_dt_tm', 'ASC');
        }

        // logId condition
        if (!empty($arr['logId'])) {
            $query->where('call_center_log.log_id', $arr['logId']);
        }

        return $query->get();
    }

    public function changeNextCallStatus($updateArr, $logId)
    {
        DB::table('call_center_log')
            ->where('log_id', $logId)
            ->update($updateArr);
    }


    public function getIndividualCustomer($companyCode)
    {
        return DB::table('corporate_companies')
            ->select(
                'title as name',
                'company_code as customer_id',
                'company_mobile as mobile',
                'address'
            )
            ->where('company_type', config('constants.INDIVIDUAL_CUST'))
            ->where('company_code', $companyCode)
            ->get();
    }

    public function getCallLeads($leadCode = null)
    {
        $query = DB::table('call_leads');

        if (!empty($leadCode)) {
            $query->where('lead_code', $leadCode);
        }

        return $query->get();
    }

    public function getCustomerFeedback($feedbackCode = null, $isActiveFlag = 1)
    {
        $query = DB::table('customer_feedback')
            ->select('customer_feedback.*', 'common_table.element as call_type_name')
            ->join('common_table', 'common_table.element_code', '=', 'customer_feedback.call_type');

        // filter by feedbackCode
        if (!empty($feedbackCode)) {
            $query->where('feedback_code', $feedbackCode);
        }

        // is_active logic
        if ($isActiveFlag == 1) {
            $query->where('customer_feedback.is_active', 1);
        } elseif ($isActiveFlag == 2) {
            $query->where('customer_feedback.is_active', 0);
        }

        // ordering
        $query->orderBy('customer_feedback.call_type')
            ->orderBy('customer_feedback.feedback_order');

        return $query->get();
    }

    public function updateForNextCallLog($updateArr, $logId)
    {
        $row = DB::table('call_center_log')
            ->where('log_id', $logId)
            ->first();

        if (!$row) {
            return 2; // fake request
        }

        $nextCallStatus = $row->next_call_status;

        if ($nextCallStatus == config('constants.HOLD_NEXT_CALL')) {

            $nextCallFlagDtTm = strtotime($row->next_call_flag_dt_tm);
            $currentTime = strtotime(date('y-m-d H:i:s'));

            $minute = round(abs($nextCallFlagDtTm - $currentTime) / 60, 2);

            if ($minute > config('constants.CALL_UNLOCK_MINUITE')) {

                DB::table('call_center_log')
                    ->where('log_id', $logId)
                    ->update($updateArr);

                return 1;
            } else {
                return 3; // one user has engaged with call
            }
        }

        if ($nextCallStatus == config('constants.HAVE_NEXT_CALL')) {

            DB::table('call_center_log')
                ->where('log_id', $logId)
                ->update($updateArr);

            return 1;
        }

        return 2; // fake request
    }

    public function addCallLog($insertArr, $previousLogUpdateArr, $previousLogId, $leadCode, $leadUpdateArr)
    {
        // Insert main call log
        DB::table('call_center_log')->insert($insertArr);

        // Update previous log if exists
        if ($previousLogId) {
            DB::table('call_center_log')
                ->where('log_id', $previousLogId)
                ->update($previousLogUpdateArr);
        }

        // Update lead if exists
        if ($leadCode) {
            DB::table('call_leads')
                ->where('lead_code', $leadCode)
                ->update($leadUpdateArr);
        }

        return 1;
    }

}