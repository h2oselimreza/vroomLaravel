@extends('client.layouts.app')

@section('content')
<div class="block-header">
    <h2>ADD ACCIDENTAL LOG</h2><br>
    <div class="breadcrumb breadcrumb-bg-blue-grey">
        <li><a href="#"> Home</a></li>
        <li><a href="#"> Vehicle</a></li>
        <li><a href="{{ route('client.accidental-log.index') }}"> Accidental Log</a></li>
        <li><a href="{{ route('client.accidental-log.create') }}"> Add Accidental Log</a></li>
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
                <form action="{{ isset($data) ? route('client.accidental-log.update', $data->id) : route('client.accidental-log.store') }}" method="POST" enctype="multipart/form-data" id="insertForm">
                    @csrf
                    @if(isset($data))
                        @method('PUT')
                    @endif
                    <div class="row">
                        <div class="col-md-2 col-sm-2 col-xs-4">
                            <button type="button" class="btn btn-default" onclick="showAddVehicleModal()">Select Vehicle</button>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-8">
                            <div id="vehicleDiv" class="m-t-10">
                                {{ $data->registration_no ?? '' }}
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-4">
                            <button type="button" class="btn btn-default" onclick="showAddDriverModal()">Select Driver</button>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-8">
                            <div class="form-group form-float" >
                                <div id="driverDiv" class="m-t-10">
                                    {{ $data->employee_name ?? '' }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group form-float" >
                                <div class="form-line">
                                    <input type="text" class="form-control"  name="place" id="place" value="{{ $data->place ?? '' }}">
                                    <label class="form-label"> Accident Place</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group form-float" >
                                <div class="form-line">
                                    <input type="text" class="form-control dateInput"  name="accidentDate" id="accidentDate" value="{{ !empty($data->accident_date_time) ? \Carbon\Carbon::parse($data->accident_date_time)->format('Y-m-d') : '' }}">
                                    <label class="form-label">Set Date</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group form-float" >
                                <div class="form-line demo-masked-input">
                                    <input type="text" class="form-control time12"  name="accidentTime" id="accidentTime" placeholder="Ex: 11:59 pm" value="{{ !empty($data->accident_date_time) ? \Carbon\Carbon::parse($data->accident_date_time)->format('g:i A') : '' }}">
                                    <!--<label class="form-label"> Time</label>-->
                                </div>
                                <div class="help-info">Set Time</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group form-float" >
                                <div class="form-line">
                                    <input type="text" class="form-control"  name="affectedAreas" id="affectedAreas" value="{{ $data->vehicle_affected_area ?? '' }}">
                                    <label class="form-label"> Vehicle Affected Areas</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group form-float" >
                                <label class="form-label">Remarks </label>
                                <div class="form-line">
                                    <textarea class="form-control" name="remarks" >{{ $data->remarks ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="file" name="accidentFile[]" id="accidentFile" onchange="checkFile(this, this.id)" multiple>
                    <br>
                    <input type="hidden" class="form-control" name="vehicleId" id="vehicleId" value="{{ $data->vehicle ?? '' }}">
                    <input type="hidden" class="form-control" name="driverId" id="driverId" value="{{ $data->driver ?? '' }}">
                    @if (isset($data))
                        <input type="hidden" class="form-control" name="id" id="id" value="{{ $data->id ?? '' }}">
                    @endif
                </form>
                @php
                    $fileArr = !empty($data->file_name) ? json_decode($data->file_name, true) : [];
                    $originalNameArr = !empty($data->file_original_name) ? json_decode($data->file_original_name, true) : [];
                @endphp

                @if(!empty($fileArr))
                    <table class="table table-bordered table-hover custom-table" style="border:1px solid #ddd">
                        <thead>
                            <tr class="bg-info">
                                <th>SL</th>
                                <th>File Name</th>
                                <th>Show</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($fileArr as $index => $file)
                                <tr>
                                    <td class="td-center">{{ $index + 1 }}</td>
                                    <td class="td-left">{{ $originalNameArr[$index] ?? '' }}</td>
                                    <td class="td-center">
                                        <a target="_blank" href="{{ asset('assets/client/files/accidental-log/' . $file) }}">
                                            Show
                                        </a>
                                    </td>
                                    <td class="td-center">
                                        <i class="fa fa-remove pointer" onclick="removeFile('{{ $file }}')"></i>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div id="my-div" class="my-div">
                        <table class="table table-bordered table-hover custom-table" style="border:1px solid #ddd">
                            <tr>
                                <td class="td-center bg-info"><b>No files uploaded</b></td>
                            </tr>
                        </table>
                    </div>
                @endif
                <button class="btn bg-blue waves-effect" onclick="insertLog()">Save</button>
                <br><br>
                <button type="button" class="btn btn-default waves-effect m-r-20 hidden" data-toggle="modal" data-target="#vehicleModal" id="vehicleModalShowBtn"></button>
                <!-- --------------- vehicle modal -------------------- -->
                <div class="modal fade" id="vehicleModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="largeModalLabel">Vehicle</h4>
                            </div>
                            <div class="modal-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover custom-table dataTable">
                                        <thead>
                                            <tr class="bg-info">
                                                <th width="10%">SL</th>
                                                <th width="25%">Registration No</th>
                                                <th width="15%">Vehicle Type</th>
                                                <th width="15%">Brand</th>
                                                <th width="15%">Brand Model</th>
                                                <th width="15%">Group</th>
                                                <th width="10%">Select</th>
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
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            @if(isset($vehicles) && count($vehicles) > 0)
                                                @php $vehicleSerial = 1; @endphp

                                                @foreach($vehicles as $vehicle)
                                                    <tr>
                                                        <td>{{ $vehicleSerial }}</td>

                                                        <td class="td-left">
                                                            <a target="_blank" href="{{ url('client/Home/vehicleDashboard?vehicleId=' . $vehicle->vehicle_id) }}">
                                                                {{ $vehicle->registration_no }}
                                                            </a>
                                                        </td>

                                                        <td class="td-left">{{ $vehicle->vehicle_type_name }}</td>
                                                        <td class="td-left">{{ $vehicle->brand_name }}</td>
                                                        <td class="td-left">{{ $vehicle->brand_model_name }}</td>
                                                        <td class="td-left">{{ $vehicle->vehicle_group_name }}</td>

                                                        <td>
                                                            <i class="material-icons pointer" onclick="addVehicle({{ $vehicleSerial }})">
                                                                arrow_drop_down_circle
                                                            </i>
                                                        </td>

                                                        <input type="hidden" id="vehicleIdModalHidden{{ $vehicleSerial }}" value="{{ $vehicle->vehicle_id }}">
                                                        <input type="hidden" id="vehicleRegModalHidden{{ $vehicleSerial }}" value="{{ $vehicle->registration_no }}">
                                                    </tr>

                                                    @php $vehicleSerial++; @endphp
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="7" class="text-center">No vehicles found</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-link waves-effect" id="modalCloseBtn" data-dismiss="modal">CLOSE</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ------------- Driver Modal ----------------- -->
                <button type="button" class="btn btn-default waves-effect m-r-20 hidden" data-toggle="modal" data-target="#driverModal" id="driverModalShowBtn"></button>
                <div class="modal fade" id="driverModal" tabindex="-1" role="dialog">
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
                                            @if(isset($drivers) && count($drivers) > 0)
                                                @php $serial = 1; @endphp

                                                @foreach($drivers as $driver)
                                                    @if($driver->emp_type == 'driver')
                                                        <tr>
                                                            <td>{{ $serial }}</td>
                                                            <td>{{ $driver->employee_id }}</td>
                                                            <td class="td-left">{{ $driver->employee_name }}</td>
                                                            <td class="td-left">{{ $driver->designation }}</td>
                                                            <td>{{ $driver->primary_mobile }}</td>
                                                            <td>
                                                                <i class="material-icons pointer" onclick="setDriver({{ $serial }})">
                                                                    arrow_drop_down_circle
                                                                </i>
                                                            </td>

                                                            <input type="hidden" id="driverIdModalHidden{{ $serial }}" value="{{ $driver->employee_id }}">
                                                            <input type="hidden" id="driverNameModalHidden{{ $serial }}" value="{{ $driver->employee_name }}">
                                                        </tr>

                                                        @php $serial++; @endphp
                                                    @endif
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="6" class="text-center">No drivers found</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-link waves-effect" id="modalCloseBtn1" data-dismiss="modal">CLOSE</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ----------------- end driver modal ------------------- -->
                <form id="deleteForm" action="{{ route('client.accidental-log-file-delete') }}" method="POST" >
                    @csrf
                    <input type="hidden" class="form-control" name="id" id="id" value="{{ $data->id ?? '' }}">
                    <input type="hidden" name="fileNameHidden" id="fileNameHidden">
                </form>
            </div> <!-- end class=body -->
        </div> <!-- end class="card" -->
    </div> <!-- end class="col-xs-12 col-sm-12 col-md-12 col-lg-12" -->
</div> <!-- end class="row clearfix" -->

@endsection
@push('scripts')
<script>
    function checkFile() {
        var fp = $("#accidentFile");
        var lg = fp[0].files.length; // get length
        var items = fp[0].files;
        var fileSize = 0;
        var fileExtension = ['jpeg', 'JPEG', 'jpg', 'JPG', 'png', 'PNG', 'mpeg', 'MPEG', 'mpg', 'MPG', 'mp4', 'MP4', 'mp3', 'MP3', 'amr', 'AMR', 'pdf', "PDF"];
        if (lg > 0) {
            for (var i = 0; i < lg; i++) {
                fileSize = fileSize + items[i].size;
                if ($.inArray(items[i].name.split('.').pop().toLowerCase(), fileExtension) === -1) {
                    sweetAlert("Only 'jpeg', 'jpg', 'png', 'mpeg', 'mpg', 'mp4', 'mp3', 'pdf' formats are allowed...!");
                    $('#accidentFile').val('');
                    return false;
                }
            }
            if (fileSize > 2097152) {
                sweetAlert('File size must not be more than 2 MB...!');
                $('#accidentFile').val('');
            }
        }
    }
    function showAddVehicleModal() {
        $('#vehicleModalShowBtn').click();
    }
    function addVehicle(vehicleSerial) {
        var vehicleId = $('#vehicleIdModalHidden' + vehicleSerial).val();
        var vehicleNo = $('#vehicleRegModalHidden' + vehicleSerial).val();
        $('#vehicleDiv').text(vehicleNo);
        $('#vehicleId').val(vehicleId);
        $('#modalCloseBtn').click();
    }

    function showAddDriverModal() {
        $('#driverModalShowBtn').click();
    }

    function setDriver(serial) {
        var driverId = $('#driverIdModalHidden' + serial).val();
        var driverName = $('#driverNameModalHidden' + serial).val();
        $('#driverDiv').text(driverName);
        $('#driverId').val(driverId);
        $('#modalCloseBtn1').click();
    }

    function insertLog() {
        if ($.trim($('#vehicleId').val()) === "" || $.trim($('#driverId').val()) === "" || 
                $.trim($('#accidentDate').val()) === "" || $.trim($('#accidentTime').val()) === "" || 
                $.trim($('#place').val()) === "" || $.trim($('#affectedAreas').val()) === "") {
            sweetAlert('Vehicle No, Driver Name, Accident Date, Time, Place and Affected Areas are required fields...!');
            return false;
        }
        if (checkTime($.trim($('#accidentTime').val())) === 0) {
            sweetAlert('Time is not in correct format...!');
            return false;
        }
        $("#insertForm").submit();
    }

    function removeFile(fileName) {
        $('#fileNameHidden').val(fileName);
        swal({
            title: "Are you sure?",
            text: "",
            type: "warning",
            showCancelButton: true,
            closeOnConfirm: false,
            confirmButtonText: "",
            confirmButtonColor: "#62ec6f"
        }, function () {
            swal.close();
            $('#deleteForm').submit();
        });
    }
</script>
@endpush