@extends('layouts.app')
@section('content')
<style>
    .panel {
        padding: 0px; 
    }

</style>

<div class="header dashboard_from">
    <h1 class="page-title">Call Log</h1>
    <ul class="breadcrumb">
        <li><a href="#">Home</a> / </li>
        <li><a href="#">  CRM</a> / </li>
        <li><a href="#">Call Log</a></li>
    </ul>
</div>
<div class="main-content">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
     <div class="row">
        <div class="col-sm-6 col-md-6">
            <div class="panel panel-default p-2"> 
                <div class="panel-heading bg-primary p-1"> 
                    <b>Call History</b>
                </div>
                <div class="panel-body"> 
                    <a href="{{ route('admin.crm.call-log.create') }}" class="btn btn-primary save_button my-3">Add Call Log</a>
                    <br><hr>
                    <div class="form-group" >
                        <label class="form-label">Choose Date</label>
                        <div class="form-group" >
                            <div class="input-group">
                                <form id="historyForm" action="admin/Crm/callLog" method="POST">
                                    <input type="text" class="form-control dateInput" name="historyDate" id="historyDate" value="{{ $callHistoryDate ?? null }}">
                                </form>
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-primary save_button" onclick="submitHistoryForm()">Go</button>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover custom-table dataTable" id="datatable">
                            <thead>
                                <tr class="bg-primary">
                                    <th>SL</th>
                                    <th>Customer Info</th>
                                    <th>Reason</th>
                                    <th>Call Time</th>
                                    <th>Call By</th>
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
                                </tr>
                            </tfoot>
                            <tbody>
                                @php $count = 1; @endphp
                                @if ($callHistories)
                                    @foreach ( $callHistories as $callHistory)
                                        <tr>
                                        <td class="td-center">{{ $count }}</td>

                                        <td>
                                            {{ $callHistory->customer_name }} 
                                            ({{ $callHistory->customer_mobile_no }})
                                        </td>

                                        <td>{{ $callHistory->call_reason_text }}</td>

                                        <td class="td-center">
                                            {{ \Carbon\Carbon::parse($callHistory->call_start_dt_tm)->format('h:i A') }}
                                        </td>

                                        <td>{{ $callHistory->created_by_name ?? null }}</td>

                                        <td class="td-center">
                                            <div class="btn-group">
                                                <button type="button" 
                                                        class="btn btn-sm dropdown-toggle" 
                                                        data-bs-toggle="dropdown" 
                                                        aria-expanded="false">
                                                    Action
                                                </button>

                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('admin/Crm/showCallLogShow?logId=' . $callHistory->log_id) }}">
                                                            Show
                                                        </a>
                                                    </li>

                                                    @if ($callHistoryDate == date('Y-m-d'))
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('admin.crm.call-log.edit',$callHistory->log_id) }}">
                                                                Edit
                                                            </a>
                                                        </li>

                                                        <li><hr class="dropdown-divider"></li>

                                                        <li>
                                                            <a class="dropdown-item text-danger" href="javascript:void(0)" 
                                                            onclick="removeCallLog('{{ $callHistory->log_id }}')">
                                                                Delete
                                                            </a>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="text-center">
                                            No call history found
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div> 
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-6">
            <div class="panel panel-default p-2"> 
                <div class="panel-heading bg-primary p-1"> 
                    <b>My Call Task</b>
                </div>
                <div class="panel-body">

                    <div class="form-group mt-2" >
                        <label class="form-label">Choose Date</label>
                        <div class="form-group" >
                            <div class="input-group">
                                <form id="taskForm" action="admin/Crm/callLog" method="POST">
                                    <input type="text" class="form-control dateInput" name="taskDate" id="taskDate" value="{{ $callTaskDate ?? null }}">
                                </form>
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-primary save_button" onclick="submitTaskForm()">Go</button>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover custom-table dataTable" id="datatable">
                            <thead>
                                <tr class="bg-primary">
                                    <th>SL</th>
                                    <th>Customer Info</th>
                                    <th>Reason</th>
                                    <th>Next Call Time</th>
                                    <th>Call By</th>
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
                                </tr>
                            </tfoot>
                            <tbody>
                                @php $count = 1; @endphp
                                @if ($callTasks)
                                    @foreach ( $callTasks as $callTask )
                                        <tr>
                                        <td class="td-center">{{ $count }}</td>

                                        <td>
                                            {{ $callTask->customer_name }} 
                                            ({{ $callTask->customer_mobile_no }})
                                        </td>

                                        <td>{{ $callTask->call_reason_text }}</td>

                                        <td class="td-center">
                                            {{ \Carbon\Carbon::parse($callTask->next_call_dt_tm)->format('h:i A') }}
                                        </td>

                                        <td>{{ $callTask->created_by_name ?? null }}</td>

                                        <td class="text-center">
                                            <div class="btn-group">
                                                <!-- Changed data-toggle to data-bs-toggle -->
                                                <button type="button" 
                                                        class="btn btn-outline-secondary btn-sm dropdown-toggle" 
                                                        data-bs-toggle="dropdown" 
                                                        aria-expanded="false">
                                                    Action
                                                </button>

                                                <!-- Changed pull-right to dropdown-menu-end -->
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/crm/make-call?logId=' . $callTask->log_id) }}">
                                                            Make Call
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            No call tasks found
                                        </td>
                                    </tr>    
                                @endif
                            </tbody>
                        </table>
                    </div> 
                </div>

            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-sm-12 col-md-12">
            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                <div class="panel panel-default p-2">
                    <div class="panel-heading" role="tab" id="headingOne">
                        <h4 class="panel-title p-2">
                            <a role="button" data-toggle="collapse" data-parent="#" href="#removeCallLog" aria-expanded="true" aria-controls="removeCallLog">
                                Delete Call Log
                            </a>
                        </h4>
                    </div>
                    <div id="removeCallLog" class="panel-collapse" role="tabpanel" aria-labelledby="headingOne">
                        <div class="panel-body">
                            <a href="#" onclick="removeAllLog()" class="btn btn-danger save_button mt-3 mb-3">Delete All</a>
                            <br>
                            <form id="removeCallLogPanelForm" action="admin/Crm/removeCallLogPanel" method="POST">
                                <div class="row">
                                    <div class="col-md-5 col-sm-5 col-xs-12">
                                        <div class="form-group">
                                            <label class="form-label">From Date</label>
                                            <input type="text" class="form-control dateInput" name="fromDate" id="fromDate" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-md-5 col-sm-5 col-xs-12">
                                        <div class="form-group">
                                            <label class="form-label">To Date</label>
                                            <input type="text" class="form-control dateInput" name="toDate" id="toDate" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-md-2 col-sm-2 col-xs-12">
                                        <div class="form-group">
                                            <label class="mt-2">&nbsp;</label>
                                            <button type="button" class="btn btn-danger form-control save_button" onclick="removeCallLogPanel()">
                                                Delete
                                            </button>
                                        </div>
                                    </div>

                                    <input type="hidden" id="removeType" name="removeType">
                                </div>
                            </form>
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
$(document).ready(function () {
    $('#datatable, .dataTable').DataTable({
        columnDefs: [
            {
                defaultContent: "-",
                targets: "_all"
            }
        ]
    });
});
</script>
<script>
    function submitHistoryForm() {
        $('#historyForm').submit();
    }

    function submitTaskForm() {
        $('#taskForm').submit();
    }

    function removeCallLog(logId) {

        Swal.fire({
            title: "Are you sure?",
            text: "This action cannot be undone",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#ec6c62",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "Cancel"
        }).then((result) => {

            if (!result.isConfirmed) return;

            showLoader();

            let url = "{{ route('admin.crm.call-log.destroy', ':log_id') }}";
            url = url.replace(':log_id', logId);

            $.ajax({
                url: url,
                type: "POST",
                data: {
                    _method: "DELETE",
                    _token: "{{ csrf_token() }}"
                }
            })
            .done(function (data) {

                hideLoader();

                if (data == '1') {

                    Swal.fire({
                        title: "Successfully Done",
                        icon: "success",
                        confirmButtonText: "Ok",
                        confirmButtonColor: "#A5DC86"
                    }).then(() => {
                        window.location.href = "/admin/crm/call-log";
                    });

                } else if (data == '2') {

                    Swal.fire("Oops", "Invalid action!", "error");

                } else if (data == '3') {

                    Swal.fire(
                        "Oops",
                        "This call is referencing another call. Failed to remove",
                        "error"
                    );

                } else {

                    window.location.href = "/admin/crm/call-log";
                }
            })
            .fail(function () {

                //hideLoader();
                Swal.fire("Oops", "We couldn't connect to the server!", "error");
            });
        });
    }
    function removeAllLog() {
        swal({
            title: "Are you sure?",
            text: "",
            type: "warning",
            showCancelButton: true,
            closeOnConfirm: false,
            confirmButtonText: "",
            confirmButtonColor: "#ec6c62"
        }, function () {
            swal.close();
            showLoader();
            $.ajax({
                url: "/admin/Crm/truncateCallLog",
                type: "DELETE"
            })

                    .done(function (data) {
                        hideLoader();
                        if (data === '1') {
                            swal({
                                title: "Successfully Done",
                                text: "",
                                type: "success",
                                closeOnConfirm: false,
                                confirmButtonText: "Ok",
                                confirmButtonColor: "#A5DC86"
                            }, function () {
                                window.location.href = "/admin/Crm/callLog";
                            });
                        }
                    })

                    .error(function (data) {
                        swal("Oops", "We couldn't connect to the server!", "error");
                    });
        });
    }

    function removeCallLogPanel() {
        var fromDate = $('#fromDate').val();
        var toDate = $('#toDate').val();

        if (fromDate === '' || toDate === '') {
            sweetAlert('From date and to date is required...!');
            return false;
        }

        var fDate = new Date(fromDate);
        var tDate = new Date(toDate);

        if (fDate > tDate) {
            sweetAlert('From date can not be greater than to date...!');
            return false;
        }

        swal({
            title: "Are you sure?",
            text: "",
            type: "warning",
            showCancelButton: true,
            closeOnConfirm: false,
            confirmButtonText: "",
            confirmButtonColor: "#ec6c62"
        }, function () {
            swal.close();
            showLoader();
            $.ajax({
                url: "/admin/Crm/removeCallLogPanel",
                data: {fromDate: fromDate, toDate: toDate},
                type: "POST"
            })

                    .done(function (data) {
                        hideLoader();
                        if (data === '1') {
                            swal({
                                title: "Successfully Done",
                                text: "",
                                type: "success",
                                closeOnConfirm: false,
                                confirmButtonText: "Ok",
                                confirmButtonColor: "#A5DC86"
                            }, function () {
                                window.location.href = "/admin/Crm/callLog";
                            });
                        }
                    })

                    .error(function (data) {
                        swal("Oops", "We couldn't connect to the server!", "error");
                    });
        });
    }
</script>
@endpush
