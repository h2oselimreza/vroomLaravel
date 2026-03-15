@extends('layouts.app')

@section('content')

<div class="header dashboard_from">
    <h1 class="page-title">{{ isset($data->exists) ? 'Edit' : 'Add' }}</h1>
    <ul class="breadcrumb">
        <li><a href="#"> Home</a></li>
        <li><a href="#">/ Website Configuration</a></li>
        <li><a href="#">/ Prayer Time</a></li>
    </ul>
</div>

<div class="main-content">
    <div class="card from_card">
        <div class="card-header">
            {{ isset($data->exists) ? 'Update' : 'Create' }}
        </div>

        <div class="card-body">

            <form class="form-horizontal"
              action="{{ route('admin.prayer-time.update', $data->id) }}"
              method="POST">

                @csrf

                @if(isset($data->exists))
                    @method('PUT')
                @endif

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Fajr :
                        </label>

                        <input type="text"
                            name="fajr"
                            class="form-control"
                            placeholder="date"
                            value="{{ old('fajr', $data->fajr ?? '') }}">

                        @error('fajr')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Zuhor :
                        </label>

                        <input type="text"
                            name="zuhor"
                            class="form-control"
                            placeholder="date"
                            value="{{ old('zuhor', $data->zuhor ?? '') }}">

                        @error('zuhor')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Asor :
                        </label>

                        <input type="text"
                            name="asor"
                            class="form-control"
                            placeholder="date"
                            value="{{ old('asor', $data->asor ?? '') }}">

                        @error('asor')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Maghrib :
                        </label>

                        <input type="text"
                            name="maghrib"
                            class="form-control"
                            placeholder="date"
                            value="{{ old('maghrib', $data->maghrib ?? '') }}">

                        @error('maghrib')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Isha :
                        </label>

                        <input type="text"
                            name="isha"
                            class="form-control"
                            placeholder="date"
                            value="{{ old('isha', $data->isha ?? '') }}">

                        @error('isha')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Jumma :
                        </label>

                        <input type="text"
                            name="jumma"
                            class="form-control"
                            placeholder="date"
                            value="{{ old('jumma', $data->jumma ?? '') }}">

                        @error('jumma')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Data Source :
                        </label>

                        @php
                            $dataSources = [
                                '1' => 'Custom',
                                '4' => 'Umm Al-Qura University, Makkah',
                                '2' => 'Islamic Society of North America'
                            ];
                        @endphp

                        <select class="form-select" name="data_source" id="data_source">
                            <option value="">Select Data Source</option>
                            @foreach($dataSources as $key => $value)
                                <option value="{{ $key }}" {{ old('data_source', $data->data_source ?? '') == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>

                        @error('data_source')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="ps-0 card-footer bg-white d-flex gap-2">
                    <button class="btn btn-primary">
                        {{ isset($data->exists) ? 'Update' : 'Save' }}
                    </button> 

                    <a href="{{ route('admin.module-group.index') }}"
                       class="btn btn-outline-secondary">
                        Cancel
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection
