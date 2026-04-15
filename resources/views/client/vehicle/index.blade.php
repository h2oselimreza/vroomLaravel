@extends('client.layouts.app')

@section('content')

<div class="block-header">
    <h2>VEHICLE LIST</h2><br>
    <div class="breadcrumb breadcrumb-bg-blue-grey">
        <li><a href="#client/Home"> Home</a></li>
        <li><a href="#"> Vehicle</a></li>
        <li><a href="#client/Vehicle/vehicleList"> Vehicle List</a></li>
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
                <a href="{{ route('client.vehicle.create') }}" class="btn bg-blue waves-effect">Add Vehicle</a>
                <!--<button class="btn bg-blue waves-effect">Add Employee</button>-->
                <br><br>
                <div class="row" >
                    <div class="col-md-2 col-sm-6 col-xs-12 custom-div-bottom" >
                        <form action="#client/Vehicle/vehicleList" id="statusForm" method="post">
                            <div class="form-group form-float" >
                                <div class="form-line">
                                    <select class="form-control" id="statusDropDown" name="statusDropDown" onchange="changeStatus()">
                                        <?php
                                        $isActiveFlag = '';
                                        if ($isActiveFlag == 1) {
                                            echo "<option value='1'>Active</option>";
                                            echo "<option value='2'>Inactive</option>";
                                        } else {
                                            echo "<option value='2'>Inactive</option>";
                                            echo "<option value='1'>Active</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="table-custom-responsive">
                    <table class="table table-bordered table-hover jq-option-datatable custom-table dataTable">
                        <thead>
                            <tr class="bg-info">
                                <th>SL</th>
                                <th>Registration No</th>
                                <th>Vehicle Type</th>
                                <th>Brand</th>
                                <th>Brand Model</th>
                                <th>Class</th>
                                <th>Group</th>
                                <th>Status</th>
                                <th>Action</th>
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
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php
                            $vehicles = [];
                            $count = 1;
                            foreach ($vehicles as $vehicle) {

                                echo "<tr>";
                                echo "<td>" . $count . "</td>";
                                echo "<td class='td-left'><a target='_blank' href='" . base_url() . "client/Home/vehicleDashboard?vehicleId=" . $vehicle['vehicle_id'] . "'>" . $vehicle['registration_no'] . "</a></td>";
                                echo "<td class='td-left'>" . $vehicle['vehicle_type_name'] . "</td>";
                                echo "<td class='td-left'>" . $vehicle['brand_name'] . "</td>";
                                echo "<td class='td-left'>" . $vehicle['brand_model_name'] . "</td>";
                                echo "<td>" . $vehicle['vehicle_class_name'] . "</td>";
                                echo "<td>" . $vehicle['vehicle_group_name'] . "</td>";
                                echo "<td>";
                                if ($vehicle['is_active'] == 1) {
                                    echo "<span class='text-success'>Active</span>";
                                } elseif ($vehicle['is_active'] == 0) {
                                    echo "<span class='custom-text-danger'>Inactive</span>";
                                }
                                echo "</td>";
                                ?>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Action <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu pull-right">
                                        <li><a href="<?php echo base_url() . 'client/Vehicle/updateVehicleShow/' . $vehicle['vehicle_id']; ?>">Update</a></li>
                                        <li role="separator" class="divider"></li>

                                        <?php
                                        if ($vehicle['is_active'] == 1) {
                                            ?>
                                            <li><a href="#" onclick="inactiveVehicle('<?php echo $vehicle['id'] ?>')">Inactive</a></li>
                                            <?php
                                        } elseif ($vehicle['is_active'] == 0) {
                                            ?>

                                            <li><a href="#" onclick="activeVehicle('<?php echo $vehicle['id'] ?>')">Active</a></li>
                                            <?php
                                        }
                                        ?>


                                    </ul>
                                </div>
                            </td>
                            <?php
                            echo "</tr>";
                            $count++;
                        }
                        ?>
                        </tbody>
                    </table>
                </div> 

                <span class="text-danger">
                    <small>
                        <b> 
                            {{-- 
                            $packageDetails = get_package_info(PACKAGE_VEHICLE_COUNT, $this->companyCode);
                            if ($packageDetails['success'] == 1) {
                                echo "*** You can add " . $packageDetails['vehicleCount'] . " vehicles";
                            }
                            ?>  --}}
                            
                        </b>
                    </small>
                </span>

            </div>
        </div>
    </div>
</div>
    
@endsection
@push('scripts')
<script>
    function inactiveVehicle(vehicleId) {
        swal({
            title: "Are you sure?",
            text: "",
            type: "warning",
            showCancelButton: true,
            closeOnConfirm: false,
            confirmButtonText: "Yes, inactive it...!",
            confirmButtonColor: "#ec6c62"
        }, function () {
            showLoader();
            $.ajax({
                url: "#client/Vehicle/changeVehicleStatus?vehicleId=" + vehicleId + "&status=0",
                type: "DELETE"
            })


                    .done(function (data) {
                        console.log(data);
                        hideLoader();
                        if (data === '1') {
                            swal({
                                title: "Inactive Successfully",
                                text: "This vehicle is inactive now",
                                type: "success",
                                closeOnConfirm: false,
                                confirmButtonText: "Ok",
                                confirmButtonColor: "#A5DC86"
                            }, function () {
                                window.location.href = "#client/Vehicle/vehicleList";
                            });
                        } else if (data === '2') {
                            window.location.href = "#client/Vehicle/vehicleList";
                        }
                    })
                    .error(function (data) {
                        swal("Oops", "We couldn't connect to the server!", "error");
                    });
        });
    }

    function activeVehicle(vehicleId) {
        swal({
            title: "Are you sure?",
            text: "",
            type: "warning",
            showCancelButton: true,
            closeOnConfirm: false,
            confirmButtonText: "Yes, active it...!",
            confirmButtonColor: "#ec6c62"
        }, function () {
            showLoader();
            $.ajax({
                url: "#client/Vehicle/changeVehicleStatus?vehicleId=" + vehicleId + "&status=1",
                type: "DELETE"
            })

                    .done(function (data) {
                        hideLoader();
                        if (data === '1') {
                            swal({
                                title: "Active Successfully",
                                text: "This vehicle is active now",
                                type: "success",
                                closeOnConfirm: false,
                                confirmButtonText: "Ok",
                                confirmButtonColor: "#A5DC86"
                            }, function () {
                                window.location.href = "#client/Vehicle/vehicleList";
                            });
                        } else if (data === '2') {
                            window.location.href = "#client/Vehicle/vehicleList";
                        } else if (data === '3') {
                            failAlert('You can not add vehicle because of package limit exceeds', 'client/Vehicle/vehicleList');
                            //window.location.href = "#client/Vehicle/vehicleList";
                        }
                    })
                    .error(function (data) {
                        swal("Oops", "We couldn't connect to the server!", "error");
                    });
        });
    }
    function changeStatus() {
        showLoader();
        $('#statusForm').submit();
    }
</script>
@endpush
