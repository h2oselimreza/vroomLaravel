@extends('client.layouts.app')
@section('content')
<style>
    .form-group{
        margin-bottom: 0px;
    }
    .panel-group .panel{
        margin-bottom: 15px;
    }
    .panel-group{
        margin-bottom: 0px;
    }
    .custom-panel-title1{
        font-size: 13px;
        font-weight: bold;
    }

    .custom-panel-heading{
        padding: 10px 14px;

    }
    .panel-group .panel1{
        margin-bottom: 5px;
    }
    .custom-form-control{
        height: 20px;
        font-size: 12px;
        text-align: right;
        padding-right: 3px;
    }
    .custom-form-control1{
        height: 20px;
        font-size: 12px;
        text-align: left;
        padding-left: 3px;
    }
    .form-group .non-editable{
        border-bottom: 0px;
    }
    .form-group .form-line:after{
        border-bottom: 0px;
    }
</style>
<?php
    $vehicleCount = count($editedVehicles);
?>

<div class="block-header">
    <h2>EDIT STOCK OUT</h2><br>
    <div class="breadcrumb breadcrumb-bg-blue-grey">
        <li><a href="#"> Home</a></li>
        <li><a href="#"> Inventory</a></li>
        <li><a href="{{ route('client.master-data.stock') }}"> Stock</a></li>
        <li><a href="{{ route('client.inventory.stock-out.index') }}"> Stock Out</a></li>
        <li><a href="{{ route('client.inventory.stock-out.edit',$summaryId) }}"> Edit Stock Out</a></li>
    </div>
