<?php

namespace App\Http\Controllers\Client\Report;

use App\Http\Controllers\Controller;
use App\Repositories\Client\InventoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryStockReportController extends Controller
{

    public function index(
        InventoryRepository $inventoryRepository
    ) {

        $leftMenuModuleUrl = "client/Report/inventoryReport";

        $arr = [];

        $arr['company'] = Auth::user()->customerEmployee->company;

        $arr['variantType'] = config('constants.PURCHASE');

        $variants = $inventoryRepository->getStockVaiant($arr);

        return view(
            'client.report.inventory.stock.index',
            compact(
                'variants'
            )
        );
    }

    public function currentStockPrint(
        InventoryRepository $inventoryRepository
    ) {

        $arr = [];

        $arr['company'] = Auth::user()->customerEmployee->company;

        $arr['variantType'] = config('constants.PURCHASE');

        $variants = $inventoryRepository->getStockVaiant($arr);

        $company = $arr['company'];

        return view(
            'client.report.inventory.stock.currentStockDetailReportView',
            compact(
                'variants',
                'company'
            )
        );
    }
}
