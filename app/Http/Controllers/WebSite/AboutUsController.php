<?php

namespace App\Http\Controllers\WebSite;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;

class AboutUsController extends Controller
{
    public function aboutSociety(){
        $moduleCode = "A001";
        //$data['pageHeading'] = 'About Us';
        $data = DB::table("web_module_description")->where("module_code", $moduleCode)->first();
        // dd($data);
        return view("website.about-us.about-society",compact("data"));  
    }
}
