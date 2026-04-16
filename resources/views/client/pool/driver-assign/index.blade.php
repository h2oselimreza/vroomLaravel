@extends('client.layouts.app')

@section('content')

<div class="block-header">
    <h2>VEHICLE-DRIVER LIST</h2><br>
    <div class="breadcrumb breadcrumb-bg-blue-grey">
        <li><a href="#"> Home</a></li>
        <li><a href="#"> Pool</a></li>
        <li><a href="#"> Driver Assign</a></li>
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
                    <form action="#" method="post">
                        <table class="table table-bordered table-hover js-exportable-no-search custom-table dataTable">
                            <thead>
                                <tr class="bg-info">
                                    <th width="10%">SL</th>
                                    <th width="20%">Registration No</th>
                                    <th width="15%">Vehicle Type</th>
                                    <th width="15%">Brand</th>
                                    <th width="15%">Brand Model</th>
                                    <th width="15%">Group</th>
                                    <th width="25%">Driver <small><i>(Click to select driver)</i></small></th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th> </th>
                                    <th> </th>
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

                                        <tr>
                                            <td>{{ $count }}</td>

                                            <td class="td-left">
                                                <a target="_blank"
                                                href="{{ url('client/Home/vehicleDashboard?vehicleId=' . $vehicle->vehicle_id) }}">
                                                    {{ $vehicle->registration_no }}
                                                </a>
                                            </td>

                                            <td class="td-left">{{ $vehicle->vehicle_type_name }}</td>
                                            <td class="td-left">{{ $vehicle->brand_name }}</td>
                                            <td class="td-left">{{ $vehicle->brand_model_name }}</td>
                                            <td class="td-left">{{ $vehicle->vehicle_group_name }}</td>

                                            <td id="driverTd{{ $count }}"
                                                class="td-left pointer"
                                                onclick="showDriverModal({{ $count }})">

                                                @if(!empty($vehicle->driver_name))
                                                    {{ $vehicle->driver_name }}
                                                @else
                                                    <span class="text-muted">
                                                        <small><i>Select Driver</i></small>
                                                    </span>
                                                @endif

                                            </td>

                                            <input type="hidden"
                                                name="driver{{ $count }}"
                                                id="driver{{ $count }}"
                                                value="{{ $vehicle->driver_id }}">

                                            <input type="hidden"
                                                name="vehicle{{ $count }}"
                                                id="vehicle{{ $count }}"
                                                value="{{ $vehicle->vehicle_id }}">

                                            <input type="hidden"
                                                name="contengencyDtTm{{ $count }}"
                                                id="contengencyDtTm{{ $count }}"
                                                value="{{ $vehicle->updated_dt_tm }}">

                                        </tr>

                                        @php $count++; @endphp

                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        <input type="hidden" name="vehicleCount" value="<?php echo $count ?>">


                    </form>

                </div> 
                <input type="hidden" id="vehicleModalCount">
                <!-- ----------------- driver modal ------------------- -->
                <button type="button" class="btn btn-default waves-effect m-r-20 hidden" data-toggle="modal" data-target="#largeModal" id="driverModalShowBtn"></button>

                <div class="modal fade" id="largeModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="largeModalLabel">Drivers</h4>
                            </div>
                            <div class="modal-body">
                                <div class="table-responsive">

                                    <table class="table table-bordered table-hover custom-table dataTable">
                                        <thead>
                                            <tr class="bg-info">
                                                <th>Serial</th>
                                                <th>Driver Id</th>
                                                <th>Name</th>
                                                <th>Designation</th>
                                                <th>Contact No</th>
                                                <th>Select</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            @php $serial = 1; @endphp

                                            @if(!empty($drivers))
                                                @foreach($drivers as $driver)

                                                    @if($driver->emp_type == 'driver')
                                                        <tr>
                                                            <td>{{ $serial }}</td>
                                                            <td>{{ $driver->employee_id }}</td>
                                                            <td class="td-left">{{ $driver->employee_name }}</td>
                                                            <td class="td-left">{{ $driver->designation }}</td>
                                                            <td>{{ $driver->primary_mobile }}</td>

                                                            <td>
                                                                <i class="material-icons pointer"
                                                                onclick="setDriver({{ $serial }})">
                                                                    arrow_drop_down_circle
                                                                </i>
                                                            </td>

                                                            <input type="hidden"
                                                                id="driverIdModalHidden{{ $serial }}"
                                                                value="{{ $driver->employee_id }}">

                                                            <input type="hidden"
                                                                id="driverNameModalHidden{{ $serial }}"
                                                                value="{{ $driver->employee_name }}">
                                                        </tr>

                                                        @php $serial++; @endphp
                                                    @endif

                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-link waves-effect" onclick="removeDriver()">CLEAR</button>
                                <button type="button" class="btn btn-link waves-effect" id="modalCloseBtn" data-dismiss="modal">CLOSE</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ----------------- end driver modal ------------------- -->
            </div>
        </div>
    </div>
