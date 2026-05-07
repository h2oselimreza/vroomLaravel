<?php

namespace App\Repositories\MasterData;

use App\Models\CommonTable;
use App\Models\MetaData\District;
use App\Models\MetaData\Division;
use App\Models\MetaData\Upozilla;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class MasterDataRepository
{
    public function getVendorGeneralInfo(array $arr)
    {
        return DB::table('corporate_vendor as cv')
            ->select(
                'cv.*',
                'd.division_en_name',
                'd.division_bn_name',
                'dt.district_en_name',
                'dt.district_bn_name',
                'u.upozilla_en_name',
                'u.upozilla_bn_name'
            )

            ->leftJoin('divisions as d', 'd.id', '=', 'cv.division')
            ->leftJoin('districts as dt', 'dt.id', '=', 'cv.district')
            ->leftJoin('upazilas as u', 'u.id', '=', 'cv.upozilla')

            ->when(isset($arr['bulkFlag']) && $arr['bulkFlag'] == 0, function ($q) use ($arr) {
                $q->where('cv.vendor_code', $arr['vendorCode']);
            })

            ->where('cv.company', $arr['companyCode'])

            ->get();
    }

    public function getVendorList($isActiveFlag = 1, $companyCode)
    {
        $query = DB::table('corporate_vendor');

        if ($isActiveFlag == 1) {
            $query->where('is_active', 1);
        } elseif ($isActiveFlag == 2) {
            $query->where('is_active', 0);
        }
        $query->where('company', $companyCode);

        return $query->get();

    }

    public function deleteFile($fileId)
    {
        $imageIds = explode(',', $fileId);
        $results = DB::table('corporate_vendor_file')
            ->select('file_name')
            ->whereIn('id', $imageIds)
            ->get();

        foreach ($results as $result) {

            $file = public_path('assets/client/files/vendor/' . $result->file_name);

            if (File::exists($file)) {
                File::delete($file);
            }
        }

        DB::table('corporate_vendor_file')
            ->whereIn('id', $imageIds)
            ->delete();

        return 1;
    }
}