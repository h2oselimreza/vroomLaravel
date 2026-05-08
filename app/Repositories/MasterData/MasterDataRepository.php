<?php

namespace App\Repositories\MasterData;

use App\Models\Admin\MasterData\CostHead;
use App\Models\CommonTable;
use App\Models\MetaData\District;
use App\Models\MetaData\Division;
use App\Models\MetaData\Upozilla;
use App\Services\TokenService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

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

    public function insertExpenseCategory(array $insertArr, $tokenService): int
    {
        $exists = DB::table('cost_categories')
            ->where('company', $insertArr['company'])
            ->where('category_name', $insertArr['category_name'])
            ->exists();

        if ($exists) {
            return 2; // duplicate entry
        }

        $insertArr['category_code'] = config('constants.COST_CTG_CODE') . $tokenService->getTokenByCode(config('constants.COST_CTG_CODE'));

        // Parent category string (unchanged logic)
        $insertArr['parent_category_str'] = $this->getParentCostCategoryStr($insertArr);

        // Insert record safely
        DB::table('cost_categories')->insert($insertArr);

        return 1;
    }

    public function getParentCostCategoryStr(array $arr): string
    {
        if ($arr['parent_category'] == 1) {
            return $arr['category_code'];
        }

        $row = DB::table('cost_categories')
            ->where('company', $arr['company'])
            ->where('category_code', $arr['parent_category'])
            ->first();

        $strArr = [];

        if ($row) {
            $strArr[] = $row->parent_category_str;
        }

        $strArr[] = $arr['category_code'];

        return implode(' / ', $strArr);
    }

    public function editExpenseCategory(array $updateArr, int $categoryId): int
    {
        // Check duplicate entry
        $exists = DB::table('cost_categories')
            ->where('company', $updateArr['company'])
            ->where('category_name', $updateArr['category_name'])
            ->where('id', '!=', $categoryId)
            ->exists();

        if ($exists) {
            return 2; // duplicate entry
        }

        // Generate parent category string
        $updateArr['parent_category_str'] = $this->getParentCostCategoryStr($updateArr);

        // Get existing category info
        $row = DB::table('cost_categories')
            ->where('company', $updateArr['company'])
            ->where('category_code', $updateArr['category_code'])
            ->first();

        // Check parent category changed
        if ($row && $updateArr['parent_category'] != $row->parent_category) {

            $categoryBatchUpdateArr = [];

            $results = DB::table('cost_categories')
                ->where('category_code', '!=', $updateArr['category_code'])
                ->where('parent_category_str', 'LIKE', '%' . $row->parent_category_str . '%')
                ->get();

            foreach ($results as $result) {

                $arr = [];

                $arr['id'] = $result->id;

                $arr['parent_category_str'] =
                    $updateArr['parent_category_str'] .
                    str_replace(
                        $row->parent_category_str,
                        '',
                        $result->parent_category_str
                    );

                $categoryBatchUpdateArr[] = $arr;
            }

            // Batch update
            if (!empty($categoryBatchUpdateArr)) {

                foreach ($categoryBatchUpdateArr as $batchData) {

                    DB::table('cost_categories')
                        ->where('id', $batchData['id'])
                        ->update([
                            'parent_category_str' => $batchData['parent_category_str']
                        ]);
                }
            }
        }

        // Update main category
        DB::table('cost_categories')
            ->where('id', $categoryId)
            ->where('company', $updateArr['company'])
            ->update($updateArr);

        return 1;
    }

    public function removeExpenseCategory(int $categoryId, $company): int
    {
        // Get category information
        $row = DB::table('cost_categories')
            ->where('id', $categoryId)
            ->where('company', $company)
            ->first();

        // Category not found
        if (!$row) {
            return 4;
        }

        $categoryCode = $row->category_code;
        $categoryId   = $row->id;

        // Check child category exists
        $childCategoryExists = DB::table('cost_categories')
            ->where('parent_category', $categoryCode)
            ->where('is_active', 1)
            ->where('company', $company)
            ->exists();

        if ($childCategoryExists) {
            return 2; // this category has child category
        }

        // Check cost head exists
        $costHeadExists = DB::table('cost_heads')
            ->where('cost_category', $categoryCode)
            ->where('is_active', 1)
            ->where('company', $company)
            ->exists();

        if ($costHeadExists) {
            return 3; // this category has services
        }

        // Update category status
        DB::table('cost_categories')
            ->where('id', $categoryId)
            ->where('company', $company)
            ->update([
                'is_active' => 0
            ]);

        return 1;
    }

    public function activeExpenseCategory(int $categoryId, $company): int
    {
        DB::table('cost_categories')
            ->where('id', $categoryId)
            ->where('company', $company)
            ->update([
                'is_active' => 1
            ]);

        return 1;
    }

    public function insertExpenseHead(array $insertArr, $tokenService): int
    {
        // Check duplicate entry
        $exists = DB::table('cost_heads')
            ->where('company', $insertArr['company'])
            ->where('cost_head', $insertArr['cost_head'])
            ->exists();

        if ($exists) {
            return 2; // duplicate entry
        }

        // Generate codes (same logic)
        $insertArr['cost_head_code'] = config('constants.COST_HEAD_CODE') . $tokenService->getTokenByCode(config('constants.COST_HEAD_CODE'));

        $insertArr['cost_head_dis_code'] = $insertArr['cost_head_code'];

        DB::table('cost_heads')->insert($insertArr);

        return 1;
    }

    public function editExpenseHead($updateArr, $costHeadId)
    {
        // 1. Duplicate Check (Logic Preserved)
        $exists = DB::table('cost_heads')
            ->where('id', '!=', $costHeadId)
            ->where('company', $updateArr['company'])
            ->where('cost_head', $updateArr['cost_head'])
            ->exists();

        if ($exists) {
            return 2; // Duplicate entry code
        }

        // 2. Perform Update (Logic Preserved)
        $updated = DB::table('cost_heads')
            ->where('id', $costHeadId)
            ->where('company', $updateArr['company'])
            ->update($updateArr);

        return 1; // Success code
    }

    public function checkExpHead(array $arr): int
    {
        $isUsed = DB::table('expense_detail')
            ->join('expense_summary', 'expense_summary.expense_no', '=', 'expense_detail.expense_no')
            ->where('expense_detail.expense_head', $arr['cost_head_code'])
            ->where('expense_summary.company', $arr['company'])
            ->exists();
        return $isUsed ? 0 : 1;
    }

    public function removeExpenseHead($costHeadId, $company, $status): int
    {
        $value = ($status == 'inactive') ? 0 : 1;
        try {
            // Replicating $this->db->where()->where()->update()
            $updated = CostHead::where('id', $costHeadId)
                ->where('company', $company)
                ->update(['is_active' => $value]);

            return 1;

        } catch (Exception $e) {
            Log::error("Failed to remove Expense Head ID {$costHeadId}: " . $e->getMessage());
            throw $e;
        }
    }
}