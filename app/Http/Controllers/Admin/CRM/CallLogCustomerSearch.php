<?php

namespace App\Http\Controllers\Admin\CRM;

use App\Http\Controllers\Controller;
use App\Repositories\Client\GenHomeServiceRepository;
use App\Repositories\CRMCallLogRepository;
use App\Services\Client\GenerateMonthlyToken;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CallLogCustomerSearch extends Controller
{

    public function index(Request $request, CRMCallLogRepository $crmCallLogRepository, GenHomeServiceRepository $genHomeServiceRepository)
    {

        $data = [];

        // Get customer list
        $customerLists = $crmCallLogRepository->getCustomer(config('constants.INDIVIDUAL_CUST'));

        $titleArr = [];
        $customerIdArr = [];
        $customerMobileArr = [];

        foreach ($customerLists as $customer) {
            $titleArr[] = $customer->title;
            $customerIdArr[] = $customer->company_code;
            $customerMobileArr[] = $customer->company_mobile;
        }

        $data['customerName'] = json_encode($titleArr);
        $data['customerId'] = json_encode($customerIdArr);
        $data['customerMobile'] = json_encode($customerMobileArr);

        $data['companies'] = [];
        $data['searchFlag'] = '0';

        // POST check
        if ($request->isMethod('post')) {

            // ------- basic search ----------//
            $arr = [];
            $arr['customerName']   = $request->filled('customerName') ? $request->input('customerName') : null;
            $arr['customerMobile'] = $request->filled('customerMobile') ? $request->input('customerMobile') : null;
            $arr['customerId']     = $request->filled('customerId') ? $request->input('customerId') : null;

            // ------ advance search ---------//
            $takenServiceVarCount = (int) $request->input('takenServiceVarCount');

            $arr['fromDate'] = $request->filled('fromDate') ? $request->input('fromDate') : null;
            $arr['toDate']   = $request->filled('toDate') ? $request->input('toDate') : null;

            $serviceCodeArr = [];

            for ($j = 1; $j <= $takenServiceVarCount; $j++) {
                $serviceVarCode = $request->input('takenServiceVarCode' . $j);
                if ($serviceVarCode) {
                    $serviceCodeArr[] = $serviceVarCode;
                }
            }

            $arr['serviceCodeArr'] = $serviceCodeArr;

            // Get company list
            $data['companies'] = $crmCallLogRepository->getIndividualAccList($arr);

            // Search flag
            $data['searchFlag'] = '2'; // advance

            if (
                $arr['fromDate'] == "" &&
                $arr['toDate'] == "" &&
                $arr['serviceCodeArr'] == []
            ) {
                $data['searchFlag'] = '1'; // basic
            }
        }

        // Service data
        $variantArr['variantType'] = config('constants.HOME_SER');

        $data['distinctServices'] = $genHomeServiceRepository->getDistinctService($variantArr);

        $data['serviceVariants'] = $genHomeServiceRepository->getHomeService($variantArr, 1);

        return view('admin.crm.call-log.customer-search', compact('data'));    
    }

    public function callLeads(Request $request)
    {

        // $data['breadcrumbModuleUrl'] = "admin/Crm/callLeads";

        // $msg = (int) $request->get('msg');

        // $data['msg'] = '';
        // $data['msgFlag'] = '';

        // if ($msg == 1) {
        //     $data['msg'] = 'Uploaded Successfully...!';
        //     $data['msgFlag'] = 'success';

        // } elseif ($msg == 2) {
        //     $invalidMobileNo = trim($request->get('invMobile'));
        //     $data['msg'] = 'Invalid mobile number format: ' . $invalidMobileNo . ' ...!';
        //     $data['msgFlag'] = 'danger';

        // } elseif ($msg == 3) {
        //     $duplicateMobileNo = trim($request->get('duplicateMobile'));
        //     $data['msg'] = 'Uploaded file contains duplicate mobile number: ' . $duplicateMobileNo;
        //     $data['msgFlag'] = 'danger';

        // } elseif ($msg == 4) {
        //     $existsMobileNo = trim($request->get('mobileNumberExist'));
        //     $data['msg'] = 'One or more mobile number already exists ' . $existsMobileNo . '...!';
        //     $data['msgFlag'] = 'danger';
        // }

        return view('admin.crm.call-log.call-leads');

    }

    public function getCallLeadsList(Request $request)
    {

        if ($request->ajax()) {

            $query = DB::table('call_leads')->select([
                'lead_code',
                'name',
                'mobile',
                'address',
                'call_status',
                'last_call_dt_tm'
            ]);

            return DataTables::of($query)

                ->addIndexColumn()

                ->editColumn('lead_code', function ($row) {
                    return $row->lead_code ?: '<small><i>N/A</i></small>';
                })

                ->editColumn('name', function ($row) {
                    return $row->name ?: '<small><i>N/A</i></small>';
                })

                ->editColumn('mobile', function ($row) {
                    return $row->mobile ?: '<small><i>N/A</i></small>';
                })

                ->editColumn('address', function ($row) {
                    return $row->address ?: '<small><i>N/A</i></small>';
                })

                ->addColumn('call_status', function ($row) {
                    return $row->call_status == 1
                        ? '<span class="badge bg-success">Done</span>'
                        : '<span class="badge bg-secondary">Not Done</span>';
                })

                ->addColumn('last_call_dt_tm', function ($row) {
                    return $row->last_call_dt_tm
                        ? date('d-m-Y h:i A', strtotime($row->last_call_dt_tm))
                        : '<small><i>N/A</i></small>';
                })

                ->addColumn('action', function ($row) {
                    return '<button type="button" class="btn btn-sm"
                        onclick="showAction(' . htmlspecialchars(json_encode($row->lead_code)) . ')">
                        Action
                    </button>';
                })

                ->rawColumns([
                    'lead_code',
                    'name',
                    'mobile',
                    'address',
                    'call_status',
                    'last_call_dt_tm',
                    'action'
                ])
                ->make(true);
        }
    }

    public function uploadCallLeadsList(Request $request, GenerateMonthlyToken $generateMonthlyToken, CRMCallLogRepository $crmCallLogRepository)
    {
        // Validate file
        $request->validate([
            'callLeadsCsvFile' => 'required|file|mimes:csv,txt'
        ]);

        $file = $request->file('callLeadsCsvFile');

        // Read CSV
        $file_data = array_map('str_getcsv', file($file->getRealPath()));
        $header = array_map('trim', $file_data[0]);
        unset($file_data[0]);

        $callLeadsArr = [];
        $mobileNoArr = [];
        $flag = 1;

        foreach ($file_data as $row) {

            $row = array_combine($header, $row);

            if (!empty($row["Mobile Number"])) {

                $mobileNumber = trim($row["Mobile Number"]);

                // check mobile number
                $flag = check_mobile_no($mobileNumber);

                if ($flag == 0) {
                    return redirect('/admin/crm/call-leads')
                        ->with('msg', 2)
                        ->with('error', 'Invalid mobile number');
                }

                // duplicate check (within file)
                if (isset($mobileNoArr[$mobileNumber])) {
                    return redirect('/admin/crm/call-leads')
                        ->with('error', 'Dublicate record');
                }

                $mobileNoArr[$mobileNumber] = $mobileNumber;

                $callLeadsArr[] = [
                    'lead_code'     => config('constants.LEAD_CODE') .  $generateMonthlyToken->generateMonthlyToken(config('constants.LEAD_CODE')),
                    'name'          => trim($row["Name"] ?? ''),
                    'mobile'        => $mobileNumber,
                    'address'       => trim($row["Address"] ?? ''),
                    'created_by'    => Auth::user()->user_id,
                    'created_dt_tm' => Carbon::now(),
                    'updated_by'    => Auth::user()->user_id,
                    'updated_dt_tm' => Carbon::now(),
                ];
            }
        }

        if (!empty($callLeadsArr)) {

            // Call model method (same logic)
            $response = $crmCallLogRepository->addCallLeadsList($callLeadsArr, $mobileNoArr);

            $responseFlag = $response['result'];

            if ($responseFlag == 4) {
                return redirect('/admin/crm/call-leads')
                    ->with('msg', $responseFlag)
                    ->with('mobileNumberExist', $response['mobileNumberExist']);
            } else {
                return redirect('/admin/crm/call-leads')
                    ->with('msg', $responseFlag);
            }

        } else {
            return redirect('/admin/crm/call-leads')->with('msg', 2);
        }
    }
}
