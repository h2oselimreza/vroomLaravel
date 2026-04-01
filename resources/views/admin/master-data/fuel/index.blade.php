@extends('layouts.app')

@section('content')

<div class="header dashboard_from">
    <h1 class="page-title">Fuel</h1>
    <ul class="breadcrumb">
        <li><a href="{{ url('admin/Home') }}">Home</a></li>
        <li><a href="#">/ Master Data</a></li>
        <li><a href="{{ url('admin/MasterData/area') }}">/ Fuel</a></li>
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
                <div class="table-responsive">

                    <table class="table table-bordered table-hover custom-table table-align" id="datatable">
                        <thead>
                            <tr class="bg-primary">
                                <th>Fuel Name</th>
                                <th>Fuel Rate</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($data as $value)
                                <tr>
                                    <td >{{ $value->fuel_name }}</td>
                                    <td >{{ $value->fuel_rate }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.module.master-data.fuel.show',$value->id) }}" 
                                        class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary action_button_modify">
                                            <span class="ui-button-text">&nbsp;View</span>
                                        </a> |
                                        <a href="{{ route('admin.module.master-data.fuel.edit',$value->id) }}" 
                                        class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary action_button_modify">
                                            <span class="ui-button-text">&nbsp;Edit</span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">No Data Found</td>
                                </tr>
                            @endforelse
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