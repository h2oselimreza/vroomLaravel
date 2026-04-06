@extends('layouts.app')

@section('content')

<div class="header dashboard_from">
    <h1 class="page-title">Appointment Service</h1>
    <ul class="breadcrumb">
        <li><a href="{{ url('admin/Home') }}">Home</a></li>
        <li><a href="#">/ Master Data</a></li>
        <li><a href="#">/ Appointment Service</a></li>
    </ul>
</div>
<div class="main-content">
    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Error Message -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    <div class="row">
        <div class="col-sm-12 col-md-12">

            @include('admin.master-data.appointment-service.tab')

            <div class="panel panel-default"> 
                <div class="add-button">
                    <a href="{{ route('admin.modules.master-data.service-variant.create') }}">Manage Variants</a>
                </div>
                <div class="table-responsive">

                    <table class="table table-bordered table-hover custom-table" id="datatable">
                        <thead>
                            <tr class="bg-primary">
                                <th class="text-center">SL</th>
                                <th class="text-center">Service</th>
                                <th class="text-start">Category</th>
                                <th class="text-start">Service Name</th>
                                <th class="text-center">Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if ($data)
                                @foreach ($data as $value)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $value->serviceDetails->service_name ?? null  }}</td>
                                        <td class="text-center">{{ $value->serviceDetails->category->category_name ?? null }}</td>
                                        <td class="text-center">{{ $value->service_variant_name ?? null }}</td>
                                        <td class="text-center">{{ ($value->is_active) ? 'Active':'Inactive' }}</td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown">
                                                    Action
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a href="{{ $value->service_code ? route('admin.modules.master-data.service-list.edit', $value->service_code ) : '#' }}" 
                                                        class="d-block ps-3">
                                                            <span class="ui-button-text">Update</span>
                                                        </a>                                    
                                                    </li>
                                                    <li class="mt-2">
                                                        <form action="{{ $value->service_code ? route('admin.modules.master-data.service-list.toggle.edit', $value->service_code ) : '#' }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="d-block ps-3 active_button">
                                                                <span>
                                                                    {{ $value->is_active ? 'Inactive' : 'Active' }}
                                                                </span>
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="text-center">No Data Found</td>
                                </tr>
                            @endif
                        </tbody>

                        <tfoot>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>

                    </table>

                </div>
            </div>

        </div>
    </div>
</div>

@endsection


@push('scripts')
<script>
$(document).ready(function () {

    $('#datatable').DataTable({
        pageLength: 10,
        ordering: true,
        searching: true
    });

});
</script>
@endpush