</div>
    
@endsection
@push('scripts')
    <script>
    function showDriverModal(count) {
        $('#driverModalShowBtn').click();
        $('#vehicleModalCount').val(count);
    }

    function setDriver(modalCount) {
        $('#modalCloseBtn').click();

        var vehicleModalCount = $('#vehicleModalCount').val();
        var driverId = $('#driverIdModalHidden' + modalCount).val();
        $('#driver' + vehicleModalCount).val(driverId);

        var vehicle = $('#vehicle' + vehicleModalCount).val();
        var contengencyDtTm = $('#contengencyDtTm' + vehicleModalCount).val();

        swal({
            title: "Are you sure?",
            text: "",
            type: "warning",
            showCancelButton: true,
            closeOnConfirm: false,
            confirmButtonText: "Yes assign this driver...!",
            confirmButtonColor: "#ec6c62"
        }, function () {

            showLoader();

            $.ajax({
                type: 'POST',
                url: "{{ url('client/pool/driver-assign') }}",
                data: {
                    vehicle: vehicle,
                    driver: driverId,
                    contengencyDtTm: contengencyDtTm,
                    _token: "{{ csrf_token() }}" 
                }
            })
            .done(function (data) {

                hideLoader();

                if (data === 1 || data === '1') {
                    $('#driverTd' + vehicleModalCount)
                        .text($('#driverNameModalHidden' + modalCount).val());
                        sweetAlert('Successfully Save...!');
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                } else if (data === 2 || data === '2') {
                    failAlert('Failed...!');

                } else if (data === 3 || data === '3') {
                    failAlert('Someone has edited this vehicle...!');
                }
            })
            .fail(function () {
                hideLoader();
                swal("Oops", "We couldn't connect to the server!", "error");
            });
        });
    }

    function removeDriver() {
        $('#modalCloseBtn').click();

        var vehicleModalCount = $('#vehicleModalCount').val();
        var cancelText = "<span class='text-muted'><small><i>Select Driver</i></small></span>";

        var vehicle = $('#vehicle' + vehicleModalCount).val();
        var contengencyDtTm = $('#contengencyDtTm' + vehicleModalCount).val();

        swal({
            title: "Are you sure?",
            text: "",
            type: "warning",
            showCancelButton: true,
            closeOnConfirm: false,
            confirmButtonText: "Yes clear this vehicle...!",
            confirmButtonColor: "#ec6c62"
        }, function () {
            showLoader();

            $.ajax({
                type: 'POST',
                url: "{{ url('client/pool/remove-driver') }}",
                data: {
                    vehicle: vehicle,
                    contengencyDtTm: contengencyDtTm,
                    _token: "{{ csrf_token() }}"
                }
            })
            .done(function (data) {
                hideLoader();

                if (data === '1' || data === 1) {
                    $('#driverTd' + vehicleModalCount).html(cancelText);
                    $('#driver' + vehicleModalCount).val("");
                    sweetAlert('Successfully Save...!');
                    setTimeout(function () {
                            location.reload();
                        }, 1000);
                } else if (data === '2' || data === 2) {
                    failAlert('Failed...!');
                } else if (data === '3' || data === 3) {
                    failAlert('Due to this vehicle is in Enroute, you can not clear this vehicle...!');
                } else if (data === '4' || data === 4) {
                    failAlert('Someone has edited this vehicle...!');
                }
            })
            .fail(function () {
                hideLoader();
                swal("Oops", "We couldn't connect to the server!", "error");
            });
        });
    }
</script>
@endpush
