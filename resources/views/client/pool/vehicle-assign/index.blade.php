@extends('client.layouts.app')

@section('content')

<style>
    .table-td-info
    {	
        background:#FFFFFF;
        font-size:11px;
        font-family:Verdana, Geneva, sans-serif;    
        font-weight:normal;
        padding-left:7px;
        padding-top:2px;
        padding-bottom:2px;
    }
</style>

<div class="block-header">
    <h2>VEHICLE-PERSON LIST</h2><br>
    <div class="breadcrumb breadcrumb-bg-blue-grey">
        <li><a href="#"> Home</a></li>
        <li><a href="#"> Pool</a></li>
        <li><a href="#"> Vehicle Assign</a></li>
    </div>
</div>

<div class="row clearfix">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="body">
                 @if(session('success'))
                    <div class="alert alert-success">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                        <strong>{{ session('success') }}</strong>
                    </div>
                @endif
                {{-- Validation Errors --}}
                @if(session('error'))
                    <div class="alert alert-danger">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                        <strong>{{ session('error') }}</strong>
                    </div>
                @endif
                
                <div class="table-custom-responsive">

                    <table class="table table-bordered table-hover custom-table dataTable">
                        <thead>
                            <tr class="bg-info">
                                <th width="5%">SL</th>
                                <th width="30%">Vehicle</th>
                                <th width="10%">Group</th>
                                <th width="15%">Used By</th>
                                <th width="15%">Designation</th>
                                <th width="15%">Receive Date</th>
                                <th width="10%">Received Location</th>
                                <th width="10%">Status</th>
                                <th width="10%">Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
                        <tbody>
                        @php $count = 1; @endphp

                        @if(!empty($vehicles))
                            @foreach($vehicles as $vehicle)
                                @php
                                    $assignTypeName = getVehicleAssignTypeName($vehicle->assign_type);
                                @endphp

                                <tr>
                                    <td>{{ $count }}</td>

                                    <td class="td-left">
                                        <a target="_blank"
                                        href="{{ url('client/vehicle/vehicle-employee-assign?vehicleId=' . $vehicle->vehicle_id) }}">
                                            {{ $vehicle->registration_no }}
                                            <br>
                                            <small><i>{{ $vehicle->brand_name }} {{ $vehicle->brand_model_name }}</i></small>
                                        </a>
                                    </td>

                                    <td class="td-left">{{ $vehicle->vehicle_group_name }}</td>
                                    <td class="td-left">{{ $vehicle->pull_emp_name }}</td>
                                    <td class="td-left">{{ $vehicle->pull_designation }}</td>
                                    <td class="td-left">{{ getDateTimeFormat($vehicle->pull_receive_date) }}</td>
                                    <td class="td-left">{{ $vehicle->pull_current_location }}</td>
                                    <td class="td-left">{{ $assignTypeName }}</td>

                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Action <span class="caret"></span>
                                            </button>

                                            <ul class="dropdown-menu pull-right">
                                                @if($vehicle->assign_type == config('constants.ASSIGN_VACANT'))
                                                    <li>
                                                        <a href="{{ url('client/vehicle/vehicle-employee-assign?vehicleId=' . $vehicle->vehicle_id . '&type=' . config('constants.ASSIGN_PERSON')) }}">
                                                            Assign Person
                                                        </a>
                                                    </li>

                                                    @if($vehicle->driver_id)
                                                        <li>
                                                            <a href="{{ url('client/vehicle/vehicle-employee-assign?vehicleId=' . $vehicle->vehicle_id . '&type=' . config('constants.ASSIGN_ENROUTE')) }}">
                                                                En Route
                                                            </a>
                                                        </li>
                                                    @endif

                                                @elseif($vehicle->assign_type == config('constants.ASSIGN_PERSON') || $vehicle->assign_type == config('constants.ASSIGN_ENROUTE'))
                                                    <li>
                                                        <a href="javascript:void(0);" onclick="showDetails('{{ $count }}')">View</a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ url('client/VehicleAssign/showVehicleVacant?vehicleId=' . $vehicle->vehicle_id . '&type=' . config('constants.ASSIGN_VACANT')) }}">
                                                            Vacant
                                                        </a>
                                                    </li>
                                                @endif

                                                <li>
                                                    <a href="{{ url('client/VehicleAssign/showCurrentLocation?vehicleId=' . $vehicle->vehicle_id) }}">
                                                        Current Location
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>

                                    <input type="hidden" id="personNameHidden{{ $count }}" value="{{ $vehicle->pull_emp_name }}">
                                    <input type="hidden" id="idNoHidden{{ $count }}" value="{{ $vehicle->pull_id_no }}">
                                    <input type="hidden" id="designationHidden{{ $count }}" value="{{ $vehicle->pull_designation }}">
                                    <input type="hidden" id="departmentHidden{{ $count }}" value="{{ $vehicle->pull_department }}">
                                    <input type="hidden" id="receiveDateTimeHidden{{ $count }}" value="{{ getDateTimeFormat($vehicle->pull_receive_date) }}">
                                    <input type="hidden" id="routeHidden{{ $count }}" value="{{ $vehicle->pull_route }}">
                                    <input type="hidden" id="currentLocationHidden{{ $count }}" value="{{ $vehicle->pull_current_location }}">
                                    <input type="hidden" id="remarksHidden{{ $count }}" value="{{ $vehicle->pull_remarks }}">
                                    <input type="hidden" id="assignTypeHidden{{ $count }}" value="{{ $assignTypeName }}">
                                    <input type="hidden" id="vehicleNameHidden{{ $count }}" value="{{ $vehicle->registration_no }} ({{ $vehicle->brand_name }} {{ $vehicle->brand_model_name }})">
                                </tr>

                                @php $count++; @endphp
                            @endforeach
                        @endif
                    </tbody>
                    </table>
                </div> 


                <button class="btn hidden" data-toggle="modal" data-target="#vehicleAssignModal" id="vehicleAssignModalShowBtn"></button>
                <div class="modal fade" id="vehicleAssignModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="vehicleTitle"></h5>
                                <span id="assignStatus"></span>
                            </div>
                            <div class="modal-body">
                                <div class="panel panel1 panel-default">
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="m-t-10 m-b-10" border="0" cellpadding="0" cellspacing="0" align="center" width="100%">
                                                <tr class="table-td-info">
                                                    <td width="20%" align="left" class="content-table-td"><b>Person Name</b></td>
                                                    <td width="2%" align="center">:</td>
                                                    <td width="28%" align="left" class="content-table-td"  id="personNameTd"></td>

                                                    <td width="20%" align="left" class="content-table-td"><b>Vehicle</b></td>
                                                    <td width="2%" align="center">:</td>
                                                    <td width="28%" align="left" class="content-table-td" id="vehicleTd"></td>
                                                </tr>

                                                <tr class="table-td-info">
                                                    <td  align="left" class="content-table-td"><b>ID No</b></td>
                                                    <td  align="center">:</td>
                                                    <td  align="left" class="content-table-td" id="idNoTd"></td>

                                                    <td  align="left" class="content-table-td"><b>Received Date Time</b></td>
                                                    <td align="center">:</td>
                                                    <td align="left" class="content-table-td" id="receiveDateTimeTd"></td>
                                                </tr>
                                                <tr class="table-td-info">
                                                    <td align="left" class="content-table-td"><b>Department</b></td>
                                                    <td align="center">:</td>
                                                    <td align="left" class="content-table-td" id="departmentTd"></td>

                                                    <td align="left" class="content-table-td"><b>Received Location</b></td>
                                                    <td align="center">:</td>
                                                    <td align="left" class="content-table-td" id="receivedLocationTd"></td>
                                                </tr>
                                                <tr class="table-td-info">
                                                    <td align="left" class="content-table-td"><b>Designation</b></td>
                                                    <td align="center">:</td>
                                                    <td align="left" class="content-table-td" id="designationTd"></td>

                                                    <td align="left" class="content-table-td"><b>Notes</b></td>
                                                    <td align="center">:</td>
                                                    <td align="left" class="content-table-td" id="noteTd"></td>
                                                </tr>

                                                <tr id="routeTr" class="table-td-info">
                                                    <td align="left" class="content-table-td"><b>Route</b></td>
                                                    <td align="center">:</td>
                                                    <td align="left" class="content-table-td" id="routeTd"></td>
                                                </tr>

                                            </table>
                                        </div>
                                    </div>

                                </div>   


                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger waves-effect" id="modalCloseBtn" data-dismiss="modal">CLOSE</button>
                            </div>

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

    function showDetails(count) {
        $('#vehicleAssignModalShowBtn').click();
        $('#vehicleTitle').text($('#vehicleNameHidden' + count).val());
        $('#personNameTd').text($('#personNameHidden' + count).val());
        $('#idNoTd').text($('#idNoHidden' + count).val());
        $('#vehicleTd').text($('#vehicleNameHidden' + count).val());
        $('#receiveDateTimeTd').text($('#receiveDateTimeHidden' + count).val());
        $('#departmentTd').text($('#departmentHidden' + count).val());
        $('#receivedLocationTd').text($('#currentLocationHidden' + count).val());
        $('#designationTd').text($('#designationHidden' + count).val());
        $('#noteTd').text($('#remarksHidden' + count).val());
        $('#routeTd').text($('#routeHidden' + count).val());
        $('#assignStatus').text($('#assignTypeHidden' + count).val());
        if ($('#assignTypeHidden' + count).val() === 'Assigned') {
            $('#routeTr').hide();
        } else {
            $('#routeTr').show();
        }

    }

</script>
@endpush
