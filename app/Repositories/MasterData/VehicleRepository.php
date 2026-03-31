<?php

namespace App\Repositories\MasterData;

use App\Models\CommonTable;
use App\Models\MetaData\District;
use App\Models\MetaData\Division;
use App\Models\MetaData\Upozilla;
use Illuminate\Support\Facades\DB;

class VehicleRepository
{
    public function getVehicleTypeCounts()
    {
        return [
            'vehicleTypeCount' => CommonTable::where(['type'=>'vehicle_type','is_active'=>1])->count(),
            'vehicleClassCount' => CommonTable::where(['type'=>'vehicle_class','is_active'=>1])->count(),
            'vehicleBrandCount' => CommonTable::where(['type'=>'vehicle_brand','is_active'=>1])->count(),
            'vehicleBrandModelCount' => CommonTable::where(['type'=>'v_brand_model','is_active'=>1])->count(),
            'vehicleColorCount' => CommonTable::where(['type'=>'vehicle_color','is_active'=>1])->count(),
            'vehicleConditionCount' => CommonTable::where(['type'=>'vehicle_cndtn','is_active'=>1])->count(),
            'vehicleGroupCount' => CommonTable::where(['type'=>'vehicle_group','is_active'=>1])->count(),
        ];
    }

}