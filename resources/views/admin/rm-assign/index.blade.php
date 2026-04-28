@extends('layouts.app')

@section('content')

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

<style>
    #datatableWithExport_wrapper .dt-buttons button{
        padding: 2px 13px;
    }
    #datatableWithExport_wrapper .dt-search{
        float: right;
    }
    .datatableWithExport_info{
        float: right;
        margin-top: -9px;
    }
    .dt-paging nav button{
        float: right;
        margin-top: -15px;
        padding: 4px 12px !important;
    }
    .dt-info{
        font-size: 13px;
        margin-top: 10px;
    }
</style>

<div class="header dashboard_from">
    <h1 class="page-title">Assign RM To Corporate Customer</h1>
    <ul class="breadcrumb">
        <li><a href="#">Home</a></li>
        <li><a href="/admin/home/home-service-list">/ RM Assign</a></li>
        <li><a href="/admin/home/home-service-list">/ To Corporate</a></li>
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
                <div class="row" >
                    <div class="col-md-12 col-sm-12 col-xs-12">

                        <div class="table-responsive">
                            <form action="{{ route('admin.rm-rm-assign.store') }}" method="post">
                                @csrf
                                <table class="table table-bordered table-hover custom-table" id="datatableWithExport">
                                    <thead>
                                        <tr class="bg-primary">
                                            <th width="20">SL</th>
                                            <th width="200">Company Title/Name</th>
                                            <th width="100">Company Code</th>
                                            <th width="100">Mobile</th>
                                            <th width="200">Relationship Manager <small><i>(Click to select RM)</i></small></th>
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

                                        @if (!empty($companies) && count($companies) > 0)

                                            @foreach ($companies as $company)

                                                <tr>

                                                    <td class="text-center">
                                                        {{ $count }}
                                                    </td>

                                                    <td>
                                                        {{ $company->title ?? '' }}
                                                    </td>

                                                    <td class="text-center">
                                                        {{ $company->company_code ?? '' }}
                                                    </td>

                                                    <td class="text-center">
                                                        {{ $company->company_mobile ?? '' }}
                                                    </td>

                                                    <td id="rmTd{{ $count }}"
                                                        class="pointer"
                                                        onclick="showRmModal({{ $count }})">

                                                        @if (!empty($company->rm_id))
                                                            {{ $company->rm_name ?? '' }}
                                                        @else
                                                            <span class="text-muted">
                                                                <small><i>Select RM</i></small>
                                                            </span>
                                                        @endif

                                                    </td>

                                                    <input type="hidden"
                                                        name="rmId{{ $count }}"
                                                        id="rmId{{ $count }}"
                                                        value="{{ $company->rm_id ?? '' }}">

                                                    <input type="hidden"
                                                        name="companyCode{{ $count }}"
                                                        id="companyCode{{ $count }}"
                                                        value="{{ $company->company_code ?? '' }}">

                                                </tr>

                                                @php $count++; @endphp

                                            @endforeach

                                        @else

                                            <tr>
                                                <td colspan="5" class="text-center text-danger">
                                                    Data not found
                                                </td>
                                            </tr>

                                        @endif

                                        </tbody>
                                </table>
                                <input type="hidden" name="companyCount" value="<?php echo $count ?>">
                                <br>
                                <input type="submit" class="btn btn-primary save_button" value="Assign RM">
                            </form>
                        </div>
                        <input type="hidden" id="companyModalCount">
                        <button type="button" class="d-none" id="rmShowBtn"></button>
                        <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="myModalLabel">Employee List</h4>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>

                                    <div class="modal-body">
                                        <table class="table table-bordered table-hover custom-table" id="datatableWithExport">
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
                                                @php $count = 1; @endphp

                                                @if (!empty($employees) && count($employees) > 0)
                                                    @foreach ($employees as $employee)
                                                        <tr>
                                                            <td class="td-center">{{ $count }}</td>
                                                            <td>{{ $employee->employee_id ?? '' }}</td>
                                                            <td>{{ $employee->employee_name ?? '' }}</td>
                                                            <td>{{ $employee->designation_name ?? '' }}</td>
                                                            <td>{{ $employee->primary_mobile ?? '' }}</td>
                                                            <td>{{ $employee->email ?? '' }}</td>

                                                            <td class="td-center">
                                                                <button type="button"
                                                                        class="btn btn-primary btn-xs btn-circle-puchase"
                                                                        onclick="setRm({{ $count }})">
                                                                    <i class="fa fa-arrow-down"></i>
                                                                </button>
                                                            </td>

                                                            <input type="hidden"
                                                                id="employeeIdModalHidden{{ $count }}"
                                                                value="{{ $employee->employee_id ?? '' }}">

                                                            <input type="hidden"
                                                                id="employeeNameModalHidden{{ $count }}"
                                                                value="{{ $employee->employee_name ?? '' }}">
                                                        </tr>
                                                        @php $count++; @endphp
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="7" class="text-center text-muted">Data not found</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger save_button" onclick="removeRm()">Clear</button>
                                        <button type="button" class="btn btn-primary save_button" id="modalCloseBtn" data-bs-dismiss="modal">Close</button>
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
<!-- Buttons -->
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script>
    $(document).ready(function () {

    $('#datatableWithExport, #dataTable').DataTable({
        pageLength: 10,
        ordering: true,
        searching: true,

        dom: 'Bfrtip', // IMPORTANT for buttons

        buttons: [
            'copy',
            'csv',
            'excel',
            'pdf',
            'print'
        ]
    });

        // $('#example').DataTable({
        //     bPaginate: false,
        //     dom: 'Bfrtip',
        //     buttons: ['copy','csv','excel','pdf','print']
        // });

    });

    function showRmModal(count) {
        $('#companyModalCount').val(count);

        const modalEl = document.getElementById('myModal');
        bootstrap.Modal.getOrCreateInstance(modalEl).show();
    }

    function setRm(modalCount) {
        $('#modalCloseBtn').click();
        var companyModalCount = $('#companyModalCount').val();
        $('#rmTd' + companyModalCount).text($('#employeeNameModalHidden' + modalCount).val());
        $('#rmId' + companyModalCount).val($('#employeeIdModalHidden' + modalCount).val());
    }

    function removeRm() {
        $('#modalCloseBtn').click();
        var vehicleModalCount = $('#companyModalCount').val();
        var cancelText = "<span class='text-muted'><small><i>Select RM</i></small></span>";
        $('#rmTd' + vehicleModalCount).html(cancelText);
        $('#rmId' + vehicleModalCount).val("");
    }
    $(document).ready(function() {
        $('#example').DataTable({
            bPaginate: false,
            dom: 'Bfrtip',
            buttons: ['copy','csv','excel','pdf','print']
        });
    });
</script>
@endpush