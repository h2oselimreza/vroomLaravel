@extends('layouts.app')
@section('content')

<div class="header dashboard_from">
    <h1 class="page-title">New Individual Account</h1>
    <ul class="breadcrumb">
        <li><a href="#">Home</a> / </li>
        <li><a href="#">Individual Customer</a> / </li>
        <li><a href="#">  Individual Account List</a> / </li>
        <li><a href="#">New Individual Account</a></li>
    </ul>
</div>
<div class="main-content">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel panel-default"> 
                <form method="POST" action="{{ route('admin.individual.individual-account.store') }}">
                    <div class="row">
                        @csrf
                        <div class="col-md-6">
                            <label class="form-label">Individual Account Name</label>
                            <span class="text-danger">*</span>
                            <input type="text"
                                name="full_name"
                                value="{{ old('full_name', $data->full_name ?? '') }}"
                                placeholder="Enter full name"
                                class="form-control @error('full_name') is-invalid @enderror">

                            @error('full_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Mobile Number</label>
                            <span class="text-danger">*</span>
                            <input type="text"
                                name="mobile"
                                value="{{ old('mobile', $data->mobile ?? '') }}"
                                placeholder="Enter mobile number"
                                class="form-control @error('mobile') is-invalid @enderror">

                            @error('mobile')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="text"
                                name="email"
                                value="{{ old('email', $data->email ?? '') }}"
                                placeholder="Enter Email"
                                class="form-control @error('email') is-invalid @enderror">

                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Address</label>
                            <input type="text"
                                name="address"
                                value="{{ old('address', $data->address ?? '') }}"
                                placeholder="Enter address"
                                class="form-control @error('address') is-invalid @enderror">

                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <button type="submit" class="btn btn-primary save_button">Create</button>
                        </div>
                    </div>
                    <input type="hidden" name="moduleType" id="moduleType" value="{{ old('moduleType', $moduleType ?? '') }}">
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')

@endpush
