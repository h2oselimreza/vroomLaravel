@extends('layouts.app')

@section('content')

<link href="{{ asset('assets/select_bo/css/dropZone.css') }}" rel="stylesheet">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" media="screen">

<style>
    .gallery { display: inline-block; margin-top: 10px; width: 100%; }
    .image_block { border-radius: 3px; padding: 5px; margin: 15px; background: #f9f9f9; border: 1px solid #ddd; transition: 0.3s; }
    .image_block:hover { box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
    .image_block_inner { position: relative; margin-bottom: 5px; text-align: center; }
    .checkbox_wrapper { position: absolute; top: 5px; left: 5px; z-index: 10; background: rgba(255,255,255,0.8); padding: 2px; border-radius: 2px; }
    .img-container { height: 150px; display: flex; align-items: center; justify-content: center; overflow: hidden; background: #eee; }
    .img-container img { max-height: 100%; width: auto; }
    .gallery_image_reload { display: block; margin: 10px 0; color: #337ab7; font-weight: bold; }
</style>

<div class="header">
    <h1 class="page-title">Attachment</h1>
    <ul class="breadcrumb">
        <li><a href="{{ url('admin/Home') }}">Home</a></li>
        <li>Corporate Customer</li>
        <li class="active">Attachment</li>
    </ul>
</div>

<div class="container">
    <div class="card shadow">
        <div class="card-body">
            <!-- Nav Tabs -->
            @include('admin.corporate_customer.nav-tab')
            {{-- Success Message --}}
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="panel panel-default" style="padding: 20px;">
                <div class="row">
                    <div class="col-md-12">
                        {{-- Dropzone Upload Form --}}
                        <form action="{{ route('admin.company.attachment.store') }}" 
                            enctype="multipart/form-data" 
                            class="dropzone" 
                            id="image-upload">
                            @csrf
                        </form>

                        <a href="javascript:location.reload();" class="gallery_image_reload">
                            <i class="fa fa-refresh"></i> Refresh page to see newly uploaded files
                        </a>
                        <hr>
                    </div>
                </div>

                <div class="gallery">
                    <div class="row">
                        @php $count = 0; @endphp
                        @forelse ($albumImages as $albumImage)
                            @php
                                $ext = strtolower(pathinfo($albumImage->image, PATHINFO_EXTENSION));
                                $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'bmp']);
                                $filePath = $isImage 
                                            ? asset('assets/images/websiteImages/' . $albumImage->image) 
                                            : asset('assets/images/pdf_icon.jpg');
                            @endphp

                            <div class="col-sm-6 col-md-3">
                                <div class="image_block">
                                    <div class="image_block_inner">
                                        <div class="checkbox_wrapper">
                                            <input type="checkbox" class="img-checkbox" id="checkBox{{ $count }}" data-id="{{ $albumImage->id }}">
                                        </div>

                                        <a class="thumbnail fancybox" rel="gallery" href="{{ $filePath }}">
                                            <div class="img-container">
                                                <img src="{{ $filePath }}" alt="{{ $albumImage->image }}">
                                            </div>
                                            <div class="text-center" style="word-break: break-all; padding: 5px;">
                                                <small class="text-muted">{{ Str::limit($albumImage->image, 20) }}</small>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @php $count++; @endphp
                        @empty
                            <div class="col-md-12 text-center">
                                <p class="alert alert-info">No images found in this album.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                @if($count > 0)
                <div class="row" style="margin-top: 20px;">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-danger" onclick="submitDelete()">
                            <i class="fa fa-trash"></i> Delete Selected
                        </button>
                    </div>
                </div>
                @endif
            </div>

            {{-- Hidden Delete Form --}}
            {{-- Replace 'admin.gallery.delete' with your actual route name --}}
            <form action="#" method="POST" id="imagePostForm">
                @csrf
                @method('DELETE')
                <input type="hidden" name="imageIdStr" id="imageIdPost">
            </form>

        </div>
    </div>
</div>
@endsection
@push('scripts')
@push('scripts')
<script src="{{ asset('assets/select_bo/js/dropZone.js') }}"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js"></script>

<script>
    $(document).ready(function () {
        // Initialize Fancybox
        $(".fancybox").fancybox({
            openEffect: "elastic",
            closeEffect: "elastic"
        });

        // Dropzone Config
        Dropzone.options.imageUpload = {
            maxFilesize: 3.5, // MB
            acceptedFiles: ".jpeg,.jpg,.png,.gif,.pdf",
            dictDefaultMessage: "Drop files here to upload",
            success: function(file, response) {
                console.log("Successfully uploaded");
            }
        };
    });

    function submitDelete() {
        var selectedIds = [];
        
        // Find all checked checkboxes and get their data-id
        $('.img-checkbox:checked').each(function() {
            selectedIds.push($(this).data('id'));
        });

        if (selectedIds.length > 0) {
            if(confirm('Are you sure you want to delete ' + selectedIds.length + ' item(s)?')) {
                $('#imageIdPost').val(selectedIds.join(','));
                $('#imagePostForm').submit();
            }
        } else {
            alert('Please select at least one image to delete.');
        }
    }
</script>
@endpush
