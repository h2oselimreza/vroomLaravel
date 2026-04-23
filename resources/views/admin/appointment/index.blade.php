@extends('layouts.app')

@section('content')

<div class="header dashboard_from">
    <h1 class="page-title">Appointment List</h1>
    <ul class="breadcrumb">
        <li><a href="#">Home</a></li>
        <li><a href="#">/ Appointment</a></li>
        <li><a href="#">/ Appointment List</a></li>
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
                <div class="row">

                    <form action="{{ route('client.appointment.module.index') }}" 
                          id="statusForm">
                
                        <div class="col-md-2 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Status</label>
                
                                <select class="form-control"
                                        id="statusDropDown"
                                        name="status"
                                        onchange="changeStatus()">
                
                                    <option value="{{ $status }}">
                                        {{ get_appointment_status($status) }}
                                    </option>
                
                                    @foreach($statusLists as $statusList => $x)
                
                                        @if($status != $x)
                                            <option value="{{ $x }}">
                                                {{ get_appointment_status($x) }}
                                            </option>
                                        @endif
                
                                    @endforeach
                
                                </select>
                            </div>
                        </div>
                
                    </form>
                
                </div> 
                <div class="table-responsive">

                    <table class="table table-bordered table-hover custom-table" id="datatable">
                        <thead>
                            <tr class="bg-primary">
                                <th class="text-center">SL</th>
                                <th class="text-center">Appointment No</th>
                                <th class="text-start">Account Name</th>
                                <th class="text-center">Account Type</th>
                                <th>Workshop</th>
                                <th>Appointment Date</th>
                                <th>Appointment Time</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($data as $index => $appointment)
                            <tr>
                                <td class="td-center">{{ $loop->iteration }}</td>
                                <td>{{ $appointment->appointment_no }}</td>
                                <td>{{ $appointment->company_name }}</td>
                            
                                <td class="td-center">
                                    {{ get_account_type_name($appointment->company_type) }}
                                </td>
                            
                                <td class="td-left">{{ $appointment->workshop_name }}</td>
                            
                                <td class="td-center">
                                    {{ get_date_format1($appointment->final_date) }}
                                </td>
                            
                                <td class="td-center">
                                    {{ get_time_format($appointment->appointment_time) }}
                                </td>
                            
                                <td class="td-center">
                                    {{ get_appointment_status($appointment->status, 'client') }}
                                </td>
                            
                                <td class="td-center">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-bs-toggle="dropdown">
                                            Action <span class="caret"></span>
                                        </button>
                            
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item"
                                                   href="{{ route('client.appointment.module.details', [$appointment->appointment_no, $appointment->company]) }}">
                                                    Show
                                                    {{-- //'companyCode' => $appointment->company --}}
                                                </a>
                                            </li>

                                            @if($appointment->status == config('constants.APPOINTMENT_PENDING'))
                                                <li>
                                                    <a class="dropdown-item"
                                                    onclick="appointmentChangeStatus('{{ $appointment->appointment_no }}', '{{ config('constants.APPOINTMENT_PROCCESSING') }}')">
                                                        Processing
                                                    </a>
                                                </li>
                                            @endif

                                            @if($appointment->status == config('constants.APPOINTMENT_PROCCESSING'))
                                                <li>
                                                    <a class="dropdown-item"
                                                       href="#"
                                                       onclick="appointmentChangeStatus('{{ $appointment->appointment_no }}', '{{ config('constants.APPOINTMENT_ACCEPT') }}')">
                                                        Accept
                                                    </a>
                                                </li>
                                            @endif
                            
                                            @if($appointment->status == config('constants.APPOINTMENT_ACCEPT'))
                                                <li>
                                                    <a class="dropdown-item"
                                                       href="#"
                                                       onclick="appointmentChangeStatus('{{$appointment->appointment_no}}', '{{config('constants.APPOINTMENT_COMPLETE')}}')">
                                                        Complete
                                                    </a>
                                                </li>
                                            @endif

                                            @if($appointment->status != config('constants.APPOINTMENT_REJECT') && $appointment->status != config('constants.APPOINTMENT_COMPLETE'))
                                                <li>
                                                    <a class="dropdown-item"
                                                       data-bs-toggle="modal"
                                                       data-bs-target="#rejectModal{{ $appointment->appointment_no }}">
                                                        Reject
                                                    </a>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- Reject Modal -->
                            <div class="modal fade" id="rejectModal{{ $appointment->appointment_no }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                            
                                        <div class="modal-header">
                                            <h5 class="modal-title">Reject Reason</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                            
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>
                                                    Reason <span class="text-danger">*</span>
                                                </label>
                            
                                                <small class="d-none text-danger"
                                                       id="reasonReq-error{{ $index }}">
                                                    Reason is required
                                                </small>
                            
                                                <textarea class="form-control"
                                                          id="rejectReason{{ $index }}"
                                                          rows="4">{{ $appointment->reject_reason }}</textarea>
                                            </div>
                                        </div>
                            
                                        <div class="modal-footer">
                                            <button class="btn btn-danger"
                                                    onclick="requestReject('{{ $index }}')">
                                                Reject
                                            </button>
                                        </div>
                            
                                        <input type="hidden"
                                               id="appointmentNoHidden{{ $index }}"
                                               value="{{ $appointment->appointment_no }}">
                            
                                    </div>
                                </div>
                            
                                <!-- Loader -->
                                <div class="appRejectModal{{ $index }}" style="display:none">
                                    <div class="spinnerModal"></div>
                                </div>
                            </div>
                            
                            @endforeach
                        </tbody>

                        <tfoot>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>

                    </table>

                </div>
            </div>

        </div>
    </div>
</div>

@endsection


@push('scripts')
<script>
$(document).ready(function () {

    $('#datatable').DataTable({
        pageLength: 10,
        ordering: true,
        searching: true
    });

});
</script>
<script>
    function appointmentChangeStatus(appointmentNo, status) {

        status = Number(status); // ✅ FIX

        var confirmText;
        var statusDropDown = $('#statusDropDown').val();

        if (status === 3) {
            confirmText = "Yes, Processing it...!";
        } else if (status === 4) {
            confirmText = "Yes, Accept it...!";
        } else if (status === 6) {
            confirmText = "Yes, Complete it...!";
        }

        Swal.fire({
            title: "Are you sure?",
            text: "",
            icon: "warning",
            confirmButtonText: confirmText,
            showCancelButton: true,
            confirmButtonColor: "#ec6c62",
            //reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('admin/appointment/appointment-change-status') }}" 
                        + "?appointmentNo=" + appointmentNo + "&status=" + status,
                    type: "GET"
                })
                .done(function (data) {
                    if (data == 1) {
                        Swal.fire({
                            title: "Successfully Done",
                            icon: "success",
                            confirmButtonText: "Ok",
                            confirmButtonColor: "#A5DC86"
                        }).then(() => {
                            window.location.href = "{{ url('/admin/appointment/appointment-list') }}";
                        });
                    } else if (data == 2) {
                        window.location.href = "{{ url('/admin/appointment/appointment-list') }}";
                    } else if (data == 3) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed',
                            text: 'Due to not set confirm appointment date and time, you can not make it Accept...!'
                        }).then(() => {
                            window.location.href = "{{ url('/admin/appointment/appointment-list') }}";
                        });
                    }
                })
                .fail(function () {
                    Swal.fire("Oops", "We couldn't connect to the server!", "error");
                });
            }
        });
        }

    function requestReject(serial) {
        var statusDropDown = $('#statusDropDown').val();
        var appointmentNo = $('#appointmentNoHidden' + serial).val();

        var fieldsArr = new Array("rejectReason" + serial + "|reasonReq-error" + serial);

        var inputFiledJsonData = getInputData(fieldsArr);

        if (!inputFiledJsonData) {
            return false;
        }

        var confirmText = "Yes, Reject it...!";

        swal({
            title: "Are you sure?",
            text: "",
            type: "warning",
            showCancelButton: true,
            closeOnConfirm: false,
            confirmButtonText: confirmText,
            confirmButtonColor: "#ec6c62"
        }, function () {

            $('.appRejectModal' + serial).show();

            $.ajax({
                type: 'POST',
                data: inputFiledJsonData,
                url: "{{ url('admin/Appointment/rejectAppointment') }}"
                    + "?serial=" + serial + "&appointmentNo=" + appointmentNo,

                success: function (result) {
                    $('.appRejectModal' + serial).hide();

                    if (result == 1) {
                        successAlert(
                            "Successfully rejected",
                            "{{ url('/admin/appointment/appointment-list') }}/" + statusDropDown
                        );

                    } else if (result == 2) {
                        successAlert(
                            "Can not possible",
                            "{{ url('/admin/appointment/appointment-list') }}/" + statusDropDown
                        );
                    }
                }
            });

        });
    }

    function changeStatus() {
        //showLoader();
        $('#statusForm').submit();
    }
</script>
@endpush