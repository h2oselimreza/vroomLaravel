<?php

namespace App\Http\Controllers\WebSite;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $data['sliderImages'] = $this->getSliderImages();
        $data['presidentSecretary'] = $this->getPresidentSecretaryValue();
        $data['aboutSociety'] = $this->getAboutUs("A001");
        $data['newsLists'] = $this->getNews();
        $data['noticeLists'] = $this->getNotices();
        $data['achievements'] = $this->getAchievements();
        $data['events'] = $this->getEvents();
        $data['dashboardCount'] = $this->dashboardCount();
        $data['birthDayMembers'] = $this->getBirthdayMember(0);
        $data['anniversaryMembers'] = $this->getAnniversaryMember(0);
        $data['prayerTime'] = $this->getPrayerTime();
        $data['footerSliderImage']   = $this->getFooterSliderImages();

        return view('website.home', $data);
    }

    public function getSliderImages()
    {
        return DB::table('web_home_page_image')
            ->select('image')
            ->orderBy('image_order', 'ASC')
            ->get();
    }
    public function getPresidentSecretaryValue()
    {
        return DB::table('web_module_description')
            ->whereIn('module_code', ['A003','A004','A039','A043'])
            ->get();
    }
    public function getAboutUs($moduleCode)
    {
        return DB::table('web_module_description')
            ->where('module_code', $moduleCode)
            ->first();
    }
    public function getNews()
    {
        return DB::table('web_news')
            ->where('is_active', 1)
            ->orderBy('updated_dt_tm', 'DESC')
            ->get();
    }
    public function getNotices()
    {
        return DB::table('web_notices')
            ->where('is_active', 1)
            ->orderBy('updated_dt_tm', 'DESC')
            ->get();
    }
    public function getAchievements()
    {
        return DB::table('web_achievements')
            ->where('is_active', 1)
            ->orderBy('date', 'DESC')
            ->get();
    }
    public function getEvents()
    {
        return DB::table('web_events')
            ->where('is_active', 1)
            ->orderBy('date', 'DESC')
            ->get();
    }
    public function dashboardCount()
    {

        $arr['lifeMemberCount'] = DB::table('members')
            ->where('is_active',1)
            ->count();

        $arr['donarMemberCount'] = DB::table('members')
            ->where('is_active',1)
            ->whereNotNull('donar_member_id')
            ->count();

        $arr['blockCount'] = DB::table('blocks')
            ->where('is_active',1)
            ->count();

        $arr['roadCount'] = DB::table('roads')
            ->where('is_active',1)
            ->count();

        return $arr;
    }
    public function getBirthdayMember($flag = 1)
    {

        $query = DB::table('members')
            ->select('members.*','blocks.block_name','roads.road_name')
            ->leftJoin('blocks','blocks.block_code','=','members.society_block')
            ->leftJoin('roads','roads.road_code','=','members.society_road')
            ->whereDay('members.dob', Carbon::now()->day)
            ->whereMonth('members.dob', Carbon::now()->month)
            ->where('members.is_active',1);

        if($flag == 1){
            $query->where('members.birthday_sms_status',0);
        }

        return $query
            ->orderBy('blocks.block_name')
            ->orderBy('roads.road_name')
            ->get();
    }
    public function getAnniversaryMember($flag = 1)
    {

        $query = DB::table('members')
            ->select('members.*','blocks.block_name','roads.road_name')
            ->leftJoin('blocks','blocks.block_code','=','members.society_block')
            ->leftJoin('roads','roads.road_code','=','members.society_road')
            ->whereDay('members.anniversary', Carbon::now()->day)
            ->whereMonth('members.anniversary', Carbon::now()->month)
            ->where('members.is_active',1);

        if($flag == 1){
            $query->where('members.anniversary_sms_status',0);
        }

        return $query
            ->orderBy('blocks.block_name')
            ->orderBy('roads.road_name')
            ->get();
    }
    function getPrayerTime()
    {
        $crrDate = Carbon::now()->format('Y-m-d');

        // Get the prayer time record
        $row = DB::table('web_prayer_time')->first();

        if (!$row) {
            return null; // or handle empty case
        }

        // Initialize prayer array
        $prayerArr = [
            'fajr'       => $row->fajr,
            'zuhor'      => $row->zuhor,
            'asor'       => $row->asor,
            'maghrib'    => $row->maghrib,
            'isha'       => $row->isha,
            'jumma'      => $row->jumma,
            'sunrise'    => $row->sunrise,
            'sunset'     => $row->sunset,
            'prayer_date'=> $crrDate,
        ];

        // If stored prayer_date is today, return the array
        if ($row->prayer_date == $crrDate) {
            return $prayerArr;
        }

        // If not, fetch new data from external source
        $prayerLibrary = app()->make('App\Libraries\PrayerLibrary'); // assuming you've made a Laravel service class
        $responsePrayerTime = $prayerLibrary->getDateWisePrayerTime([
            'prayerDate' => Carbon::now()->format('d-m-Y'),
            'dataSource' => $row->data_source,
        ]);

        $prayerTimeObj = json_decode($responsePrayerTime);

        if ($prayerTimeObj && $prayerTimeObj->code == 200) {
            $prayerArr['sunrise'] = Carbon::parse($prayerTimeObj->data->timings->Sunrise)->format('h:i A');
            $prayerArr['sunset']  = Carbon::parse($prayerTimeObj->data->timings->Sunset)->format('h:i A');

            if ($row->data_source != 1) {  // custom source
                $prayerArr['fajr']    = Carbon::parse($prayerTimeObj->data->timings->Fajr)->format('h:i A');
                $prayerArr['zuhor']   = Carbon::parse($prayerTimeObj->data->timings->Dhuhr)->format('h:i A');
                $prayerArr['asor']    = Carbon::parse($prayerTimeObj->data->timings->Asr)->format('h:i A');
                $prayerArr['maghrib'] = Carbon::parse($prayerTimeObj->data->timings->Maghrib)->format('h:i A');
                $prayerArr['isha']    = Carbon::parse($prayerTimeObj->data->timings->Isha)->format('h:i A');
            }
        }

        // Update record
        $prayerArr['updated_by'] = 'system';
        $prayerArr['updated_dt_tm'] = Carbon::now()->toDateTimeString();

        DB::table('web_prayer_time')->where('id', 1)->update($prayerArr);

        return $prayerArr;
    }
    function getFooterSliderImages()
    {
        return DB::table('web_footer_image')
            ->select('image')
            ->orderBy('image_order', 'ASC')
            ->get()
            ->toArray(); // returns an array of objects
    }
}
