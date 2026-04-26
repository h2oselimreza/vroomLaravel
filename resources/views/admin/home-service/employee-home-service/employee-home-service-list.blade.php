@extends('layouts.app')

@section('content')

<div class="header dashboard_from">
    <h1 class="page-title">Employee Home Service Details</h1>
    <ul class="breadcrumb">
        <li><a href="admin/Home">Home</a> / </li>
        <li><a href="/admin/home/employee-home-service">  Employee Home Service List</a> / </li>
        <li><a href="#"> Employee Home Service Details</a></li>
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
                <div class="text-center">
                    @if($empPersonalInfo)
                        <h4><b>{{ $empPersonalInfo->employee_name }}</b></h4>
                        <h5>({{ $empPersonalInfo->employee_id }})</h5>
                    @endif                
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
                                <th>Status</th>
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
                            @forelse ($empHomeSerLists as $empHomeSerList)
                                <tr>
                                    <td class="td-center">{{ $loop->iteration }}</td>

                                    <td>{{ $empHomeSerList->appointment_no }}</td>

                                    <td>{{ $empHomeSerList->company_name }}</td>

                                    <td class="td-center">
                                        {{ get_account_type_name($empHomeSerList->company_type) }}
                                    </td>

                                    <td class="td-center">
                                        {{ get_date_format1($empHomeSerList->final_date) }}
                                    </td>

                                    <td class="td-center">
                                        {{ get_time_format($empHomeSerList->appointment_time) }}
                                    </td>

                                    <td class="td-center">
                                        {{ get_appointment_status($empHomeSerList->status, 'client') }}
                                    </td>

                                    <td class="td-center">
                                        <div class="btn-group">
                                            <button type="button"
                                                    class="btn btn-default btn-xs dropdown-toggle"
                                                    data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                Action <span class="caret"></span>
                                            </button>

                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item"
                                                    href="{{ route('admin.employee-home-service-details',[$empHomeSerList->appointment_no, $empPersonalInfo->employee_id]) }}">
                                                        Show
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">
                                        No records found.
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