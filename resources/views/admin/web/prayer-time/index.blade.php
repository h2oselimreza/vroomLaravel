@extends('layouts.app')
@section('content')

<div class="header dashboard_from">
    <h1 class="page-title">Prayer Time</h1>
    <ul class="breadcrumb">
        <li><a href="#"> Home</a></li>
        <li><a href="#">/ Website Configuration</a></li>
        <li><a href="#">/ Prayer Time</a></li>
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
      @php
        $dataSources = [
            '1' => 'Custom',
            '4' => 'Umm Al-Qura University, Makkah',
            '2' => 'Islamic Society of North America'
        ];
    @endphp
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel panel-default"> 
                <div class="table-responsive">
                     <table class="table table-bordered table-hover custom-table" id="datatable">
                        <thead>
                            <tr class="bg-primary">
                                <th>Prayer Date</th>
                                <th>Fajr</th>
                                <th>Zuhor</th>
                                <th>Asor</th>
                                <th>Maghrib</th>
                                <th>Isha</th>
                                <th>Jumma</th>
                                <th>Sunrise</th>
                                <th>Sunset</th>
                                <th>Data Source</th>
                                <th>Actions</th>                                
                            </tr>
                        </thead>
                        <tbody>
                            <tr></tr>
                            @if ($data)
                                @foreach ($data as $value)
                                    <td class="text-center">{{ $value->prayer_date }}</td>
                                    <td class="text-center">{{ $value->fajr }}</td>
                                    <td class="text-center">{{ $value->zuhor }}</td>
                                    <td class="text-center">{{ $value->asor }}</td>
                                    <td class="text-center">{{ $value->maghrib }}</td>
                                    <td class="text-center">{{ $value->isha }}</td>
                                    <td class="text-center">{{ $value->jumma }}</td>
                                    <td class="text-center">{{ $value->sunrise }}</td>
                                    <td class="text-center">{{ $value->sunset }}</td>
                                    <td class="text-center">{{ $dataSources[$value->data_source] }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.prayer-time.edit.module',$value->id) }}" 
                                        class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary action_button_modify">
                                            <span class="ui-button-text">&nbsp;Edit</span>
                                        </a>
                                    </td>
                                @endforeach
                            @else
                                <td colspan="11" class="text-center">Data is not Available</td>
                            @endif
                            </tbody>
                        </tbody>
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

    $('#datatable').DataTable();

});
</script>
@endpush
