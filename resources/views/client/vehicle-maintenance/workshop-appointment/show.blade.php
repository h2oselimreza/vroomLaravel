@extends('client.layouts.app')

@section('content')
<style>
    .form-group{margin-bottom: 0px; }
    .panel-group{margin-bottom: 0px;}

    .card .header{ padding: 10px;}
    .table-td-info
    {
        background:#FFFFFF;
        font-size:11px;
        font-family:Verdana, Geneva, sans-serif;
        font-weight:normal;
        padding-left:7px;
        padding-top:2px;
        padding-bottom:2px;
    }
    .bottom-border{border-bottom: 1px solid #ddd;}
    .card .body .service-variant{
        margin-bottom: 1px;
        margin-top: 2px;
    }
    .card .body .service{
        margin-bottom: 1px;
    }
</style>

<div class="block-header">
    <h2>SHOW WORKSHOP APPOINTMENT</h2><br>
    <div class="breadcrumb breadcrumb-bg-blue-grey">
        <li><a href="#"> Home</a></li>
        <li><a href="#"> Vehicle Maintenance</a></li>
        <li><a href="/client/vehicle-maintenance/workshop-service-list">Workshop Appointment List</a></li>
        <li><a href="/client/vehicle-maintenance/show-workshop-details/{{ $appointmentNo }}"> Show Workshop Appointment</a></li>
    </div>
</div>

<div class="row clearfix">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="body">
                {{-- Success Message --}}
                @if(session('success'))
                    <div class="alert alert-success">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                        <strong>{{ session('success') }}</strong>
                    </div>
                @endif
                {{-- Validation Errors --}}
                @if(session('error'))
                    <div class="alert alert-danger">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                        <strong>{{ session('error') }}</strong>
                    </div>
                @endif
                <div class="text-center font-16 m-b-10">
                    <b> Appointmented to <span class="pointer text-vroom-orange" onclick="showDetails('{{ $appointmentSummary->workshop }}')">{{ $appointmentSummary->workshop_name }}</span></b>
                </div>

                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="panel-group" id="vehicleGroupDiv">
                            <div class="table-responsive">
                                <table class="m-t-10 m-b-10" border="0" cellpadding="0" cellspacing="0" align="center" width="100%">
                                    <tr class="table-td-info">
                                        <td width="20%" align="left" class="content-table-td"><b>Appointment No</b></td>
                                        <td width="2%" align="center">:</td>
                                        <td width="28%" align="left" class="content-table-td">{{ $appointmentNo }}</td>
                                    </tr>

                                    <tr class="table-td-info">
                                        <td width="20%" align="left" class="content-table-td"><b>Appointment Date 1</b></td>
                                        <td width="2%" align="center">:</td>
                                        <td width="28%" align="left" class="content-table-td">{{ get_date_format1($appointmentSummary->date_1) }}</td>

                                        <td width="20%" align="left" class="content-table-td"><b>Appointment Date 2</b></td>
                                        <td width="2%" align="center">:</td>
                                        <td width="28%" align="left" class="content-table-td">{{ get_date_format1($appointmentSummary->date_2) }}</td>
                                    </tr>

                                    <tr class="table-td-info">
                                        <td align="left" class="content-table-td"><b>Time Slot 1</b></td>
                                        <td align="center">:</td>
                                        <td align="left" class="content-table-td">{{ $appointmentSummary->time_slot_1 }}</td>

                                        <td align="left" class="content-table-td"><b>Time Slot 2</b></td>
                                        <td align="center">:</td>
                                        <td align="left" class="content-table-td">{{ $appointmentSummary->time_slot_2 }}</td>
                                    </tr>
                                </table>
                            </div>

                            @if ($appointmentSummary->status == config('constants.APPOINTMENT_PENDING'))
                                <div class="text-right p-b-10">
                                    <button class="btn btn-xs bg-blue waves-effect" data-toggle="modal" data-target="#changeDateTimeModal">Change Date & Time Slot</button>
                                </div>
                            @endif

                            @php
                                $vehicleCount = 1;
                                $requestedVehicleArr = array();
                            @endphp

                            @foreach ($appointmentedVehicles as $appointmentedVehicle)
                                <div id="vehicleDiv{{ $vehicleCount }}">
                                    <div class="panel panel2 panel-default">
                                        <div class="panel-heading">
                                            <div class="panel-title custom1-panel-title">
                                                <div class="row p-l-20 p-r-20">
                                                    <div class="float-left">
                                                        <i class="fa fa-car"></i> {{ $appointmentedVehicle->registration_no }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel-body custom1-panel-body font-13">
                                            <div id="vehicleServiceDiv{{ $vehicleCount }}">
                                                @php
                                                    $i = 1;
                                                    $serviceVariantCodeArr = array();
                                                    $servVarCodeReqNoArr = array();
                                                    $servVarReqDetailsNoArr = array();
                                                @endphp

                                                @foreach ($appointmentDetails as $appointmentDetail)
                                                    @if ($appointmentedVehicle->vehicle == $appointmentDetail->vehicle)
                                                        {{ $i }}. {{ $appointmentDetail->service_variant_name }}<br>
                                                        @php $i++; @endphp
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @php $vehicleCount++; @endphp
                            @endforeach

                            <div class="font-13 m-t-20">
                                <b>Remarks</b>
                                <br>
                                <span class="font-12">{{ $appointmentSummary->remarks }}</span>
                            </div>

                            @if ($appointmentSummary->reject_reason)
                                <div class="panel-group m-t-30">
                                    <div class="panel panel-danger">
                                        <div class="panel-heading">
                                            <div class="panel-title custom1-panel-title">
                                                Reject Reason
                                            </div>
                                        </div>
                                        <div class="panel-body custom1-panel-body font-13">
                                            {{ $appointmentSummary->reject_reason }}
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="created-updated">
                                <div class="float-left">
                                    <b>Created By: </b>{{ get_create_update_by_name($appointmentSummary->created_by, $appointmentSummary->created_type) }}
                                    <br><b>Created Date Time: </b>{{ get_date_time_format($appointmentSummary->created_dt_tm) }}
                                </div>
                                <div class="float-right">
                                    <b>Updated By: </b>{{ get_create_update_by_name($appointmentSummary->updated_by, $appointmentSummary->updated_type) }}
                                    <br><b>Updated Date Time: </b>{{ get_date_time_format($appointmentSummary->updated_dt_tm) }}
                                </div>
                            </div>

                            <div class="modal fade" id="changeDateTimeModal" tabindex="-1" role="dialog">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="largeModalLabel">Change Date and Time</h4>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('client.vehicle-maintenance.changeDateTimeSlot') }}" method="post" id="appointmentForm">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <div class="form-group form-float">
                                                            <div class="form-line">
                                                                <input type="text" class="form-control dateInput" name="date1" id="date1">
                                                                <label class="form-label"> Select Date</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <div class="form-group form-float">
                                                            <div class="form-line">
                                                                <select class="form-control" name="timeSlot1" id="timeSlot1">
                                                                    <option value=""></option>
                                                                    <option value="Morning">Morning</option>
                                                                    <option value="Evening">Evening</option>
                                                                </select>
                                                                <div class="help-info">Select Time Slot</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <div class="form-group form-float">
                                                            <div class="form-line">
                                                                <input type="text" class="form-control dateInput" name="date2" id="date2">
                                                                <label class="form-label"> Select Date</label>
                                                            </div>
                                                            <div class="help-info">Alternative</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <div class="form-group form-float">
                                                            <div class="form-line">
                                                                <select class="form-control" name="timeSlot2">
                                                                    <option value=""></option>
                                                                    <option value="Morning">Morning</option>
                                                                    <option value="Evening">Evening</option>
                                                                </select>
                                                                <div class="help-info">Select Time Slot (Alternative)</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <input type="hidden" name="appointmentNo" value="{{ $appointmentNo }}">
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn bg-blue waves-effect" onclick="chnageDateTime()">Save</button>
                                            <button type="button" class="btn bg-red waves-effect" id="serviceModalCloseBtn" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <input type="hidden" id="title" value="{{ $workshopDetails->title }}">
                        <input type="hidden" id="email" value="{{ $workshopDetails->workshop_email }}">
                        <input type="hidden" id="website" value="{{ $workshopDetails->website }}">
                        <input type="hidden" id="address" value="{{ $workshopDetails->address }}">
                        <input type="hidden" id="division" value="{{ optional($workshopDetails)->division_en_name }} ({{ optional($workshopDetails)->division_bn_name }})">
                        <input type="hidden" id="district" value="{{ optional($workshopDetails)->district_en_name }} ({{ optional($workshopDetails)->district_bn_name }})">
                        <input type="hidden" id="uplozilla" value="{{ optional($workshopDetails)->upozilla_en_name }} ({{ optional($workshopDetails)->upozilla_bn_name }})">

                        <!-- --------------- workshop  details modal ---------------- -->
                        <button class="btn hidden" data-toggle="modal" data-target="#workshopModal" id="workshopModalShowBtn"></button>

                        <div class="modal fade" id="workshopModal" tabindex="-1" role="dialog">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="workshopTitle"></h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                            <div class="panel panel1 panel-default">
                                                <div class="panel-heading custom-panel-heading" role="tab" id="headingOne">
                                                    <p class="panel-title custom-panel-title1 p-t-0 p-b-0">
                                                        <a role="button" data-toggle="collapse" data-parent="#" href="#generalCollapseOne" aria-expanded="true" aria-controls="generalCollapseOne">
                                                            <i class="fa fa-tags"></i> General Information
                                                        </a>
                                                    </p>
                                                </div>
                                                <div id="generalCollapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                                    <div class="panel-body">
                                                        <div class="table-responsive">
                                                            <table border="0" cellpadding="0" cellspacing="0" align="center" width="100%">
                                                                <tr class="table-td-info">
                                                                    <td width="10%" align="left" class="content-table-td"><b>Email</b></td>
                                                                    <td width="2%" align="center">:</td>
                                                                    <td width="38%" align="left" id="workshopEmail"></td>
                                                                    <td width="10%" align="left" class="content-table-td"><b>Website</b></td>
                                                                    <td width="2%" align="center">:</td>
                                                                    <td width="38%" align="left" id="workshopWebsite"></td>
                                                                </tr>
                                                                <tr class="table-td-info">
                                                                    <td align="left" class="content-table-td"><b>Address</b></td>
                                                                    <td align="center">:</td>
                                                                    <td align="left" id="workshopAddress"></td>
                                                                    <td align="left" class="content-table-td"><b>Division</b></td>
                                                                    <td align="center">:</td>
                                                                    <td align="left" id="workshopDivision"></td>
                                                                </tr>
                                                                <tr class="table-td-info">
                                                                    <td align="left" class="content-table-td"><b>District</b></td>
                                                                    <td align="center">:</td>
                                                                    <td align="left" id="workshopDistrict"></td>
                                                                    <td align="left" class="content-table-td"><b>Upozilla</b></td>
                                                                    <td align="center">:</td>
                                                                    <td align="left" id="workshopUpozilla"></td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="panel panel1 panel-default">
                                                <div class="panel-heading custom-panel-heading" role="tab" id="two">
                                                    <p class="panel-title custom-panel-title1 p-t-0 p-b-0">
                                                        <a role="button" data-toggle="collapse" data-parent="#" href="#timeShedule" aria-expanded="true" aria-controls="timeShedule">
                                                            <i class="fa fa-clock-o"></i> Time Schedule
                                                        </a>
                                                    </p>
                                                </div>
                                                <div id="timeShedule" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="two">
                                                    <div class="panel-body">
                                                        <table border="0" cellpadding="0" cellspacing="0" align="center" width="100%">
                                                            <tr class="table-td-info">
                                                                <td width="10%" align="left" class="content-table-td"><b><span id="day1"></span></b></td>
                                                                <td width="2%" align="center">:</td>
                                                                <td width="85%" align="left" id="time1"></td>
                                                            </tr>
                                                            @for ($i = 2; $i < 8; $i++)
                                                                <tr class="table-td-info">
                                                                    <td align="left" class="content-table-td"><b><span id="day{{ $i }}"></span></b></td>
                                                                    <td align="center">:</td>
                                                                    <td align="left" id="time{{ $i }}"></td>
                                                                </tr>
                                                            @endfor
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="panel panel1 panel-default">
                                                <div class="panel-heading custom-panel-heading" role="tab" id="three">
                                                    <p class="panel-title custom-panel-title1 p-t-0 p-b-0">
                                                        <a role="button" data-toggle="collapse" data-parent="#" href="#vehicleServie" aria-expanded="true" aria-controls="vehicleServie">
                                                            <i class="fa fa-car"></i> Vehicle Services
                                                        </a>
                                                    </p>
                                                </div>
                                                <div id="vehicleServie" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="three">
                                                    <div class="panel-body">
                                                        <div class="font-13" id="vehicleServieDiv"></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="panel panel1 panel-default">
                                                <div class="panel-heading custom-panel-heading" role="tab" id="four">
                                                    <p class="panel-title custom-panel-title1 p-t-0 p-b-0">
                                                        <a role="button" data-toggle="collapse" data-parent="#" href="#service" aria-expanded="true" aria-controls="service">
                                                            <i class="fa fa-bars"></i> Services
                                                        </a>
                                                    </p>
                                                </div>
                                                <div id="service" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="four">
                                                    <div class="panel-body">
                                                        <div class="font-13" id="serviceDiv"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger waves-effect" id="modalCloseBtn" data-dismiss="modal">CLOSE</button>
                                    </div>
                                </div>
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
    function chnageDateTime() {
        if ($('#date1').val() === "" || $('#timeSlot1').val() === "") {
            sweetAlert('Date and Time Slot is mendetory...!');
            return false;
        }
        $("#appointmentForm").submit();
    }

    function showDetails(workshopCode) {
    showLoader();

    for (var i = 1; i < 8; i++) {
        $('#day' + i).text('');
        $('#time' + i).text('');
    }

    $('#vehicleServieDiv').html("");
    $('#serviceDiv').html("");
    $('#workshopModalShowBtn').click();

    $.ajax({
        type: 'POST',
        url: '/client/vehicle-maintenance/getWorkshopInfo',
        data: {
            workshopCode: workshopCode,
            _token: "{{ csrf_token() }}"// ✅ IMPORTANT for Laravel
        },
        dataType: 'json', // ✅ no need for parseJSON
        success: function (resultObj) {
            hideLoader();

            var j = 1;

            // -------- time schedule --------
            for (var i = 0; i < resultObj.timeShedule.length; i++) {
                $('#day' + j).text(resultObj.timeShedule[i].weekday_name);

                var startTime = getTimeAmPmFormat(resultObj.timeShedule[i].start_time);
                var endTime = getTimeAmPmFormat(resultObj.timeShedule[i].end_time);

                if (resultObj.timeShedule[i].weekend_status === "1") {
                    $('#time' + j).html('<span class="text-danger"><b>WEEKEND</b></span>');
                } else {
                    $('#time' + j).text(startTime + ' To ' + endTime);
                }
                j++;
            }

            // -------- vehicle services --------
            var serviceVehicleStr = "<ul>";
            for (var i = 0; i < resultObj.serviceVehicle.length; i++) {
                serviceVehicleStr += '<li>' + resultObj.serviceVehicle[i].vehicle_type_name + "</li>";
            }
            $('#vehicleServieDiv').html(serviceVehicleStr + "</ul>");

            // -------- services --------
            var serviceStr = "";

            for (var i = 0; i < resultObj.distinctService.length; i++) {
                var serviceCode = resultObj.distinctService[i].service;
                var serviceVariantStr = "";

                for (var j = 0; j < resultObj.allService.length; j++) {
                    if (serviceCode === resultObj.allService[j].service) {
                        serviceVariantStr += "<div class='col-md-6 col-sm-6 col-xs-12 font-12 service-variant'>" +
                            "<li>" + resultObj.allService[j].service_variant_name + "</li>" +
                            "</div>";
                    }
                }

                serviceStr += "<div class='row'>" +
                    "<div class='col-md-12 col-sm-12 col-xs-12 font-12 service'>" +
                    "<div class='bottom-border'>" +
                    "<b>" + resultObj.distinctService[i].service_name + "</b>" +
                    "</div>" +
                    "</div>" +
                    "</div>" +
                    "<div class='row m-b-20'>" + serviceVariantStr + "</div>";
            }

            $('#serviceDiv').html(serviceStr);
        },
        error: function () {
            hideLoader();
            alert('Something went wrong!');
        }
    });

    // -------- static data --------
    $('#workshopTitle').text($('#title').val());
    $('#workshopEmail').text($('#email').val());
    $('#workshopWebsite').text($('#website').val());
    $('#workshopAddress').text($('#address').val());
    $('#workshopDivision').text($('#division').val());
    $('#workshopDistrict').text($('#district').val());
    $('#workshopUpozilla').text($('#uplozilla').val());
}
</script>
@endpush