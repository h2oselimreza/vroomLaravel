@extends('layouts.app')

@section('content')

<div class="header dashboard_from">
    <h1 class="page-title">Package</h1>
    <ul class="breadcrumb">
        <li><a href="{{ url('admin/Home') }}">Home</a></li>
        <li><a href="#">/ Master Data</a></li>
        <li><a href="{{ url('admin/MasterData/area') }}">/ Package</a></li>
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
    <div class="add-button">
        <a href="{{ route('admin.module.master-data.package.create') }}">Add Package</a>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel panel-default"> 
                <div class="table-responsive">

                    <table class="table table-bordered table-hover custom-table table-align" id="datatable">
                        <thead>
                            <tr class="bg-primary">
                                <th>SL</th>
                                <th>Package</th>
                                <th>Max User</th>
                                <th>Max Vehicle</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if ($data)
                                @foreach ($data as $value)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $value->package_name }}</td>
                                    <td>{{ $value->max_user }}</td>
                                    <td>{{ $value->max_vehicle }}</td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown">
                                                Action
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a href="{{ route('admin.module.master-data.package.edit',$value->id) }}" 
                                                    class="d-block ps-3">
                                                        <span class="ui-button-text">Update</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3" class="text-center">No Data Found</td>
                                </tr>
                            @endif
                        </tbody>

                        <tfoot>
                            <tr>
                                <th></th>
                                <th></th>
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