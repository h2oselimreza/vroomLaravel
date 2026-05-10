@extends('client.layouts.app')
@section('content')
<style>
    .table-td-info
    {	
        background:#FFFFFF;
        font-size:11px;
        font-family:Verdana, Geneva, sans-serif;    
        font-weight:normal;
        padding-left:7px;
        padding-top:4px;
        padding-bottom:4px;
    }
    .reminderDetailTable td{
        padding: 5px;
    }
</style>

<div class="block-header">
    <h2>REMINDER LIST</h2><br>
    <div class="breadcrumb breadcrumb-bg-blue-grey">
        <li><a href="/client/Home"> Home</a></li>
        <li><a href="#"> Reminder</a></li>
        <li><a href="/client/Reminder/reminder">Reminder List</a></li>

    </div>
</div>
<div class="row clearfix">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="body">
                <a href="{{ route('client.reminder.set-reminder.create') }}" class="btn bg-blue waves-effect">Add New Reminder</a>
                <br> <br>
                <div class="table-custom-responsive">
                    <table class="table table-bordered table-hover custom-table dataTable">
                        <thead>
                            <tr class="bg-info">
                                <th width="">SL</th>
                                <th width="">Heading</th>
                                <th>Body</th>
                                <th>Reminder On Date</th>
                                <th>Repeat Every</th>
                                <th>Show Reminder</th>
                                <th>Next Show Reminder</th>
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
                                <th></th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @php
                                $count = 1;

                                $reminderTypeArr = unserialize(config('constants.VEHICLE_REMINDER_TYPE_ARR'));

                                $reminderForArr = unserialize(config('constants.REMINDER_FOR_ARR'));
                            @endphp

                            @foreach ($reminders as $reminder)

                                @php

                                    $reminderForName = '';

                                    $reminderFor = $reminder->reminder_for;

                                    foreach ($reminderForArr as $key => $value) {

                                        if ($reminderFor == $key) {

                                            $reminderForName = $value;
                                        }
                                    }

                                    $reminderTypeName = '';

                                    $reminderType = $reminder->reminder_type;

                                    foreach ($reminderTypeArr as $key => $value) {

                                        if ($reminderType == $key) {

                                            $reminderTypeName = $value;
                                        }
                                    }

                                @endphp

                                <tr>

                                    <td>
                                        {{ $count }}
                                    </td>

                                    <td class="td-left">
                                        {{ $reminder->heading }}
                                    </td>

                                    <td class="td-left">

                                        <span
                                            class="pointer"
                                            data-toggle="tooltip"
                                            data-placement="right"
                                            title="{{ $reminder->body }}"
                                        >

                                            {{ shorten_string($reminder->body, 30) }}

                                        </span>

                                    </td>

                                    <td class="td-left">
                                        {{ get_date_format1($reminder->reminder_on_dt_tm) }}
                                    </td>

                                    <td class="td-left">
                                        {{ $reminder->repeat_every . ' ' . $reminder->repeat_type }}
                                    </td>

                                    <td class="td-left">
                                        {{ $reminder->before_reminder_count . ' ' . $reminder->before_reminder_type . ' Before' }}
                                    </td>

                                    <td class="td-left">
                                        {{ get_date_time_format($reminder->next_show_dt_tm) }}
                                    </td>

                                    <input
                                        type="hidden"
                                        id="reminderForDbHidden{{ $count }}"
                                        value="{{ $reminderFor }}"
                                    >

                                    <input
                                        type="hidden"
                                        id="reminderForHidden{{ $count }}"
                                        value="{{ $reminderForName }}"
                                    >

                                    <input
                                        type="hidden"
                                        id="reminderTypeHidden{{ $count }}"
                                        value="{{ $reminderTypeName }}"
                                    >

                                    <input
                                        type="hidden"
                                        id="reminderForValueHidden{{ $count }}"
                                        value="{{ $reminder->reminder_for_value }}"
                                    >

                                    <input
                                        type="hidden"
                                        id="headingHidden{{ $count }}"
                                        value="{{ $reminder->heading }}"
                                    >

                                    <input
                                        type="hidden"
                                        id="bodyHidden{{ $count }}"
                                        value="{{ $reminder->body }}"
                                    >

                                    <input
                                        type="hidden"
                                        id="reminderOnDtTmHidden{{ $count }}"
                                        value="{{ get_date_format1($reminder->reminder_on_dt_tm) }}"
                                    >

                                    <input
                                        type="hidden"
                                        id="nextShowDtTmHidden{{ $count }}"
                                        value="{{ get_date_time_format($reminder->next_show_dt_tm) }}"
                                    >

                                    <input
                                        type="hidden"
                                        id="repeatEveryHidden{{ $count }}"
                                        value="{{ $reminder->repeat_every . ' ' . $reminder->repeat_type }}"
                                    >

                                    <input
                                        type="hidden"
                                        id="showReminderHidden{{ $count }}"
                                        value="{{ $reminder->before_reminder_count . ' ' . $reminder->before_reminder_type }}"
                                    >

                                    <input
                                        type="hidden"
                                        id="mobileNoHidden{{ $count }}"
                                        value="{{ $reminder->mobile_no }}"
                                    >

                                    <input
                                        type="hidden"
                                        id="emailHidden{{ $count }}"
                                        value="{{ $reminder->email }}"
                                    >

                                    <input
                                        type="hidden"
                                        id="defaultMobileHidden{{ $count }}"
                                        value="{{ $reminder->default_mobile_flag }}"
                                    >

                                    <input
                                        type="hidden"
                                        id="defaultEmailHidden{{ $count }}"
                                        value="{{ $reminder->default_email_flag }}"
                                    >

                                    <td>

                                        <div class="btn-group">

                                            <button
                                                type="button"
                                                class="btn btn-default btn-xs dropdown-toggle"
                                                data-toggle="dropdown"
                                                aria-haspopup="true"
                                                aria-expanded="false"
                                            >
                                                Action <span class="caret"></span>
                                            </button>

                                            <ul class="dropdown-menu pull-right">

                                                <li>
                                                    <a href="#"
                                                    onclick="showDetails('{{ $count }}')">
                                                        Show
                                                    </a>
                                                </li>

                                                <li>
                                                    <a href="#"
                                                    onclick="removeReminder('{{ $reminder->reminder_no }}')">
                                                        Remove
                                                    </a>
                                                </li>

                                            </ul>

                                        </div>

                                    </td>

                                </tr>

                                @php
                                    $count++;
                                @endphp

                            @endforeach

                        </tbody>
                    </table>
                </div> 

                <!-- --------------- workshop  details modal ---------------- -->
                <button class="btn hidden" data-toggle="modal" data-target="#reminderDetailsModal" id="reminderDetailsShowBtn"></button>
                <div class="modal fade" id="reminderDetailsModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">

                            <div class="modal-body" style="color:black">
                                <div class="panel panel1 panel-default">
                                    <div class="panel-heading custom-panel-heading">
                                        <p class="panel-title custom-panel-title1 p-t-0 p-b-0">
                                            <i class="fa fa-clock-o"></i> Reminder Details
                                        </p>
                                    </div>
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="reminderDetailTable" border="0" cellpadding="0" cellspacing="0" align="center" width="100%">
                                                <tr class="table-td-info">
                                                    <td width="30%" align="left" class="content-table-td"><b>Reminder For</b></td>
                                                    <td width="2%" align="center">:</td>
                                                    <td width="68%" align="left" id="reminderFor"></td>

                                                </tr>
                                                <tr class="table-td-info" id="vehicleRegNoTr">
                                                    <td align="left" class="content-table-td"><b>Vehicle Reg No</b></td>
                                                    <td align="center">:</td>
                                                    <td align="left" id="vehicleRegNo"></td>
                                                </tr>
                                                <tr class="table-td-info" id="reminderTypeTr">
                                                    <td align="left" class="content-table-td"><b>Reminder Type</b></td>
                                                    <td align="center">:</td>
                                                    <td align="left" id="reminderType"></td>
                                                </tr>
                                                <tr class="table-td-info">
                                                    <td align="left" class="content-table-td"><b>Heading</b></td>
                                                    <td align="center">:</td>
                                                    <td align="left" id="reminderHeading"></td>
                                                </tr>
                                                <tr class="table-td-info">
                                                    <td align="left" class="content-table-td"><b>Body</b></td>
                                                    <td align="center">:</td>
                                                    <td align="left" id="reminderBody"></td>
                                                </tr>

                                                <tr class="table-td-info">
                                                    <td align="left" class="content-table-td"><b>Reminder On</b></td>
                                                    <td align="center">:</td>
                                                    <td align="left" id="reminderOn"></td>
                                                </tr>

                                                <tr class="table-td-info">
                                                    <td align="left" class="content-table-td"><b>Repeat Every</b></td>
                                                    <td align="center">:</td>
                                                    <td align="left" id="repeatEvery"></td>
                                                </tr>
                                                <tr class="table-td-info">
                                                    <td align="left" class="content-table-td"><b>Show Reminder</b></td>
                                                    <td align="center">:</td>
                                                    <td align="left" id="showReminder"></td>
                                                </tr>

                                                <tr class="table-td-info">
                                                    <td align="left" class="content-table-td"><b>Default Mobile</b></td>
                                                    <td align="center">:</td>
                                                    <td align="left" id="defaultMobile"></td>
                                                </tr>

                                                <tr class="table-td-info">
                                                    <td align="left" class="content-table-td"><b>Default Email</b></td>
                                                    <td align="center">:</td>
                                                    <td align="left" id="defaultEmail"></td>
                                                </tr>
                                                <tr class="table-td-info">
                                                    <td align="left" class="content-table-td"><b>Mobile</b></td>
                                                    <td align="center">:</td>
                                                    <td align="left" id="mobile"></td>
                                                </tr>
                                                <tr class="table-td-info">
                                                    <td align="left" class="content-table-td"><b>Email</b></td>
                                                    <td align="center">:</td>
                                                    <td align="left" id="email"></td>
                                                </tr>

                                            </table>
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
    
