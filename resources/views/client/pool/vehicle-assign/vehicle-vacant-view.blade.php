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
    <h2>VACANT</h2><br>
    <div class="breadcrumb breadcrumb-bg-blue-grey">
        <li><a href="client/Home"> Home</a></li>
        <li><a href="#"> Pool</a></li>
        <li><a href="client/VehicleAssign/employeeVehicleAssign"> Vehicle Assign</a></li>
        <li><a href="client/VehicleAssign/showEmployeeAssign?vehicleId={{ $data['vehicleId'] }}"> Vacant</a></li>
    </div>
</div>


<div class="row clearfix">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="body">
                <form action="{{ route('client.pool.vehicle-employee-vacant.update') }}" method="POST" id="vacantForm">
                    @csrf
                    <div class="panel-group">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="text-center font-16 m-b-10">
                                    <b> Last History <br>
                                        <span class="text-vroom-orange font-12"><i>
                                                {{ $data['vehicleDetails']?->assign_type ?? '' }}

                                            </i></span>
                                    </b>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">

                                        <div class="table-responsive">
                                            <table class="m-t-10 m-b-10" border="0" cellpadding="0" cellspacing="0" align="center" width="100%">
                                                <tr class="table-td-info">
                                                    <td width="20%" align="left" class="content-table-td"><b>Person Name</b></td>
                                                    <td width="2%" align="center">:</td>
                                                    <td width="28%" align="left" class="content-table-td"><?php echo $data['vehicleDetails']->pull_emp_name ?></td>

                                                    <td width="20%" align="left" class="content-table-td"><b>Vehicle</b></td>
                                                    <td width="2%" align="center">:</td>
                                                    <td width="28%" align="left" class="content-table-td"><?php echo $data['vehicleDetails']->registration_no ?></td>
                                                </tr>

                                                <tr class="table-td-info">
                                                    <td  align="left" class="content-table-td"><b>ID No</b></td>
                                                    <td  align="center">:</td>
                                                    <td  align="left" class="content-table-td"><?php echo $data['vehicleDetails']->pull_id_no ?></td>

                                                    <td  align="left" class="content-table-td"><b>Received Date Time</b></td>
                                                    <td align="center">:</td>
                                                    <td align="left" class="content-table-td">{{ \Carbon\Carbon::parse($data['vehicleDetails']->pull_receive_date)->format('d-m-Y H:i:s') }}</td>
                                                </tr>
                                                <tr class="table-td-info">
                                                    <td align="left" class="content-table-td"><b>Department</b></td>
                                                    <td align="center">:</td>
                                                    <td align="left" class="content-table-td"><?php echo $data['vehicleDetails']->pull_department ?></td>

                                                    <td align="left" class="content-table-td"><b>Received Location</b></td>
                                                    <td align="center">:</td>
                                                    <td align="left" class="content-table-td"><?php echo $data['vehicleDetails']->pull_current_location ?></td>
                                                </tr>
                                                <tr class="table-td-info">
                                                    <td align="left" class="content-table-td"><b>Designation</b></td>
                                                    <td align="center">:</td>
                                                    <td align="left" class="content-table-td"><?php echo $data['vehicleDetails']->pull_designation ?></td>

                                                    <td align="left" class="content-table-td"><b>Notes</b></td>
                                                    <td align="center">:</td>
                                                    <td align="left" class="content-table-td"><?php echo $data['vehicleDetails']->pull_remarks ?></td>
                                                </tr>
                                                <?php
                                                if ($data['vehicleDetails']->assign_type == config('constants.ASSIGN_ENROUTE')) {
                                                    ?>
                                                    <tr class="table-td-info">
                                                        <td align="left" class="content-table-td"><b>Route</b></td>
                                                        <td align="center">:</td>
                                                        <td align="left" class="content-table-td"><?php echo $data['vehicleDetails']->pull_route ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <hr>
                                <br>
                                <div class="row">
                                    <?php
                                    $x = 6;
                                    ?>

                                    <div class="col-md-<?php echo $x ?> col-sm-6 col-xs-12">
                                        <div class="form-group form-float" >
                                            <div class="form-line demo-masked-input">
                                                <input type="text" class="form-control datetime" name="vacantDate" value="<?php echo date('Y-m-d H:i') ?>" id="vacantDate">
                                                <!--<label class="form-label"> Vacant Date</label>-->
                                            </div>
                                            <div class="help-info">Vacant Date Time (yyyy-mm-dd h:m)</div>
                                            <label id="vacantDateReq-error" class="error hidden">Vacant Date is required</label>
                                        </div>
                                    </div>
                                    <?php
                                    $vacantLocation = "";

                                    if(isset($data['vehicleDetails']->route_json)) {
                                        $routeJsonArr = json_decode($data['vehicleDetails']->route_json,true);
                                        //print_r(json_decode($vehicelDetails->route_json,true));
                                        $jsonLength = count($routeJsonArr);
                                        $vacantLocation = $routeJsonArr[$jsonLength-1]['routeTo'] ?? '';
                                        //print_r($routeJsonArr[$jsonLength-1]['routeTo']);

                                    }

                                    ?>
                                    <div class="col-md-5 col-sm-5 col-xs-12">
                                        <div class="form-group form-float" >
                                            <div class="form-line">
                                                <input type="text" class="form-control" value="<?php echo $vacantLocation?>" name="location" id="location" >
                                            </div>
                                            <div class="help-info">Vacant Location </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-sm-1 col-xs-12">
                                        <?php
                                        if ($data['vehicleDetails']->communication_code != "") {
                                            ?>
                                            <button type="button" class="btn bg-red waves-effect btn-xs" onclick="getCurrentLocation()"><i class="material-icons">location_on</i></button>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <textarea  class="form-control" name="notes"></textarea>
                                                <label class="form-label"> Notes</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn bg-blue waves-effect" onclick="vacantVehicle()">Vacant</button>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="vehicle" value="<?php echo $data['vehicleId'] ?>">
                    <input type="hidden" name="assignType" value="<?php echo $data['assignType'] ?>">
                    <input type="hidden" id="receiveDateTime" value="<?php echo $data['vehicleDetails']->pull_receive_date ?>">

                </form>
                <!--    <button class="btn bg-red waves-effect" onclick="clearAllField()">Clear</button>-->
            </div> <!-- end class=body -->
        </div> <!-- end class="card" -->
    </div> <!-- end class="col-xs-12 col-sm-12 col-md-12 col-lg-12" -->
</div> <!-- end class="row clearfix" -->
    
@endsection
@push('scripts')
<script>
    $(function () {
        var $demoMaskedInput = $('.demo-masked-input');
        $demoMaskedInput.find('.datetime').inputmask('y-m-d h:s', {placeholder: '____-__-__ __:__', alias: "datetime", hourFormat: '24'});

    });
</script>
<script>
    function vacantVehicle() {
        var errorMsg = "";
        // filed id, error div id
        var fieldsArr = new Array("vacantDate|vacantDateReq-error");  // filed id, error div id
        var inputFiledJsonData = getInputData(fieldsArr);
        if (!inputFiledJsonData) {
            errorMsg += getReuiredFiledErrorMsg();
            showErrorMsg(errorMsg);
            return false;  // required filed check
        } else {
            hideErrorDiv();
        }
        var vacantDateTime = $.trim($('#vacantDate').val());
        if (checkDateTime(vacantDateTime) === 0) {
            sweetAlert('Date Time is not in correct format...!');
            return false;
        }

        var vacantDateTime = new Date(vacantDateTime);
        var receiveDateTime = new Date($('#receiveDateTime').val());
        var diff = new Date(vacantDateTime - receiveDateTime);
        var days = diff / 1000 / 60 / 60 / 24;
        if (days < 0) {
            sweetAlert('Vacant date time cannot be less than receive date time...!');
            return false;
        }
        $('#vacantForm').submit();
    }

    function getCurrentLocation() {
        var vehicleId = '<?php echo $data['vehicleId'] ?>';
        showLoader();
        $.ajax({
            type: 'POST',
            data: {vehicleId: vehicleId},
            url: 'client/VehicleAssign/getCurrentLocation',
            success: function (result) {
                hideLoader();
                var resultObj = jQuery.parseJSON(result);
                hideLoader();
                if (resultObj.success === 1) {
                    $('#location').val(resultObj.address);
                } else if (resultObj.success === 2) {
                    sweetAlert('No VTS APP Key found...!');
                    return false;
                } else if (resultObj.success === 3) {
                    sweetAlert('No Vehicle found...!');
                    return false;
                }

            },
            error: function () {
                hideLoader();
                sweetAlert('Fail to get current location...!');
            }
        });
    }
</script>
@endpush
