@extends('layouts.app')
@section('content')

<div class="header dashboard_from">
    <h1 class="page-title">Member Anniversary SMS</h1>
    <ul class="breadcrumb">
        <li><a href="#"> Home</a></li>
        <li><a href="#">/ SMS</a></li>
        <li><a href="#">/ Member Anniversary SMS</a></li>
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
        <div class="col-sm-12 col-md-12">
            <div class="panel panel-default"> 
                <div class="table-responsive">
                     <table class="table table-bordered table-hover custom-table" id="datatable">
                        <thead>
                            <tr class="bg-primary">
                                <th>SL</th>
                                <th>Member ID</th>
                                <th>Member Name</th>
                                <th>Contact No</th>
                                <th>Block</th>
                                <th>Road</th>
                                <th>Plot</th>
                                <th>Flat</th>
                                <th><input type="checkbox" id="selectAll"></th>
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
                <div class="d-flex justify-content-end">
                     <!-- Form 2: Print Id Card -->
                    <form action="{{ route('admin.member-anniversary-sms-send',1) }}" method="post" class="me-2">
                        @csrf
                        <input type="hidden" name="ids" id="ids_card">
                        <input type="submit" class="btn btn-success save_button" value="Send SMS">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function () {
    table = $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.member-anniversary-sms-data.index') }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex' },
            {data: 'member_id', name: 'member_id', orderable:true, searchable:true, className: 'text-center'},
            {data: 'member_name', name: 'member_name', orderable:false, searchable:false, className: 'text-center'},
            {data: 'contact_no', name: 'contact_no', orderable:false, searchable:false, className: 'text-center'},
            {data: 'block', name: 'block', orderable:false, searchable:false, className: 'text-center'},
            {data: 'road', name: 'road', orderable:false, searchable:false, className: 'text-center'},
            {data: 'society_plot', name: 'society_plot', orderable:false, searchable:false, className: 'text-center'},
            {data: 'society_flat', name: 'society_flat', orderable:false, searchable:false, className: 'text-center'},
            {data: 'checkbox', name: 'checkbox', orderable:false, searchable:false, className: 'text-center'},
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


    var selected = [];

    // Restore checked state on draw (pagination/search)
    table.on('draw', function () {
        $('.rowCheckbox').each(function () {
            $(this).prop('checked', selected.includes($(this).val()));
        });
    });

    // Handle individual row checkbox click
    $(document).on('change', '.rowCheckbox', function () {
        var value = $(this).val();
        if (this.checked) {
            if (!selected.includes(value)) {
                selected.push(value);
            }
        } else {
            selected = selected.filter(id => id !== value);
        }

        // Store in hidden field
        $('#ids').val(selected.join(','));
    });

    // Handle "Select All" checkbox
    $('#selectAll').on('change', function () {


        console.log("fnaju");

        var checked = this.checked;
        $('.rowCheckbox').each(function () {
            $(this).prop('checked', checked).trigger('change');
        });
    });

    $('form').on('submit', function () {
    // Update both hidden inputs
    $('#ids_list').val(selected.join(','));
    $('#ids_card').val(selected.join(','));
});

});
</script>
@endpush