@endsection
@push('scripts')
<script>

    function removeReminder(reminderNo) {

        swal({
            title: "Are you sure?",
            text: "",
            type: "warning",
            showCancelButton: true,
            closeOnConfirm: false,
            confirmButtonText: "Yes, remove it...!",
            confirmButtonColor: "#ec6c62"

        }, function () {

            showLoader();

            $.ajax({

                url: "/client/reminder/set-reminder/" + reminderNo,

                type: "DELETE",

                data: {
                    _token: "{{ csrf_token() }}"
                }

            })

            .done(function (data) {

                hideLoader();

                if (data === '1' || data == 1) {

                    swal({
                        title: "Remove Successfully",
                        text: "This Reminder is removed now",
                        type: "success",
                        closeOnConfirm: false,
                        confirmButtonText: "Ok",
                        confirmButtonColor: "#A5DC86"

                    }, function () {

                        window.location.href = "/client/reminder/set-reminder";
                    });

                } else if (data == '2' || data == 2) {

                    window.location.href = "/client/reminder/set-reminder";
                }

            })

            .fail(function () {
                hideLoader();

                swal(
                    "Oops",
                    "We couldn't connect to the server!",
                    "error"
                );
            });

        });
    }


    var defaultReminderToObj = @json(json_decode($defaultReminderTo));


    function showDetails(count) {

        $('#reminderDetailsShowBtn').click();

        if ($('#reminderForHidden' + count).val() === 'Custom') {

            $('#vehicleRegNoTr').hide();

            $('#reminderTypeTr').hide();

        } else {

            $('#vehicleRegNoTr').show();

            $('#reminderTypeTr').show();

            $('#vehicleRegNo').text(
                $('#reminderForValueHidden' + count).val()
            );

            $('#reminderType').text(
                $('#reminderTypeHidden' + count).val()
            );
        }

        $('#reminderFor').text(
            $('#reminderForHidden' + count).val()
        );

        $('#reminderHeading').text(
            $('#headingHidden' + count).val()
        );

        $('#reminderBody').text(
            $('#bodyHidden' + count).val()
        );

        $('#reminderOn').text(
            $('#reminderOnDtTmHidden' + count).val()
        );

        $('#repeatEvery').text(
            $('#repeatEveryHidden' + count).val()
        );

        $('#showReminder').text(
            $('#showReminderHidden' + count).val() +
            ' Before Reminder On Date'
        );

        $('#mobile').text(
            $('#mobileNoHidden' + count).val()
        );

        $('#email').text(
            $('#emailHidden' + count).val()
        );


        var reminderFor = $('#reminderForDbHidden' + count).val();

        var defaultMobileFlag = $('#defaultMobileHidden' + count).val();

        var defaultEmailFlag = $('#defaultEmailHidden' + count).val();


        var mobileNoArr = [];

        var emailArr = [];


        if (defaultMobileFlag === '1') {

            for (
                var i = 0;
                i < defaultReminderToObj.defaultReminder.length;
                i++
            ) {

                if (
                    defaultReminderToObj.defaultReminder[i].reminder_for === reminderFor &&
                    defaultReminderToObj.defaultReminder[i].reminder_channel_type === 'mobileNo'
                ) {

                    mobileNoArr.push(
                        defaultReminderToObj.defaultReminder[i].reminder_no
                    );
                }
            }
        }


        if (defaultEmailFlag === '1') {

            for (
                var i = 0;
                i < defaultReminderToObj.defaultReminder.length;
                i++
            ) {

                if (
                    defaultReminderToObj.defaultReminder[i].reminder_for === reminderFor &&
                    defaultReminderToObj.defaultReminder[i].reminder_channel_type === 'email'
                ) {

                    emailArr.push(
                        defaultReminderToObj.defaultReminder[i].reminder_no
                    );
                }
            }
        }


        $('#defaultMobile').text(
            mobileNoArr.join(' , ')
        );

        $('#defaultEmail').text(
            emailArr.join(' , ')
        );
    }

</script>
@endpush