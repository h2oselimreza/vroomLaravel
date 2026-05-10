@extends('client.layouts.app')
@section('content')
<style>
    .default-reminder-table{border-collapse: collapse;outline: 1px solid #ddd;}
    .default-reminder-table td{font-size: 13px;padding-left: 5px;padding-right: 5px;}
    .default-reminder-table th{background-color: #eee;font-size: 13px;text-align: center;padding: 8px}
    .left-border{border-left: 1px solid #ddd;} 
    .right-border{ border-right: 1px solid #ddd;} 
    .top-border{border-top: 1px solid #ddd;; } 
    .bottom-border{border-bottom: 1px solid #ddd;;} 
    .reminderOnDateNotCustomDiv{
        border-bottom:2px solid #1f91f3; padding-top:13px;
    }
</style>

<div class="block-header">
    <h2>ADD REMINDER</h2><br>
    <div class="breadcrumb breadcrumb-bg-blue-grey">
        <li><a href="/client/Home"> Home</a></li>
        <li><a href="#"> Reminder</a></li>
        <li><a href="{{ route('client.reminder.set-reminder.index') }}">Reminder List</a></li>
        <li><a href="{{ route('client.reminder.set-reminder.create') }}">Add Reminder</a></li>
    </div>
</div>

<div class="row clearfix">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="body">
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
                <?php
                $reminderTypeArr = unserialize(config('constants.VEHICLE_REMINDER_TYPE_ARR'));
                $reminderForArr = unserialize(config('constants.REMINDER_FOR_ARR'));
                ?>
                <form action="{{ route('client.reminder.set-reminder.create') }}" method="post" id="reminderTypeForm">
                    @csrf
                    <input type="hidden" name="reminderFor" id="reminderForHidden">
                    <input type="hidden" name="vehicleRegHidden" id="vehicleRegHidden">
                    <input type="hidden" name="vehicleIdHidden" id="vehicleIdHidden">
                </form>
                <form action="{{ route('client.reminder.set-reminder.store') }}" method="post" id="addReminderForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group form-float" >
                                @php
                                    $optionStr = '';
                                    $reminderForName = '';
                                @endphp

                                <div class="form-line">

                                    <select
                                        class="form-control"
                                        id="reminderFor"
                                        name="reminderFor"
                                        onchange="changeReminderFor(this.value)"
                                    >

                                        @php

                                            foreach ($reminderForArr as $key => $value) {

                                                if ($reminderFor == $key) {

                                                    $reminderForName = $value;
                                                }

                                                if ($key != $reminderFor) {

                                                    $optionStr .= "<option value='$key'>$value</option>";
                                                }
                                            }

                                            if ($reminderFor) {

                                                echo "<option value='$reminderFor'>$reminderForName</option>";
                                            }

                                            echo $optionStr;

                                        @endphp

                                    </select>

                                    <div class="help-info">
                                        Reminder For
                                    </div>

                                </div>
                            </div>	
                        </div>

                        <?php
                        if ($reminderFor == 'vehicle') {
                            ?>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group form-float" >
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="vehicleRegNo" id="vehicleRegNo" onclick="vehicleModalShow()" value="<?php echo $vehicleRegNo ?>" readonly>
                                        <input type="hidden" class="form-control" name="reminderForValue" value="<?php echo $vehicleId ?>" id="vehicleId">

                                        <input type="hidden" class="form-control" name="vehicleFitness" value="" id="vehicleFitness">
                                        <input type="hidden" class="form-control" name="vehicleTaxToken" value="" id="vehicleTaxToken">
                                        <input type="hidden" class="form-control" name="vehicleInsurance" value="" id="vehicleInsurance">

                                    </div>
                                    <div class="help-info">Vehicle</div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group form-float" >
                                    <div class="form-line">
                                        <select class="form-control" id="reminderType" name="reminderType" onchange="changeReminderType(this.value)">
                                            <?php
                                            $optionStr = "";
                                            foreach ($reminderTypeArr as $key => $value) {
                                                $optionStr .= "<option value='$key'>$value</option>";
                                            }
                                            echo $optionStr;
                                            ?>
                                        </select>
                                        <div class="help-info">Reminder Type</div>
                                    </div>
                                </div>	
                            </div>

                            <?php
                        }
                        ?>
                    </div>

                    <div class="row">
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group form-float" >
                                <label class="form-label"> Reminder On Date</label>
                                <div class="" id="reminderOnDateNotCustom" style="">

                                </div>
                                <input type="hidden" id="reminderOnDateHidden">
                                <div class="form-line" id="reminderOnDateDiv">
                                    <input type="text" class="form-control dateInput" name="reminderOnDate" id="reminderOnDate">
                                </div>
                                <!-- 2px solid #1f91f3 padding-top:13px -->
                            </div>
                        </div>
                        <div class="col-md-8 col-sm-8 col-xs-12">
                            <div class="form-group form-float" >
                                <label class="form-label"> Reminder Heading </label>
                                <div class="form-line">
                                    <input type="text" maxlength="100" class="form-control" name="reminderHeading" id="reminderHeading" >
                                </div>
                            </div>
                        </div>

                    </div>
                    <div id="vehicleDataDiv">
                    </div>
                    <div class="row">  
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group form-float" >
                                <label class="form-label"> Reminder Body </label>
                                <div class="form-line">
                                    <input type="text" class="form-control" name="reminderBody" id="reminderBody" onkeyup="getBodyCursorValue()" onmouseup="getBodyCursorValue()" >
                                    <input type="hidden" id="bodyCursorPosition">
                                </div>
                                <div class="help-info">SMS Count: <span id="messagePartDiv"></span></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group form-float" >
                                <label class="form-label"> Repeat Every </label>
                                <div class="form-line">
                                    <input type="text" class="form-control" name="repeatEvery" id="repeatEvery">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group form-float" >
                                <label class="form-label"><div class="p-t-15"></div></label>
                                <div class="form-line">
                                    <select class="form-control" id="repeatType" name="repeatType">
                                        <option value="day">Day(s)</option>
                                        <option value="week">Week(s)</option>
                                        <option value="month">Month(s)</option>
                                        <option value="year">Year(s)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group form-float" >
                                <label class="form-label"> Show reminder</label>
                                <div class="form-line">
                                    <input type="text" class="form-control" name="beforeReminderCount" id="beforeReminderCount">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group form-float" >
                                <label class="form-label"><div class="p-t-15"></div></label>
                                <div class="form-line">
                                    <select class="form-control" id="beforeReminderType" name="beforeReminderType">
                                        <option value="day">Day(s)</option>
                                        <option value="week">Week(s)</option>
                                        <option value="month">Month(s)</option>
                                        <option value="year">Year(s)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group form-float" >
                                <div class="p-t-30">Before Reminder On Date</div>
                            </div>
                        </div>
                    </div>


                    <?php
                    $mobileStr = "";
                    $emailStr = "";
                    $defaultMobileArr = array();
                    $defaultEmailArr = array();
                    foreach ($defaultReminders as $defaultReminder) {
                        if ($defaultReminder['reminder_channel_type'] == 'mobileNo') {
                            $mobileStr .= $defaultReminder['reminder_no'] . "<br>";
                            $defaultMobileArr[] = $defaultReminder['reminder_no'];
                        }
                        if ($defaultReminder['reminder_channel_type'] == 'email') {
                            $emailStr .= $defaultReminder['reminder_no'] . "<br>";
                            $defaultEmailArr[] = $defaultReminder['reminder_no'];
                        }
                    }
                    ?>

                    <input type="hidden" name="defaultMobileNo" value="<?php echo implode(',', $defaultMobileArr) ?>">
                    <input type="hidden" name="defaultEmail" value="<?php echo implode(',', $defaultEmailArr) ?>">

                    <div class="table-responsive">
                        <!--<table class="m-t-10 m-b-10" border="1" cellpadding="0" cellspacing="0" align="center" width="100%">-->
                        <table class="default-reminder-table" cellpadding="0" cellspacing="0" width="100%">
                            <tr>
                                <th width="100%" align="center" colspan="4" class="bottom-border right-border left-border"><b>Default Reminder To</b></th>
                            </tr>
                            <tr>
                                <td width="10%" align="center" class="bottom-border left-border">
                                    <?php
                                    if ($mobileStr) {
                                        ?>
                                        <input type="checkbox" name="defaultMobileCheck" id="defaultMobileCheck" class="filled-in chk-col-blue" checked>
                                        <label for="defaultMobileCheck" class="form-label m-l-20 m-b--10"></label>
                                        <?php
                                    }
                                    ?>
                                </td>
                                <td width="40" align="left" class="bottom-border right-border">
                                    <?php
                                    if ($mobileStr) {
                                        echo $mobileStr;
                                    } else {
                                        echo "There is no Default mobile no set for reminder";
                                    }
                                    ?>


                                </td>
                                <td width="10%" align="center" class="bottom-border left-border">
                                    <?php
                                    if ($emailStr) {
                                        ?>
                                        <input type="checkbox" name="defaultEmailCheck" id="defaultEmailCheck" class="filled-in chk-col-blue" checked>
                                        <label for="defaultEmailCheck" class="form-label m-l-20 m-b--10"></label>
                                        <?php
                                    }
                                    ?>
                                </td>
                                <td width="40%" align="left" class="bottom-border right-border">
                                    <?php
                                    if ($emailStr) {
                                        echo $emailStr;
                                    } else {
                                        echo "There is no Default email set for reminder";
                                    }
                                    ?>

                                </td>
                            </tr>
                        </table>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group form-float" >
                                <label class="form-label"> Add more Mobile no  </label>
                                <div class="form-line">
                                    <input type="text" class="form-control" data-role="tagsinput" id="addMoreMobileNo" name="moreMobileNo">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group form-float" >
                                <label class="form-label"> Add more Email  </label>
                                <div class="form-line">
                                    <input type="text" class="form-control" data-role="tagsinput" id="addMoreEmail" name="moreEmail">
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn bg-blue waves-effect" onclick="addReminder()">Add Reminder</button>
                </form>





                <!-- --------------- vehicle modal -------------------- -->
                <button type="button" class="btn hidden" data-toggle="modal" id="vehicleModalBtn" data-target="#vehicleModal"></button>
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
                                            @php
                                                $vehicleSerial = 1;
                                            @endphp
                                            @if (isset($vehicles) && $vehicles != "")

                                                @foreach ($vehicles as $vehicle)

                                                    <tr>

                                                        <td>
                                                            {{ $vehicleSerial }}
                                                        </td>

                                                        <td class="td-left">

                                                            <a
                                                                target="_blank"
                                                                href="{{ url('client/Home/vehicleDashboard?vehicleId=' . $vehicle->vehicle_id) }}"
                                                            >
                                                                {{ $vehicle->registration_no }}
                                                            </a>

                                                        </td>

                                                        {{-- <td class='td-left'>{{ $vehicle->registration_no }}</td> --}}

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
                                                            <i
                                                                class="material-icons pointer"
                                                                onclick="addVehicle({{ $vehicleSerial }})"
                                                            >
                                                                arrow_drop_down_circle
                                                            </i>
                                                        </td>

                                                        <input
                                                            type="hidden"
                                                            id="vehicleIdModalHidden{{ $vehicleSerial }}"
                                                            value="{{ $vehicle->vehicle_id }}"
                                                        >

                                                        <input
                                                            type="hidden"
                                                            id="vehicleRegModalHidden{{ $vehicleSerial }}"
                                                            value="{{ $vehicle->registration_no }}"
                                                        >

                                                        <input
                                                            type="hidden"
                                                            id="fitnessVaildToDate{{ $vehicleSerial }}"
                                                            value="{{ $vehicle->fitness_validity_todate }}"
                                                        >

                                                        <input
                                                            type="hidden"
                                                            id="taxPeroidToDate{{ $vehicleSerial }}"
                                                            value="{{ $vehicle->tax_period_to_date }}"
                                                        >

                                                        <input
                                                            type="hidden"
                                                            id="insuranceToDate{{ $vehicleSerial }}"
                                                            value="{{ $vehicle->insurance_to_date }}"
                                                        >

                                                    </tr>

                                                    @php
                                                        $vehicleSerial++;
                                                    @endphp

                                                @endforeach
                                            @endif
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
            </div>
        </div>
    </div>
</div>
    
@endsection
@push('scripts')
<script>

    var showReminderTime = '{{ config('constants.SHOW_REMINDER_TIME') }}';
    function changeReminderType(reportType) {
        $('#reminderForHidden').val($('#reminderFor').val());

        var vehicleRegNo = $('#vehicleRegNo').val();
        var customFlag = 1;
        var reminderOnDate = "";
        var reminderHeading = "";
        var reminderBody = "";
        var reminderOnDateCustomDiv = "";
        if (reportType === 'fitness') {
            customFlag = 0;
            reminderOnDate = $('#vehicleFitness').val();
            reminderHeading = 'Fitness Issue';
            reminderBody = 'Fitness valid to date is ' + reminderOnDate + ' of your vehicle ' + vehicleRegNo;
        } else if (reportType === 'taxtoken') {
            customFlag = 0;
            reminderOnDate = $('#vehicleTaxToken').val();
            reminderHeading = 'Tax Token Issue';
            reminderBody = 'Last Tax token period date is ' + reminderOnDate + ' of your vehicle ' + vehicleRegNo;
        } else if (reportType === 'insurance') {
            customFlag = 0;
            reminderOnDate = $('#vehicleInsurance').val();
            reminderHeading = 'Insurance Issue';
            reminderBody = 'Insurance valid to date is ' + reminderOnDate + ' of your vehicle ' + vehicleRegNo;
        } else if (reportType === 'custom') {
            $('#reminderOnDateDiv').show();
            $('#reminderOnDate').val('');
        }

        if (customFlag === 0) {
            $('#reminderOnDateDiv').hide();
            reminderOnDateCustomDiv = '<div class="reminderOnDateNotCustomDiv">' + reminderOnDate + '</div>';

            if (reminderOnDate === "") {
                if ($('#vehicleId').val() === "") {
                    sweetAlert('Please select vehicle');
                } else {
                    sweetAlert('Data not found of this Reminder type...!');
                }

                reminderOnDate = "";
                reminderHeading = "";
                reminderBody = "";
                reminderOnDateCustomDiv = "";
                $('#vehicleRegNo').val('');
                $('#vehicleId').val('');
                $('#vehicleFitness').val('');
                $('#vehicleTaxToken').val('');
                $('#vehicleInsurance').val('');
                reminderOnDateCustomDiv = '<div class="reminderOnDateNotCustomDiv"> <br></div>';

            }
        }
        $('#reminderOnDateHidden').val(reminderOnDate);
        $('#reminderOnDateNotCustom').html(reminderOnDateCustomDiv);
        $('#reminderHeading').val(reminderHeading);
        $('#reminderBody').val(reminderBody);
        countSms();
        var optionStr = '<option value="' + vehicleRegNo + '">' + vehicleRegNo + '</option>';
        if (reminderOnDate !== "") {
            optionStr += '<option value="' + reminderOnDate + '">' + reminderOnDate + '</option>';
        }
        var vehicleDataDropDown = '<div class="row hidden">\n\
                                        <div class="col-md-12 col-sm-12 col-xs-12">\n\
                                            <div class="form-group form-float" >\n\
                                                <label class="form-label"> Vehicle Data </label>\n\
                                                <div class="form-line">\n\
                                                    <select  class="form-control" id="vechileData" onchange="setValueToBody(this.value)">\n\
                                                        ' + optionStr + '\n\
                                                    </select>\n\
                                                </div>\n\
                                            </div>\n\
                                        </div>\n\
                                    </div>';
        $('#vehicleDataDiv').html(vehicleDataDropDown);

    }

    function changeReminderFor(reminderFor) {
        console.log(reminderFor);
        $('#reminderForHidden').val(reminderFor);
        $('#reminderTypeForm').submit();
    }

    function vehicleModalShow() {
        $('#vehicleModalBtn').click();
    }

    function addVehicle(vehicleSerial) {
        var vehicleId = $('#vehicleIdModalHidden' + vehicleSerial).val();
        var vehicleRegNo = $('#vehicleRegModalHidden' + vehicleSerial).val();

        var fitness = $('#fitnessVaildToDate' + vehicleSerial).val();
        var taxPeriod = $('#taxPeroidToDate' + vehicleSerial).val();
        var insurance = $('#insuranceToDate' + vehicleSerial).val();

        $('#vehicleRegNo').val(vehicleRegNo);
        $('#vehicleId').val(vehicleId);
        $('#vehicleFitness').val(fitness);
        $('#vehicleTaxToken').val(taxPeriod);
        $('#vehicleInsurance').val(insurance);

        var reminderType = $('#reminderType').val();
        changeReminderType(reminderType);


        $('#modalCloseBtn').click();
    }

    function addReminder() {

        if ($('#reminderFor').val() === 'vehicle') {
            if ($('#vehicleId').val() === "") {
                sweetAlert('Please select a vehicle...!');
                return false;
            }

            if ($('#reminderType').val() !== "custom") {
                $('#reminderOnDate').val($('#reminderOnDateHidden').val());
            }
        }



        if ($.trim($('#reminderOnDate').val()) === "") {
            sweetAlert('Reminder On Date is required...!');
            return false;
        }

        if ($.trim($('#reminderHeading').val()) === "") {
            sweetAlert('Reminder Heading is required...!');
            return false;
        }

        if ($.trim($('#reminderBody').val()) === "") {
            sweetAlert('Reminder Body is required...!');
            return false;
        }

        //----------- repeat every ----------------//
        var repeatEvery = $.trim($('#repeatEvery').val());
        if (repeatEvery === "") {
            sweetAlert('Repeat Every is required...!');
            return false;
        }
        if (!$.isNumeric(repeatEvery)) {
            $('#repeatEvery').val('');
            sweetAlert('Repeat Every must be a numeric value...!');
            return false;
        }

        //----------- Show reminder ----------------//
        var beforeReminderCount = $.trim($('#beforeReminderCount').val());
        if (beforeReminderCount === "") {
            sweetAlert('Show reminder is required...!');
            return false;
        }

        if (!$.isNumeric(beforeReminderCount)) {
            $('#beforeReminderCount').val('');
            sweetAlert('Show reminder must be a numeric value...!');
            return false;
        }

        var reminderOnDate = $('#reminderOnDate').val() + ' ' + showReminderTime;
        var currentDate = new Date();
        reminderOnDate = new Date(reminderOnDate);

        //console.log($('#repeatEvery').val());

        if (currentDate >= reminderOnDate && $.trim($('#repeatEvery').val()) === "0") {
            sweetAlert('When Reminder date is greater than current date then yot can not set Repeat Every value "0"...!');
            return false;
        }

        var addMoreMobileNo = $.trim($('#addMoreMobileNo').val());
        var addMoreEmail = $.trim($('#addMoreEmail').val());

        var addMoreMobileNoArr = addMoreMobileNo.split(',');
        for (var i = 0; i < addMoreMobileNoArr.length; i++) {
            if (addMoreMobileNoArr[i] !== "") {
                if (validMobileNoCheck(addMoreMobileNoArr[i]) === 2) {
                    sweetAlert('The mobile no "' + addMoreMobileNoArr[i] + '" is wrong. Please enter valid mobile number...! eg. 88017XXXXXXXX');
                    return false;
                }
            }
        }

        var addMoreEmailArr = addMoreEmail.split(',');
        for (var i = 0; i < addMoreEmailArr.length; i++) {
            if (addMoreEmailArr[i] !== "") {
                if (validEmail(addMoreEmailArr[i]) === 2) {
                    sweetAlert('The email "' + addMoreEmailArr[i] + '" is wrong. Please enter valid email address...! eg. name@domain.com');
                    return false;
                }
            }
        }
        var defaultMobileCheck = $("#defaultMobileCheck").is(':checked');
        var defaultEmailCheck = $("#defaultEmailCheck").is(':checked');
        if (defaultMobileCheck || defaultEmailCheck || addMoreMobileNo !== "" || addMoreEmail !== "") {
            $('#addReminderForm').submit();
        } else {
            sweetAlert('No Email or Mobile no found to add this reminder...!');
        }
    }

    function setValueToBody(bodyValue) {

        var reminderOldBody = $('#reminderBody').val();
        var position = $('#bodyCursorPosition').val();
        var reminderNewBody = [reminderOldBody.slice(0, position), bodyValue, reminderOldBody.slice(position)].join('');
        $('#reminderBody').val(reminderNewBody);
        countSms();
    }

    var charset7bit = ['@', '£', '$', '¥', 'è', 'é', 'ù', 'ì', 'ò', 'Ç', "\n", 'Ø', 'ø', "\r", 'Å', 'å', 'Δ', '_', 'Φ', 'Γ', 'Λ', 'Ω', 'Π', 'Ψ', 'Σ', 'Θ', 'Ξ', 'Æ', 'æ', 'ß', 'É', ' ', '!', '"', '#', '¤', '%', '&', "'", '(', ')', '*', '+', ',', '-', '.', '/', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', ':', ';', '<', '=', '>', '?', '¡', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'Ä', 'Ö', 'Ñ', 'Ü', '§', '¿', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'ä', 'ö', 'ñ', 'ü', 'à'];
    var charset7bitext = ["\f", '^', '{', '}', '\\', '[', '~', ']', '|', '€'];

    function getBodyCursorValue() {
        el = document.getElementById('reminderBody');
        var val = el.value;
        var cursorPosition = val.slice(0, el.selectionStart).length;
        $('#bodyCursorPosition').val(cursorPosition);

        countSms();
    }

    function countSms() {
        var content = $('#reminderBody').val();
        var chars_arr = content.split("");
        var coding = '7bit';
        var parts = 1;
        var chars_used = 0;
        for (i = 0; i < chars_arr.length; i++) {
            if (charset7bit.indexOf(chars_arr[i]) >= 0) {
                chars_used = chars_used + 1;
            } else if (charset7bitext.indexOf(chars_arr[i]) >= 0) {
                chars_used = chars_used + 2;
            } else {
                coding = '16bit';
                chars_used = chars_arr.length;
                break;
            }
        }

        if (coding === '7bit') {
            if (chars_used > 160) {
                parts = Math.ceil(chars_used / 153);
            }
        } else {
            if (chars_used > 70) {
                parts = Math.ceil(chars_used / 67);
            }
        }

        $('#messagePartDiv').text(parts);
    }

</script>
@endpush