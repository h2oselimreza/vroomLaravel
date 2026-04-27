@extends('layouts.app')

@section('content')

<link href="{{ asset('assets/select_bo/css/calendar/fullcalendar.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/select_bo/css/calendar/fullcalendar.print.css') }}" rel="stylesheet" media="print" />

<style>
    .panel-group{
        margin-bottom: 0px;
    }
    .custom-panel-title1{
        font-size: 13px;
        font-weight: bold;
    }
    .custom-panel-heading{
        padding: 8px 14px;
    }
    .panel-group .panel1{
        margin-bottom: 5px;
    }
    .custom1-panel-body{
        font-size: 12px;
    }
    .content-table-td{font-size: 12px}
    .custom-form-control{
        height: 20px;
        font-size: 12px;
        text-align: right;
    }

    .table#clientListTable tr td,th{
        padding: 2px 2px;
        vertical-align: middle;
        text-align: center;
        font-size: 13px;
    }
</style>

<div class="header dashboard_from">
    <h1 class="page-title">Raise Home Service</h1>
    <ul class="breadcrumb">
        <li><a href="#">Home</a></li>
        <li><a href="/admin/home/home-service-list">/ Home Service</a></li>
        <li><a href="/admin/home/home-service-list">/ Raise Home Service</a></li>
    </ul>
