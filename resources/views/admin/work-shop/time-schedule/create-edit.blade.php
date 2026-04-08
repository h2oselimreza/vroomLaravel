@extends('layouts.app')

@section('content')

<div class="header dashboard_from">
    <h1 class="page-title">
        {{ isset($data->exists) ? 'Edit Time Schedule' : 'Add Workshop' }}
    </h1>
</div>

@php
    $path = request()->path();
    $lastPart = collect(explode('/', $path))->last();
@endphp
<div class="container">
    <div class="card shadow">
        <div class="card-body">
            <!-- Nav Tabs -->
            @include('admin.work-shop.tab')
            {{-- Success Message --}}
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Validation Errors --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Tab Content -->
            <div class="tab-content" id="employeeTabContent">

                <div class="tab-pane fade show active"
                    id="personal"
                    role="tabpanel">
                    @php
                        $isEdit = isset($data);
                    @endphp
                    <div class="accordion" id="employeeAccordion">

                        {{-- Time Schedule --}}
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#personalInfo"
                                        aria-expanded="true">
                                    Time Schedule
                                </button>
                            </h2>

                            <div id="personalInfo"
                                class="accordion-collapse collapse show"
                                data-bs-parent="#employeeAccordion">

                                <div class="accordion-body">

                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-xs-12">

                                            <form action="{{ route('admin.workshop-time-schedule.update',1) }}" method="post" id="timeScheduleForm">
                                                @csrf
                                                @method('PUT')

                                                <div class="table-responsive">
                                                    <table class="table table-bordered custom-table" id="timeScheduleTable">
                                                        <thead>
                                                            <tr class="bg-info">
                                                                <th>Day</th>
                                                                <th>From Time</th>
                                                                <th>To Time</th>
                                                                <th>Total Time (H:m)</th>
                                                                <th class="text-center">Weekend</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php $serial = 1; @endphp
                                                            @foreach ($weekdays as $weekday)
                                                                @php
                                                                    $checkBoxFlag = '';
                                                                    $regFromTime = '';
                                                                    $regToTime = '';
                                                                    $totalTime = '';
                                                                    $timeScheduleId = '';
                                                                    $readonlyFlag = '';
                                                                    $rowClass = '';

                                                                    foreach ($timeSchedules as $timeSchedule) {
                                                                        if ($timeSchedule->week_day == $weekday->element_code) {
                                                                            if ($timeSchedule->weekend_status == 1) {
                                                                                $checkBoxFlag = 'checked';
                                                                                $readonlyFlag = 'readonly';
                                                                                $rowClass = 'row-blurred';
                                                                            }
                                                                            $regFromTime = $timeSchedule->start_time ? date('H:i a', strtotime($timeSchedule->start_time)) : '';
                                                                            $regToTime = $timeSchedule->end_time ? date('H:i a', strtotime($timeSchedule->end_time)) : '';
                                                                            $totalTime = $timeSchedule->total_time ? \Carbon\Carbon::parse($timeSchedule->total_time)->format('H:i') : '';
                                                                            $timeScheduleId = $timeSchedule->id;
                                                                        }
                                                                    }
                                                                @endphp

                                                                <tr class="{{ $rowClass }}" id="row_{{ $serial }}">
                                                                    <td class="text-center"><b>{{ $weekday->element }}</b></td>
                                                                    <td>
                                                                        <input type="text" class="form-control timepicker" 
                                                                            id="regFromTime{{ $serial }}" name="regFromTime{{ $serial }}" 
                                                                            onchange="setRegMinTime({{ $serial }})" 
                                                                            value="{{ $regFromTime }}" {{ $readonlyFlag }}>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" class="form-control timepicker" 
                                                                            id="regToTime{{ $serial }}" name="regToTime{{ $serial }}" 
                                                                            onchange="setRegMinTime({{ $serial }})" 
                                                                            value="{{ $regToTime }}" {{ $readonlyFlag }}>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" class="form-control" 
                                                                            id="regMinTime{{ $serial }}" name="regMinTime{{ $serial }}" 
                                                                            value="{{ $totalTime }}" {{ $readonlyFlag }}>
                                                                    </td>
                                                                    <td class="td-center">
                                                                        <input type="checkbox" name="weekendCheckBox{{ $serial }}" 
                                                                            id="weekendCheckBox{{ $serial }}" 
                                                                            onclick="weekendCheck({{ $serial }})" {{ $checkBoxFlag }}>
                                                                    </td>
                                                                    
                                                                    {{-- Hidden Fields --}}
                                                                    <input type="hidden" name="weekDayCode{{ $serial }}" id="weekDayCode{{ $serial }}" value="{{ $weekday->element_code }}">
                                                                    <input type="hidden" name="weekDayName{{ $serial }}" id="weekDayName{{ $serial }}" value="{{ $weekday->element }}">
                                                                    <input type="hidden" name="timeScheduleId{{ $serial }}" id="timeScheduleId{{ $serial }}" value="{{ $timeScheduleId }}">
                                                                </tr>
                                                                @php $serial++; @endphp
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <input type="hidden" name="workshopCode" value="{{ $workshopCode }}">
                                                <input type="hidden" name="total_count" value="{{ $serial - 1 }}">
                                            </form>
                                            <button class="btn btn-primary save_button" onclick="saveTimeSchedule()">Save Time Schedule</button>
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
</div>
@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js"></script>
<script>
    function setRegMinTime(serial) {
        if ($('#weekendCheckBox' + serial).is(':checked')) {
            $('#regFromTime' + serial).val('');
            $('#regToTime' + serial).val('');
            $('#regMinTime' + serial).val('');
        }
        var regFromTime = $('#regFromTime' + serial).val();
        var regToTime = $('#regToTime' + serial).val();
        var duration = getTimeDuration(regFromTime, regToTime);
        $('#regMinTime' + serial).val(duration);
    }

    function getTimeDuration(regFromTime, regToTime) {
        var startTime = moment(regFromTime, "HH:mm:ss a");
        var endTime = moment(regToTime, "HH:mm:ss a");
        var duration = moment.duration(endTime.diff(startTime));
        var hours = parseInt(duration.asHours());
        var minutes = parseInt(duration.asMinutes()) - hours * 60;

        if ($.isNumeric(hours) && $.isNumeric(minutes)) {
            if (hours >= 0 && minutes >= 0) {
                if (minutes < 10) {
                    minutes = '0' + minutes;
                }
                return hours + ':' + minutes;
            } else {
                return '';
            }
        }
    }

    function weekendCheck(serial) {
        if ($('#weekendCheckBox' + serial).is(':checked')) {
            $('#regFromTime' + serial).attr('readonly', true);
            $('#regToTime' + serial).attr('readonly', true);
            $('#regMinTime' + serial).attr('readonly', true);

            $('#regFromTime' + serial).val('');
            $('#regToTime' + serial).val('');
            $('#regMinTime' + serial).val('');
        } else {
            $('#regFromTime' + serial).attr('readonly', false);
            $('#regToTime' + serial).attr('readonly', false);
            $('#regMinTime' + serial).attr('readonly', false);
        }

    }

    function saveTimeSchedule() {
        var flag = 0;

        for (var i = 1; i < 8; i++) {
            if (!$('#weekendCheckBox' + i).is(':checked')) {
                flag = 1;
                var regFromTime = $('#regFromTime' + i).val();
                var regToTime = $('#regToTime' + i).val();
                var regMinTime = $('#regMinTime' + i).val();
                var regMinTimeArr = regMinTime.split(':');
                var hour = regMinTimeArr[0];
                var minute = regMinTimeArr[1];

                if (regFromTime === "" || regToTime === "" || regMinTime === "") {
                    // sweetAlert('From Time, To Time and Total Time are required of ' + $('#weekDayName' + i).val());
                    alert($('#weekDayName' + i).val());
                    hideLoader();
                    return false;
                }

                if (typeof (minute) === 'undefined' || minute === "" || hour === "") {
                    // sweetAlert('Incorrect format of total time of ' + $('#weekDayName' + i).val());
                    alert($('#weekDayName' + i).val());
                    hideLoader();
                    return false;
                }
                if (!$.isNumeric(hour) && !$.isNumeric(minute)) {
                    // sweetAlert('Hour and Minute must be numeric value of ' + $('#weekDayName' + i).val());
                    alert($('#weekDayName' + i).val());
                    hideLoader();
                    return false;
                }
                var duration = moment.duration(moment(regToTime, "HH:mm:ss a").diff(moment(regFromTime, "HH:mm:ss a")));
                var regMinTimeMinutesGiven = parseInt(duration.asMinutes());
                var regMinTimeMinutesTemp = (parseInt(hour) * 60) + parseInt(minute);
                if (regMinTimeMinutesTemp > regMinTimeMinutesGiven) {
                    sweetAlert('Total time of Regular Working can not be greater than the difference between From time and To time of ' + $('#weekDayName' + i).val());
                    hideLoader();
                    return false;
                }

            }
        }

        $('#timeScheduleForm').submit();
    }
    function hideLoader(){

    }

</script>
<script type="text/javascript">
    function weekendCheck(serial) {
        const isChecked = $('#weekendCheckBox' + serial).is(':checked');
        const $row = $('#row_' + serial);
        const $inputs = $row.find('input:not([type="checkbox"], [type="hidden"])');

        if (isChecked) {
            // Clear values and blur
            $inputs.val('').attr('readonly', true);
            $row.addClass('row-blurred');
        } else {
            // Restore access
            $inputs.attr('readonly', false);
            $row.removeClass('row-blurred');
        }
    }

    function setRegMinTime(serial) {
        let fromTime = $('#regFromTime' + serial).val();
        let toTime = $('#regToTime' + serial).val();

        if (fromTime && toTime) {
            let start = moment(fromTime, "hh:mm A");
            let end = moment(toTime, "hh:mm A");

            if (end.isBefore(start)) {
                end.add(1, 'days');
            }

            let duration = moment.duration(end.diff(start));
            let hours = Math.floor(duration.asHours());
            let minutes = duration.minutes();

            // Format as H:i
            let result = hours.toString().padStart(2, '0') + ":" + minutes.toString().padStart(2, '0');
            $('#regMinTime' + serial).val(result);
        }
    }
</script>
@endpush