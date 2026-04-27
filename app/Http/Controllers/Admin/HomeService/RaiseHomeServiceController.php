<?php

namespace App\Http\Controllers\Admin\HomeService;

use App\Http\Controllers\Controller;
use App\Repositories\Client\HomeServiceRepository;
use App\Repositories\CommonRepository;
use App\Services\Client\GenerateMonthlyToken;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class RaiseHomeServiceController extends Controller
{
    public function index(Request $request, HomeServiceRepository $homeServiceRepository, CommonRepository $commonRepository)
    {

        $data = [];

        // ---------------- COMPANY INFO ----------------
        $companyCode = trim((string) $request->get('companyCode')) ?: null;

        if ($companyCode) {
            $data['companyInfo'] = $homeServiceRepository
                ->getSingleCompanyInfo($companyCode);
        } else {
            $data['companyInfo'] = null;
        }

        $variantArr = [];
        $variantArr['variantType'] = config('constants.HOME_SER');

        $data['distinctServices'] = $homeServiceRepository
            ->getDistinctService($variantArr);

        $data['serviceVariants'] = $homeServiceRepository
            ->getHomeService($variantArr, 1);

        $commonTableElementArr = ['type' => 'hm_leads'];

        $data['leads'] = $commonRepository
            ->getCommonTableElement($commonTableElementArr);

        // ---------------- CALENDAR ----------------
        $month = $request->post('inputMonth') ?: date("m");
        $year  = $request->post('inputYear') ?: date("Y");

        $calendarDefaultDate = date('Y-m-d');

        if ($request->inputMonth) {
            $calendarDefaultDate = $year . '-' . $month . '-01';
        }

        $arr = [];
        $arr['fromDate'] = $year . '-' . $month . '-01';
        $arr['toDate'] = date("Y-m-t", strtotime($arr['fromDate']));

        $data['myMonth'] = $month;
        $data['myYear'] = $year;

        $data['calendarEvents'] = json_encode(
            $homeServiceRepository->getHomeServiceCalendarValue($arr)
        );

        $data['calendarDefaultDate'] = $calendarDefaultDate;

        // ---------------- VIEW ----------------
        return view("admin.home-service.raise-home-service.index", compact('data'));
    }

    public function getClientList(Request $request, CommonRepository $commonRepository)
    {

            $results = $commonRepository->getCompanyList();

            $response = [];
            $i = 1;

            foreach ($results as $result) {

                $companyType = $result->company_type ?? null;

                if ($companyType == config('constants.INDIVIDUAL_CUST')) {
                    $companyType = 'Individual Customer';
                } elseif ($companyType == config('constants.CORPORATE_CUST')) {
                    $companyType = 'Corporate Customer';
                }

                $companyCode = $result->company_code ?? '';
                $title = $result->title ?? '';
                $mobile = $result->company_mobile ?? '';
                $address = $result->address ?? '';

                $actionBtn = '<button class="btn btn-primary btn-xs btn-circle-puchase"
                                onclick="setClient(\'' . $companyCode . '\',\'' . $title . '\',\'' . $mobile . '\',\'' . $address . '\')">
                                <i class="fa fa-chevron-down"></i>
                            </button>';

                $response[] = [
                    $i,
                    $companyCode ?: '<small><i>N/A</i></small>',
                    $companyType ?: '<small><i>N/A</i></small>',
                    $title ?: '<small><i>N/A</i></small>',
                    $address ?: '<small><i>N/A</i></small>',
                    $mobile ?: '<small><i>N/A</i></small>',
                    $actionBtn
                ];

                $i++;
            }

            return response()->json(['data' => $response]);

    }

    public function addRaiseHomeService(Request $request, GenerateMonthlyToken $generateMonthlyToken)
    {
        $summaryArr = [
            'company' => $request->clientId,
            'name' => $request->name,
            'mobile' => $request->mobile,
            'address' => $request->address,
            'final_date' => $request->confirmDate ? $request->confirmDate : null,
            'admin_remarks' => $request->vroomComment ? $request->vroomComment : null,
            'leads_by' => $request->leadsBy ? $request->leadsBy : null,
        ];

        $confirmTime = $request->confirmTime
            ? $request->confirmTime
            : null;

        $summaryArr['appointment_time'] = $confirmTime
            ? date("H:i:s", strtotime($confirmTime))
            : null;

        if (
            !$summaryArr['name'] ||
            !$summaryArr['mobile'] ||
            !$summaryArr['address'] ||
            !$summaryArr['final_date'] ||
            !$summaryArr['appointment_time'] ||
            !$summaryArr['leads_by']
        ) {
            return redirect()->route('admin.home-service.raise-home-service.index')->with('error','Name or Mobile or address or final_date or appointment_time or leads_by value should not empty!');
        }

        DB::beginTransaction();

        try {

            $appointmentNo = config('constants.HOMESERVICE_NO') . $generateMonthlyToken->generateMonthlyToken(config('constants.HOMESERVICE_NO'));

            $summaryArr['service_date'] = $summaryArr['final_date'];
            $summaryArr['service_time'] = $summaryArr['appointment_time'];
            $summaryArr['appointment_no'] = $appointmentNo;
            $summaryArr['status'] = config('constants.APPOINTMENT_ACCEPT');
            $summaryArr['grand_total'] = 0;

            $userId = Auth::user()->user_id ?? null;

            $summaryArr['created_by'] = $userId;
            $summaryArr['created_type'] = config('constants.P_ADMIN');
            $summaryArr['created_dt_tm'] = Carbon::now();
            $summaryArr['updated_by'] = $userId;
            $summaryArr['updated_type'] = config('constants.P_ADMIN');
            $summaryArr['updated_dt_tm'] = Carbon::now();

            $detailArr = [];
            $grandTotal = 0;

            $count = (int) $request->takenServiceVarCount;

            for ($j = 1; $j <= $count; $j++) {

                $serviceVarCode = $request->input('takenServiceVarCode' . $j);

                if ($serviceVarCode) {

                    $unitPrice = (float) $request->input('takenServiceUnitPrice' . $j);
                    $quantity = (float) $request->input('quantity' . $j);

                    $total = $unitPrice * $quantity;

                    $detailArr[] = [
                        'appointment_no' => $appointmentNo,
                        'service_variant' => $serviceVarCode,
                        'unit_price' => $unitPrice,
                        'quantity' => $quantity,
                        'total_amount' => $total,
                        'created_by' => $userId,
                        'created_type' => config('constants.P_ADMIN'),
                        'created_dt_tm' => Carbon::now(),
                        'updated_by' => $userId,
                        'updated_type' => config('constants.P_ADMIN'),
                        'updated_dt_tm' => Carbon::now(),
                    ];

                    $grandTotal += $total;
                }
            }

            $summaryArr['grand_total'] = $grandTotal;

            DB::table('home_service_app_summary_gen')->insert($summaryArr);

            if (!empty($detailArr)) {
                DB::table('home_service_app_detail_gen')->insert($detailArr);
            }

            DB::commit();

            return redirect()->route('admin.home-service.raise-home-service.index')->with('success','Home service create successfully');

        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()
                ->route('admin.home-service.raise-home-service.index')
                ->with('error', 'Something went wrong. Please try again.');
            }
    }
}
