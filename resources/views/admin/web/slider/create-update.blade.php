@extends('layouts.app')

@section('content')

<div class="header dashboard_from">
    <h1 class="page-title">
        {{ isset($data->exists) ? 'Edit Slider' : 'Add Slider' }}
    </h1>
    <ul class="breadcrumb">
        <li><a href="#"> Home</a></li>
        <li><a href="#">/ Website Configuration</a></li>
        <li><a href="#">/ Slider</a></li>
    </ul>
</div>


<div class="main-content">
    <div class="card from_card">
        <div class="card-header">
            {{ isset($data->exists) ? 'Update News' : 'Create News' }}
        </div>

        <div class="card-body">

            <form class="form-horizontal"
              action="{{ isset($data->exists) 
                    ? route('admin.slider.module.update', $data->id) 
                    : route('admin.slider.module.store') }}"
              method="POST" enctype="multipart/form-data">

                @csrf

                @if(isset($data->exists))
                    @method('PUT')
                @endif

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Image *</label>
                        
                        @if(isset($data) && $data->image)
                            <div class="mb-2">
                                <img src="{{ asset('images/slider/' . $data->image) }}" alt="Banner" width="300">
                            </div>
                        @endif

                        <input type="file" name="image" class="form-control">
                        
                        @error('image')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Order -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Image Order :
                        </label>

                        <input 
                            type="number" 
                            name="image_order" 
                            class="form-control" 
                            placeholder="Image order" 
                            value="{{ old('image_order', $data->image_order ?? '') }}"
                        >

                        @error('image_order')
                            <div class="text-danger">{{ $message }}</div>
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
        </div>
    </div>
</div>

@endsection
@push('scripts')
<script>
     $(document).ready(function(){
        $('.dateInput').datepicker({
            format: 'yyyy-mm-dd',  // format compatible with Laravel date column
            autoclose: true,       // close picker after selecting a date
            todayHighlight: true,  // highlight today
            clearBtn: true,        // optional clear button
            orientation: 'bottom'  // show below the input
        });
    });
    // tinymce.init({
    //     selector: '#editor',
    //     height: 300
    // });
    ClassicEditor.create(document.querySelector('#editor'));
</script>
@endpush