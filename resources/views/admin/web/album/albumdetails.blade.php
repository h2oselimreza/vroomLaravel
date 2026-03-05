@extends('layouts.app')

@section('content')

<script>
    $(document).ready(function () {
        var imageStr = $('#imageStrId').val();
        var imageArr = imageStr.split(",");
        for (var i = 0; i < imageArr.length; i++) {
            var fileArr = imageArr[i].split(".");
            if(fileArr[1] === 'jpg' || fileArr[1] === 'png' || fileArr[1] === 'bmp' || fileArr[1] == 'jpeg' || fileArr[1] === 'JPG' || fileArr[1] === 'PNG' || fileArr[1] === 'JPEG'){
                $('#albumTdId' + i).html('<img class="img-responsive img-thumbnail image" style="height:70px" alt="" src="{{ url('/') }}/assets/images/websiteImages/'+imageArr[i]+'" />');
            }else{
                $('#albumTdId' + i).html('<img class="img-responsive img-thumbnail image" style="height:70px" alt="" src="{{ url('/') }}/assets/images/company/pdf_icon.jpg" />');
            }
            
        }
    });
</script>

<style>
    .custom-table tfoot th select{
        width:100%;
    }
    .table.custom-table tr td,th{
        padding: 2px 2px;
        vertical-align: middle;
        text-align: center;
        font-size: 13px;    
    }
    .table.custom-table tr td{
        padding-left: 5px;
        vertical-align: middle;
        text-align: left;
        font-size: 13px;
    }
    .custom-select{height:24px;padding:0px;}
</style>

<input type="hidden" id="imageStrId" value="{{ $albumImageStr }}">

@php
    //$imagePath = ALBUMSHOW_URL;
    $imagePath = url('/').'/assets/images/websiteImages/';
@endphp

<div class="col-sm-12 col-md-12">
    <div class="panel panel-default"> 
        <table class="table table-hover table-bordered custom-table" id="datatable">
            <thead>
                <tr>
                    <th style="width:100px">File</th>
                    <th>Link</th>
                    <th style="width:100px">Type</th>
                </tr>
            </thead>
            <tbody>

            @php $count = 0; @endphp

            @foreach ($albumImages as $albumImage)

            <tr>
                <td style="text-align:center" id="albumTdId{{ $count }}">
                    @php
                        $ext = strtolower(pathinfo($albumImage->image, PATHINFO_EXTENSION));
                    @endphp

                    @if(in_array($ext, ['jpg','jpeg','png','bmp']))
                        <img class="img-responsive img-thumbnail image"
                            style="height:70px"
                            alt=""
                            src="{{ asset('images/album/'.$albumImage->image) }}" />
                    @else
                        <img class="img-responsive img-thumbnail image"
                            style="height:70px"
                            alt=""
                            src="{{ asset('assets/images/pdf_icon.jpg') }}" />
                    @endif
                </td>
                <td>
                    {{ $imagePath.$albumImage->image }}
                </td>
                <td>
                    {{ $ext }}
                </td>
            </tr>

            @php $count++; @endphp

            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function () {
        $('#datatable').DataTable();
    });
</script>
@endpush
