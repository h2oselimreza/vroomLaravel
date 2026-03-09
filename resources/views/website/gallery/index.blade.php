@extends('website.layouts.single-page')
@section('main-content')
<style>
    .album-left{
        border:2px solid white;
        width: 50%;height: 50%;float:left;background-color: #ddd;box-shadow:1px 0px 9px 0px rgba(53, 50, 50, 0.85);
    }
    .album-right{
        border:2px solid white; 
        width: 50%;height: 50%;float: left;background-color: #ddd;box-shadow:1px 0px 9px 0px rgba(53, 50, 50, 0.85);
    }
</style>

<div class="col-md-9">
    <div class="heading-custom-panel">
        <div class="panel-heading">
            Gallery
        </div>
    </div>
    <br>
    <div class="row">
        @if($galleryAlbums)
            @foreach($galleryAlbums as $galleryAlbum)
                <a href="{{ url('gallery/'.$galleryAlbum['id'].'/'.$galleryAlbum['album_name']) }}">
                    <div class="col-md-3">
                        <div class="heading-custom-panel" style="margin-bottom:10px">
                            <div class="pricing">
                                <div class="pricing-header" style="height: 180px">
                                    @php
                                        $serial = 1;
                                        $float = 'left';
                                    @endphp

                                    @foreach($galleryAlbumImages as $galleryAlbumImage)
                                        @if($galleryAlbum['id'] == $galleryAlbumImage['gallery_album'])
                                            <div class="album-{{ $float }}">
                                                @php $image = $galleryAlbumImage['image']; @endphp
                                                <img src="{{ asset('assets/images/websiteImages/'.$image) }}" style="height: 100%; width: 100%; object-fit: contain">
                                            </div>

                                            @php
                                                $float = 'right';
                                                if ($serial == 4) {
                                                    break;
                                                }
                                                $serial++;
                                            @endphp
                                        @endif
                                    @endforeach

                                </div>
                                <div class="gallry-album-body">
                                    <p style="word-break: break-all; text-align: left;padding-left:10px;padding-bottom: 5px">
                                        {{ $galleryAlbum['album_name'] }}  
                                    </p>

                                </div>
                            </div>
                        </div>
                    </div>

                </a>
            @endforeach
        @else
            no data found
        @endif
    </div>
</div>
@endsection