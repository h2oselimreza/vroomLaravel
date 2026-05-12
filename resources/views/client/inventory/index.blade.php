
@extends('client.layouts.app')
@section('content')
    <div class="block-header">
        <h2>STOCK</h2><br>
        <div class="breadcrumb breadcrumb-bg-blue-grey">
            <li><a href="/client/Home"> Home</a></li>
            <li><a href="#"> Inventory</a></li>
            <li><a href="{{ route('client.master-data.stock') }}"> Inventory</a></li>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="body">
                    @include('client.inventory.tab')
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')

@endpush