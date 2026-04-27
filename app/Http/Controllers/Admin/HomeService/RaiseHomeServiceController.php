<?php

namespace App\Http\Controllers\Admin\HomeService;

use App\Http\Controllers\Controller;
use App\Repositories\Client\HomeServiceRepository;
use App\Repositories\CommonRepository;
use Illuminate\Http\Request;

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
}
