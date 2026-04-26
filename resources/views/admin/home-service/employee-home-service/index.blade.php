@extends('layouts.app')

@section('content')

<div class="header dashboard_from">
    <h1 class="page-title">Employee Home Service</h1>
    <ul class="breadcrumb">
        <li><a href="admin/Home">Home</a> / </li>
        <li><a href="#"> Employee Home Service</a> / </li>
        <li><a href="#"> Employee Home Service List</a></li>
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
 
                <div class="table-responsive">

                    <table class="table table-bordered table-hover custom-table" id="datatable">
                        <thead>
                            <tr class="bg-primary">
                                <th>SL</th>
                                <th>Employee ID</th>
                                <th>Employee Name</th>
                                <th>Designation</th>
                                <th>Contact No</th>
                                <!--<th>Total Engaged Service</th>-->
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>SL</th>
                                <th>Employee ID</th>
                                <th>Employee Name</th>
                                <th>Designation</th>
                                <th>Contact No</th>
                                <!--<th>Total Engaged Service</th>-->
                                <th>Status</th>
                            </tr>
                        </tfoot>

                        <tbody>
                            @forelse($employees as $employee)
                                <tr>
                                    <td class="td-center">{{ $loop->iteration }}</td>

                                    <td>{{ $employee->employee_id }}</td>
                                    <td>{{ $employee->employee_name }}</td>
                                    <td>{{ $employee->designation_name }}</td>
                                    <td>{{ $employee->primary_mobile }}</td>

                                    <td class="td-center">
                                        @if($employee->is_active == 1)
                                            Active
                                        @elseif($employee->is_active == 0)
                                            Inactive
                                        @endif
                                    </td>

                                    <td class="td-center">
                                        <div class="btn-group">
                                            <button type="button"
                                                    class="btn btn-default btn-xs dropdown-toggle"
                                                    data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                Action
                                            </button>

                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item"
                                                    href="{{ route('admin.home-service.employee-home-service.show', $employee->employee_id) }}">
                                                        Assigned Home Service List
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">
                                        No employees found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
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

@endpush