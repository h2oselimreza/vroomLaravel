@extends('layouts.app')

@section('content')

<div class="header dashboard_from">
    <h1 class="page-title">
        {{ isset($data->exists) ? 'Edit Achievements' : 'Add Achievements' }}
    </h1>
    <ul class="breadcrumb">
        <li><a href="#"> Home</a></li>
        <li><a href="#">/ Website Configuration</a></li>
        <li><a href="#">/ Achievements</a></li>
    </ul>
</div>


<div class="main-content">
    <div class="card from_card">
        <div class="card-header">
            {{ isset($data->exists) ? 'Update Notice' : 'Create Notice' }}
        </div>

        <div class="card-body">

            <form class="form-horizontal"
              action="{{ isset($data->exists) 
                    ? route('admin.notices.module.update', $data->id) 
                    : route('admin.notices.module.store') }}"
              method="POST" enctype="multipart/form-data">

                @csrf

                @if(isset($data->exists))
                    @method('PUT')
                @endif

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Heading :</label>
                        <input type="text" name="heading" placeholder="Heading" class="form-control" value="{{ old('heading', $data->heading ?? '') }}">
                        
                        @error('heading')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>    
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="form-label">
                            Body :
                        </label>

                        <div id="editor" style="height:200px;"></div>
                        <input type="hidden" name="body" id="description" value="{{ old('body', $data->body ?? '') }}">

                        @error('body')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Active Status :</label>

                        @php
                            $statuses = [
                                1 => 'Active',
                                0 => 'Inactive',
                            ];

                            $selectedStatus = old('is_active', $data->is_active ?? null);
                        @endphp

                        <select class="form-select" name="is_active" id="is_active">
                            <option value="" @selected(is_null($selectedStatus))>
                                Select Status
                            </option>

                            @foreach($statuses as $value => $label)
                                <option value="{{ $value }}" 
                                    @selected((string)$selectedStatus === (string)$value)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>

                        @error('is_active')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Publish Date :</label>
                        <input type="text" name="publish_date" placeholder="Select Date" class="form-control dateInput" value="{{ old('publish_date', $data->publish_date ?? '') }}">
                        
                        @error('date')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="ps-0 card-footer bg-white d-flex gap-2">
                    <button class="btn btn-primary">
                        {{ isset($data->exists) ? 'Update' : 'Save' }}
                    </button> 

                    <a href="{{ route('admin.notices.module.index') }}"
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