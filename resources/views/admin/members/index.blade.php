@extends('layouts.app')
@section('content')

<div class="header dashboard_from">
    <h1 class="page-title">Member</h1>
    <ul class="breadcrumb">
        <li><a href="#"> Home</a></li>
        <li><a href="#">/ Master Data</a></li>
        <li><a href="#">/ Member</a></li>
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

    <div class="add-button">
        <a href="{{ route('admin.member.module.create') }}">Add Member</a>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel panel-default"> 
                <div class="table-responsive">
                     <table class="table table-bordered table-hover custom-table" id="datatable">
                        <thead>
                            <tr class="bg-primary">
                                <th>SL</th>
                                <th>Member ID</th>
                                <th>Donar Member Id</th>
                                <th>Member Name</th>
                                <th>Member Type</th>
                                <th>Contact No</th>
                                <th>Block</th>
                                <th>Road</th>
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
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.member.data.index') }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex' },
            {data: 'member_id', name: 'member_id', orderable:true, searchable:true, className: 'text-center'},
            {data: 'donar_member_id', name: 'donar_member_id', orderable:false, searchable:false, className: 'text-center'},
            {data: 'member_name', name: 'member_name', orderable:false, searchable:false, className: 'text-center'},
            {data: 'member_type', name: 'member_type', orderable:false, searchable:false, className: 'text-center'},
            {data: 'contact_no', name: 'contact_no', orderable:false, searchable:false, className: 'text-center'},
            {data: 'block', name: 'block', orderable:false, searchable:false, className: 'text-center'},
            {data: 'road', name: 'road', orderable:false, searchable:false, className: 'text-center'},
            {data: 'action', name: 'action', orderable:false, searchable:false, className: 'text-center'},
        ],

        // ✅ moved here (DO NOT create second DataTable)
        initComplete: function () {
            this.api().columns().every(function () {
                var column = this;

                // ❌ Skip Action column (last column index = 7)
                if (column.index() === 8) return;

                var select = $('<select class="form-control" style="width:100%"><option value="">All</option></select>')
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
