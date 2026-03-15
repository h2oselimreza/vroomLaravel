<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeAnniversaryOrBirthdayCardController extends Controller
{
    public function index() {
        $commonTableElementArr = array('type' => 'emp_designation');
        $designations = $this->getCommonTableElement($commonTableElementArr);
        return view("admin.anniversary-birthday-card.employee-anniversary-card-view", compact('designations'));
    }

    public function showEmployeeAnniversaryCardPanel(Request $request)
    {
        if (!empty($request->filled('designation'))) {
            $data['designation'] = $request->designation;
            $data['checkBulkEmployeeFlag'] = $request->listFlag;
            $data['anniversaryDate'] = $request->anniversaryDate;
            $data['cardType'] = $request->cardType;

            $data['employeeIdStr'] = '';
            if ($data['checkBulkEmployeeFlag'] == 2) {
                $data['employees'] = Employee::getEmployeeDetails($data);
                return view("admin.anniversary-birthday-card.anniversary-employee-list-view", compact('data'));
            }
        } else {
            return redirect()->route('admin.employee-anniversary-birthday-card.index')->with('error', 'Data is not selected');
        }
    }

    public function showEmployeeAnniversaryCard(Request $request) {
        if (!empty($request->filled('employeeIdArr'))) {
            $employeeIdArr = $request->employeeIdArr;
            $cardType = $request->cardType;
            if ($employeeIdArr) {
                $personalInformations = Employee::getEmpPersonalInfo(null, null,null, $employeeIdArr);
                if($cardType == 'anniversary'){
                    return view('admin.anniversary-birthday-card./employee-anniversary-card-print-view', compact('personalInformations'));
                } elseif($cardType == 'birthday'){
                    return view('admin/anniversary-birthday-card./employee-birthday-card-print-view', compact('personalInformations'));
                }

            } else {
                return redirect()->route('admin.employee-anniversary-birthday-card.index')->with('error', 'Data is not selected');
            }
        } else {
            return redirect()->route('admin.employee-anniversary-birthday-card.index')->with('error', 'Data is not selected');
        }
    }

    private function getCommonTableElement($params)
    {
        $query = DB::table('common_table');

        if (isset($params['type'])) {
            $query->where('type', $params['type']);
        }

        if (isset($params['depend_on_element'])) {
            $query->where('depend_on_element', $params['depend_on_element']);
        }

        return $query->get()->toArray();
    }
}
