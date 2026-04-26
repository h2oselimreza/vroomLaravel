@extends('layouts.app')

@section('content')

<div class="header dashboard_from">
    <h1 class="page-title">Assign Employee To Home Service</h1>
    <ul class="breadcrumb">
        <li><a href="admin/Home">Home</a> / </li>
        <li><a href="#">Home Service</a> / </li>
        <li><a href="admin/AdminHomeService/assignEmployee">Assign Employee To Home Service</a></li>
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
                                <th>SL</th>
                                <th>Home Service No</th>
                                <th>Account Name</th>
                                <th>Account Type</th>
                                <th>Confirm Date</th>
                                <th>Confirm Time</th>
                                <th>Assigned Employee</th>
                                <th>Assigned Date Time</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if ($appointmentLists)
                                @foreach ($appointmentLists as $appointment)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        
                                        <td>
                                            <a target="_blank" href="{{ url('admin/AdminHomeService/showAssignEmployee') }}?appointmentNo={{ $appointment->appointment_no }}&companyCode={{ $appointment->company }}">
                                                {{ $appointment->appointment_no }}
                                            </a>
                                        </td>

                                        <td>{{ $appointment->company_name }}</td>
                                        
                                        <td class="text-center">
                                            {{ get_account_type_name($appointment->company_type) }}
                                        </td>
                                        
                                        <td class="text-center">
                                            {{ get_date_format1($appointment->final_date) }}
                                        </td>
                                        
                                        <td class="text-center">
                                            {{ get_time_format($appointment->appointment_time) }}
                                        </td>
                                        
                                        <td>{{ $appointment->assigned_employee_name ?? '-' }}</td>

                                        <td class="text-center">
                                            {{ get_date_time_format($appointment->assign_emp_dt_tm) }}
                                        </td>

                                        <td class="text-center">
                                            <div class="btn-group">
                                                <button type="button" 
                                                        class="btn btn-sm dropdown-toggle border-0 shadow-none bg-transparent text-dark p-0" 
                                                        data-bs-toggle="dropdown" 
                                                        aria-expanded="false">
                                                    Action
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                                    <li>
                                                        <a class="dropdown-item" href="javascript:void(0)" onclick="showAssignEmployeeModal('{{ $appointment->appointment_no }}')">
                                                            <i class="bi bi-person-check me-1"></i> Assign
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                 <tr>
                                    <td class="text-center text-muted">No appointments found.</td>
                                </tr>   
                            @endif
                        </tbody>
                    </table>
                    <button type="button" class="hidden" data-toggle="modal" data-target="#myModal" id="showAssignEmployeeModalBtn"></button>
                    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel">Employee List</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover custom-table dataTable" id="">
                                            <thead>
                                                <tr class="bg-info">
                                                    <th>SL</th>
                                                    <th>Employee ID</th>
                                                    <th>Employee Name</th>
                                                    <th>Designation</th>
                                                    <th>Contact No</th>
                                                    <th>Email</th>
                                                    <th>Select</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if ($employees)
                                                    @foreach ($employees as $employee)
                                                        <tr>
                                                            <td class="td-center">{{ $loop->iteration }}</td>

                                                            <td>{{ $employee['employee_id'] }}</td>
                                                            <td>{{ $employee['employee_name'] }}</td>
                                                            <td>{{ $employee['designation_name'] }}</td>
                                                            <td>{{ $employee['primary_mobile'] }}</td>
                                                            <td>{{ $employee['email'] }}</td>

                                                            <td class="td-center">
                                                                <button 
                                                                    type="button"
                                                                    class="btn btn-primary btn-xs btn-circle-puchase"
                                                                    onclick="assignEmployee('{{ $employee['employee_id'] }}')">
                                                                    <i class="fa fa-arrow-down"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else 
                                                    <tr>
                                                        <td class="text-center text-muted">
                                                            No employees found.
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary save_button" id="modalCloseBtn" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type='hidden' id='appointmentNoHidden' name="appointmentNoHidden">
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
    function showAssignEmployeeModal(appointmentNo) {
         $('#appointmentNoHidden').val(appointmentNo);
        const modalEl = document.getElementById('myModal');
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    }

    function assignEmployee(employeeId) {
        let appointmentNo = $.trim($('#appointmentNoHidden').val());
        employeeId = $.trim(employeeId);

        if (!appointmentNo || !employeeId) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Invalid appointment or employee.'
            });
            return;
        }

        Swal.fire({
            title: 'Are you sure?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            confirmButtonColor: '#62ec6f'
        }).then((result) => {

            if (result.isConfirmed) {

                $('#modalCloseBtn').click();
                //showLoader();

                $.ajax({
                    type: 'POST',
                    url: '{{ route('admin.home-service.assign-employee.store') }}',
                    data: {
                        appointmentNo: appointmentNo,
                        employeeId: employeeId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        //hideLoader();

                        if (response === '1') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Employee successfully assigned.',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.location.href = "{{ url('/admin/home/service-assign-employee') }}";
                            });

                        } else if (response === '2') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Failed!',
                                text: 'Failed to assign employee.'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Unexpected Error!',
                                text: 'Something unexpected happened.'
                            });
                        }
                    },
                    error: function () {
                        hideLoader();
                        Swal.fire({
                            icon: 'error',
                            title: 'Server Error!',
                            text: 'Please try again later.'
                        });
                    }
                });
            }
        });
    }

    function changeStatus() {
        showLoader();
        $('#statusForm').submit();
    }
</script>

@endpush