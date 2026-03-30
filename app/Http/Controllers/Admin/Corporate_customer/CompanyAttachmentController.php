<?php

namespace App\Http\Controllers\Admin\Corporate_customer;

use App\Http\Controllers\Controller;
use App\Models\CompanyFile;
use App\Models\CorporateCompany;
use Illuminate\Http\Request;

class CompanyAttachmentController extends Controller
{
    public function edit($company_code){
        $albumImages = CompanyFile::where(
            ['company'=>$company_code,
            'file_type'=>'attachment',
            'is_active'=>1
            ])
        ->orderBy('created_dt_tm', 'desc')->get();
        $imageArray = $albumImages->pluck('image')->toArray();
        $albumImageStr = implode(',', $imageArray);
        $data = CorporateCompany::where('company_code',$company_code)->first();
        return view('admin.corporate_customer.attachment',compact('albumImages','albumImageStr','data'));
    }

    public function store(Request $request){

    }

    public function delete($company_code){

    }
}
