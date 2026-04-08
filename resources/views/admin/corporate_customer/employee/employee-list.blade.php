@extends('layouts.app')
@section('content')

<div class="header dashboard_from">
    <h1 class="page-title">Customer Employee</h1>
    <ul class="breadcrumb">
        <li><a href="<?php /*echo base_url() */ ?>admin/Home"> Home</a></li>
        <li><a href="#"> Company</a></li>
        <li><a href="#"> Customer Employee</a></li>
    </ul>
</div>
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
<div class="main-content">
    <div class="add-button">
        <a href="{{ route('admin.customer-employee.create',['company_code' => request('company_code')]) }}">Add Employee</a>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel panel-default"> 
                <div class="table-responsive">
                     <table class="table table-bordered table-hover custom-table" id="datatable">
                        <thead>
                            <tr class="bg-primary">
                                <th>SL</th>
                                <th>Employee Id</th>
                                <th>Employee Name</th>
                                <th>Designation</th>
                                <th>Contact No</th>
                                <th>Type</th>
                                <th>System User</th>
                                <th>User Group</th>
                                <th>First Time Reset</th>
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
                            <th></th>
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
        // processing: true,
        // serverSide: true,
        ajax: {
            url: "{{ route('admin.customer-employee-data.index') }}",
            data: function (d) {
                d.company_code = "{{ request('company_code') }}";
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable:false, searchable:false, className: 'text-center'},
            {data: 'employee_id', name: 'employee_id'},
            {data: 'employee_name', name: 'employee_name'},
            {data: 'designation', name: 'designation'},
            {data: 'primary_mobile', name: 'primary_mobile'},
            {data: 'customer_type', name: 'customer_type', searchable:false, className: 'text-center'},
            {data: 'system_user', name: 'system_user', searchable:false, className: 'text-center'},
            {data: 'user_group', name: 'user_group', searchable:false, className: 'text-center'},
            {data: 'is_reset', name: 'is_reset', searchable:false, className: 'text-center'},
            {data: 'is_active', name: 'is_active', orderable:false, searchable:false, className: 'text-center'},
            {data: 'action', name: 'action', orderable:false, searchable:false, className: 'text-center'},
        ],

        // ✅ moved here (DO NOT create second DataTable)
        initComplete: function () {
            this.api().columns().every(function () {
                var column = this;

                // ❌ Skip Action column (last column index = 7)
                if (column.index() === 10) return;

                var select = $('<select class="form-control" style="width:100%"><option value="">Select All</option></select>')
                    .appendTo($(column.footer()).empty())
                    .on('change', function () {
                        var val = $.fn.dataTable.util.escapeRegex($(this).val());

                        column
                            .search(val ? '^' + val + '$' : '', true, false)
                            .draw();
                    });

                column.data().unique().sort().each(function (d) {

                    // ✅ Convert HTML → plain text
                    var text = $('<div>').html(d).text().trim();

                    if (text) {
                        select.append('<option value="' + text + '">' + text + '</option>');
                    }
                });
            });
        }
    });

});
</script>
@endpush
