<?php

namespace App\Http\Controllers\Admin\CRM;

use App\Http\Controllers\Controller;
use App\Repositories\Client\GenHomeServiceRepository;
use App\Repositories\CRMCallLogRepository;
use Illuminate\Http\Request;

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
}
