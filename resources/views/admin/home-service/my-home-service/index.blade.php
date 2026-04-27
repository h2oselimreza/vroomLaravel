@extends('layouts.app')

@section('content')

<link href="{{ asset('assets/select_bo/css/calendar/fullcalendar.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/select_bo/css/calendar/fullcalendar.print.css') }}" rel="stylesheet" media="print" />

<style>
    .panel-group{
        margin-bottom: 0px;
    }
    .custom-panel-title1{
        font-size: 13px;
        font-weight: bold;
    }
    .custom-panel-heading{
        padding: 8px 14px;
    }
    .panel-group .panel1{
        margin-bottom: 5px;
    }
    .custom1-panel-body{
        font-size: 12px;
    }
    .content-table-td{font-size: 12px}
    .custom-form-control{
        height: 20px;
        font-size: 12px;
        text-align: right;
    }

    .table#clientListTable tr td,th{
        padding: 2px 2px;
        vertical-align: middle;
        text-align: center;
        font-size: 13px;
    }
</style>

<div class="header dashboard_from">
    <h1 class="page-title">My Home Service Details</h1>
    <ul class="breadcrumb">
        <li><a href="#">Home</a></li>
        <li><a href="/admin/home/home-service-list">/ My Home Service</a></li>
        <li><a href="/admin/home/home-service-list">/ My Home Service Details</a></li>
    </ul>
</div>

<div class="main-content">
    <!-- Error Message -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel panel-default">
                <div class="row" >
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="aatable-responsive">
                            <table class="table table-hover custom-table" id="homeservice-datatable" style="width: 100%">
                                <thead class="hidden">
                                    <tr>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($data['empHomeSerLists'] ?? [] as $empHomeSerList)

                                        <tr>
                                            <td>
                                                <b>
                                                    {{ $empHomeSerList->appointment_no ?? '' }}

                                                    <span class="float-end">
                                                        {{ get_appointment_status($empHomeSerList->status ?? null, 'client') }}
                                                    </span>
                                                </b>
                                                <br>

                                                <i class="fa fa-user"></i>
                                                {{ $empHomeSerList->company_name ?? '' }}
                                                <br>

                                                <i class="fa fa-calendar"></i>
                                                <i>{{ get_date_format1($empHomeSerList->final_date ?? null) }}</i>
                                                <br>

                                                <i class="fa fa-clock-o"></i>
                                                <i>{{ get_time_format($empHomeSerList->appointment_time ?? null) }}</i>
                                            </td>

                                            <td>
                                                {{ $empHomeSerList->appointment_no ?? '' }}
                                            </td>
                                        </tr>

                                    @empty

                                        <tr>
                                            <td colspan="2" class="text-center text-danger">
                                                No data found
                                            </td>
                                        </tr>

                                    @endforelse

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function () {

        var homeServiceTable = $('#homeservice-datatable').DataTable({
            "bDestroy": true,
            "deferRender": true,
            "aaSorting": [],
            "iDisplayLength": 20,
            "bLengthChange": false,
            "columnDefs": [
                {
                    "targets": [1],
                    "visible": false,
                    "searchable": true
                }
            ],
            "createdRow": function (row, data, index) {
                $(row).addClass('pointer');
            }

        });

        $('#homeservice-datatable tbody').on('click', 'tr', function () {
            var data = homeServiceTable.row(this).data();
            console.log(data);
            showHomeServiceDetails(data[1]);
        });
    });

    function showHomeServiceDetails(homeServiceNo) {
     window.location.href = "{{ url('admin/home/employee-home-service-details') }}/" 
    + homeServiceNo + "/" + "{{ $empId }}";
    }


</script>
@endpush