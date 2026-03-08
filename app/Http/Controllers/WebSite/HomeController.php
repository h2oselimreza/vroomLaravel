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
        $data['achievements'] = $this->getAchievements();
        $data['events'] = $this->getEvents();
        $data['dashboardCount'] = $this->dashboardCount();
        $data['birthDayMembers'] = $this->getBirthdayMember(0);
        $data['anniversaryMembers'] = $this->getAnniversaryMember(0);

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
}
