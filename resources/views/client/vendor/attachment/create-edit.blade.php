@extends('client.layouts.app')

@section('content')
<link href="{{ asset('assets/select_bo/css/dropZone.css') }}" rel="stylesheet">

<style>
    .dropzone{
        border-color: gray!important;
        background-color: white!important;
        box-shadow: 1px 1px rgba(0,0,0,.2)!important;
    }
</style>

<div class="block-header">
    <h2>PROFILE IMAGE</h2><br>
    <div class="breadcrumb breadcrumb-bg-blue-grey">
        <li><a href="/client/Home">Home</a></li>
        <li><a href="#">Master Data</a></li>
        <li><a href="{{ route('client.vendor.venor-list.index') }}">Vendor List</a></li>
        <li><a href="{{ route('client.vendor.attachment.edit', $vedorCode) }}">Attachment</a></li>
    </div>
</div>
<div class="row clearfix">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="body">
                @include('client.vendor.tab')
                <br>
                <div id="errorDiv" class="alert alert-danger hidden">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                </div>
                <div class="panel-group" >
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="panel-title p-t-10 p-b-10 p-l-5 table-responsive">
                                <table width="99%">
                                    <tr>
                                        <td class="text-left">Attachment</td>
                                        <td class="text-right"><?php echo $vedorCode ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <form action="{{ route('client.vendor.attachment.store') }}" enctype="multipart/form-data" class="dropzone" id="image-upload" style="background-color: white">
                                    @csrf
                                    <div class="dz-message" data-dz-message><span>Drop files to upload</span></div>
                                    <input type="hidden" name="vendorCode" id="vendorCode" value="<?php echo $vedorCode ?>">
                                </form>
                                <a href="{{ route('client.vendor.attachment.edit',$vedorCode) }}">Please reload to see upload files</a>
                            </div>
                            <hr>
                            <table class="table table-bordered table-striped custom-table" id="dataTable">
                                <thead>
                                    <tr class="bg-primary">
                                        <th>SL</th>
                                        <th>File Name</th>
                                        <th>File Type</th>
                                        <th>Show</th>
                                        <th>Remove</th>
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
                                    @php $serial = 1; @endphp

                                    @foreach ($attachedFiles as $attachedFile)
                                        <tr>
                                            <td class="td-center">{{ $serial }}</td>

                                            <td>{{ $attachedFile->original_name }}</td>

                                            @php
                                                $info = pathinfo($attachedFile->file_name);
                                                $extension = $info['extension'] ?? '';
                                            @endphp

                                            <td class="td-center">{{ $extension }}</td>

                                            <td class="td-center">
                                                <a target="_blank"
                                                href="{{ asset('assets/client/files/vendor/' . $attachedFile->file_name) }}">
                                                    Show
                                                </a>
                                            </td>

                                            <td class="td-center">
                                                <i class="fa fa-remove pointer" onclick="deleteFile({{ $serial }})"></i>
                                            </td>

                                            <input type="hidden"
                                                id="fileId{{ $serial }}"
                                                value="{{ $attachedFile->id }}">

                                        </tr>

                                        @php $serial++; @endphp
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div> <!-- end class=body -->
        </div> <!-- end class="card" -->
    </div> <!-- end class="col-xs-12 col-sm-12 col-md-12 col-lg-12" -->
</div> <!-- end class="row clearfix" -->
    
@endsection
@push('scripts')
<script>
window.onload = function () {
    document.querySelectorAll('.dataTables_wrapper').forEach(function(el) {
        el.style.position = 'static';
    });
};
</script>
<script src="{{ asset('assets/select_bo/js/dropZone.js') }}"></script>
<script>
    function deleteFile(serial) {

        let fileId = $('#fileId' + serial).val();
        let vendorCode = $('#vendorCode').val();

        swal({
            title: "Are you sure?",
            text: "",
            type: "warning",
            showCancelButton: true,
            closeOnConfirm: false,
            confirmButtonText: "Yes, remove it...!",
            confirmButtonColor: "#ec6c62"
        }, function () {

            showLoader();

            $.ajax({
                url: "{{ route('client.vendor.attachment.destroy', ':vendorCode') }}"
                    .replace(':vendorCode', vendorCode),

                type: "DELETE",

                data: {
                    fileId: fileId,
                    vendorCode: vendorCode,
                    _token: "{{ csrf_token() }}"
                },

                success: function (data) {

                    hideLoader();

                    swal({
                        title: "Remove Successfully",
                        text: "This file is remove now",
                        type: "success",
                        closeOnConfirm: false,
                        confirmButtonText: "Ok",
                        confirmButtonColor: "#A5DC86"
                    }, function () {

                        window.location.href =
                        "{{ route('client.vendor.attachment.edit', ':vendorCode') }}"
                            .replace(':vendorCode', vendorCode);

                    });

                },

                error: function (xhr) {

                    hideLoader();

                    swal(
                        "Oops",
                        "We couldn't connect to the server!",
                        "error"
                    );

                }
            });

        });
    }
</script>
@endpush