@extends('layouts.app')

@section('content')

<div class="header dashboard_from">
    <h1 class="page-title">
        {{ isset($data->exists) ? 'Edit News' : 'Add News' }}
    </h1>
    <ul class="breadcrumb">
        <li><a href="#"> Home</a></li>
        <li><a href="#">/ Website Configuration</a></li>
        <li><a href="#">/ News</a></li>
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
                    ? route('admin.news.module.update', $data->id) 
                    : route('admin.news.module.store') }}"
              method="POST">

                @csrf

                @if(isset($data->exists))
                    @method('PUT')
                @endif

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Heading :
                        </label>

                        <input type="text"
                            name="heading"
                            class="form-control"
                            placeholder="Heading"
                            value="{{ old('heading', $data->heading ?? '') }}">

                        @error('heading')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Order -->
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="form-label">
                            Body :
                        </label>
                        <div id="editor" style="height:250px;"></div>
                        <input type="hidden" name="body" id="description" value="{{ old('body', $data->body ?? '') }}">

                        @error('body')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Active Status :</label>

                        <select class="form-select" name="is_active" id="is_active">
                            <option value="">Select Status</option>
                            <option value="1" {{ old('is_active', $data->is_active ?? '') == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active', $data->is_active ?? '') == 0 ? 'selected' : '' }}>Inactive</option>
                        </select>

                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Publish Date :
                        </label>

                        <input type="date"
                            name="publish_date"
                            class="form-control dateInput"
                            placeholder="publish date"
                            value="{{ old('publish_date', $data->publish_date ?? '') }}">

                        @error('publish_date')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="ps-0 card-footer bg-white d-flex gap-2">
                    <button class="btn btn-primary">
                        {{ isset($data->exists) ? 'Update' : 'Save' }}
                    </button> 

                    <a href="{{ route('admin.news.module.index') }}"
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
</script>
@endpush