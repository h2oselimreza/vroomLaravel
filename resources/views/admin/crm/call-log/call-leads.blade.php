@extends('layouts.app')
@section('content')
<style>
    .table#callLeadsDatatable tr td,th{
        padding: 2px 2px;
        vertical-align: middle;
        text-align: center;
        font-size: 13px;
    }
</style>

<div class="header dashboard_from">
    <h1 class="page-title">Call Leads</h1>
    <ul class="breadcrumb">
        <li><a href="#">Home</a> / </li>
        <li><a href="#">CRM</a> / </li>
        <li><a href="#">Call Leads</a></li>
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

    @if(session('msg'))

        @if(session('msg') == 1)
            <div class="alert alert-success alert-dismissible fade show">
                Uploaded Successfully...!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>

        @elseif(session('msg') == 2)
            <div class="alert alert-danger alert-dismissible fade show">
                Invalid mobile number format: {{ session('invMobile') ?? '' }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>

        @elseif(session('msg') == 3)
            <div class="alert alert-danger alert-dismissible fade show">
                Duplicate mobile number: {{ session('duplicateMobile') ?? '' }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>

        @elseif(session('msg') == 4)
            <div class="alert alert-danger alert-dismissible fade show">
                Mobile number already exists: {{ session('mobileNumberExist') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>

        @endif

    @endif

    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel panel-default"> 
                <div class="table-responsive">
                    <table class="table table-bordered table-hover custom-table" id="datatable">
                        <thead>
                            <tr class="bg-primary">
                                <th style="width:30px">SL</th>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Mobile</th>
                                <th>Address</th>
                                <th>Call Status</th>
                                <th>Last Call Date Time</th>
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
                            </tr>
                        </tfoot>
                    </table>
                    <hr>
                    <div class="row mb-5">
                        <div class="text-center mb-4"><h4><b>Call Leads</b></h4></div>
                        <br>
                        <form id="callLeadsUploadForm" action="/admin/crm/upload-call-leads-list" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group" >
                                        <input type="file" class="form-control" name="callLeadsCsvFile" id="callLeadsCsvFile" onchange='checkFile(this, this.id);'>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group" >
                                        <button type="button" class="btn btn-primary save_button" id="csvUploadBtn" onclick="uploadCsv()">Upload Call Leads</button>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <a href="{{ asset('assets/files/demo_csv/leads.csv') }}" download>
                                            Demo CSV Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <button type="button" class="hidden" data-toggle="modal" data-target="#myModal" id="actionShowBtn"></button>
            <div class="modal fade" id="myModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title" id="myModalLabel"></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body text-center">
                            <button class="btn btn-success save_button w-100 mb-2" onclick="makeCall()">Make Call</button>
                            <button class="btn btn-danger save_button w-100" onclick="removeCallLead()">Delete</button>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary save_button" data-bs-dismiss="modal">
                                Close
                            </button>
                        </div>

                    </div>
                </div>
            </div>
            <input type="hidden" id="modalActionCode">
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
        ajax: "{{ route('admin.crm.lead-data') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable:false, searchable:false,className: 'text-center'},
            {data: 'lead_code', name: 'lead_code'},
            {data: 'name', name: 'name'},
            {data: 'mobile', name: 'mobile'},
            {data: 'address', name: 'address'},
            {data: 'call_status', name: 'call_status', className: 'text-center'},
            {data: 'last_call_dt_tm', name: 'last_call_dt_tm', orderable:false, searchable:false, className: 'text-center'},
            {data: 'action', name: 'action', orderable:false, searchable:false, className: 'text-center'},
        ],

        // ✅ moved here (DO NOT create second DataTable)
        initComplete: function () {
            this.api().columns().every(function () {
                var column = this;

                // ❌ Skip Action column (last column index = 7)
                if (column.index() === 7) return;

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

    function uploadCsv() {
        $('#callLeadsUploadForm').submit();
    }

    function checkFile(obj, id) {
        var fileExtensionImage = ['csv'];
        if ($.inArray($(obj).val().split('.').pop().toLowerCase(), fileExtensionImage) === -1) {
            $('#callLeadsCsvFile').val('');
            sweetAlert("Only '.csv' format is allowed.");
            return false;
        }
    }

    function showAction(leadCode) {
        document.getElementById('modalActionCode').value = leadCode;

        const modalEl = document.getElementById('myModal');
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();
    }

    function removeCallLead() {
        $('#modalCloseBtn').click();
        var leadCode = $.trim($('#modalActionCode').val());
        swal({
            title: "Are you sure?",
            text: "",
            type: "warning",
            showCancelButton: true,
            closeOnConfirm: false,
            confirmButtonText: "Yes, remove it...!",
            confirmButtonColor: "#ec6c62"
        }, function () {
            swal.close();
            showLoader();
            $.ajax({
                url: "/admin/Crm/removeCallLead?leadCode=" + leadCode,
                type: "DELETE"
            })
                    .done(function (data) {
                        hideLoader();
                        if (data === '1') {
                            swal({
                                title: "Remove Successfully",
                                text: "This file is remove now",
                                type: "success",
                                closeOnConfirm: false,
                                confirmButtonText: "Ok",
                                confirmButtonColor: "#A5DC86"
                            }, function () {
                                window.location.href = "/admin/Crm/callLeads";
                            });
                        } else {
                            window.location.href = "/admin/Crm/callLeads";
                        }
                    })
                    .error(function (data) {
                        swal("Oops", "We couldn't connect to the server!", "error");
                    });
        });
    }

    function makeCall() {
        $('#modalCloseBtn').click();
        var leadCode = $.trim($('#modalActionCode').val());
        window.open("/admin/crm/make-call?leadCode=" + leadCode + "&callerType=leads", '_blank');
    }
</script>
@endpush
