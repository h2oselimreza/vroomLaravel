@extends('layouts.app')

@section('content')

<div class="header dashboard_from">
    <h1 class="page-title">
        {{ isset($data->exists) ? 'Edit Gallery Image' : 'Add Gallery Image' }}
    </h1>
    <ul class="breadcrumb">
        <li><a href="#"> Home</a></li>
        <li><a href="#">/ Website Configuration</a></li>
        <li><a href="#">/ Gallery Image</a></li>
    </ul>
</div>


<div class="main-content">
    <div class="card from_card">
        <div class="card-header">
            {{ isset($data->exists) ? 'Update Gallery Image' : 'Create Gallery Image' }}
        </div>

        <div class="card-body">

            <form class="form-horizontal"
              action="{{ isset($data->exists) 
                    ? route('admin.gallery-image.module.update', $data->id) 
                    : route('admin.gallery-image.module.store') }}"
              method="POST" enctype="multipart/form-data">

                @csrf

                @if(isset($data->exists))
                    @method('PUT')
                @endif

                <div class="row">
                                    <!-- Order -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Album Name :
                        </label>

                        <input 
                            type="text" 
                            name="album_name" 
                            class="form-control" 
                            placeholder="Album name" 
                            value="{{ old('album_name', $data->album_name ?? '') }}"
                        >

                        @error('album_name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Image *</label>
                        
                        @if(isset($data) && $data->image)
                            <div class="mb-2">
                                <img src="{{ asset('images/gallery/' . $data->image) }}" alt="Banner" width="300">
                            </div>
                        @endif

                        <input type="file" name="image[]" class="form-control">
                        
                        @error('image')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="ps-0 card-footer bg-white d-flex gap-2">
                    <button class="btn btn-primary">
                        {{ isset($data->exists) ? 'Update' : 'Save' }}
                    </button> 

                    <a href="{{ route('admin.slider.module.index') }}"
                       class="btn btn-outline-secondary">
                        Cancel
                    </a>
                </div>

            </form>

            @if(isset($data->exists) && $data->images->isNotEmpty())
           
                <form action="{{ route('gallery-image.module.update') }}" method="POST">
                    @csrf
                    <table class="table table-bordered custom-table">
                        <thead>
                            <tr>
                                <th style="width:20px">Serial</th>
                                <th>Image</th>
                                <th style="width:200px">Action</th>
                                <th style="width:200px">Home Page Gallery</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $serial = 1; @endphp

                            @foreach ($data->images as $image)
                                <tr>
                                    <td>{{ $serial }}</td>
                                    <td>
                                        <img src="{{ asset('assets/images/websiteImages/' . $image->image) }}" 
                                            style="height:60px;">
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('gallery-image.destroy', ['album_id' => $data->id, 'gallery_id' => $image->id]) }}" 
                                            class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary action_button_modify">
                                            <span class="ui-button-text">&nbsp;Delete</span>
                                        </a>
                                    </td>
                                    <td class="td-center">
                                        <input type="checkbox" name="galleryCheckbox{{ $serial }}" 
                                            value="{{ $image->id }}" 
                                            {{ $image->home_flag == 1 ? 'checked' : '' }}>
                                        <input type="hidden" name="imageId{{ $serial }}" 
                                            value="{{ $image->id }}">
                                    </td>
                                </tr>
                                @php $serial++; @endphp
                            @endforeach
                        </tbody>
                    </table>

                    <input type="hidden" name="albumId" value="{{ $data->id }}">
                    <input type="hidden" name="imageCount" value="{{ $serial }}">
                    <input type="submit" class="btn btn-success save_button" value="Submit Home Gallery Image">
                </form>
            @endif
        </div>
    </div>
</div>

@endsection
@push('scripts')
<script>
</script>
@endpush