</div>
<div class="row clearfix">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="body">
                @include('client.inventory.tab')
                <br>
                <!-- Success Message -->
                @if(session('success'))
                    <div class="alert alert-success">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                        <strong>{{ session('success') }}</strong>
                    </div>
                @endif

                <!-- Error Message -->
                @if(session('error'))
                    <div class="alert alert-danger" role="alert">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                        <strong>Error!</strong> {{ session('error') }}
                    </div>
                @endif

                <div class="panel panel-default"> 
                    <div class="panel-body">
                        <form action="{{ route('client.inventory.stock-out.update',$summaryId) }}" method="POST" id="stockOutForm">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group form-float" >
                                        <div class="form-line">
                                            <input type="text" class="form-control dateInput" name="stockOutDate" id="stockOutDate" value="{{ $stockSummary[0]->stock_date }}" >
                                            <label class="form-label"> Stock Out Date </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group form-float" >
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="stockId" id="stockId" value="{{ $summaryId }}" >
                                            <label class="form-label"> Stock Id </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel-group" id="vehicleGroupDiv">

                                        <?php
                                        $vehicleCount = 1;
                                        foreach ($editedVehicles as $vehicle) {
                                            ?>
                                            <div id="vehicleDiv<?php echo $vehicleCount ?>">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">
                                                        <div class="panel-title custom1-panel-title">
                                                            <div class="row p-l-25 p-t-10 p-b-10">
                                                                <div class="float-left">
                                                                    <i class="fa fa-car"></i>{{$vehicle->registration_no}}
                                                                </div>
                                                                <div class="float-right">
                                                                    <i class="fa fa-remove pointer text-danger" onclick="removeVehicle('<?php echo $vehicleCount ?>')"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="panel-body custom1-panel-body">
                                                        <div id="vehicleVariantDiv<?php echo $vehicleCount ?>">

                                                            <table class="table table-bordered custom-table" id="variantTable<?php echo $vehicleCount ?>">
                                                                <tr>
                                                                    <td width="25%"><b>Product Variant</b></td>
                                                                    <td width="15%"><b>Quantity</b></td>
                                                                    <td width="15%"><b>Unit Name</b></td>
                                                                    <td width="50%"><b>Remarks</b></td>
                                                                    <td width="10%"><b>Action</b></td>
                                                                </tr>

                                                                <?php
                                                                $takenVariantCount = 1;
                                                                foreach ($stockDetails as $stockDetail) {

                                                                    if ($stockDetail->vehicle == $vehicle->vehicle) {
                                                                        $quantity = 0;
                                                                        $quantity = $stockDetail->debit_quantity - $stockDetail->credit_quantity;
                                                                        if ($quantity) {
                                                                            ?>
                                                                            <tr id="variantTakenTr<?php echo $vehicleCount . $takenVariantCount ?>">
                                                                                <td class="td-left pointer" id="variantTd<?php echo $vehicleCount . $takenVariantCount ?>" >
                                                                                    <?php echo $stockDetail->variant_name ?>
                                                                                    <input type="hidden" name="variantCode<?php echo $vehicleCount . $takenVariantCount ?>" id="variantCode<?php echo $vehicleCount . $takenVariantCount ?>" value="<?php echo $stockDetail->variant ?>">
                                                                                    <input type="hidden" name="stockDetailAutoId<?php echo $vehicleCount . $takenVariantCount ?>" id="stockDetailAutoId<?php echo $vehicleCount . $takenVariantCount ?>" value="<?php echo $stockDetail->stock_details_auto_id ?>">
                                                                                </td>

                                                                                <td>
                                                                                    <div class="form-group form-float" >
                                                                                        <div class="form-line non-editable">
                                                                                            <input type="text" class="form-control custom-form-control" name="quantity<?php echo $vehicleCount . $takenVariantCount ?>" id="quantity<?php echo $vehicleCount . $takenVariantCount ?>" value="<?php echo $quantity ?>" readonly>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td class="td-left" id="variantUnitNameTd<?php echo $vehicleCount . $takenVariantCount ?>">
                                                                                    <?php echo $stockDetail->unit_name ?>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="form-group form-float" >
                                                                                        <div class="form-line non-editable">
                                                                                            <input type="text" class="form-control custom-form-control1" max="200" name="remarks<?php echo $vehicleCount . $takenVariantCount ?>" id="remarks<?php echo $vehicleCount . $takenVariantCount ?>" value="<?php echo $stockDetail->remarks ?>" readonly>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td><i class="fa fa-remove pointer text-danger" onclick="removeVariant('<?php echo $vehicleCount ?>', '<?php echo $takenVariantCount ?>')"></i></td>
                                                                            </tr>
                                                                            <?php
                                                                            $takenVariantCount++;
                                                                        }
                                                                    }
                                                                }
                                                                ?>

                                                                <input type="hidden" id="takenVariantCount<?php echo $vehicleCount ?>" name="takenVariantCount<?php echo $vehicleCount ?>" value="<?php echo $takenVariantCount ?>">
                                                            </table>


                                                        </div>
                                                        <button type="button" class="btn bg-blue btn-xs waves-effect" onclick="showVariantTable('<?php echo $vehicleCount ?>')" >Add Product Variant</button>
                                                    </div>
                                                    <input type="hidden" name="vehicleId<?php echo $vehicleCount ?>" id="vehicleId<?php echo $vehicleCount ?>" value="{{ $vehicle->vehicle }}">
                                                </div>
                                            </div>
                                            <?php
                                            $vehicleCount++;
                                        }
                                        ?>
                                    </div>
                                    <input type="hidden" name="vehicleCount" id="vehicleCount" value="<?php echo $vehicleCount ?>">
                                    <input type="hidden" id="vehicleSerial">
                                    <input type="hidden" id="takenStockSerial"> 
                                    <input type="hidden" name="variantDeleteStr" id="variantDeleteStr">

                                    <button type="button" class="btn btn-default btn-sm waves-effect" data-toggle="modal" data-target="#vehicleModal">Add Vehicle</button>

                                </div>
                            </div>
                        </form>
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <button type="button"  class="btn bg-blue btn-sm waves-effect" onclick="addNewStockOut()">Save</button>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="created-updated">
                                    <div class="float-left">
                                        <b>Created By: </b><?php echo get_create_update_by_name($stockSummary[0]->created_by) ?>
                                        <br><b>Created Date Time: </b><?php echo get_date_time_format($stockSummary[0]->created_dt_tm) ?>

                                    </div>
                                    <div class="float-right">
                                        <b>Updated By: </b><?php echo get_create_update_by_name($stockSummary[0]->updated_by) ?>
                                        <br><b>Updated Date Time: </b><?php echo get_date_time_format($stockSummary[0]->updated_dt_tm) ?>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- --------------- vehicle modal -------------------- -->
                        <div class="modal fade" id="vehicleModal" tabindex="-1" role="dialog">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="largeModalLabel">Vehicle</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="">

                                            <table class="table table-bordered table-hover custom-table dataTable">
                                                <thead>
                                                    <tr class="bg-info">
                                                        <th width="10%">SL</th>
                                                        <th width="25%">Registration No</th>
                                                        <th width="15%">Vehicle Type</th>
                                                        <th width="15%">Brand</th>
                                                        <th width="15%">Brand Model</th>
                                                        <th width="10%">Select</th>
                                                    </tr>
                                                </thead>
                                                <tfoot>
                                                    <tr>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                    </tr>
                                                </tfoot>
                                                <tbody>
                                                    @foreach ($vehicles as $vehicle)

                                                        <tr>

                                                            <td>
                                                                {{ $loop->iteration }}
                                                            </td>

                                                            <td class="td-left">
                                                                <a target="_blank"
                                                                href="{{ url('client/Home/vehicleDashboard?vehicleId=' . $vehicle->vehicle_id) }}">
                                                                    {{ $vehicle->registration_no }}
                                                                </a>
                                                            </td>

                                                            {{--
                                                            <td class="td-left">
                                                                {{ $vehicle->registration_no }}
                                                            </td>
                                                            --}}

                                                            <td class="td-left">
                                                                {{ $vehicle->vehicle_type_name }}
                                                            </td>

                                                            <td class="td-left">
                                                                {{ $vehicle->brand_name }}
                                                            </td>

                                                            <td class="td-left">
                                                                {{ $vehicle->brand_model_name }}
                                                            </td>

                                                            <td>
                                                                <i class="material-icons pointer"
                                                                onclick="addVehicle({{ $loop->iteration }})">
                                                                    arrow_drop_down_circle
                                                                </i>
                                                            </td>

                                                            <input type="hidden"
                                                                id="vehicleIdModalHidden{{ $loop->iteration }}"
                                                                value="{{ $vehicle->vehicle_id }}">

                                                            <input type="hidden"
                                                                id="vehicleRegModalHidden{{ $loop->iteration }}"
                                                                value="{{ $vehicle->registration_no }}">

                                                        </tr>

                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-link waves-effect" id="modalCloseBtn" data-dismiss="modal">CLOSE</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- ------------- ----------------- ----------------- -->

                        <!-- ----------- variant modal ----------------- -->
                        <button type="button" class="btn btn-default btn-sm waves-effect hidden" id="showVarinatModalBtn" data-toggle="modal" data-target="#variantModal"></button>
                        <div class="modal fade" id="variantModal" tabindex="-1" role="dialog">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="largeModalLabel">Product Variant</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="table-custom-responsive">

                                            <table class="table table-bordered table-hover custom-table dataTable">
                                                <thead>
                                                    <tr class="bg-info">
                                                        <th width="10%">SL</th>
                                                        <th width="25%">Category</th>
                                                        <th width="15%">Product</th>
                                                        <th width="15%">Variant</th>
                                                        <th width="15%">Quantity</th>
                                                        <th width="15%">Unit Name</th>
                                                        <th width="10%">Select</th>
                                                    </tr>
                                                </thead>
                                                <tfoot>
                                                    <tr>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                    </tr>
                                                </tfoot>
                                                <tbody>

                                                @php
                                                    $productCode = '';
                                                    $bgColor = '#fcf8e3';
                                                @endphp

                                                @foreach ($variants as $variant)

                                                    @php

                                                        if ($productCode == '') {

                                                            $bgColor = '#fcf8e3';

                                                        } elseif ($productCode != $variant->product) {

                                                            if ($bgColor == '#e5eaec') {
                                                                $bgColor = '#fcf8e3';
                                                            } else {
                                                                $bgColor = '#e5eaec';
                                                            }
                                                        }

                                                        $productCode = $variant->product;

                                                    @endphp

                                                    <tr style="background-color:{{ $bgColor }}">

                                                        <td class="td-center">
                                                            {{ $loop->iteration }}
                                                        </td>

                                                        <td>
                                                            {{ $variant->category_name }}
                                                        </td>

                                                        <td class="td-left">
                                                            {{ $variant->product_name }}
                                                        </td>

                                                        @if ($variant->variant_name == 'Default')
                                                            <td class="text-muted td-left">
                                                                <i>{{ $variant->variant_name }}</i>
                                                            </td>
                                                        @else
                                                            <td class="td-left">
                                                                {{ $variant->variant_name }}
                                                            </td>
                                                        @endif

                                                        <td class="td-right">
                                                            {{ $variant->quantity }}
                                                        </td>

                                                        <td class="td-left">
                                                            {{ $variant->unit_name }}
                                                        </td>

                                                        <td>
                                                            <i class="material-icons pointer"
                                                            onclick="addVariant({{ $loop->iteration }})">
                                                                arrow_drop_down_circle
                                                            </i>
                                                        </td>

                                                        <input type="hidden"
                                                            id="variantCodeModalHidden{{ $loop->iteration }}"
                                                            value="{{ $variant->variant_code }}">

                                                        <input type="hidden"
                                                            id="variantNameModalHidden{{ $loop->iteration }}"
                                                            value="{{ $variant->variant_name }}">

                                                        <input type="hidden"
                                                            id="variantUnitModalHidden{{ $loop->iteration }}"
                                                            value="{{ $variant->unit_name }}">

                                                    </tr>

                                                @endforeach
                                            </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-link waves-effect" id="variantModalCloseBtn" data-dismiss="modal">CLOSE</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ----------------------------------------------- -->

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    
@endsection
@push('scripts')
<script>
    var counter = '<?php echo $vehicleCount ?>';
    counter++;
    function addVehicle(vehicleSerial) {
        var vehicleId = $('#vehicleIdModalHidden' + vehicleSerial).val();
        var vehicleRegNo = $('#vehicleRegModalHidden' + vehicleSerial).val();
        for (var i = 1; i < counter; i++) {
            if (typeof ($('#vehicleId' + i).val()) !== 'undefined') {
                if ($('#vehicleId' + i).val() === vehicleId) {
                    sweetAlert("You have already select this vehicle...!");
                    return false;
                }
            }
        }

        var newRow = $(document.createElement('div')).attr("id", 'vehicleDiv' + counter);
        var vehicleDiv = '<div class="panel panel-default">\n\
                            <div class="panel-heading">\n\
                                <div class="panel-title custom1-panel-title">\n\
                                    <div class="row p-l-20 p-r-20">\n\
                                        <div class="float-left">\n\
                                            <i class="fa fa-car"></i> ' + vehicleRegNo + '\n\
                                        </div>\n\
                                        <div class="float-right">\n\
                                            <i class="fa fa-remove pointer text-danger" onclick="removeVehicle(' + counter + ')"></i>\n\
                                        </div>\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                            <div class="panel-body custom1-panel-body">\n\
                                <div id="vehicleVariantDiv' + counter + '">\n\
                                </div>\n\
                                <button type="button" class="btn bg-blue btn-xs waves-effect" onclick="showVariantTable(' + counter + ')" >Add Product Variant</button>\n\
                            </div>\n\
                            <input type="hidden" name="vehicleId' + counter + '" id="vehicleId' + counter + '" value="' + vehicleId + '">\n\
                        </div>';
        newRow.after().html(vehicleDiv);
        newRow.appendTo("#vehicleGroupDiv");
        $('#vehicleCount').val(counter);
        counter++;
        $('#modalCloseBtn').click();
    }

    function removeVehicle(vehicleSerial) {
        var idArr = new Array();
        var takenVariantCount = $("#takenVariantCount" + vehicleSerial).val();
        for (var j = 1; j <= takenVariantCount; j++) {
            var stockDetailAutoId = $('#stockDetailAutoId' + vehicleSerial + j).val();
            if (typeof stockDetailAutoId !== 'undefined') {
                var idArr = new Array();
                if (typeof (stockDetailAutoId) !== 'undefined') {
                    idArr.push(stockDetailAutoId);
                }
            }
        }
        if ($('#variantDeleteStr').val() !== "") {
            idArr.push($('#variantDeleteStr').val());
        }
        $('#variantDeleteStr').val(idArr.join());

        $('#vehicleDiv' + vehicleSerial).remove();
    }

    function showVariantTable(vehicleSerial) {
        var takenVariantCount = $("#takenVariantCount" + vehicleSerial).val();
        if (typeof takenVariantCount === "undefined") {
            var vaiantTableStr = '<tr id="variantTakenTr' + vehicleSerial + '1">\n\
                                            <td class="td-left pointer" id="variantTd' + vehicleSerial + '1" onclick="showVatiantModal(' + vehicleSerial + ',1)">\n\
                                                <small class="text-muted"><i>Show Product Variant</i></small>\n\
                                            </td>\n\
                                            <input type="hidden" name="variantCode' + vehicleSerial + '1" id="variantCode' + vehicleSerial + '1">\n\
                                            <td><div class="form-group form-float" >\n\
                                                    <div class="form-line">\n\
                                                        <input type="text" class="form-control custom-form-control"  name="quantity' + vehicleSerial + '1" id="quantity' + vehicleSerial + '1" >\n\
                                                    </div>\n\
                                                </div>\n\
                                            </td>\n\
                                            <td class="td-left" id="variantUnitNameTd' + vehicleSerial + '1">\n\
                                            </td>\n\
                                            <td><div class="form-group form-float" >\n\
                                                    <div class="form-line">\n\
                                                        <input type="text" class="form-control custom-form-control1" max="200" name="remarks' + vehicleSerial + '1" id="remarks' + vehicleSerial + '1" >\n\
                                                    </div>\n\
                                                </div>\n\
                                            </td>\n\
                                            <td><i class="fa fa-remove pointer text-danger" onclick="removeVariant(' + vehicleSerial + ',1)"></i></td>\n\
                                        </tr>';
            var newRow = $(document.createElement('div')).attr("id", 'variantTableDiv' + vehicleSerial);
            var variantTableDiv = '<table class="table table-bordered custom-table" id="variantTable' + vehicleSerial + '">\n\
                                    <tr>\n\
                                        <td width="25%"><b>Product Variant</b></td>\n\
                                        <td width="15%"><b>Quantity</b></td>\n\
                                        <td width="15%"><b>Unit Name</b></td>\n\
                                        <td width="50%"><b>Remarks</b></td>\n\
                                        <td width="10%"><b>Action</b></td>\n\
                                    </tr>\n\
                                    ' + vaiantTableStr + '\n\
                                    <input type="hidden" id="takenVariantCount' + vehicleSerial + '" name="takenVariantCount' + vehicleSerial + '" value="1">\n\
                                </table>';
            newRow.after().html(variantTableDiv);
            newRow.appendTo("#vehicleVariantDiv" + vehicleSerial);
        } else {
            takenVariantCount++;
            var newRow = $(document.createElement('tr')).attr("id", 'variantTakenTr' + vehicleSerial + takenVariantCount);
            var variantTableRowStr = '<td class="td-left pointer" id="variantTd' + vehicleSerial + takenVariantCount + '" onclick="showVatiantModal(' + vehicleSerial + "," + takenVariantCount + ')">\n\
                                            <small class="text-muted"><i>Show Product Variant</i></small>\n\
                                           </td>\n\
                                            <input type="hidden" name="variantCode' + vehicleSerial + takenVariantCount + '" id="variantCode' + vehicleSerial + takenVariantCount + '">\n\
                                            <td><div class="form-group form-float" >\n\
                                                    <div class="form-line">\n\
                                                        <input type="text" class="form-control custom-form-control" name="quantity' + vehicleSerial + takenVariantCount + '" id="quantity' + vehicleSerial + takenVariantCount + '">\n\
                                                    </div>\n\
                                                </div>\n\
                                            </td>\n\
                                            <td class="td-left" id="variantUnitNameTd' + vehicleSerial + takenVariantCount + '">\n\
                                           </td>\n\
                                            <td><div class="form-group form-float" >\n\
                                                    <div class="form-line">\n\
                                                        <input type="text" class="form-control custom-form-control1" max="200" name="remarks' + vehicleSerial + takenVariantCount + '" id="remarks' + vehicleSerial + takenVariantCount + '" >\n\
                                                    </div>\n\
                                                </div>\n\
                                            </td>\n\
                                            <td><i class="fa fa-remove pointer text-danger" onclick="removeVariant(' + vehicleSerial + "," + takenVariantCount + ')"></i></td>';
            newRow.after().html(variantTableRowStr);
            newRow.appendTo("#variantTable" + vehicleSerial);
            $("#takenVariantCount" + vehicleSerial).val(takenVariantCount);
        }
    }

    function removeVariant(vehicleSerial, takenVariantCount) {

        var idArr = new Array();
        if (typeof ($('#stockDetailAutoId' + vehicleSerial + takenVariantCount).val()) !== 'undefined') {
            idArr.push($('#stockDetailAutoId' + vehicleSerial + takenVariantCount).val());
        }
        if ($('#variantDeleteStr').val() !== "") {
            idArr.push($('#variantDeleteStr').val());
        }
        $('#variantDeleteStr').val(idArr.join());

        $('#variantTakenTr' + vehicleSerial + takenVariantCount).remove();
        var tableRowCount = $("#variantTable" + vehicleSerial + " tr").length;
        if (tableRowCount === 1) {
            $("#variantTable" + vehicleSerial).remove();
        }
    }

    function showVatiantModal(vehicleSerial, takenVariantCount) {
        $('#showVarinatModalBtn').click();
        $('#vehicleSerial').val(vehicleSerial);
        $('#takenStockSerial').val(takenVariantCount);
    }

    function addVariant(variantCount) {
        var vehicleSerial = $('#vehicleSerial').val();
        var takenStockSerial = $('#takenStockSerial').val();
        var variantCode = $('#variantCodeModalHidden' + variantCount).val();
        var variantName = $('#variantNameModalHidden' + variantCount).val();
        var variantUnitName = $('#variantUnitModalHidden' + variantCount).val();
        var takenVariantCount = $("#takenVariantCount" + vehicleSerial).val();
        for (var i = 1; i < takenVariantCount; i++) {
            if (typeof ($('#variantCode' + vehicleSerial + i).val()) !== 'undefined') {

                if ($('#variantCode' + vehicleSerial + i).val() === variantCode) {
                    sweetAlert("You have already select this product variant for this vehicle...!");
                    return false;
                }
            }
        }
        $('#variantTd' + vehicleSerial + takenStockSerial).text(variantName);
        $('#variantUnitNameTd' + vehicleSerial + takenStockSerial).text(variantUnitName);
        $('#variantCode' + vehicleSerial + takenStockSerial).val(variantCode);
        $('#variantModalCloseBtn').click();
    }

    function addNewStockOut() {
        var vehicleFlag = 0;
        var variantQuantityArr = new Array();
        for (var i = 1; i < counter; i++) {
            var vehicleId = $('#vehicleId' + i).val();
            if (typeof vehicleId !== 'undefined') {
                vehicleFlag = 1;
                var takenVariantCount = $('#takenVariantCount' + i).val();
                var variantFlag = 0;
                for (var j = 1; j <= takenVariantCount; j++) {
                    var quantity = $('#quantity' + i + j).val();
                    var stockAutoId = $('#stockDetailAutoId' + i + j).val();
                    if (typeof quantity !== 'undefined') {
                        variantFlag = 1;
                        var variantCode = $('#variantCode' + i + j).val();
                        if (quantity === "" || variantCode === "") {
                            if ($('#remarks' + i + j).val().length > 200) {
                                sweetAlert('Remarks max length is 200 characters...!');
                                return false;
                            }

                            sweetAlert('Variant and Quantity is required...!');
                            return false;
                        }
                        if (!$.isNumeric(quantity)) {
                            sweetAlert('Quantity must be numeric value...!');
                            return false;
                        }
                        if (parseFloat(quantity) <= 0) {
                            sweetAlert('Quantity must be greater than zero...!');
                            return false;
                        }
                        if (typeof stockAutoId === 'undefined') {
                            variantQuantityArr.push(variantCode + ":" + quantity);
                        }
                    }
                }
            }
        }
        if (vehicleFlag === 0) {
            sweetAlert('Please select at least one vehicle...!');
            return false;
        }

        if (variantFlag === 0) {
            sweetAlert('Please select at least one product variant of taken vehicles...!');
            return false;
        }

        if ($.trim($('#stockOutDate').val()) === "") {
            sweetAlert('Stock Out Date is required...!');
            return false;
        }
        var variantQuantityStr = variantQuantityArr.join(',');

        showLoader();
        $.ajax({
            type: 'POST',

            url: '{{ route("client.master-data.checkStockQuantityEdit") }}',

            data: {
                _token: '{{ csrf_token() }}',
                variantQuantityStr: variantQuantityStr,
                variantDeleteStr: $('#variantDeleteStr').val(),
                summaryId: $('#stockId').val()
            },

            success: function (result) {

                hideLoader();

                if (result == '1' || result == 1) {
                    $('#stockOutForm').submit();
                } else if (result === '2' || result == 2) {
                    sweetAlert('Quantity is not availabe...!');
                }
            }
        });
    }
</script>
@endpush