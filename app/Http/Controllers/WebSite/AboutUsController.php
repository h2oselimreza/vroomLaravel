<?php

namespace App\Http\Controllers\WebSite;

use App\Http\Controllers\Controller;
use App\Models\Web\WebAchievement;
use DB;
use Illuminate\Http\Request;

class AboutUsController extends Controller
{
    public function aboutSociety(){
        $routeName = request()->segment(1);;
        $moduleMap = [
            'about-society'   => 'A001',
            'history-of-society' => 'A002',
            'message-from-president'          => 'A003',
            'message-from-general-secretary'    => 'A004',
            'message-from-office-secretary'    => 'A039',
            'message-from-pnp-secretary'    => 'A043',
            'mission-vision'    => 'A005',
            'campaign'    => 'A006',
        ];
        $moduleCode = $moduleMap[$routeName] ?? null;
        if(!$moduleCode) {
            abort(404);
        }
        $data = DB::table("web_module_description")->where("module_code", $moduleCode)->first();
        return view("website.about-us.about-society",compact("data"));  
    }

    public function achievements() {
        $achievements = WebAchievement::where('is_active', 1)->orderBy('date','desc')->get();
        return view('website.achievement.index', compact('achievements'));
    }
}
