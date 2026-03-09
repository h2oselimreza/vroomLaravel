<?php

namespace App\Http\Controllers\WebSite;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SinglePageController extends Controller
{
    public function index(){
        $routeName = request()->segment(1);;
        $moduleMap = [
            'present-executive-committee' => 'A008',
            'present-sub-committee'     => 'A009',
            'adviser-comittee'          => 'A010',
            'central-mosque-committee'  => 'A011',
            'show-facilities'=> 'A012',

            'ec-meetings'=> 'A014',
            'agm'=> 'A015',
            'gm'=> 'A016',

            'previous-executive-committee'=> 'A017',
            'previous-president'=> 'A018',
            'previous-general-secretary'=> 'A019',

            'a-block'=> 'A020',
            'b-block'=> 'A021',
            'c-block'=> 'A022',
            'd-block'=> 'A023',
            'e-block'=> 'A024',
            'f-block'=> 'A025',
            'g-block'=> 'A026',

            'vacancy'=> 'A031',
            'career-result'=> 'A032',
            'advertisement'=> 'A033',

            'letters'=> 'A034',
            'forms'=> 'A035',

            'show-tender'=> 'A013',
        ];
        $moduleCode = $moduleMap[$routeName] ?? null;
        if(!$moduleCode) {
            abort(404);
        }
        $data = DB::table("web_module_description")->where("module_code", $moduleCode)->first();
        return view("website.single-page.index",compact("data"));  
    }
}
