@extends('website.layouts.single-page')
@section('main-content')
<link href="{{ asset('assets/website/css/jquery.littlelightbox.css') }}" rel="stylesheet" type="text/css">
<div class="col-md-9">
    <div class="heading-custom-panel">
        <div class="panel-heading">
            Gallery
        </div>
        <div class="custom-panel-body" >
            <div class="text-center">
                <h4>{{ urldecode($galleryAlbumName) }}</h4>
            </div>
            <div class="row">
                @foreach ($galleryAlbumImages as $galleryAlbumImage)
                        <div class="col-md-3" style="height:150px;overflow:hidden;margin-bottom:20px">
                            <a class="lightbox1 thumbnail" href="{{ asset('assets/images/websiteImages/'.$galleryAlbumImage['image']) }}" data-littlelightbox-group="gallery" title="">
                                <img style='height: 90%; width: 90%; object-fit: contain' src="{{ asset('assets/images/websiteImages/'.$galleryAlbumImage['image']) }}" />
                            </a>
                        </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
    <script src="{{ asset('assets/website/js/jquery.littlelightbox.js') }}"></script>
    <script>
        $('.lightbox1').littleLightBox();
    </script>
@endpush