</div>
<div class="main-content">
    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Error Message -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel panel-default"> 
                <div id="errorDiv" class="alert alert-danger hidden">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                </div>
                <form action="{{ route('admin.home-service.add-raise-home-service') }}" id="submitForm" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group" >
                                <label class="form-label"> Client ID</label><span class="text-danger">*</span><small class="hidden custom-text-danger" id="clientIdReq-error"> Client ID is Required</small>
                                <input type="text" name="clientId" id="clientId" class="form-control"  placeholder="Click here to select client" onclick="showClientList()" readonly value="{{ $companyInfo->company_code ?? null }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group" >
                                <label class="form-label"> Name</label><span class="text-danger">*</span><small class="hidden custom-text-danger" id="nameReq-error"> Name is Required</small>
                                <input type="text" class="form-control" name="name" id="name" value="{{ $data['companyInfo']->title ?? null }}">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group" >
                                <label class="form-label"> Mobile</label><span class="text-danger">*</span><small class="hidden custom-text-danger" id="mobileReq-error"> Mobile No is Required</small>
                                <input type="text" class="form-control"  name="mobile" id="mobile" onchange="checkMobileNumber(this.value, this.id)" value="{{ $data['companyInfo']->company_mobile ?? null }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group" >
                                <label class="form-label"> Address</label><span class="text-danger">*</span><small class="hidden custom-text-danger" id="addressReq-error"> Address is Required</small>
                                <input type="text" class="form-control"  name="address" id="address" value="{{ $data['companyInfo']->address ?? null }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-md-6 col-xs-12">
                            <div class="form-group">
                                <label class="form-label">Confirm Date</label><span class="text-danger">*</span><small class="hidden custom-text-danger" id="confirmDateReq-error"> Confirm Date is Required</small>
                                <input type="text" class="form-control dateInput" name="confirmDate" id="confirmDate" >
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-xs-12">
                            <div class="form-group">
                                <label class="form-label">Confirm Time</label><span class="text-danger">*</span><small class="hidden custom-text-danger" id="confirmTimeReq-error"> Confirm Time is Required</small>
                                <input type="text" class="form-control timepicker" name="confirmTime" id="confirmTime">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <label class="form-label" data-error="wrong" data-success="right" for="comment">Vroom Comment</label>
                            <textarea id="vroomComment" id="vroomComment" name="vroomComment" style="resize:none" class="form-control validate" rows="5"></textarea>                    
                        </div>
                    </div>
                    <br>
                    <div id="vehicleServiceDiv">
                        <div id="serviceTableDiv">
                            <table class="table table-bordered custom-table">
                                <tr class="bg-info">
                                    <th colspan="5"><b>Service</b></th>
                                </tr>
                                <tr>
                                    <th width="50%"><b>Service Name</b></th>
                                    <th width="20%"><b>Price</b></th>
                                    <th width="10%"><b>Quantity</b></th>
                                    <th width="10%"><b>Amount (BDT)</b></th>
                                    <th width="10%"><b>Action</b></th>
                                </tr>
                                @php
                                    $i = 1;
                                    $serviceVarCodeArr = [];
                                @endphp
                                @if (isset($data['homeServiceDetails']))
                    
                                    @foreach ($data['homeServiceDetails'] as $homeServiceDetail)

                                        @php
                                            $serviceVarCodeArr[] = $homeServiceDetail['service_variant'];
                                        @endphp

                                        <tr id="serviceTakenTd{{ $i }}">

                                            <td class="td-left">
                                                {{ $homeServiceDetail['service_variant_name'] }}
                                            </td>

                                            <td class="td-left">
                                                BDT {{ $homeServiceDetail['unit_price'] }} Per {{ $homeServiceDetail['unit_name'] }}
                                            </td>

                                            @if ($appointmentSummary->status == APPOINTMENT_PROCCESSING || $appointmentSummary->status == APPOINTMENT_ACCEPT)

                                                <td>
                                                    <input class="form-control custom-form-control"
                                                        type="number"
                                                        min="0"
                                                        value="{{ $homeServiceDetail['quantity'] }}"
                                                        onkeyup="calculateGrandTotal({{ $i }})"
                                                        onchange="calculateGrandTotal({{ $i }})"
                                                        name="quantity{{ $i }}"
                                                        id="quantity{{ $i }}">
                                                </td>

                                            @else

                                                <td class="td-center">
                                                    {{ $homeServiceDetail['quantity'] }}
                                                </td>

                                            @endif

                                            <td class="td-right" id="amountTd{{ $i }}">
                                                {{ $homeServiceDetail['total_amount'] }}
                                            </td>

                                            @if ($appointmentSummary->status == APPOINTMENT_PROCCESSING || $appointmentSummary->status == APPOINTMENT_ACCEPT)

                                                <td class="td-center">

                                                    <i class="fa fa-remove pointer text-danger"
                                                    onclick="removeService({{ $i }})"></i>

                                                    <input type="hidden"
                                                        id="takenServiceVarCode{{ $i }}"
                                                        name="takenServiceVarCode{{ $i }}"
                                                        value="{{ $homeServiceDetail['service_variant'] }}">

                                                    <input type="hidden"
                                                        id="takenServiceUnitPrice{{ $i }}"
                                                        name="takenServiceUnitPrice{{ $i }}"
                                                        value="{{ $homeServiceDetail['unit_price'] }}">

                                                    <input type="hidden"
                                                        id="amount{{ $i }}"
                                                        name="amount{{ $i }}"
                                                        value="{{ $homeServiceDetail['total_amount'] }}">

                                                </td>

                                            @endif

                                        </tr>

                                        @php $i++; @endphp

                                    @endforeach

                                @endif
                                <tr>
                                    <td colspan="3" class="td-right"><b>Total</b></td>
                                    <td class="td-right" id="totalAmount"> {{ $data['appointmentSummary']->grand_total ?? null }}</td>
                                    <td></td>
                                </tr>
                            </table
                            <input type="hidden" id="serviceVarCodeStr" value="<?php echo implode(',', $serviceVarCodeArr) ?>">
                            <input type="hidden" id="takenServiceVarCount" name="takenServiceVarCount" value="<?php echo $i ?>">
                        </div>
                    </div>
                    <button type="button" class="btn btn-info btn-xs save_button" onclick="setShowServiceModal()" ><i class="fa fa-plus"></i> Add Service</button>
                    <br><br>
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group" >
                                <label class="form-label">Leads By</label><span class="text-danger">*</span><small class="hidden custom-text-danger" id="leadsByReq-error"> Leads By is Required</small>
                                <select class="form-control" name="leadsBy" id="leadsBy">
                                    <option value=""></option>
                                    <?php foreach ($data['leads'] as $lead) { ?>
                                        <option value="<?php echo $lead->element_code ?>"><?php echo $lead->element ?></option>
                                    <?php }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
                <hr>
                <button type="button" id="saveBtn" class="btn btn-success save_button mt-3" onclick="raiseHomeService()">Raise Home Service</button>
                <br><br>
                <!-- --------------- service modal -------------------- -->
                <button class="btn btn-default btn-sm waves-effect hidden" data-toggle="modal" data-target="#serviceModal" id="serviceModalShowBtn"></button>
                <div class="modal fade" id="serviceModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h4 class="modal-title" id="largeModalLabel">Service</h4>
                            </div>

                            <div class="modal-body">

                                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

                                    @php
                                        $flag = 1;
                                        $serviceCount = 1;
                                    @endphp

                                    @if (!empty($data['distinctServices']))

                                        @foreach ($data['distinctServices'] as $distinctService)

                                            <div class="panel panel1 panel-default">

                                                <div class="panel-heading custom-panel-heading" role="tab">

                                                    <p class="panel-title custom-panel-title1 p-t-0 p-b-0">

                                                        <a role="button"
                                                        data-toggle="collapse"
                                                        data-parent="#accordion"
                                                        href="#generalCollapseOne{{ $distinctService->service }}"
                                                        aria-expanded="true"
                                                        aria-controls="generalCollapseOne{{ $distinctService->service }}">

                                                            <i class="fa fa-tags"></i>
                                                            {{ $distinctService->service_name }}

                                                        </a>

                                                    </p>

                                                </div>

                                                <div id="generalCollapseOne{{ $distinctService->service }}"
                                                    class="panel-collapse"
                                                    role="tabpanel">

                                                    <div class="panel-body">

                                                        <table class="table table-striped custom-table">

                                                            @php
                                                                $serviceVarSerial = 1;
                                                            @endphp

                                                            @foreach ($data['serviceVariants'] as $serviceVariant)

                                                                @if (
                                                                    isset($distinctService->service) &&
                                                                    isset($serviceVariant->service) &&
                                                                    $serviceVariant->service == $distinctService->service
                                                                )

                                                                    @php $flag = 0; @endphp

                                                                    <tr>

                                                                        <td>{{ $serviceVarSerial }}</td>

                                                                        <td class="td-left" style="width:80%">
                                                                            {{ $serviceVariant->service_variant_name ?? '' }}
                                                                        </td>

                                                                        <td class="td-right" style="width:15%">
                                                                            BDT {{ $serviceVariant->unit_price ?? '' }}
                                                                        </td>

                                                                        <td class="td-left" style="width:5%">
                                                                            {{ $serviceVariant->unit_name ?? '' }}
                                                                        </td>

                                                                        <td class="td-left">
                                                                            <input type="checkbox"
                                                                                name="serviceVarCheckBox{{ $serviceCount }}"
                                                                                id="serviceVarCheckBox{{ $serviceCount }}">
                                                                        </td>

                                                                        <input type="hidden"
                                                                            id="serviceVariantCode{{ $serviceCount }}"
                                                                            value="{{ $serviceVariant->variant_code ?? '' }}">

                                                                        <input type="hidden"
                                                                            id="serviceVariantName{{ $serviceCount }}"
                                                                            value="{{ $serviceVariant->service_variant_name ?? '' }}">

                                                                        <input type="hidden"
                                                                            id="serviceVariantUnitName{{ $serviceCount }}"
                                                                            value="{{ $serviceVariant->unit_name ?? '' }}">

                                                                        <input type="hidden"
                                                                            id="serviceVariantUnitPrice{{ $serviceCount }}"
                                                                            value="{{ $serviceVariant->unit_price ?? '' }}">

                                                                        @php
                                                                            $serviceVarSerial++;
                                                                            $serviceCount++;
                                                                        @endphp

                                                                    </tr>

                                                                @endif

                                                            @endforeach

                                                        </table>

                                                    </div>

                                                </div>

                                            </div>

                                        @endforeach

                                    @endif

                                    <input type="hidden" id="serviceVariantCount" value="{{ $serviceCount }}">

                                </div>

                                @if ($flag == 1)
                                    <span class="text-danger">
                                        No service has been added to Home Service
                                    </span>
                                @endif

                            </div>

                            <div class="modal-footer">

                                <button type="button"
                                        class="btn btn-link waves-effect"
                                        id="serviceModalSelectBtn"
                                        onclick="setAddService()">
                                    SELECT
                                </button>

                                <!-- ✅ FIX: proper close button -->
                                <button type="button"
                                        class="btn btn-link waves-effect"
                                        data-bs-dismiss="modal">
                                    CLOSE
                                </button>

                            </div>

                        </div>
                    </div>
                </div>
                <!-- ------------- ----------------- ----------------- -->
                <button class="btn btn-default btn-sm waves-effect hidden" id="showClientListBtn" data-toggle="modal" data-target="#clientList"></button>
                <div class="modal fade" id="clientList" tabindex="-1" role="dialog" aria-labelledby="clientList">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" id='clientListPanelCloseBtn' data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">Client List</h4>
                            </div>
                            <div class="modal-body">
                                <div class='table-responsive'>
                                    <table class="table table-bordered table-hover custom-table" id="clientListTable">
                                        <thead>
                                            <tr class="bg-primary">
                                                <th>SL</th>
                                                <th>Client ID</th>
                                                <th>Client Type</th>
                                                <th>Client Name</th>
                                                <th>Address</th>
                                                <th>Mobile</th>
                                                <th>Action</th>
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
                                        </tbody>
                                    </table>
                                    <a class="btn btn-primary" href="admin/Individual/addIndividualAccountShow?type={{ config('constants.HOME_SERVICE_MODULE') }}">New Individual Account</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="calenderHeading">
                        <h4 class="panel-title py-2 px-2">
                            <a role="button" data-toggle="collapse" data-parent="#" href="#calenderCollapse" aria-expanded="true" aria-controls="calenderCollapse">
                                <i class="fa fa-calendar"></i> Home Services Schedule
                            </a>
                        </h4>
                    </div>
                    <div id="calenderCollapse" class="panel-collapse" role="tabpanel" aria-labelledby="calenderHeading">
                        <div class="panel-body">
                            <div class="row">
                                <form id="scheduleForm"
                                    action="{{ url('admin/AdminHomeService/raiseHomeService') }}"
                                    method="POST"
                                    class="w-100">

                                    @csrf

                                    <div class="row align-items-end">

                                        <!-- MONTH -->
                                        <div class="col-md-3 col-sm-3 col-12">
                                            <label class="form-label">Month</label>

                                            <select class="form-control" name="inputMonth">

                                                <option value="{{ $data['myMonth'] ?? date('m') }}">
                                                    {{ $data['myMonth'] ? date('F', mktime(0, 0, 0, $data['myMonth'], 10)) : date('F') }}
                                                </option>

                                                @php
                                                    $monthArr = config('constants.months');
                                                @endphp

                                                @foreach ($monthArr ?? [] as $monthNumber => $month)
                                                    <option value="{{ $monthNumber }}">
                                                        {{ $month }}
                                                    </option>
                                                @endforeach

                                            </select>
                                        </div>

                                        <!-- YEAR -->
                                        <div class="col-md-3 col-sm-3 col-12">
                                            <label class="form-label">Year</label>

                                            <select class="form-control" name="inputYear">

                                                <option value="{{ $data['myYear'] ?? date('Y') }}">
                                                    {{ $data['myYear'] ?? date('Y') }}
                                                </option>

                                                @for ($year = 2019; $year <= 2026; $year++)
                                                    <option value="{{ $year }}">
                                                        {{ $year }}
                                                    </option>
                                                @endfor

                                            </select>
                                        </div>

                                        <!-- BUTTON (NOW SAME ROW) -->
                                        <div class="col-md-1 col-sm-2 col-12 d-flex align-items-end">

                                            <button type="button"
                                                    class="btn btn-primary save_button"
                                                    onclick="getSchedule()">

                                                <i class="fa fa-search"></i>

                                            </button>

                                        </div>

                                    </div>

                                </form>
                            </div>
                            <!--<h3><b>Schedule List for <?php //echo date('F', mktime(0, 0, 0, $myMonth, 10)) . ", " . $myYear ?> Loaded. Please go the respective month.</b></h3>-->
                            <div class="table-responsive">
                                <div id="calendar"></div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="hidden">
        <button class="btn btn-primary" id="showClientListBtn" data-toggle="modal" data-target="#clientList"></button>
    </div>
    <div class="modal fade" id="clientList" tabindex="-1" role="dialog" aria-labelledby="clientList">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" id='clientListPanelCloseBtn' data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Client List</h4>
                </div>
                <div class="modal-body">
                    <div class='table-responsive'>
                        <table class="table table-bordered table-hover custom-table" id="clientListTable">
                            <thead>
                                <tr class="bg-primary">
                                    <th>SL</th>
                                    <th>Client ID</th>
                                    <th>Client Type</th>
                                    <th>Client Name</th>
                                    <th>Address</th>
                                    <th>Mobile</th>
                                    <th>Action</th>
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
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection


@push('scripts')
<script src="{{ asset('assets/select_bo/js/calendar/moment.min.js') }}"></script>
<script src="{{ asset('assets/select_bo/js/calendar/fullcalendar.min.js') }}"></script>
<script src="{{ asset('assets/select_bo/js/moment.js') }}"></script>
<script>
$(document).ready(function () {

    $('#datatable').DataTable({
        pageLength: 10,
        ordering: true,
        searching: true
    });

    $('#calendar').fullCalendar({
        header: {
            left: '', // prev,next today
            center: 'title',
            right: 'month,basicWeek,basicDay'
        },
        defaultDate: '{{ $data['calendarDefaultDate'] }}',
        contentHeight: 'auto',
        editable: false,
        eventLimit: true, // allow "more" link when too many events
        events: '{{ $data['calendarEvents'] }}'
    });
});

    function showClientList() {
        var modal = new bootstrap.Modal(document.getElementById('clientList'));
        modal.show();
    }

    function setClient(clientId, clientName, clientMbl, clientAddress) {
        $('#clientId').val(clientId);
        $('#name').val(clientName);
        $('#mobile').val(clientMbl);
        $('#address').val(clientAddress);
        var modalEl = document.getElementById('clientList');
        var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.hide();
        return false;
    }

    function setShowServiceModal() {

        var serviceVariantCount = $("#serviceVariantCount").val();
        var serviceVarCodeStr = $("#serviceVarCodeStr").val();

        // ✅ FIX: Proper Bootstrap modal open
        $("#serviceModal").modal('show');

        // Uncheck all first
        for (var i = 1; i < serviceVariantCount; i++) {
            $('#serviceVarCheckBox' + i).prop('checked', false);
        }

        if (typeof serviceVarCodeStr !== 'undefined' && serviceVarCodeStr) {

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

    function setAddService() {
        var serviceVariantCount = $("#serviceVariantCount").val();
        var serviceVariantCode;
        var serviceVariantName;
        var serviceVariantUnitName;
        var serviceVariantUnitPrice;
        var serviceTableStr = "";
        var serviceVarCodeArr = new Array();
        var takenServiceVarCount = 1;

        var totalserviceVariantUnitPrice = 0.00;

        var takenServieVarCountFinal = $("#takenServiceVarCount").val();
        if (typeof takenServieVarCountFinal === 'undefined') {
            takenServieVarCountFinal = 0;
        }
        var quantity;
        var amount;
        var i = 1;
        for (var x = 1; x < serviceVariantCount; x++) {
            if ($("#serviceVarCheckBox" + x).is(':checked')) {
                serviceVariantCode = $("#serviceVariantCode" + x).val();
                serviceVariantName = $("#serviceVariantName" + x).val();

                serviceVariantUnitName = $("#serviceVariantUnitName" + x).val();
                serviceVariantUnitPrice = $("#serviceVariantUnitPrice" + x).val();
                quantity = 1;
                amount = (parseFloat(quantity) * parseFloat(serviceVariantUnitPrice));

                for (var j = 1; j < takenServieVarCountFinal; j++) {
                    if ($('#takenServiceVarCode' + j).val() === serviceVariantCode) {
                        quantity = $("#quantity" + j).val();

                        amount = (parseFloat(quantity) * parseFloat(serviceVariantUnitPrice));
                    }
                }

                serviceTableStr += '<tr id="serviceTakenTd' + i + '">\n\
                                        <td class="td-left">' + serviceVariantName + '</td>\n\
                                        <td class="td-left">BDT ' + serviceVariantUnitPrice + ' Per ' + serviceVariantUnitName + '</td>\n\
                                        <td><input class="form-control custom-form-control" type="number" min="0" value="' + quantity + '" onkeyup="calculateGrandTotal(' + i + ')" onchange="calculateGrandTotal(' + i + ')" name="quantity' + i + '" id="quantity' + i + '"></td>\n\
                                        <td class="td-right" id="amountTd' + i + '">' + amount.toFixed(2) + '</td>\n\
                                        <td class="td-center"><i class="fa fa-remove pointer text-danger" onclick="removeService(' + i + ')"></i>\n\
                                        <input type="hidden" id="takenServiceVarCode' + i + '" name="takenServiceVarCode' + i + '" value="' + serviceVariantCode + '">\n\
                                        <input type="hidden" id="takenServiceUnitPrice' + i + '" name="takenServiceUnitPrice' + i + '" value="' + serviceVariantUnitPrice + '">\n\
                                        <input type="hidden" id="amount' + i + '" name="amount' + i + '" value="' + amount + '"></td>\n\
                                    </tr>';
                serviceVarCodeArr.push(serviceVariantCode);
                takenServiceVarCount++;
                i++;
            }
        }
        $('#serviceTableDiv').remove();
        if (serviceTableStr !== "") {
            var newRow = $(document.createElement('div')).attr("id", 'serviceTableDiv');
            var serviceTableDiv = '<table class="table table-bordered custom-table">\n\
                                <tr class="bg-info">\n\
                                    <th colspan="5"><b>Service</b></th>\n\
                                </tr>\n\
                                <tr>\n\
                                    <th width="50%"><b>Service Name</b></th>\n\
                                    <th width="20%"><b>Price</b></th>\n\
                                    <th width="10%"><b>Quantity</b></th>\n\
                                    <th width="10%"><b>Amount</b></th>\n\
                                    <th width="10%"><b>Action</b></th>\n\
                                </tr>\n\
                                ' + serviceTableStr + '\n\
                                <tr>\n\
                                    <td colspan="3" class="td-right"><b>Total</b></td>\n\
                                    <td class="td-right" id="totalAmount">' + totalserviceVariantUnitPrice + '</td>\n\
                                    <td></td>\n\
                                </tr>\n\
                                <input type="hidden" id="serviceVarCodeStr' + '" value="' + serviceVarCodeArr.join() + '">\n\
                                <input type="hidden" id="takenServiceVarCount' + '" name="takenServiceVarCount' + '" value="' + takenServiceVarCount + '">\n\
                            </table>';
            newRow.after().html(serviceTableDiv);
            newRow.appendTo("#vehicleServiceDiv");
        }
        $('#serviceModalCloseBtn').click();
        grandTotal();
    }


    function calculateGrandTotal(takenService) {
        var quantity = $('#quantity' + takenService).val();
        var unitPrice = $('#takenServiceUnitPrice' + takenService).val();
        if (!$.isNumeric(quantity)) {
            quantity = 0;
            $('#quantity' + takenService).val('');
        }

        if (!$.isNumeric(unitPrice)) {
            unitPrice = 0;
            $('#takenServiceUnitPrice' + takenService).val('');
        }

        var amount = (parseFloat(quantity) * parseFloat(unitPrice));
        if (!$.isNumeric(amount)) {
            $('#amountTd' + takenService).text('0.00');
            $('#amount' + takenService).val('');
        } else {
            $('#amountTd' + takenService).text(amount.toFixed(2));
            $('#amount' + takenService).val(amount);
        }
        grandTotal();
    }

    function grandTotal() {
        var totalAmount = 0;
        var takenServiceVarCount = $('#takenServiceVarCount').val();
        for (var j = 1; j <= takenServiceVarCount; j++) {
            var amount = $('#amount' + j).val();
            if (typeof amount !== 'undefined' && amount !== "") {
                totalAmount += parseFloat(amount);
            }
        }

        totalAmount = totalAmount.toFixed(2);
        if (!$.isNumeric(totalAmount)) {
            totalAmount = '0.00';
        }
        $('#totalAmount').text(totalAmount);
    }

    function removeService(serviceSerial) {
        $('#serviceTakenTd' + serviceSerial).remove();
        var serviceVarCodeArr = new Array();
        var takenServiceVarCode;
        var takenServiceVarCount = $('#takenServiceVarCount').val();
        for (var i = 1; i < takenServiceVarCount; i++) {
            takenServiceVarCode = $('#takenServiceVarCode' + i).val();

            if (typeof takenServiceVarCode !== 'undefined') {
                serviceVarCodeArr.push(takenServiceVarCode);
            }
        }

        if (serviceVarCodeArr.length !== 0) {
            $('#serviceVarCodeStr').val(serviceVarCodeArr.join());
        } else {
            $('#serviceTableDiv').remove();
        }
        grandTotal();
    }

    function raiseHomeService() {

        var errorMsg = "";

        // field id | error div id
        var fieldsArr = [
            "clientId|clientIdReq-error",
            "name|nameReq-error",
            "mobile|mobileReq-error",
            "address|addressReq-error",
            "confirmDate|confirmDateReq-error",
            "confirmTime|confirmTimeReq-error",
            "leadsBy|leadsByReq-error"
        ];

        var inputFiledJsonData = getInputData(fieldsArr);

        if (!inputFiledJsonData) {
            errorMsg += getReuiredFiledErrorMsg();
            showErrorMsg(errorMsg);
            return false;
        } else {
            //hideErrorDiv();
        }

        var confirmDate = $.trim($('#confirmDate').val());
        var confirmTime = $.trim($('#confirmTime').val());
        var confirmDtTm = confirmDate + " " + confirmTime;

        var checkDtTm = checkDateTime(confirmDtTm);

        if (checkDtTm !== 3) {
            Swal.fire('Error', 'Please input correct date and time...!', 'error');
            return false;
        }

        //it will consider next time
        // if (checkTime($.trim($('#serviceTime').val())) === 0) {
        //     Swal.fire('Error', 'Time is not in correct format...!', 'error');
        //     return false;
        // }

        var takenServiceVarCount = $("#takenServiceVarCount").val();
        var serviceProductFlag = 0;

        for (var j = 1; j <= takenServiceVarCount; j++) {

            var takenServiceVarCode = $("#takenServiceVarCode" + j).val();

            if (typeof takenServiceVarCode !== 'undefined' && takenServiceVarCode !== '') {
                serviceProductFlag = 1;
            }

            var quantity = $("#quantity" + j).val();

            if (quantity <= 0) {
                Swal.fire('Error', 'Amount must be greater than 1', 'error');
                return false;
            }
        }

        if (serviceProductFlag === 0) {
            Swal.fire('Error', 'Please take at least one service...!', 'error');
            return false;
        }

        $("#submitForm").submit();
    }

    function checkTime(time) {
        var regex = /^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/;
        if (!time) {
            return 0;
        }
        return regex.test(time) ? 1 : 0;
    }

    function checkDateTime(dateTime) {

        // Convert to Date object
        var dt = new Date(dateTime);

        // If invalid date
        if (isNaN(dt.getTime())) {
            return 0;
        }

        // Optional: prevent past date (uncomment if needed)
        // var now = new Date();
        // if (dt < now) return 1;

        // Your original logic expects 3 as valid
        return 3;
    }

    function getInputData(fieldsArr) {

        var data = {};
        var isValid = true;

        fieldsArr.forEach(function (field) {

            var parts = field.split('|');
            var fieldId = parts[0];
            var errorId = parts[1];

            var value = $.trim($('#' + fieldId).val());

            if (!value) {
                $('#' + errorId).show();
                isValid = false;
            } else {
                $('#' + errorId).hide();
                data[fieldId] = value;
            }

        });

        return isValid ? data : false;
    }

    function getSchedule() {
        $("#scheduleForm").submit();
    }

    $('.timepicker').timepicker({
        defaultTime: false,
        disableFocus: true
    });

    $('.dateInput').datepicker({
        format: 'yyyy-mm-dd',  // format compatible with Laravel date column
        autoclose: true,       // close picker after selecting a date
        todayHighlight: true,  // highlight today
        clearBtn: true,        // optional clear button
        orientation: 'bottom'  // show below the input
    });

    $(document).ready(function () {

        $('#clientListTable').DataTable({
            bDestroy: true,
            ajax: "{{ route('admin.home-service.get-client-list') }}",
            deferRender: true,
            responsive: true,

            initComplete: function () {

                this.api().columns().every(function () {

                    var column = this;

                    var select = $('<select class="form-select"><option value=""></option></select>')
                        .appendTo($(column.footer()).empty())
                        .on('change', function () {

                            var val = $.fn.dataTable.util.escapeRegex($(this).val());

                            column
                                .search(val ? '^' + val + '$' : '', true, false)
                                .draw();
                        });

                    column.data().unique().sort().each(function (d) {
                        select.append('<option value="' + d + '">' + d + '</option>');
                    });

                });
            }
        });

    });
</script>

@endpush