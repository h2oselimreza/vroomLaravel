@extends('layouts.app')

@section('content')
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

    .content-table-td{
        font-size: 12px
    }

</style>

<div class="header dashboard_from">
    <h1 class="page-title">Show Appointment</h1>

    <ul class="breadcrumb">
        <li><a href="/admin/dashboard">Home</a> /</li>
        <li><a href="#">Appointment</a></li>
        <li><a href="{{ url('admin/appointment/appointment-list') }}">Appointment List</a> /</li>
        <li>
            <a href="{{ url('admin/Appointment/showAppointment?appointmentNo=' . $appointmentNo . '&companyCode=' . $companyCode) }}">
                Show Appointment
            </a>
        </li>
    </ul>
</div>

<div class="main-content">
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel panel-default">

                <div class="text-center font-17 mb-3">
                    <b>
                        Appointmented to
                        <span class="pointer text-dark-blue">
                            {{ $appointmentSummary->workshop_name }}
                        </span>
                    </b>
                </div>

                <table class="m-t-10 mb-3" border="0" cellpadding="0" cellspacing="0" align="center" width="100%">

                    <tr class="table-td-info">
                        <td width="20%" class="content-table-td"><b>Appointment No</b></td>
                        <td width="2%" align="center">:</td>
                        <td width="28%" class="content-table-td">{{ $appointmentNo }}</td>

                        <td width="20%" class="content-table-td"><b>Customer</b></td>
                        <td width="2%" align="center">:</td>
                        <td width="28%" class="content-table-td">
                            {{ get_company_name($appointmentSummary->company) }}
                        </td>
                    </tr>

                    <tr class="table-td-info">
                        <td class="content-table-td"><b>Appointment Date 1</b></td>
                        <td align="center">:</td>
                        <td class="content-table-td">
                            {{ get_date_format1($appointmentSummary->date_1) }}
                        </td>

                        <td class="content-table-td"><b>Appointment Date 2</b></td>
                        <td align="center">:</td>
                        <td class="content-table-td">
                            {{ get_date_format1($appointmentSummary->date_2) }}
                        </td>
                    </tr>

                    <tr class="table-td-info">
                        <td class="content-table-td"><b>Time Slot 1</b></td>
                        <td align="center">:</td>
                        <td class="content-table-td">{{ $appointmentSummary->time_slot_1 }}</td>

                        <td class="content-table-td"><b>Time Slot 2</b></td>
                        <td align="center">:</td>
                        <td class="content-table-td">{{ $appointmentSummary->time_slot_2 }}</td>
                    </tr>

                </table>

                @php
                    $vehicleCount = 1;
                    $requestedVehicleArr = [];
                @endphp

                @foreach($appointmentedVehicles as $appointmentedVehicle)

                <div id="vehicleDiv{{ $vehicleCount }}">
                    <div class="panel panel-default">

                        <div class="panel-heading custom-panel-heading">
                            <div class="panel-title custom-panel-title1">
                                <div class="row p-l-20 p-r-20">
                                    <div class="float-left">
                                        <i class="fa fa-car"></i>
                                        {{ $appointmentedVehicle->registration_no }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel-body custom1-panel-body">

                            <div id="vehicleServiceDiv{{ $vehicleCount }}" class="vehicleServicelist">

                                @php
                                    $i = 1;
                                    $serviceVariantCodeArr = [];
                                    $servVarCodeReqNoArr = [];
                                    $servVarReqDetailsNoArr = [];
                                @endphp

                                @foreach($appointmentDetails as $appointmentDetail)

                                    @if($appointmentedVehicle->vehicle == $appointmentDetail->vehicle)
                                        {{ $i }}. {{ $appointmentDetail->service_variant_name }} <br>
                                        @php $i++; @endphp
                                    @endif

                                @endforeach

                            </div>

                        </div>
                    </div>
                </div>

                @php $vehicleCount++; @endphp

                @endforeach

                <div class="font-13 mt-3">
                    <b>Remarks</b>
                    <br>
                    <small>{{ $appointmentSummary->remarks }}</small>
                </div>

                @if($appointmentSummary->status == config('constants.APPOINTMENT_PROCCESSING'))

                <hr>

                <div class="row mt-3">

                    <form action="{{ route('client.appointment.module.appointment-date-update') }}" method="post" id="confirmDateTimeForm">
                        @csrf

                        <div class="row">
                            <div class="col-sm-6 col-md-6 col-xs-12">
                                <div class="form-group">
                                    <label class="form-label">Confirm Date</label>
                                    <input type="text" class="form-control dateInput"
                                           name="confirmDate"
                                           id="confirmDate"
                                           value="{{ $appointmentSummary->final_date }}">
                                </div>
                            </div>
    
                            <div class="col-sm-6 col-md-6 col-xs-12">
                                <div class="form-group">
                                    <label class="form-label">Confirm Time</label>
                                    <input type="text" class="form-control timepicker"
                                           name="confirmTime"
                                           id="confirmTime"
                                           value="{{ get_time_format($appointmentSummary->appointment_time) }}">
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="appointmentNo" value="{{ $appointmentNo }}">
                    </form>

                    <div class="col-sm-6 col-md-6 col-xs-12">
                        <button class="btn btn-primary btn-sm save_button mt-3" onclick="confirmDateTimeSubmit()">
                            Confirm Date & Time
                        </button>
                    </div>

                </div>

                @endif

            </div>
        </div>
    </div>
</div>

@endsection


@push('scripts')
{{-- <script src="{{ asset('assets/select_bo/js/moment.js') }}"></script> --}}

<script>
    function confirmDateTimeSubmit() {
        var confirmDate = $.trim($('#confirmDate').val());
        var confirmTime = $.trim($('#confirmTime').val());

        if (confirmDate === "" || confirmTime === "") {
            sweetAlert('Confirm Date and Time is required');
            return false;
        }

        $('#confirmDateTimeForm').submit();
    }
</script>

<script>

    $(document).ready(function(){
        $('.dateInput').datepicker({
            format: 'yyyy-mm-dd',  // format compatible with Laravel date column
            autoclose: true,       // close picker after selecting a date
            todayHighlight: true,  // highlight today
            clearBtn: true,        // optional clear button
            orientation: 'bottom'  // show below the input
        });

        // $('.timepicker').timepicker({
        //     showMeridian: true,   // ✅ AM/PM format (12:00 AM)
        //     defaultTime: false,
        // });
        $('.timepicker').timepicker({
            showMeridian: true,
            defaultTime: false,
            explicitMode: true   // ✅ IMPORTANT FIX
        });
    });
</script>
@endpush