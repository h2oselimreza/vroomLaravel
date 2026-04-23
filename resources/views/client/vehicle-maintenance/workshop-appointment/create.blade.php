@extends('client.layouts.app')

@section('content')


<div class="block-header">
    <h2>SET NEW WORKSHOP APPOINTMENT</h2><br>
    <div class="breadcrumb breadcrumb-bg-blue-grey">
        <li><a href="client/Home"> Home</a></li>
        <li><a href="#"> Vehicle Maintenance</a></li>
        <li><a href="{{ route('client.vehicle-maintenance.set-workshop-appointment') }}"> Set Workshop Appointment</a></li>
        <li><a href="{{route('client.vehicle-maintenance.createAppointment')}}?workshop=<?php echo $workshop ?>"> Set New Workshop Appointment</a></li>
    </div>
</div>

<div class="row clearfix">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="body">
                {{-- Success Message --}}
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

                <form action="{{ route('client.vehicle-maintenance.addNewAppointment') }}" method="post" id="appointmentForm">
                    @csrf
                    <div class="text-center font-17 m-b-10">
                        <b>Set Appointment to <span class="pointer text-vroom-orange" onclick="showDetails('<?php echo $workshop ?>')"><?php echo get_workshop_name($workshop) ?></span></b>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="panel-group" id="vehicleGroupDiv">
                            </div>
                            <input type="hidden" id="vehicleSerial" name="vehicleSerial">
                            <input type="hidden" name="vehicleCount" id="vehicleCount">


                            <button type="button" class="btn btn-default btn-sm waves-effect" data-toggle="modal" data-target="#vehicleModal">Add Vehicle</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group form-float" >
                                <div class="form-line">
                                    <input type="text" class="form-control dateInput"  name="date1" id="date1" value="<?php echo date('Y-m-d')?>">
                                    <label class="form-label"> Select Date</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group form-float" >
                                <div class="form-line">
                                    <select class="form-control" name="timeSlot1" id="timeSlot1">
                                        <option value=""></option>
                                        <option value="Morning">Morning</option>
                                        <option value="Evening">Evening</option>

                                    </select>
                                    <div class="help-info">Select Time Slot</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group form-float" >
                                <div class="form-line">
                                    <input type="text" class="form-control dateInput"  name="date2" id="date2" value="<?php echo date('Y-m-d')?>">
                                    <label class="form-label"> Select Date</label>
                                </div>
                                <div class="help-info">Alternative</div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group form-float" >
                                <div class="form-line">
                                    <select class="form-control" name="timeSlot2">
                                        <option value=""></option>
                                        <option value="Morning">Morning</option>
                                        <option value="Evening">Evening</option>

                                    </select>
                                    <div class="help-info">Select Time Slot (Alternative)</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group form-float" >
                                <label class="form-label">Remarks </label>
                                <div class="form-line">
                                    <textarea class="form-control" name="remarks" ></textarea>
                                </div>
                            </div>
                            <input type="hidden" name="workshop" value="<?php echo $workshop ?>">
                            <button type="button"  class="btn bg-blue btn-sm waves-effect m-t-30" onclick="setAppoinment()">Set Appointment</button>
                        </div>
                    </div>
                </form>


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
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            @php
                                                $vehicleSerial = 1;
                                            @endphp
                                            
                                            @foreach ($vehicles as $vehicle)
                                            
                                                <tr>
                                                    <td>{{ $vehicleSerial }}</td>
                                            
                                                    <td class="td-left">
                                                        <a target="_blank"
                                                           href="{{ url('client/Home/vehicleDashboard?vehicleId=' . $vehicle->vehicle_id) }}">
                                                            {{ $vehicle->registration_no }}
                                                        </a>
                                                    </td>
                                            
                                                    <td class="td-left">
                                                        {{ $vehicle->vehicle_type_name }}
                                                    </td>
                                            
                                                    <td class="td-left">
                                                        {{ $vehicle->brand_name }}
                                                    </td>
                                            
                                                    <td class="td-left">
                                                        {{ $vehicle->brand_model_name }}
                                                    </td>
                                            
                                                    <td>
                                                        <i class="material-icons pointer"
                                                           onclick="addVehicle({{ $vehicleSerial }})">
                                                            arrow_drop_down_circle
                                                        </i>
                                                    </td>
                                            
                                                    <input type="hidden"
                                                           id="vehicleIdModalHidden{{ $vehicleSerial }}"
                                                           value="{{ $vehicle->vehicle_id }}">
                                            
                                                    <input type="hidden"
                                                           id="vehicleRegModalHidden{{ $vehicleSerial }}"
                                                           value="{{ $vehicle->registration_no }}">
                                            
                                                </tr>
                                            
                                                @php
                                                    $vehicleSerial++;
                                                @endphp
                                            
                                            @endforeach
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
                <!-- ------------- ----------------- ----------------- -->

                <!-- --------------- service modal -------------------- -->
                <button class="btn btn-default btn-sm waves-effect hidden" data-toggle="modal" data-target="#serviceModal" id="serviceModalShowBtn">Add service</button>
                <div class="modal fade" id="serviceModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="largeModalLabel">Service</h4>
                            </div>
                            <div class="modal-body">
                                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                    @php
                                        $flag = 1;
                                        $serviceCount = 1;
                                    @endphp
                            
                                    @foreach ($distinctServices as $distinctService)
                                        <div class="panel panel1 panel-default">
                                            <div class="panel-heading custom-panel-heading" role="tab" id="headingOne">
                                                <p class="panel-title custom-panel-title1 p-t-0 p-b-0">
                                                    <a role="button"
                                                       data-toggle="collapse"
                                                       data-parent="#"
                                                       href="#generalCollapseOne{{ $distinctService->service }}"
                                                       aria-expanded="true"
                                                       aria-controls="generalCollapseOne{{ $distinctService->service }}">
                                                        <i class="fa fa-tags"></i> {{ $distinctService->service_name }}
                                                    </a>
                                                </p>
                                            </div>
                            
                                            <div id="generalCollapseOne{{ $distinctService->service }}"
                                                 class="panel-collapse collapse"
                                                 role="tabpanel"
                                                 aria-labelledby="headingOne">
                                                <div class="panel-body">
                                                    <table class="table table-striped custom-table">
                                                        @php
                                                            $serviceVarSerial = 1;
                                                        @endphp
                            
                                                        @foreach ($serviceVariants as $serviceVariant)
                                                            @if ($serviceVariant->service == $distinctService->service)
                                                                @php
                                                                    $flag = 0;
                                                                @endphp
                            
                                                                <tr>
                                                                    <td>{{ $serviceVarSerial }}</td>
                                                                    <td class="td-left" style="width:80%">
                                                                        {{ $serviceVariant->service_variant_name }}
                                                                    </td>
                                                                    <td class="td-left">
                                                                        <input type="checkbox"
                                                                               name="serviceVarCheckBox{{ $serviceCount }}"
                                                                               id="serviceVarCheckBox{{ $serviceCount }}"
                                                                               class="filled-in chk-col-blue">
                                                                        <label for="serviceVarCheckBox{{ $serviceCount }}"
                                                                               class="form-label"
                                                                               style="margin-bottom: -12px"></label>
                                                                    </td>
                            
                                                                    <input type="hidden"
                                                                           id="serviceVariantCode{{ $serviceCount }}"
                                                                           value="{{ $serviceVariant->variant_code }}">
                            
                                                                    <input type="hidden"
                                                                           id="serviceVariantName{{ $serviceCount }}"
                                                                           value="{{ $serviceVariant->service_variant_name }}">
                            
                                                                    @php
                                                                        $serviceVarSerial++;
                                                                        $serviceCount++;
                                                                    @endphp
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                            
                                    <input type="hidden" id="serviceVariantCount" value="{{ $serviceCount }}">
                                </div>
                            
                                @if ($flag)
                                    <span class="text-danger">No service has been add to this workshop</span>
                                @endif
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-link waves-effect" id="serviceModalSelectBtn" onclick="setAddService()">SELECT</button>
                                <button type="button" class="btn btn-link waves-effect" id="serviceModalCloseBtn" data-dismiss="modal">CLOSE</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ------------- ----------------- ----------------- -->
                <!-- --------------- workshop  details modal ---------------- -->
                <button class="btn hidden" data-toggle="modal" data-target="#workshopModal" id="workshopModalShowBtn"></button>
                <div class="modal fade" id="workshopModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="workshopTitle"></h4>
                            </div>
                            <div class="modal-body">
                                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                    <div class="panel panel1 panel-default">
                                        <div class="panel-heading custom-panel-heading" role="tab" id="headingOne">
                                            <p class="panel-title custom-panel-title1 p-t-0 p-b-0">
                                                <a role="button" data-toggle="collapse" data-parent="#" href="#generalCollapseOne" aria-expanded="true" aria-controls="generalCollapseOne">
                                                    <i class="fa fa-tags"></i> General Information
                                                </a>
                                            </p>
                                        </div>
                                        <div id="generalCollapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                            <div class="panel-body">
                                                <div class="table-responsive">
                                                    <table border="0" cellpadding="0" cellspacing="0" align="center" width="100%">
                                                        <tr class="table-td-info">
                                                            <td width="10%" align="left" class="content-table-td"><b>Email</b></td>
                                                            <td width="2%" align="center">:</td>
                                                            <td width="38%" align="left" id="workshopEmail"></td>
                                                            <td width="10%" align="left" class="content-table-td"><b>Website</b></td>
                                                            <td width="2%" align="center">:</td>
                                                            <td width="38%" align="left" id="workshopWebsite"></td>
                                                        </tr>
                                                        <tr class="table-td-info">
                                                            <td align="left" class="content-table-td"><b>Address</b></td>
                                                            <td align="center">:</td>
                                                            <td align="left" id="workshopAddress"></td>
                                                            <td align="left" class="content-table-td"><b>Division</b></td>
                                                            <td align="center">:</td>
                                                            <td align="left" id="workshopDivision"></td>
                                                        </tr>
                                                        <tr class="table-td-info">
                                                            <td align="left" class="content-table-td"><b>District</b></td>
                                                            <td align="center">:</td>
                                                            <td align="left" id="workshopDistrict"></td>
                                                            <td align="left" class="content-table-td"><b>Upozilla</b></td>
                                                            <td align="center">:</td>
                                                            <td align="left" id="workshopUpozilla"></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>   
                                    <div class="panel panel1 panel-default">
                                        <div class="panel-heading custom-panel-heading" role="tab" id="two">
                                            <p class="panel-title custom-panel-title1 p-t-0 p-b-0">
                                                <a role="button" data-toggle="collapse" data-parent="#" href="#timeShedule" aria-expanded="true" aria-controls="timeShedule">
                                                    <i class="fa fa-clock-o"></i> Time Schedule
                                                </a>
                                            </p>
                                        </div>
                                        <div id="timeShedule" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="two">
                                            <div class="panel-body">
                                                <table border="0" cellpadding="0" cellspacing="0" align="center" width="100%">
                                                    <tr class="table-td-info">
                                                        <td width="10%" align="left" class="content-table-td"><b><span id="day1"></span></b></td>
                                                        <td width="2%" align="center">:</td>
                                                        <td width="85%" align="left" id="time1"></td>
                                                    </tr>
                                                    <?php
                                                    for ($i = 2; $i < 8; $i++) {
                                                        ?>
                                                        <tr class="table-td-info">
                                                            <td align="left" class="content-table-td"><b><span id="day<?php echo $i ?>"></span></b></td>
                                                            <td align="center">:</td>
                                                            <td align="left" id="time<?php echo $i ?>"></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    ?>
                                                </table>
                                            </div>
                                        </div>
                                    </div>



                                    <div class="panel panel1 panel-default">
                                        <div class="panel-heading custom-panel-heading" role="tab" id="three">
                                            <p class="panel-title custom-panel-title1 p-t-0 p-b-0">
                                                <a role="button" data-toggle="collapse" data-parent="#" href="#vehicleServie" aria-expanded="true" aria-controls="vehicleServie">
                                                    <i class="fa fa-car"></i> Vehicle Services
                                                </a>
                                            </p>
                                        </div>
                                        <div id="vehicleServie" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="three">
                                            <div class="panel-body">
                                                <div class="font-13" id="vehicleServieDiv">
                                                </div>
                                            </div>
                                        </div>
                                    </div>



                                    <div class="panel panel1 panel-default">
                                        <div class="panel-heading custom-panel-heading" role="tab" id="four">
                                            <p class="panel-title custom-panel-title1 p-t-0 p-b-0">
                                                <a role="button" data-toggle="collapse" data-parent="#" href="#service" aria-expanded="true" aria-controls="service">
                                                    <i class="fa fa-bars"></i> Services
                                                </a>
                                            </p>
                                        </div>
                                        <div id="service" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="four">
                                            <div class="panel-body">
                                                <div class="font-13" id="serviceDiv">

                                                </div>
                                            </div>
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

                <input type="hidden" id="title" value="<?php echo $workshopDetails->title ?>">
                <input type="hidden" id="email" value="<?php echo $workshopDetails->workshop_email ?>">
                <input type="hidden" id="website" value="<?php echo $workshopDetails->website ?>">
                <input type="hidden" id="address" value="<?php echo $workshopDetails->address ?>">
                <input type="hidden" id="division" value="<?php echo $workshopDetails->division_en_name . ' (' . $workshopDetails->division_bn_name . ')' ?>">
                <input type="hidden" id="district" value="<?php echo $workshopDetails->district_en_name . ' (' . $workshopDetails->district_bn_name . ')' ?>">
                <input type="hidden" id="uplozilla" value="<?php echo $workshopDetails->upozilla_en_name . ' (' . $workshopDetails->upozilla_bn_name . ')' ?>">


            </div>
        </div>
    </div>
</div>


@endsection
@push('scripts')
<script>
    var counter = 1;
    function addVehicle(vehicleSerial) {
        var vehicleId = $('#vehicleIdModalHidden' + vehicleSerial).val();
        var vehicleRegNo = $('#vehicleRegModalHidden' + vehicleSerial).val();
        for (var i = 1; i < counter; i++) {
            if (typeof ($('#vehicleId' + i).val()) !== 'undefined') {
                if ($('#vehicleId' + i).val() === vehicleId) {
                    sweetAlert("You have already select this vehicle...!");
                    return false;
                }
            }
        }

        var newRow = $(document.createElement('div')).attr("id", 'vehicleDiv' + counter);
        var vehicleDiv = '<div class="panel panel2 panel-default">\n\
                            <div class="panel-heading">\n\
                                <div class="panel-title custom1-panel-title">\n\
                                    <div class="row p-l-20 p-r-20">\n\
                                        <div class="float-left">\n\
                                            <i class="fa fa-car"></i> ' + vehicleRegNo + '\n\
                                        </div>\n\
                                        <div class="float-right">\n\
                                            <i class="fa fa-remove pointer text-danger" onclick="removeVehicle(' + counter + ')"></i>\n\
                                        </div>\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                            <div class="panel-body custom1-panel-body">\n\
                                <div id="vehicleServiceDiv' + counter + '">\n\
                                </div>\n\
                                <button type="button" class="btn bg-blue btn-xs waves-effect" onclick="setShowServiceModal(' + counter + ')" >Add Service</button>\n\
                            </div>\n\
                            <input type="hidden" name="vehicleId' + counter + '" id="vehicleId' + counter + '" value="' + vehicleId + '">\n\
                        </div>';
        newRow.after().html(vehicleDiv);
        newRow.appendTo("#vehicleGroupDiv");
        $('#vehicleCount').val(counter);
        counter++;
        $('#modalCloseBtn').click();
    }

    function setShowServiceModal(vehicleSerial) {
        $("#vehicleSerial").val(vehicleSerial);
        var serviceVariantCount = $("#serviceVariantCount").val();
        var serviceVarCodeStr = $("#serviceVarCodeStr" + vehicleSerial).val();
        $('#serviceModalShowBtn').click();
        for (var i = 1; i < serviceVariantCount; i++) {
            $('#serviceVarCheckBox' + i).prop('checked', false);
        }
        if (typeof serviceVarCodeStr !== 'undefined') {
            if (serviceVarCodeStr) {
                var serviceVarCodeArr = serviceVarCodeStr.split(',');
                for (var i = 1; i < serviceVariantCount; i++) {
                    if (jQuery.inArray($("#serviceVariantCode" + i).val(), serviceVarCodeArr) !== -1) {
                        $('#serviceVarCheckBox' + i).prop('checked', true);

                    } else {
                        $('#serviceVarCheckBox' + i).prop('checked', false);
                    }
                }
            }
        }
    }

    function setAddService() {
        var vehicleSerial = $("#vehicleSerial").val();
        var serviceVariantCount = $("#serviceVariantCount").val();
        var serviceVariantCode;
        var serviceVariantName;
        var serviceTableStr = "";
        var serviceVarCodeArr = new Array();
        var takenServiceVarCount = 1;

        var takenServieVarCountFinal = $("#takenServiceVarCount" + vehicleSerial).val();
        if (typeof takenServieVarCountFinal === 'undefined') {
            takenServieVarCountFinal = 0;
        }
        var i = 1;
        for (var x = 1; x < serviceVariantCount; x++) {
            if ($("#serviceVarCheckBox" + x).is(':checked')) {
                serviceVariantCode = $("#serviceVariantCode" + x).val();
                serviceVariantName = $("#serviceVariantName" + x).val();


                serviceTableStr += '<tr id="serviceTakenTd' + vehicleSerial + i + '">\n\
                                        <td class="td-left">' + serviceVariantName + '</td>\n\
                                        <td><i class="fa fa-remove pointer text-danger" onclick="removeService(' + vehicleSerial + "," + i + ')"></i></td>\n\
                                        <input type="hidden" id="takenServiceVarCode' + vehicleSerial + i + '" name="takenServiceVarCode' + vehicleSerial + i + '" value="' + serviceVariantCode + '">\n\
                                    </tr>';
                serviceVarCodeArr.push(serviceVariantCode);
                takenServiceVarCount++;
                i++;
            }
        }
        $('#serviceTableDiv' + vehicleSerial).remove();
        if (serviceTableStr !== "") {
            var newRow = $(document.createElement('div')).attr("id", 'serviceTableDiv' + vehicleSerial);
            var serviceTableDiv = '<table class="table table-bordered custom-table">\n\
                                <tr class="bg-info">\n\
                                    <td colspan="3"><b>Service</b></td>\n\
                                </tr>\n\
                                <tr>\n\
                                    <td width="50%"><b>Service Name</b></td>\n\
                                    <td width="10%"><b>Action</b></td>\n\
                                </tr>\n\
                                ' + serviceTableStr + '\n\
                                <input type="hidden" id="serviceVarCodeStr' + vehicleSerial + '" value="' + serviceVarCodeArr.join() + '">\n\
                                <input type="hidden" id="takenServiceVarCount' + vehicleSerial + '" name="takenServiceVarCount' + vehicleSerial + '" value="' + takenServiceVarCount + '">\n\
                            </table>';
            newRow.after().html(serviceTableDiv);
            newRow.appendTo("#vehicleServiceDiv" + vehicleSerial);
        }
        $('#serviceModalCloseBtn').click();
    }

    function removeVehicle(vehicleSerial) {
        $('#vehicleDiv' + vehicleSerial).remove();
    }

    function removeService(vehicleSerial, serviceSerial) {
        $('#serviceTakenTd' + vehicleSerial + serviceSerial).remove();
        var serviceVarCodeArr = new Array();
        var takenServiceVarCode;
        var takenServiceVarCount = $('#takenServiceVarCount' + vehicleSerial).val();
        for (var i = 1; i < takenServiceVarCount; i++) {
            takenServiceVarCode = $('#takenServiceVarCode' + vehicleSerial + i).val();

            if (typeof takenServiceVarCode !== 'undefined') {
                serviceVarCodeArr.push(takenServiceVarCode);
            }
        }

        if (serviceVarCodeArr.length !== 0) {
            $('#serviceVarCodeStr' + vehicleSerial).val(serviceVarCodeArr.join());
        } else {
            $('#serviceTableDiv' + vehicleSerial).remove();
        }

    }

    function setAppoinment() {
        var takenServiceVarCount;
        var serviceProductFlag;
        var vehicleFlag = 0;
        var vehicleCount = $('#vehicleCount').val();
        for (var i = 1; i <= vehicleCount; i++) {
            if (typeof $('#vehicleId' + i).val() !== "undefined") {
                serviceProductFlag = 0;
                vehicleFlag = 1;
                //--------- service check ------------//
                takenServiceVarCount = $("#takenServiceVarCount" + i).val();
                for (var j = 1; j <= takenServiceVarCount; j++) {
                    var takenServiceVarCode = $("#takenServiceVarCode" + i + j).val();
                    if (typeof takenServiceVarCode !== 'undefined') {
                        serviceProductFlag = 1;
                    }
                }

                if (serviceProductFlag === 0) {
                    sweetAlert('Please take at least one service...!');
                    return false;
                }
            }

        }
        if (vehicleFlag === 0) {
            sweetAlert('Please take at least one vehicle...!');
            return false;
        }
        if ($('#date1').val() === "" || $('#timeSlot1').val() === "") {
            sweetAlert('Date and Time Slot is mendetory...!');
            return false;
        }
        $("#appointmentForm").submit();
    }

    function showDetails(workshopCode) {
    showLoader();

    for (var i = 1; i < 8; i++) {
        $('#day' + i).text('');
        $('#time' + i).text('');
    }

    $('#vehicleServieDiv').html("");
    $('#serviceDiv').html("");
    $('#workshopModalShowBtn').click();

    $.ajax({
        type: 'POST',
        url: '/client/vehicle-maintenance/getWorkshopInfo',
        data: {
            workshopCode: workshopCode,
            _token: "{{ csrf_token() }}"// ✅ IMPORTANT for Laravel
        },
        dataType: 'json', // ✅ no need for parseJSON
        success: function (resultObj) {
            hideLoader();

            var j = 1;

            // -------- time schedule --------
            for (var i = 0; i < resultObj.timeShedule.length; i++) {
                $('#day' + j).text(resultObj.timeShedule[i].weekday_name);

                var startTime = getTimeAmPmFormat(resultObj.timeShedule[i].start_time);
                var endTime = getTimeAmPmFormat(resultObj.timeShedule[i].end_time);

                if (resultObj.timeShedule[i].weekend_status == 1) {
                    $('#time' + j).html('<span class="text-danger"><b>WEEKEND</b></span>');
                } else {
                    $('#time' + j).text(startTime + ' To ' + endTime);
                }
                j++;
            }

            // -------- vehicle services --------
            var serviceVehicleStr = "<ul>";
            for (var i = 0; i < resultObj.serviceVehicle.length; i++) {
                serviceVehicleStr += '<li>' + resultObj.serviceVehicle[i].vehicle_type_name + "</li>";
            }
            $('#vehicleServieDiv').html(serviceVehicleStr + "</ul>");

            // -------- services --------
            var serviceStr = "";

            for (var i = 0; i < resultObj.distinctService.length; i++) {
                var serviceCode = resultObj.distinctService[i].service;
                var serviceVariantStr = "";

                for (var j = 0; j < resultObj.allService.length; j++) {
                    if (serviceCode === resultObj.allService[j].service) {
                        serviceVariantStr += "<div class='col-md-6 col-sm-6 col-xs-12 font-12 service-variant'>" +
                            "<li>" + resultObj.allService[j].service_variant_name + "</li>" +
                            "</div>";
                    }
                }

                serviceStr += "<div class='row'>" +
                    "<div class='col-md-12 col-sm-12 col-xs-12 font-12 service'>" +
                    "<div class='bottom-border'>" +
                    "<b>" + resultObj.distinctService[i].service_name + "</b>" +
                    "</div>" +
                    "</div>" +
                    "</div>" +
                    "<div class='row m-b-20'>" + serviceVariantStr + "</div>";
            }

            $('#serviceDiv').html(serviceStr);
        },
        error: function () {
            hideLoader();
            alert('Something went wrong!');
        }
    });

    // -------- static data --------
    $('#workshopTitle').text($('#title').val());
    $('#workshopEmail').text($('#email').val());
    $('#workshopWebsite').text($('#website').val());
    $('#workshopAddress').text($('#address').val());
    $('#workshopDivision').text($('#division').val());
    $('#workshopDistrict').text($('#district').val());
    $('#workshopUpozilla').text($('#uplozilla').val());
}
</script>
@endpush