<?php

namespace App\Http\Controllers\Admin\SMS;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\SentSms;
use App\Models\SentSmsDetail;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EmployeeBulkSmsController extends Controller
{
    public function index(){
        $commonTableElementArr = array('type' => 'emp_designation');
        $designations = $this->getCommonTableElement($commonTableElementArr);
        return view('admin.sms.employee.employee-bulk-sms-view',compact('designations'));
    }

    public function showEmployeeBulkSmsPanel(Request $request)
    {
        if($request->filled('designation') && $request->filled('listFlag')){
            $designation = $request->input('designation');
            $checkBulkEmployeeFlag = $request->input('listFlag');
            $employeeIdStr = ''; 

            if ($checkBulkEmployeeFlag == 1) {
                return view('admin.sms.employee.custom-employee-sms-panel-view', compact(
                    'designation', 'employeeIdStr', 'checkBulkEmployeeFlag'
                ));
            } elseif ($checkBulkEmployeeFlag == 2) {
                // Get employees based on designation
                $employees = Employee::getEmployeeDetails([
                    'designation' => $designation
                ]);
                return view('admin.sms.employee.sms-employee-list-view', compact(
                    'designation', 'employees', 'employeeIdStr', 'checkBulkEmployeeFlag'
                ));
            }
        }else{
            return redirect()->route('admin.employee-bulk-sms.index')
                 ->with('error', 'Designations is not selected');
        }
    }

    public function showEmployeeSmsPanelFromList(Request $request)
    {
        $data = [];
        $data['designation'] = "";
        $data['checkBulkEmployeeFlag'] = 2;

        if($request->filled('employee_ids')){

            $employeeIdArr = explode(',', $request->employee_ids);

            if (!empty($employeeIdArr)) {
                $data['employees'] = Employee::getEmployeeDetails($employeeIdArr, 1);
                $data['employeeIdStr'] = implode(',', $employeeIdArr);

                return view(
                    'admin.sms.employee.custom-employee-sms-panel-view',
                    $data
                );
            }
        }else{
            return redirect()->route('admin.employee-bulk-sms.index');
        }
        
    }

    public function sendEmployeeCustomBulkMsg(Request $request, SmsService $smsService)
    {

        // Validate the request
        $request->validate([
            'customMsg' => 'required|string',
            'designation' => 'nullable|string',
            'employeeIdStr' => 'nullable|string',
        ]);

        $createUser = Auth::user()->user_id; // get currently logged-in user ID
        $createdDtTm = now(); // current datetime
        $customMsg = trim($request->input('customMsg'));

        $arr = [
            'designation' => $request->designation,
            'employeeIdStr' => $request->employeeIdStr,
        ];

        $smsTemplate = $arr['employeeIdStr'] ? 'selectedEmployeeBulkCustom' : 'bulkEmployeeCustom';
        $refNo = reference_no(); 
        $singleMsgCount = $this->singleMshCount($customMsg); 

        $employees = Employee::getEmployeeCustomBulkMsgData($arr);
        // Format messages (keep your existing logic)
        $responsedbdata = $smsService->getFormatedMessArrayV2($employees, $smsTemplate, $customMsg);
        // Send SMS (keep your existing SMS library call)
        //$smsService->sendMessageV2($responsedbdata['message']);

        // Prepare SMS details for database insert
        $insertSmsDetailsArr = [];
        foreach ($employees as $employee) {
            if (!empty($employee['mob1'])) {
                $insertSmsDetailsArr[] = [
                    'mobile_no'      => $employee['mob1'],
                    'summary_ref_no' => $refNo,
                    'template'       => $smsTemplate,
                    'created_by'     => $createUser,
                    'created_dt_tm'  => $createdDtTm,
                ];
            }
        }

        // Insert summary and details if there are recipients
        if (!empty($insertSmsDetailsArr)) {
            $messcount = count($insertSmsDetailsArr);
            $smsArr = [
                'reference_number' => $refNo,
                'sms_template'     => $smsTemplate,
                'sms_count'        => $messcount + ($messcount * $singleMsgCount),
                'custom_sms'       => $customMsg,
                'job_status'       => 0,
                'created_by'       => $createUser,
                'created_dt_tm'    => $createdDtTm,
                'updated_by'       => $createUser,
                'updated_dt_tm'    => $createdDtTm,
            ];

            DB::transaction(function () use ($smsArr, $insertSmsDetailsArr) {
                DB::table('sent_sms')->insert($smsArr);
                DB::table('sent_sms_details')->insert($insertSmsDetailsArr);
            });

            return redirect()->route('admin.employee-bulk-sms.index')
                            ->with('success', 'SMS has been successfully sent!');
        }

        return redirect()->route('admin.employee-bulk-sms.index')
                        ->with('error', 'No employee found to send SMS.');
    }

    private function singleMshCount($msgText) {
        $msgLength = strlen($msgText);
        $longMsgcount = 0;
        $messcount = 1;
        if ($msgLength > 160) {
            $longMsgcount = (int) ($msgLength / 160);
            if ($msgLength % 160 == 0) {
                $longMsgcount--;
            }
        }
        $messcount = $messcount + $longMsgcount;  
    }
    private function getCommonTableElement($commonTableElementArr)
    {
        return DB::table('common_table')
            ->when(isset($commonTableElementArr['type']), function ($q) use ($commonTableElementArr) {
                $q->where('type', $commonTableElementArr['type']);
            })
            ->when(isset($commonTableElementArr['depend_on_element']), function ($q) use ($commonTableElementArr) {
                $q->where('depend_on_element', $commonTableElementArr['depend_on_element']);
            })
            ->get()
            ->toArray();
    }
}
