
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
        text-align: center;
    }
    .custom-form-control1{
        height: 20px;
        font-size: 12px;
        text-align: left;
        border-bottom: 1px solid black;
    }
</style>
<div class="block-header">
    <h2>NEW REQUEST FOR QUOTATION </h2><br>
    <div class="breadcrumb breadcrumb-bg-blue-grey">
        <li><a href="#"> Home</a></li>
        <li><a href="#"> Quotation</a></li>
        <li><a href="{{ route('client.quotation.quotation-list.index') }}"> Requested List For Quotation</a></li>
        <li><a href="{{ route('client.quotation.quotation-list.create') }}"> New Request For Quotation</a></li>
    </div>
</div>

<div class="row clearfix">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
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
            <div class="body">
                <form action="{{ route('client.quotation.quotation-list.store') }}" method="post" id="requestForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group form-float" >
                                <div class="form-line">
                                    <input type="text" class="form-control dateInput" name="lastsubmitDate" id="lastsubmitDate" value="<?php echo date('Y-m-d') ?>">
                                    <label class="form-label"> Quotation Last Submit Date </label>
                                </div>
                                <label id="lastSubmitDateError" class="error"></label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">

                            <div class="panel-group" id="vehicleGroupDiv">

                            </div>
                            <input type="hidden" name="saveStatusFlag" id="saveStatusFlag">
                            <input type="hidden" id="vehicleSerial" name="vehicleSerial">
                            <input type="hidden" name="vehicleCount" id="vehicleCount">


                            <button type="button" class="btn btn-default btn-sm waves-effect" data-toggle="modal" data-target="#vehicleModal">Add Vehicle</button>
                            <div class="form-group form-float m-t-30" >
                                <label class="form-label">Remarks </label>
                                <div class="form-line">
                                    <textarea class="form-control" name="remarks" ></textarea>
                                </div>

                            </div>
                            <button type="button"  class="btn bg-blue btn-sm waves-effect m-t-30" onclick="addRequest(2)">Save As Draft</button>
                            <button type="button"  class="btn bg-blue btn-sm waves-effect m-t-30" onclick="addRequest(3)">Save & Send Request</button>
                        </div>
                    </div>
                </form>


                <!-- --------------- vehicle modal -------------------- -->
                <div class="modal fade" id="vehicleModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="largeModalLabel">Vehicle</h4>
                            </div>
                            <div class="modal-body">
                                <div class="table-responsive">

                                    <table class="table table-bordered table-hover custom-table dataTable">
                                        <thead>
                                            <tr class="bg-info">
                                                <th width="10%">SL</th>
                                                <th width="25%">Registration No</th>
                                                <th width="15%">Vehicle Type</th>
                                                <th width="15%">Brand</th>
                                                <th width="15%">Brand Model</th>
                                                <th width="15%">Group</th>
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
                                            @foreach ($vehicles as $vehicle)

                                                <tr>

                                                    <td>
                                                        {{ $loop->iteration }}
                                                    </td>

                                                    <td class="td-left">

                                                        <a
                                                            target="_blank"
                                                            href="{{ url('client/Home/vehicleDashboard?vehicleId=' . $vehicle->vehicle_id) }}"
                                                        >
                                                            {{ $vehicle->registration_no }}
                                                        </a>

                                                    </td>

                                                    <td class="td-left">
                                                        {{ $vehicle->vehicle_type_name }}
                                                    </td>

                                                    <td class="td-left">
                                                        {{ $vehicle->brand_name }}
                                                    </td>

                                                    <td class="td-left">
                                                        {{ $vehicle->brand_model_name }}
                                                    </td>

                                                    <td class="td-left">
                                                        {{ $vehicle->vehicle_group_name }}
                                                    </td>

                                                    <td>

                                                        <i
                                                            class="material-icons pointer"
                                                            onclick="addVehicle({{ $loop->iteration }})"
                                                        >
                                                            arrow_drop_down_circle
                                                        </i>

                                                    </td>

                                                    <input
                                                        type="hidden"
                                                        id="vehicleIdModalHidden{{ $loop->iteration }}"
                                                        value="{{ $vehicle->vehicle_id }}"
                                                    >

                                                    <input
                                                        type="hidden"
                                                        id="vehicleRegModalHidden{{ $loop->iteration }}"
                                                        value="{{ $vehicle->registration_no }}"
                                                    >

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

                <!-- --------------- service modal -------------------- -->
                <button class="btn btn-default btn-sm waves-effect hidden" data-toggle="modal" data-target="#serviceModal" id="serviceModalShowBtn">Add service</button>
                <div class="modal fade" id="serviceModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="largeModalLabel">Service</h4>
                            </div>
                            <div class="modal-body">
                                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                    <?php
                                    $serviceCount = 1;
                                    foreach ($distinctServices as $distinctService) {
                                        ?>
                                        <div class="panel panel1 panel-default">
                                            <div class="panel-heading custom-panel-heading" role="tab" id="headingOne">
                                                <p class="panel-title custom-panel-title1 p-t-0 p-b-0">
                                                    <a role="button" data-toggle="collapse" data-parent="#" href="#generalCollapseOne{{$distinctService->service}}" aria-expanded="true" aria-controls="generalCollapseOne{{$distinctService->service}}">
                                                        <i class="fa fa-tags"></i> {{ $distinctService->service_name }}
                                                    </a>
                                                </p>
                                            </div>
                                            <div id="generalCollapseOne{{$distinctService->service}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                                                <div class="panel-body">
                                                   <table class="table table-striped custom-table">

                                                        @php
                                                            $serviceVarSerial = 1;
                                                        @endphp

                                                        @foreach ($serviceVariants as $serviceVariant)

                                                            @if ($serviceVariant->service == $distinctService->service)

                                                                <tr>

                                                                    <td>
                                                                        {{ $serviceVarSerial }}
                                                                    </td>

                                                                    <td
                                                                        class="td-left"
                                                                        style="width:80%"
                                                                    >
                                                                        {{ $serviceVariant->service_variant_name }}
                                                                    </td>

                                                                    <td class="td-left">

                                                                        <input
                                                                            type="checkbox"
                                                                            name="serviceVarCheckBox{{ $serviceCount }}"
                                                                            id="serviceVarCheckBox{{ $serviceCount }}"
                                                                            class="filled-in chk-col-blue"
                                                                        >

                                                                        <label
                                                                            for="serviceVarCheckBox{{ $serviceCount }}"
                                                                            class="form-label"
                                                                            style="margin-bottom: -12px"
                                                                        >
                                                                        </label>

                                                                    </td>

                                                                    <input
                                                                        type="hidden"
                                                                        id="serviceVariantCode{{ $serviceCount }}"
                                                                        value="{{ $serviceVariant->variant_code }}"
                                                                    >

                                                                    <input
                                                                        type="hidden"
                                                                        id="serviceVariantName{{ $serviceCount }}"
                                                                        value="{{ $serviceVariant->service_variant_name }}"
                                                                    >

                                                                </tr>

                                                                @php
                                                                    $serviceVarSerial++;
                                                                    $serviceCount++;
                                                                @endphp

                                                            @endif

                                                        @endforeach
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                    <input type="hidden" id="serviceVariantCount" value="<?php echo $serviceCount ?>" >
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-link waves-effect" id="serviceModalSelectBtn" onclick="setAddService()">SELECT</button>
                                <button type="button" class="btn btn-link waves-effect" id="serviceModalCloseBtn" data-dismiss="modal">CLOSE</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ------------- ----------------- ----------------- -->
            </div>
        </div>
    </div>
</div>    
@endsection
@push('scripts')
<script>
    var counter = 1;
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
                                <div id="vehicleServiceDiv' + counter + '">\n\
                                </div>\n\
                                <button type="button" class="btn bg-blue btn-xs waves-effect" onclick="setShowServiceModal(' + counter + ')" >Add Service</button>\n\
                                <br><br>\n\
                                <div id="vehicleProductDiv' + counter + '">\n\
                                </div>\n\
                                <button type="button" class="btn bg-blue btn-xs waves-effect" onclick="showProductTable(' + counter + ')" >Add Vehicle Parts</button>\n\
                            </div>\n\
                            <input type="hidden" name="vehicleId' + counter + '" id="vehicleId' + counter + '" value="' + vehicleId + '">\n\
                        </div>';
        newRow.after().html(vehicleDiv);
        newRow.appendTo("#vehicleGroupDiv");
        $('#vehicleCount').val(counter);
        counter++;
        $('#modalCloseBtn').click();
    }

    function showProductTable(vehicleSerial) {
        var takenProductCount = $("#takenProductCount" + vehicleSerial).val();
        if (typeof takenProductCount === "undefined") {
            var productTableStr = '<tr id="productTakenTr' + vehicleSerial + '1">\n\
                                            <td class="td-left"><div class="form-group form-float"> \n\
                                                <div class="form-line">\n\
                                                        <input type="text" class="form-control custom-form-control1" name="productName' + vehicleSerial + '1" id="productName' + vehicleSerial + '1">\n\
                                                    </div>\n\
                                                </div>\n\
                                            </td>\n\
                                            <td><div class="form-group form-float" >\n\
                                                    <div class="form-line">\n\
                                                        <input type="number" class="form-control custom-form-control" name="productQuantity' + vehicleSerial + '1" id="productQuantity' + vehicleSerial + '1" min="1" value="1">\n\
                                                    </div>\n\
                                                </div>\n\
                                            </td>\n\
                                            <td><div class="form-group form-float" >\n\
                                                    <div class="form-line">\n\
                                                        <input type="text" class="form-control custom-form-control" name="productUnitName' + vehicleSerial + '1" id="productUnitName' + vehicleSerial + '1" >\n\
                                                    </div>\n\
                                                </div>\n\
                                            </td>\n\
                                            <td><i class="fa fa-remove pointer text-danger" onclick="removeProduct(' + vehicleSerial + ',1)"></i></td>\n\
                                        </tr>';

            var newRow = $(document.createElement('div')).attr("id", 'productTableDiv' + vehicleSerial);
            var productTableDiv = '<table class="table table-bordered custom-table" id="productTable' + vehicleSerial + '">\n\
                                    <tr class="bg-info">\n\
                                        <td colspan="4"><b>Vehicle Parts</b></td>\n\
                                    </tr>\n\
                                    <tr>\n\
                                        <td width="50%"><b>Parts Name</b></td>\n\
                                        <td width="15%"><b>Quantity</b></td>\n\
                                        <td width="25%"><b>Unit Name</b></td>\n\
                                        <td width="10%"><b>Action</b></td>\n\
                                    </tr>\n\
                                    ' + productTableStr + '\n\
                                    <input type="hidden" id="takenProductCount' + vehicleSerial + '" name="takenProductCount' + vehicleSerial + '" value="1">\n\
                                </table>';
            newRow.after().html(productTableDiv);
            newRow.appendTo("#vehicleProductDiv" + vehicleSerial);
        } else {
            takenProductCount++;
            var newRow = $(document.createElement('tr')).attr("id", 'productTakenTr' + vehicleSerial + takenProductCount);
            var productTableRowStr = '<td class="td-left"><div class="form-group form-float">\n\
                                                            <div class="form-line">\n\
                                                                <input type="text" class="form-control custom-form-control1" name="productName' + vehicleSerial + takenProductCount + '" id="productName' + vehicleSerial + takenProductCount + '">\n\
                                                            </div>\n\
                                                        </div>\n\
                                            </td>\n\
                                            <td><div class="form-group form-float" >\n\
                                                    <div class="form-line">\n\
                                                        <input type="number" class="form-control custom-form-control" name="productQuantity' + vehicleSerial + takenProductCount + '" id="productQuantity' + vehicleSerial + takenProductCount + '" min="1" value="1">\n\
                                                    </div>\n\
                                                </div>\n\
                                            </td>\n\
                                            <td><div class="form-group form-float" >\n\
                                                    <div class="form-line">\n\
                                                        <input type="text" class="form-control custom-form-control" name="productUnitName' + vehicleSerial + takenProductCount + '" id="productUnitName' + vehicleSerial + takenProductCount + '" >\n\
                                                    </div>\n\
                                                </div>\n\
                                            </td>\n\
                                            <td><i class="fa fa-remove pointer text-danger" onclick="removeProduct(' + vehicleSerial + "," + takenProductCount + ')"></i></td>';
            newRow.after().html(productTableRowStr);
            newRow.appendTo("#productTable" + vehicleSerial);
            $("#takenProductCount" + vehicleSerial).val(takenProductCount);
        }
    }

    function removeProduct(vehicleSerial, productSerial) {
        $('#productTakenTr' + vehicleSerial + productSerial).remove();
        var tableRowCount = $("#productTable" + vehicleSerial + " tr").length;
        if (tableRowCount === 2) {
            $("#productTable" + vehicleSerial).remove();
        }
    }


    function setShowServiceModal(vehicleSerial) {
        $("#vehicleSerial").val(vehicleSerial);
        var serviceVariantCount = $("#serviceVariantCount").val();
        var serviceVarCodeStr = $("#serviceVarCodeStr" + vehicleSerial).val();
        $('#serviceModalShowBtn').click();
        for (var i = 1; i < serviceVariantCount; i++) {
            $('#serviceVarCheckBox' + i).prop('checked', false);
        }
        if (typeof serviceVarCodeStr !== 'undefined') {
            if (serviceVarCodeStr) {
                var serviceVarCodeArr = serviceVarCodeStr.split(',');
                for (var i = 1; i < serviceVariantCount; i++) {
                    if (jQuery.inArray($("#serviceVariantCode" + i).val(), serviceVarCodeArr) !== -1) {
                        $('#serviceVarCheckBox' + i).prop('checked', true);

                    } else {
                        $('#serviceVarCheckBox' + i).prop('checked', false);
                    }
                }
            }
        }
    }

    function setAddService() {
        var vehicleSerial = $("#vehicleSerial").val();
        var serviceVariantCount = $("#serviceVariantCount").val();
        var serviceVariantCode;
        var serviceVariantName;
        var serviceTableStr = "";
        var serviceVarCodeArr = new Array();
        var takenServiceVarCount = 1;
        var quantity;

        var takenServieVarCountFinal = $("#takenServiceVarCount" + vehicleSerial).val();
        if (typeof takenServieVarCountFinal === 'undefined') {
            takenServieVarCountFinal = 0;
        }
        var i = 1;
        for (var x = 1; x < serviceVariantCount; x++) {
            if ($("#serviceVarCheckBox" + x).is(':checked')) {
                serviceVariantCode = $("#serviceVariantCode" + x).val();
                serviceVariantName = $("#serviceVariantName" + x).val();
                quantity = 1;

                for (var j = 1; j < takenServieVarCountFinal; j++) {
                    if ($('#takenServiceVarCode' + vehicleSerial + j).val() === serviceVariantCode) {
                        quantity = $("#srviceQuantity" + vehicleSerial + j).val();
                        // console.log(quantity);
                    }
                }

                serviceTableStr += '<tr id="serviceTakenTd' + vehicleSerial + i + '">\n\
                                        <td class="td-left">' + serviceVariantName + '</td>\n\
                                        <td><div class="form-group form-float" >\n\
                                                <div class="form-line">\n\
                                                    <input type="number" class="form-control custom-form-control" name="srviceQuantity' + vehicleSerial + i + '" id="srviceQuantity' + vehicleSerial + i + '" min="1" value="' + quantity + '">\n\
                                                </div>\n\
                                            </div>\n\
                                        </td>\n\
                                        <td><i class="fa fa-remove pointer text-danger" onclick="removeService(' + vehicleSerial + "," + i + ')"></i></td>\n\
                                        <input type="hidden" id="takenServiceVarCode' + vehicleSerial + i + '" name="takenServiceVarCode' + vehicleSerial + i + '" value="' + serviceVariantCode + '">\n\
                                    </tr>';
                serviceVarCodeArr.push(serviceVariantCode);
                takenServiceVarCount++;
                i++;
            }
        }
        $('#serviceTableDiv' + vehicleSerial).remove();
        if (serviceTableStr !== "") {
            var newRow = $(document.createElement('div')).attr("id", 'serviceTableDiv' + vehicleSerial);
            var serviceTableDiv = '<table class="table table-bordered custom-table">\n\
                                <tr class="bg-info">\n\
                                    <td colspan="3"><b>Service</b></td>\n\
                                </tr>\n\
                                <tr>\n\
                                    <td width="50%"><b>Service Name</b></td>\n\
                                    <td width="20%"><b>Quantity</b></td>\n\
                                    <td width="10%"><b>Action</b></td>\n\
                                </tr>\n\
                                ' + serviceTableStr + '\n\
                                <input type="hidden" id="serviceVarCodeStr' + vehicleSerial + '" value="' + serviceVarCodeArr.join() + '">\n\
                                <input type="hidden" id="takenServiceVarCount' + vehicleSerial + '" name="takenServiceVarCount' + vehicleSerial + '" value="' + takenServiceVarCount + '">\n\
                            </table>';
            newRow.after().html(serviceTableDiv);
            newRow.appendTo("#vehicleServiceDiv" + vehicleSerial);
        }
        $('#serviceModalCloseBtn').click();
    }

    function removeVehicle(vehicleSerial) {
        $('#vehicleDiv' + vehicleSerial).remove();
    }

    function removeService(vehicleSerial, serviceSerial) {
        $('#serviceTakenTd' + vehicleSerial + serviceSerial).remove();
        var serviceVarCodeArr = new Array();
        var takenServiceVarCode;
        var takenServiceVarCount = $('#takenServiceVarCount' + vehicleSerial).val();
        for (var i = 1; i < takenServiceVarCount; i++) {
            takenServiceVarCode = $('#takenServiceVarCode' + vehicleSerial + i).val();

            if (typeof takenServiceVarCode !== 'undefined') {
                serviceVarCodeArr.push(takenServiceVarCode);
            }
        }

        if (serviceVarCodeArr.length !== 0) {
            $('#serviceVarCodeStr' + vehicleSerial).val(serviceVarCodeArr.join());
        } else {
            $('#serviceTableDiv' + vehicleSerial).remove();
        }

    }

    function addRequest(flag) {
        var takenProductCount;
        var productName = "";
        var productQuantity = "";
        var productUnitName = "";
        var takenServiceVarCount;
        var srviceQuantity = "";
        var serviceProductFlag;
        var vehicleFlag = 0;
        var vehicleCount = $('#vehicleCount').val();
        for (var i = 1; i <= vehicleCount; i++) {
            if (typeof $('#vehicleId' + i).val() !== "undefined") {

                serviceProductFlag = 0;
                vehicleFlag = 1;
                //---------- product check ------------//
                takenProductCount = $("#takenProductCount" + i).val();
                for (var j = 1; j <= takenProductCount; j++) {
                    productName = $("#productName" + i + j).val();
                    if (typeof productName !== 'undefined') {
                        serviceProductFlag = 1;
                        if ($.trim(productName) === "") {
                            sweetAlert('Parts Name is required...!');
                            return false;
                        }
                    }
                    productQuantity = $("#productQuantity" + i + j).val();
                    if (typeof productQuantity !== 'undefined') {
                        if ($.trim(productQuantity) === "") {
                            sweetAlert('Parts Quantity is required...!');
                            return false;
                        }
                        if (!$.isNumeric(productQuantity)) {
                            sweetAlert('Parts Quantity must be a numeric value...!');
                            return false;
                        }
                    }
                    productUnitName = $("#productUnitName" + i + j).val();

                    if (typeof productUnitName !== 'undefined') {
                        if ($.trim(productUnitName) === "") {
                            sweetAlert('Parts Unit Name is required...!');
                            return false;
                        }
                    }
                }
                //--------- service check ------------//
                takenServiceVarCount = $("#takenServiceVarCount" + i).val();
                for (var j = 1; j <= takenServiceVarCount; j++) {
                    srviceQuantity = $("#srviceQuantity" + i + j).val();
                    if (typeof srviceQuantity !== 'undefined') {
                        serviceProductFlag = 1;
                        if ($.trim(srviceQuantity) === "") {
                            sweetAlert('Service Quantity is required...!');
                            return false;
                        }
                        if (!$.isNumeric(srviceQuantity)) {
                            sweetAlert('Service Quantity must be a numeric value...!');
                            return false;
                        }
                    }
                }

                if (serviceProductFlag === 0) {
                    sweetAlert('Please take at least one service or vehicle part...!');
                    return false;
                }
            }

        }
        if (vehicleFlag === 0) {
            sweetAlert('Please take at least one vehicle...!');
            return false;
        }

        if ($.trim($('#lastsubmitDate').val()) === "") {
            sweetAlert('Please give Quotation Last Submit Date...!');
            return false;
        }

        $("#saveStatusFlag").val(flag);
        $("#requestForm").submit();
    }
</script>
@endpush