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
            {{ isset($data->exists) ? 'Update Achievements' : 'Create Achievements' }}
        </div>

        <div class="card-body">

            <form class="form-horizontal"
              action="{{ isset($data->exists) 
                    ? route('admin.achievements.module.update', $data->id) 
                    : route('admin.achievements.module.store') }}"
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
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Short Description :</label>
                        <input type="text" name="short_description" placeholder="Short description" class="form-control" value="{{ old('short_description', $data->short_description ?? '') }}">
                        
                        @error('short_description')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="form-label">
                            Detais :
                        </label>

                        <div id="editor" style="height:200px;"></div>
                        <input type="hidden" name="details" id="description" value="{{ old('details', $data->details ?? '') }}">

                        @error('details')
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
                    </div>
                </div>    
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Image :</label>
                        
                        @if(isset($data) && $data->image)
                            <div class="mb-2">
                                <img src="{{ asset('images/achievements/' . $data->image) }}" alt="image" width="200" height="150">
                            </div>
                        @endif

                        <input type="file" name="image" class="form-control">
                        
                        @error('image')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Date :</label>
                        <input type="text" name="date" placeholder="Select Date" class="form-control dateInput" value="{{ old('date', $data->date ?? '') }}">
                        
                        @error('date')
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