@extends('client.layouts.app')

@section('content')

@php
    $heading = ($data['assignType'] == config('constants.ASSIGN_PERSON')) ? 'PERSON' : 'EN ROUTE';
    $breadcrumbName = ($data['assignType']  == config('constants.ASSIGN_PERSON')) ? 'Person' : 'En Route';
@endphp

<div class="block-header">
    <h2>ASSIGN {{ $heading }}</h2><br>

    <div class="breadcrumb breadcrumb-bg-blue-grey">
        <li>
            <a href="{{ url('client/Home') }}">Home</a>
        </li>

        <li>
            <a href="#">Pool</a>
        </li>

        <li>
            <a href="{{ url('client/VehicleAssign/employeeVehicleAssign') }}">
                Vehicle Assign
            </a>
        </li>

        <li>
            <a href="{{ url('client/VehicleAssign/showEmployeeAssign?vehicleId=' . $data['vehicleId']) }}">
                Assign {{ $breadcrumbName }}
            </a>
        </li>
    </div>
</div>


<div class="row clearfix">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="body">
                <form  action="client/VehicleAssign/assignEmployee" method="POST" id="insertForm">
                    <div class="panel-group">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group form-float" >
                                            <div class="form-line">
                                                <input type="text" class="form-control" value="<?php echo (isset($bookingSummary[0]['person_name'])) ? $bookingSummary[0]['person_name'] : '' ?>" name="personName" id="personName" >
                                                <label class="form-label"> Person Name</label>
                                            </div>
                                            <label id="personNameReq-error" class="error hidden">Person Name is required</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group form-float" >
                                            <div class="form-line">
                                                <input type="text" class="form-control" name="idNo" id="idNo" value="<?php echo (isset($bookingSummary[0]['person_emp_id'])) ? $bookingSummary[0]['person_emp_id'] : '' ?>">
                                                <label class="form-label"> ID No</label>

                                            </div>
                                            <!--<label id="designationReq-error" class="error hidden">Designation Type is required</label>-->
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <input type="text" class="form-control" name="department" id="department" value="<?php echo (isset($bookingSummary[0]['department'])) ? $bookingSummary[0]['department'] : '' ?>" >
                                                <label class="form-label"> Department</label>
                                            </div>
                                            <!--<label id="brandReq-error" class="error hidden">Brand is required</label>-->
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group form-float" >
                                            <div class="form-line">
                                                <input type="text" class="form-control" name="designation" id="designation" value="<?php echo (isset($bookingSummary[0]['designation'])) ? $bookingSummary[0]['designation'] : '' ?>">
                                                <label class="form-label"> Designation</label>
                                            </div>
                                            <!--<label id="brandModelReq-error" class="error hidden">Brand Model is required</label>-->
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <?php
                                    $x = 6;
                                    if ($data['assignType'] == config('constants.ASSIGN_ENROUTE')) {
                                        $x = 6;
                                    }
                                    ?>

                                    <div class="col-md-<?php echo $x ?> col-sm-6 col-xs-12">
                                        <div class="form-group form-float" >
                                            <div class="form-line demo-masked-input">

                                                <?php
                                                        $receivedDtTm = date('Y-m-d H:i');

                                                ?>

                                                <input type="text" class="form-control datetime" value="<?php echo $receivedDtTm?>" name="receiveDate" id="receiveDate">
                                                <!--<label class="form-label"> Received Date</label>-->
                                            </div>
                                            <div class="help-info">Receive Date Time (yyyy-mm-dd h:m)</div>
                                            <label id="receiveDateReq-error" class="error hidden">Receive Date is required</label>
                                        </div>
                                    </div>

                                    <?php
                                    $receivedLocation = "";
                                    $bookingNo = "";
                                    $vehicleRouteJson = "";
                                    if(isset($bookingSummary[0]['route'])) {
                                        $vehicleRouteJson = $bookingSummary[0]['route'];
                                        $routeObj = json_decode($bookingSummary[0]['route']);
                                        $receivedLocation = isset($routeObj[0]->routeFrom) ? $routeObj[0]->routeFrom : '';
                                        $bookingNo = $bookingSummary[0]['booking_no'];
                                    }

                                    ?>

                                    <div class="col-md-<?php echo $x ?> col-md-5 col-sm-5 col-xs-12">
                                        <div class="form-group form-float" >
                                            <div class="form-line">
                                                <input type="text" class="form-control" name="location" id="location" value="<?php echo $receivedLocation ?>">

                                            </div>
                                            <div class="help-info">Received Location</div>
                                            <input type="hidden" name="route_json" value='{{ $data['bookingSummary'][0]['route'] ?? '' }}'>
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-sm-1 col-xs-12">
                                        <?php
                                        if (isset($data['locationBtnFlag'])) {
                                            ?>
                                            <button type="button" class="btn bg-red waves-effect btn-xs" onclick="getCurrentLocation()"><i class="material-icons">location_on</i></button>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <?php
                                    if ($data['assignType'] == config('constants.ASSIGN_ENROUTE')) {
                                        ?>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" value="{{ $data['routeStr'] }}" name="route" id="route" >
                                                    <label class="form-label"> Route </label>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <textarea  class="form-control" name="notes"><?php echo (isset($bookingSummary[0]['remarks'])) ? $bookingSummary[0]['remarks'] : '' ?></textarea>
                                                <label class="form-label"> Notes</label>
                                            </div>
                                            <!--<label id="brandReq-error" class="error hidden">Brand is required</label>-->
                                        </div>
                                    </div>

                                </div>
                                <button type="button" class="btn bg-blue waves-effect" onclick="insertVehicle()">Save</button>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="vehicle" id="vehicle" value="{{ $vehicleId ?? '' }}">
                    <input type="hidden" name="assignType" value="{{ $assignType ?? '' }}">
                    <input type="hidden" name="bookingNo" value="{{ $bookingNo ?? '' }}">
                </form>

                <!--                <button class="btn bg-red waves-effect" onclick="clearAllField()">Clear</button>-->
            </div> <!-- end class=body -->
        </div> <!-- end class="card" -->
    </div> <!-- end class="col-xs-12 col-sm-12 col-md-12 col-lg-12" -->
</div> <!-- end class="row clearfix" -->

<script>
    $(function () {
        var $demoMaskedInput = $('.demo-masked-input');
        $demoMaskedInput.find('.datetime').inputmask('y-m-d h:s', {placeholder: '____-__-__ __:__', alias: "datetime", hourFormat: '24'});

    });
</script>
    
@endsection
@push('scripts')
<script>
    function insertVehicle() {
        var errorMsg = "";
        // filed id, error div id
        var fieldsArr = new Array("personName|personNameReq-error", "receiveDate|receiveDateReq-error");  // filed id, error div id
        var inputFiledJsonData = getInputData(fieldsArr);
        if (!inputFiledJsonData) {
            errorMsg += getReuiredFiledErrorMsg();
            showErrorMsg(errorMsg);
            return false;  // required filed check
        } else {
            hideErrorDiv();
        }

        var receiveDateTime = $.trim($('#receiveDate').val());
        if (checkDateTime(receiveDateTime) === 0) {
            sweetAlert('Date Time is not in correct format...!');
            return false;
        }
        showLoader();
        $.ajax({
            type: 'POST',
            data: {receiveDateTime: receiveDateTime, vehicle: $('#vehicle').val()},
            url: 'client/VehicleAssign/checkReceiveDateInsert',
            success: function (result) {
                hideLoader();
                if (result === '1') {
                    $('#insertForm').submit();
                } else {
                    sweetAlert('This vehicle was used by another person on this Receive Date Time...!');
                }
            }
        });
    }

    function getCurrentLocation() {
        var vehicleId = '{{ $data['vehicleId'] }}';
        showLoader();
        $.ajax({
            type: 'POST',
            data: {vehicleId: vehicleId},
            url: 'client/VehicleAssign/getCurrentLocation',
            success: function (result) {
                hideLoader();
                var resultObj = jQuery.parseJSON(result);
                hideLoader();
                if (resultObj.success === 1) {
                    $('#location').val(resultObj.address);
                } else if (resultObj.success === 2) {
                    sweetAlert('No VTS APP Key found...!');
                    return false;
                } else if (resultObj.success === 3) {
                    sweetAlert('No Vehicle found...!');
                    return false;
                }

            },
            error: function () {
                hideLoader();
                sweetAlert('Fail to get current location...!');
            }
        });
    }
</script>
@endpush
