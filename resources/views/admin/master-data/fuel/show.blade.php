@extends('layouts.app')

@section('content')

<div class="header dashboard_from">
    <h1 class="page-title">Fuel</h1>
    <ul class="breadcrumb">
        <li><a href="{{ url('admin/Home') }}">Home</a></li>
        <li><a href="#">/ Master Data</a></li>
        <li><a href="{{ url('admin/master-data/fuel') }}">/ Fuel</a></li>
    </ul>
</div>
<div class="main-content">
    <div class="card from_card data_view">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <div class="card shadow-sm border-0">
                    <div class="card-header">
                        Record Fuel
                    </div>

                    <div class="card-body">

                        <div class="row row-item">
                            <div class="col-md-4 label">Fuel Name :</div>
                            <div class="col-md-8 value">{{ $data->fuel_name }}</div>
                        </div>

                        <div class="row row-item">
                            <div class="col-md-4 label">Fuel Rate :</div>
                            <div class="col-md-8 value">{{ $data->fuel_rate }}</div>
                        </div>

                    </div>

                    <div class="card-footer text-end bg-white">
                        <a href="{{ route('admin.module.master-data.fuel.index') }}" class="btn btn-outline-secondary">
                            ← Back to list
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection