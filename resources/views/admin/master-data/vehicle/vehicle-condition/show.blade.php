@extends('layouts.app')

@section('content')

<div class="header dashboard_from">
    <h1 class="page-title">Vehicle Condition</h1>
    <ul class="breadcrumb">
        <li><a href="{{ url('admin/Home') }}">Home</a></li>
        <li><a href="#">/ Master Data</a></li>
        <li><a href="{{ url('admin/MasterData/area') }}">/ Vehicle Condition</a></li>
    </ul>
</div>
<div class="main-content">
    <div class="card from_card data_view">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <div class="card shadow-sm border-0">
                    <div class="card-header">
                        Record Vehicle Condition
                    </div>

                    <div class="card-body">

                        <div class="row row-item">
                            <div class="col-md-4 label">Vehicle Condition :</div>
                            <div class="col-md-8 value">{{ $data->element }}</div>
                        </div>

                        <div class="row row-item">
                            <div class="col-md-4 label">Depend on element :</div>
                            <div class="col-md-8 value">--</div>
                        </div>

                        <div class="row row-item">
                            <div class="col-md-4 label">Active :</div>
                            <div class="col-md-8 value">{{ $data->is_active }}</div>
                        </div>

                        <div class="row row-item">
                            <div class="col-md-4 label">Vehicle Condition Order :</div>
                            <div class="col-md-8 value">{{ $data->element_order }}</div>
                        </div>

                    </div>

                    <div class="card-footer text-end bg-white">
                        <a href="{{ route('admin.modules.master-data.vehicle-condition.index') }}" class="btn btn-outline-secondary">
                            ← Back to list
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection