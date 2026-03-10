{{-- resources/views/admin/web/gallery/index.blade.php --}}
@extends('layouts.app')
@section('content')
<link href="{{ asset('assets/select_bo/css/dropZone.css') }}" rel="stylesheet">
<script src="{{ asset('assets/select_bo/js/dropZone.js') }}"></script>

<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" media="screen">
<script src="//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js"></script>

<input type="hidden" id="imageStrId" value="{{ $albumImageStr }}">

<script>
    $(document).ready(function () {
        var imageStr = $('#imageStrId').val();
        var imageArr = imageStr ? imageStr.split(",") : [];

        for (var i = 0; i < imageArr.length; i++) {
            var fileArr = imageArr[i].split(".");
            var extension = fileArr[1];

            if (extension === 'jpg' || extension === 'png' || extension === 'bmp' ||
                extension === 'jpeg' || extension === 'JPG' || extension === 'PNG' || extension === 'JPEG') {

                $('#imageDiv' + i).html(
                    '<img class="img-responsive" style="height:200px" alt="" src="{{ asset('assets/images/websiteImages') }}/' + imageArr[i] + '" />'
                );
            } else {
                $('#imageDiv' + i).html(
                    '<img class="img-responsive" style="height:200px" alt="" src="{{ asset('assets/images/company/pdf_icon.jpg') }}" />'
                );
            }
        }
    });

    function deleteImage(count) {
        var imageIdArr = [];
        var flag = 0;

        for (var i = 0; i < count; i++) {
            if ($('#checkBox' + i).is(':checked')) {
                var imageId = $('#albumImage' + i).val();
                imageIdArr.push(imageId);
                flag = 1;
            }
        }

        if (flag === 1) {
            var imageIdStr = imageIdArr.join();
            $('#imageIdPost').val(imageIdStr);
            $('#imagePostForm').submit();
        } else {
            alert('No image is selected...!');
            return false;
        }
    }
</script>

<style>
    .gallery {
        display: inline-block;
        margin-top: 0px;
    }

    .image_block {
        border-radius: 3px;
        padding: 5px;
        width: 200px;
        float: left;
        margin: 15px;
    }

    .image_block_inner {
        position: relative;
        margin-bottom: 5px;
    }

    .checkbox {
        position: absolute;
        z-index: 2;
        background: white;
        box-shadow: 1px 1px rgba(0,0,0,.2);
    }
</style>

<div class="header">
    <h1 class="page-title">Album</h1>
    <ul class="breadcrumb">
        <li><a href="{{ asset('admin/Home') }}">Home</a></li>
        <li><a href="#">Website Configuration</a></li>
        <li class="active"><a href="#">Album</a></li>
    </ul>
</div>

<div class="col-sm-12 col-md-12">
    <div class="panel panel-default">

        <a class="btn btn-primary save_button" href="{{ route('admin.album.details') }}">
            Album Details
        </a>

        <br><br>

        {{-- Dropzone Upload --}}
        <form action="{{ route('admin.album.store') }}"
              enctype="multipart/form-data"
              class="dropzone"
              id="image-upload">
            @csrf
        </form>

        <a href="{{ route('admin.album.index') }}" class="gallery_image_reload">
            Please reload to see upload images in gallery
        </a>

        <hr>

        <div class='list-group gallery'>
            @php $count = 0; @endphp
            <div class="row">
                @foreach ($albumImages as $albumImage)
                    @php
                        $ext = strtolower(pathinfo($albumImage->image, PATHINFO_EXTENSION));
                    @endphp

                    <div class='col-sm-6 col-xs-12 col-md-3 col-lg-3 mr-5'>
                        <div class="image_block">
                            <div class="image_block_inner">

                                <div class="checkbox">
                                    <input type="checkbox" id="checkBox{{ $count }}">
                                </div>

                                <input type="hidden"
                                    id="albumImage{{ $count }}"
                                    value="{{ $albumImage->id }}">

                                <a class="thumbnail fancybox"
                                rel="ligthbox"
                                href="{{ in_array($ext, ['jpg','jpeg','png','bmp']) 
                                            ? asset('assets/images/websiteImages/'.$albumImage->image) 
                                            : asset('assets/images/pdf_icon.jpg') }}">

                                    <div id="imageDiv{{ $count }}">
                                        <img style="height:100px" src="{{ in_array($ext, ['jpg','jpeg','png','bmp']) 
                                                ? asset('assets/images/websiteImages/'.$albumImage->image) 
                                                : asset('assets/images/pdf_icon.jpg') }}" 
                                            alt="image">
                                    </div>

                                    <div class='text-right' style="word-break: break-all;">
                                        <small class='text-muted'>
                                            {{ $albumImage->image }}
                                        </small>
                                    </div>
                                </a>

                            </div>
                        </div>
                    </div>

                    @php $count++; @endphp
                    @endforeach
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <button type="button"
                class="btn btn-danger btn-sm save_button"
                onclick="deleteImage('{{ $count }}')">
                Delete Image
                </button>
            </div>
        </div>

    </div>

    {{-- Delete Form --}}
    <form action="{{ route('admin.album.delete') }}"
          method="POST"
          id="imagePostForm">
        @csrf
        <input type="hidden" name="imageIdStr" id="imageIdPost">
    </form>
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function () {
        $(".fancybox").fancybox({
            openEffect: "none",
            closeEffect: "none"
        });
    });
    Dropzone.options.imageUpload = {
        maxFilesize: 3.5,
        acceptedFiles: ".jpeg,.jpg,.png,.gif,.pdf"
    };
</script>
@endpush
