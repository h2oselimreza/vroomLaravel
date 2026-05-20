@extends('client.layouts.app')
@section('content')
<style>
    .form-group{
        margin-bottom: 0px;
    }
    .panel-group .panel{
        margin-bottom: 15px;
    }
    .panel-group{
        margin-bottom: 0px;
    }
    .custom-panel-title1{
        font-size: 13px;
        font-weight: bold;
    }

    .custom-panel-heading{
        padding: 10px 14px;

    }
    .panel-group .panel1{
        margin-bottom: 5px;
    }
    .custom-form-control{
        height: 20px;
        font-size: 12px;
        text-align: center;
    }
    .custom-form-control1{
        height: 20px;
        font-size: 12px;
        text-align: left;
        border-bottom: 1px solid black;
    }
    .form-group .form-line:after{
        border-bottom: 0px;
    }
</style>

<div class="block-header">
    <h2>EDIT REQUEST FOR QUOTATION </h2><br>
    <div class="breadcrumb breadcrumb-bg-blue-grey">
        <li><a href="/client/Home"> Home</a></li>
        <li><a href="#"> Quotation</a></li>
        <li><a href="{{ route('client.quotation.quotation-list.index') }}"> Requested List For Quotation</a></li>
        <li><a href="{{ route('client.quotation.quotation-list.edit', $data['requestNo']) }}"> Edit Request For Quotation </a></li>
    </div>
</div>

<?php
foreach ($data['requestSummaries'] as $requestSummary) {
    $remarks = $requestSummary->remarks;
    $adminRemarks = $requestSummary->admin_remarks;
    $quotationSubmittedDate = $requestSummary->quotation_submitted_date;
    $status = $requestSummary->status;
    $updateDtTm = $requestSummary->updated_dt_tm;
    $rejectReason = $requestSummary->reject_reason;
}
?>

<div class="row clearfix">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="body">
                <form action="{{route('client.quotation.quotation-list.update',$data['requestNo'])}}" method="post" id="requestForm">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group form-float" >
                                <div class="form-line">
                                    <input type="text" class="form-control dateInput" name="lastsubmitDate" id="lastsubmitDate" value="<?php echo $quotationSubmittedDate ?>" >
                                    <label class="form-label"> Quotation Last Submit Date </label>
                                </div>
                                <label id="lastSubmitDateError" class="error"></label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="panel-group" id="vehicleGroupDiv">
                                <?php
                                $vehicleCount = 1;
                                $requestedVehicleArr = array();
                                foreach ($data['requestedVehicles'] as $requestedVehicle) {
                                    ?>
                                    <div id="vehicleDiv<?php echo $vehicleCount ?>">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <div class="panel-title custom1-panel-title">
                                                    <div class="row p-l-20 p-r-10 p-t-5 p-b-5">
                                                        <div class="float-left">
                                                            <i class="fa fa-car"></i> {{ $requestedVehicle->registration_no }}
                                                        </div>
                                                        <div class="float-right">
                                                            <?php
                                                            if ($status == config('constants.REQ_DRAFT_STATUS') || $status == config('constants.REQ_PENDING_STATUS') || $status == config('constants.REQ_REJECT_STATUS')) {
                                                                ?>
                                                                <i class="fa fa-remove pointer text-danger" onclick="removeVehicle('<?php echo $vehicleCount ?>')"></i>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="panel-body custom1-panel-body">
                                                <div id="vehicleServiceDiv<?php echo $vehicleCount ?>">

                                                    <?php
                                                    $serviceStr = "";
                                                    $i = 1;
                                                    $serviceVariantCodeArr = array();
                                                    $servVarCodeReqNoArr = array();
                                                    $servVarReqDetailsNoArr = array();
                                                    foreach ($data['requestDetails'] as $requestDetail) {
                                                        if ($requestedVehicle->vehicle == $requestDetail->vehicle && $requestDetail->request_type == config('constants.REQ_TYPE_SERVICE')) {
                                                            $removeStr = "";
                                                            if ($status == config('constants.REQ_DRAFT_STATUS') || $status == config('constants.REQ_PENDING_STATUS') || $status == config('constants.REQ_REJECT_STATUS')) {
                                                                $removeStr = '<i class="fa fa-remove pointer text-danger" onclick="removeService(' . $vehicleCount . "," . $i . ')"></i>';
                                                            }
                                                            $serviceStr .= '<tr id="serviceTakenTd' . $vehicleCount . $i . '">
                                                                                <td class="td-left">' . $requestDetail->service_variant_name . '</td>
                                                                                <td>
                                                                                    <div class="form-group form-float" >
                                                                                        <div class="form-line">
                                                                                            <input type="number" class="form-control custom-form-control" name="srviceQuantity' . $vehicleCount . $i . '" id="srviceQuantity' . $vehicleCount . $i . '" min="1" value="' . $requestDetail->quantity . '">
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>' . $removeStr . '</td>
                                                                                <input type="hidden" id="takenServiceVarCode' . $vehicleCount . $i . '" name="takenServiceVarCode' . $vehicleCount . $i . '" value="' . $requestDetail->service_veriant . '">
                                                                                <input type="hidden" id="reqServiceDetailTableId' . $vehicleCount . $i . '" name="reqServiceDetailTableId' . $vehicleCount . $i . '" value="' . $requestDetail->id . '">
                                                                                <input type="hidden" id="reqServiceDetailsNo' . $vehicleCount . $i . '" name="reqServiceDetailsNo' . $vehicleCount . $i . '" value="' . $requestDetail->request_details_no . '">
                                                                            </tr>';
                                                            $serviceVariantCodeArr[] = $requestDetail->service_veriant;
                                                            $servVarCodeReqNoArr[] = $requestDetail->service_veriant . ',' . $requestDetail->request_details_no;
                                                            $servVarReqDetailsNoArr[] = $requestDetail->request_details_no;
                                                            $i++;
                                                        }
                                                    }
                                                    if ($serviceStr) {
                                                        ?>
                                                        <div id="serviceTableDiv<?php echo $vehicleCount ?>">
                                                            <table class="table table-bordered custom-table">
                                                                <tr class="bg-info">
                                                                    <td colspan="3"><b>Service</b></td>
                                                                </tr>
                                                                <tr>
                                                                    <td width="50%"><b>Service Name</b></td>
                                                                    <td width="20%"><b>Quantity</b></td>
                                                                    <td width="10%"><b>Action</b></td>
                                                                </tr>
                                                                <?php echo $serviceStr ?>
                                                                <input type="hidden" id="serviceVarCodeStr<?php echo $vehicleCount ?>" value="<?php echo implode(',', $serviceVariantCodeArr) ?>">
                                                                <input type="hidden" id="takenServiceVarCount<?php echo $vehicleCount ?>" name="takenServiceVarCount<?php echo $vehicleCount ?>" value="<?php echo $i ?>">
                                                            </table>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?> 
                                                </div>
                                                <?php
                                                if ($status == config('constants.REQ_DRAFT_STATUS') || $status == config('constants.REQ_PENDING_STATUS') || $status == config('constants.REQ_REJECT_STATUS')) {
                                                    ?>
                                                    <button type="button" class="btn bg-blue btn-xs waves-effect" onclick="setShowServiceModal('<?php echo $vehicleCount ?>')" >Add Service</button>
                                                    <br><br>
                                                <?php } ?>
                                                <div id="vehicleProductDiv<?php echo $vehicleCount ?>">

                                                    <?php
                                                    $productStr = "";
                                                    $i = 1;
                                                    foreach ($data['requestDetails'] as $requestDetail) {
                                                        if ($requestedVehicle->vehicle == $requestDetail->vehicle && $requestDetail->request_type == config('constants.REQ_TYPE_PRODUCT')) {
                                                            $removeStr = "";
                                                            if ($status == config('constants.REQ_DRAFT_STATUS') || $status == config('constants.REQ_PENDING_STATUS') || $status == config('constants.REQ_REJECT_STATUS')) {
                                                                $removeStr = '<i class="fa fa-remove pointer text-danger" onclick="removeProduct(' . $vehicleCount . ',' . $i . ')"></i>';
                                                            }
                                                            $productStr .= '<tr id="productTakenTr' . $vehicleCount . $i . '">
                                                                                <td class="td-left">
                                                                                    <div class="form-group form-float" >
                                                                                        <div class="form-line">
                                                                                            <input type="text" class="form-control custom-form-control1" name="productName' . $vehicleCount . $i . '" id="productName' . $vehicleCount . $i . '" value="' . $requestDetail->product_display_name . '">
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="form-group form-float" >
                                                                                        <div class="form-line">
                                                                                            <input type="number" class="form-control custom-form-control" name="productQuantity' . $vehicleCount . $i . '" id="productQuantity' . $vehicleCount . $i . '" min="1" value="' . $requestDetail->quantity . '">
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="form-group form-float" >
                                                                                        <div class="form-line">
                                                                                            <input type="text" class="form-control custom-form-control" name="productUnitName' . $vehicleCount . $i . '" id="productUnitName' . $vehicleCount . $i . '" value="' . $requestDetail->unit_name . '" >
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>' . $removeStr . '</td>
                                                                                <input type="hidden" id="reqProductDetailTableId' . $vehicleCount . $i . '" name="reqProductDetailTableId' . $vehicleCount . $i . '" value="' . $requestDetail->id . '">
                                                                                <input type="hidden" id="reqProductDetailsNo' . $vehicleCount . $i . '" name="reqProductDetailsNo' . $vehicleCount . $i . '" value="' . $requestDetail->request_details_no . '">
                                                                            </tr>';
                                                            $i++;
                                                        }
                                                    }

                                                    if ($productStr) {
                                                        ?>
                                                        <div id="productTableDiv<?php echo $vehicleCount ?>">
                                                            <table class="table table-bordered custom-table" id="productTable<?php echo $vehicleCount ?>">
                                                                <tr class="bg-info">
                                                                    <td colspan="4"><b>Vehicle Parts</b></td>
                                                                </tr>
                                                                <tr>
                                                                    <td width="50%"><b>Parts Name</b></td>
                                                                    <td width="15%"><b>Quantity</b></td>
                                                                    <td width="25%"><b>Unit Name</b></td>
                                                                    <td width="10%"><b>Action</b></td>
                                                                </tr>
                                                                <?php echo $productStr ?>
                                                                <input type="hidden" id="takenProductCount<?php echo $vehicleCount ?>" name="takenProductCount<?php echo $vehicleCount ?>" value="<?php echo $i ?>">
                                                            </table>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                                <?php
                                                if ($status == config('constants.REQ_DRAFT_STATUS') || $status == config('constants.REQ_PENDING_STATUS') || $status == config('constants.REQ_REJECT_STATUS')) {
                                                    ?>
                                                    <button type="button" class="btn bg-blue btn-xs waves-effect" onclick="showProductTable('<?php echo $vehicleCount ?>')" >Add Vehicle Parts</button>
                                                <?php } ?>
                                            </div>
                                            <input type="hidden" name="vehicleId<?php echo $vehicleCount ?>" id="vehicleId<?php echo $vehicleCount ?>" value="<?php echo $requestedVehicle->vehicle ?>">
                                        </div>
                                    </div>
                                    <?php
                                    $serviceVarCodeStr = "";
                                    $servVarCodeReqNoStr = "";
                                    $servVarReqDetailsNoStr = "";
                                    if ($serviceStr) {
                                        $serviceVarCodeStr = implode(',', $serviceVariantCodeArr);
                                        $servVarCodeReqNoStr = implode('|', $servVarCodeReqNoArr);
                                        $servVarReqDetailsNoStr = implode(',', $servVarReqDetailsNoArr);
                                    }
                                    ?>
                                    <input type="hidden" id="perVehclSerVarCodeReqNoStr<?php echo $vehicleCount ?>" value="<?php echo $servVarCodeReqNoStr ?>">
                                    <input type="hidden" id="dbServiceVarCodeStr<?php echo $vehicleCount ?>" name="dbServiceVarCodeStr<?php echo $vehicleCount ?>" value="<?php echo $serviceVarCodeStr ?>">
                                    <input type="hidden" id="dbServiceVarReqDetailStr<?php echo $vehicleCount ?>" name="dbServiceVarReqDetailStr<?php echo $vehicleCount ?>" value="<?php echo $servVarReqDetailsNoStr ?>">
                                    <input type="hidden" id="vehicleIdForVehicleCount<?php echo $requestedVehicle->vehicle ?>" name="vehicleIdForVehicleCount<?php echo $requestedVehicle->vehicle ?>" value="<?php echo $vehicleCount ?>">
                                    <?php
                                    $vehicleCount++;
                                    $requestedVehicleArr[] = $requestedVehicle->vehicle;
                                }
                                $dbVehicleStr = "";
                                if ($requestedVehicleArr) {
                                    $dbVehicleStr = implode(',', $requestedVehicleArr);
                                }
                                ?>
                            </div>
                            <input type="hidden" name="requestNo" value="<?php echo $data['requestNo'] ?>">
                            <input type="hidden" name="saveStatusFlag" id="saveStatusFlag">
                            <input type="hidden" id="vehicleSerial" name="vehicleSerial">
                            <input type="hidden" name="vehicleCount" id="vehicleCount" value="<?php echo count($data['requestedVehicles']) ?>">
                            <input type="hidden" id="dbVehicleStr" name="dbVehicleStr" value="<?php echo $dbVehicleStr ?>">
                            <input type="hidden" name="takenVehicleStr" id="takenVehicleStr">
                            <?php
                            if ($status == config('constants.REQ_DRAFT_STATUS') || $status == config('constants.REQ_PENDING_STATUS') || $status == config('constants.REQ_REJECT_STATUS')) {
                                ?>
                                <button type="button" class="btn btn-default btn-sm waves-effect" data-toggle="modal" data-target="#vehicleModal">Add Vehicle</button>
                            <?php } ?>
                            <div class="form-group form-float m-t-30" >
                                <label class="form-label">Remarks </label>
                                <div class="form-line">
                                    <textarea class="form-control" name="remarks" ><?php echo $remarks ?></textarea>
                                </div>
                            </div>

                            <div class="form-group form-float m-t-30" >
                                <label class="form-label">Vroom Remarks </label>
                                <div class="form-line">
                                    <textarea class="form-control" readonly><?php echo $adminRemarks ?></textarea>
                                </div>
                            </div>    

                            <?php
                            //if ($status == REQ_REJECT_STATUS) {
                            if ($rejectReason) {
                                ?>
                                <div class="panel-group m-t-30">
                                    <div class="panel panel-danger">
                                        <div class="panel-heading">
                                            <div class="panel-title custom1-panel-title">
                                                Reject Reason
                                            </div>
                                        </div>
                                        <div class="panel-body custom1-panel-body">
                                            <?php echo $rejectReason; ?>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>

                            <input type="hidden" name="productDelteStr" id="productDelteStr">
                            <input type="hidden" name="updateDtTm" value="<?php echo $updateDtTm ?>">
                            <?php
                            if ($status == config('constants.REQ_DRAFT_STATUS') || $status == config('constants.REQ_PENDING_STATUS') || $status == config('constants.REQ_REJECT_STATUS')) {
                                ?>
                                <button type="button"  class="btn bg-blue btn-sm waves-effect m-t-30" onclick="editRequest(2)">Save As Draft</button>
                                <button type="button"  class="btn bg-blue btn-sm waves-effect m-t-30" onclick="editRequest(3)">Save & Send Request</button>
                                <?php
                            }
                            ?>

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

                                        @foreach ($data['vehicles'] as $vehicle)

                                            <tr>

                                                <td>
                                                    {{ $loop->iteration }}
                                                </td>

                                                <td class="td-left">

                                                    <a
                                                        target="_blank"
                                                        href="{{ url('client/Home/vehicleDashboard?vehicleId=' . $vehicle->vehicle_id) }}"
                                                    >
                                                        {{ $vehicle->registration_no }}
                                                    </a>

                                                </td>

                                                {{-- 
                                                <td class="td-left">
                                                    {{ $vehicle->registration_no }}
                                                </td>
                                                --}}

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

                                                    <i
                                                        class="material-icons pointer"
                                                        onclick="addVehicle({{ $loop->iteration }})"
                                                    >
                                                        arrow_drop_down_circle
                                                    </i>

                                                </td>

                                                <input
                                                    type="hidden"
                                                    id="vehicleIdModalHidden{{ $loop->iteration }}"
                                                    value="{{ $vehicle->vehicle_id }}"
                                                >

                                                <input
                                                    type="hidden"
                                                    id="vehicleRegModalHidden{{ $loop->iteration }}"
                                                    value="{{ $vehicle->registration_no }}"
                                                >

                                            </tr>

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
                                    <?php
                                    $serviceCount = 1;
                                    foreach ($data['distinctServices'] as $distinctService) {
                                        ?>
                                        <div class="panel panel1 panel-default">
                                            <div class="panel-heading custom-panel-heading" role="tab" id="headingOne">
                                                <p class="panel-title custom-panel-title1 p-t-0 p-b-0">
                                                    <a role="button" data-toggle="collapse" data-parent="#" href="#generalCollapseOne<?php echo $distinctService->service ?>" aria-expanded="true" aria-controls="generalCollapseOne<?php echo $distinctService->service ?>">
                                                        <i class="fa fa-tags"></i> <?php echo $distinctService->service_name ?>
                                                    </a>
                                                </p>
                                            </div>
                                            <div id="generalCollapseOne<?php echo $distinctService->service ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                                                <div class="panel-body">
                                                    <table class="table table-striped custom-table">
                                                        <?php
                                                        $serviceVarSerial = 1;
                                                        foreach ($data['serviceVariants'] as $serviceVariant) {
                                                            if ($serviceVariant->service == $distinctService->service) {
                                                                echo "<tr>";
                                                                echo "<td>$serviceVarSerial</td>";
                                                                echo "<td class='td-left' style='width:80%'>" . $serviceVariant->service_variant_name . "</td>";
                                                                echo "<td class='td-left'>";
                                                                echo "<input type='checkbox' name='serviceVarCheckBox$serviceCount' id='serviceVarCheckBox$serviceCount' class='filled-in chk-col-blue'>";
                                                                echo "<label for='serviceVarCheckBox$serviceCount' class='form-label' style='margin-bottom: -12px'>";
                                                                echo "</td>";
                                                                echo "<input type='hidden' id='serviceVariantCode$serviceCount' value='$serviceVariant->variant_code'>";
                                                                echo "<input type='hidden' id='serviceVariantName$serviceCount' value='$serviceVariant->service_variant_name'>";
                                                                $serviceVarSerial++;
                                                                $serviceCount++;
                                                                echo "</tr>";
                                                            }
                                                        }
                                                        ?>
                                                    </table>

                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                    <input type="hidden" id="serviceVariantCount" value="<?php echo $serviceCount ?>" >
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-link waves-effect" id="serviceModalSelectBtn" onclick="setAddService()">SELECT</button>
                                <button type="button" class="btn btn-link waves-effect" id="serviceModalCloseBtn" data-dismiss="modal">CLOSE</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ------------- ----------------- ----------------- -->
            </div>
        </div>
    </div>
</div>

@endsection
@push('scripts')
<script>
    var counter = "{{ count($data['requestedVehicles']) }}";
    counter++;

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
        var vehicleDiv = '<div class="panel panel-default">\n\
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
                                <br><br>\n\
                                <div id="vehicleProductDiv' + counter + '">\n\
                                </div>\n\
                                <button type="button" class="btn bg-blue btn-xs waves-effect" onclick="showProductTable(' + counter + ')" >Add Vehicle Parts</button>\n\
                            </div>\n\
                            <input type="hidden" name="vehicleId' + counter + '" id="vehicleId' + counter + '" value="' + vehicleId + '">\n\
                        </div>';
        newRow.after().html(vehicleDiv);
        newRow.appendTo("#vehicleGroupDiv");
        $('#vehicleCount').val(counter);
        counter++;
        $('#modalCloseBtn').click();
    }

    function showProductTable(vehicleSerial) {
        var takenProductCount = $("#takenProductCount" + vehicleSerial).val();
        if (typeof takenProductCount === "undefined") {
            var productTableStr = '<tr id="productTakenTr' + vehicleSerial + '1">\n\
                                            <td class="td-left"><div class="form-group form-float" >\n\
                                                <div class="form-line">\n\
                                                        <input type="text" class="form-control custom-form-control1" name="productName' + vehicleSerial + '1" id="productName' + vehicleSerial + '1">\n\
                                                    </div>\n\
                                                </div>\n\
                                            </td>\n\
                                            <td><div class="form-group form-float" >\n\
                                                    <div class="form-line">\n\
                                                        <input type="number" class="form-control custom-form-control" name="productQuantity' + vehicleSerial + '1" id="productQuantity' + vehicleSerial + '1" min="1" value="1">\n\
                                                    </div>\n\
                                                </div>\n\
                                            </td>\n\
                                            <td><div class="form-group form-float" >\n\
                                                    <div class="form-line">\n\
                                                        <input type="text" class="form-control custom-form-control" name="productUnitName' + vehicleSerial + '1" id="productUnitName' + vehicleSerial + '1" >\n\
                                                    </div>\n\
                                                </div>\n\
                                            </td>\n\
                                            <td><i class="fa fa-remove pointer text-danger" onclick="removeProduct(' + vehicleSerial + ',1)"></i></td>\n\
                                            <input type="hidden" id="reqProductDetailTableId' + vehicleSerial + '1" name="reqProductDetailTableId' + vehicleSerial + '1" value="0">\n\
                                            <input type="hidden" id="reqProductDetailsNo' + vehicleSerial + '1" name="reqProductDetailsNo' + vehicleSerial + '1" value="0">\n\
                                        </tr>';
            var newRow = $(document.createElement('div')).attr("id", 'productTableDiv' + vehicleSerial);
            var productTableDiv = '<table class="table table-bordered custom-table" id="productTable' + vehicleSerial + '">\n\
                                    <tr class="bg-info">\n\
                                        <td colspan="4"><b>Vehicle Parts</b></td>\n\
                                    </tr>\n\
                                    <tr>\n\
                                        <td width="50%"><b>Parts Name</b></td>\n\
                                        <td width="15%"><b>Quantity</b></td>\n\
                                        <td width="25%"><b>Unit Name</b></td>\n\
                                        <td width="10%"><b>Action</b></td>\n\
                                    </tr>\n\
                                    ' + productTableStr + '\n\
                                    <input type="hidden" id="takenProductCount' + vehicleSerial + '" name="takenProductCount' + vehicleSerial + '" value="1">\n\
                                </table>';
            newRow.after().html(productTableDiv);
            newRow.appendTo("#vehicleProductDiv" + vehicleSerial);
        } else {
            takenProductCount++;
            var newRow = $(document.createElement('tr')).attr("id", 'productTakenTr' + vehicleSerial + takenProductCount);
            var productTableRowStr = '<td class="td-left"><div class="form-group form-float"> \n\
                                                            <div class="form-line">\n\
                                                                <input type="text" class="form-control custom-form-control1" name="productName' + vehicleSerial + takenProductCount + '" id="productName' + vehicleSerial + takenProductCount + '">\n\
                                                            </div>\n\
                                                        </div>\n\
                                            </td>\n\
                                            <td><div class="form-group form-float" >\n\
                                                    <div class="form-line">\n\
                                                        <input type="number" class="form-control custom-form-control" name="productQuantity' + vehicleSerial + takenProductCount + '" id="productQuantity' + vehicleSerial + takenProductCount + '" min="1" value="1">\n\
                                                    </div>\n\
                                                </div>\n\
                                            </td>\n\
                                            <td><div class="form-group form-float" >\n\
                                                    <div class="form-line">\n\
                                                        <input type="text" class="form-control custom-form-control" name="productUnitName' + vehicleSerial + takenProductCount + '" id="productUnitName' + vehicleSerial + takenProductCount + '" >\n\
                                                    </div>\n\
                                                </div>\n\
                                            </td>\n\
                                            <td><i class="fa fa-remove pointer text-danger" onclick="removeProduct(' + vehicleSerial + "," + takenProductCount + ')"></i></td>\n\
                                            <input type="hidden" id="reqProductDetailsNo' + vehicleSerial + takenProductCount + '" name="reqProductDetailsNo' + vehicleSerial + takenProductCount + '" value="0">\n\
                                            <input type="hidden" id="reqProductDetailTableId' + vehicleSerial + takenProductCount + '" name="reqProductDetailTableId' + vehicleSerial + takenProductCount + '" value="0">';

            newRow.after().html(productTableRowStr);
            newRow.appendTo("#productTable" + vehicleSerial);
            $("#takenProductCount" + vehicleSerial).val(takenProductCount);
        }
    }

    function removeProduct(vehicleSerial, productSerial) {
        var idArr = new Array();
        idArr.push($('#reqProductDetailTableId' + vehicleSerial + productSerial).val());
        if ($('#productDelteStr').val() !== "") {
            idArr.push($('#productDelteStr').val());
        }
        $('#productDelteStr').val(idArr.join());
        $('#productTakenTr' + vehicleSerial + productSerial).remove();
        var tableRowCount = $("#productTable" + vehicleSerial + " tr").length;
        if (tableRowCount === 2) {
            $("#productTable" + vehicleSerial).remove();
        }
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
        var quantity;
        var takenServieVarCountFinal = $("#takenServiceVarCount" + vehicleSerial).val();
        if (typeof takenServieVarCountFinal === 'undefined') {
            takenServieVarCountFinal = 0;
        }
        var i = 1;
        for (var x = 1; x < serviceVariantCount; x++) {
            if ($("#serviceVarCheckBox" + x).is(':checked')) {
                serviceVariantCode = $("#serviceVariantCode" + x).val();
                serviceVariantName = $("#serviceVariantName" + x).val();
                quantity = 1;
                for (var j = 1; j < takenServieVarCountFinal; j++) {
                    if ($('#takenServiceVarCode' + vehicleSerial + j).val() === serviceVariantCode) {
                        quantity = $("#srviceQuantity" + vehicleSerial + j).val();
                        // console.log(quantity);
                    }
                }

                serviceTableStr += '<tr id="serviceTakenTd' + vehicleSerial + i + '">\n\
                                        <td class="td-left">' + serviceVariantName + '</td>\n\
                                        <td><div class="form-group form-float" >\n\
                                                <div class="form-line">\n\
                                                    <input type="number" class="form-control custom-form-control" name="srviceQuantity' + vehicleSerial + i + '" id="srviceQuantity' + vehicleSerial + i + '" min="1" value="' + quantity + '">\n\
                                                </div>\n\
                                            </div>\n\
                                        </td>\n\
                                        <td><i class="fa fa-remove pointer text-danger" onclick="removeService(' + vehicleSerial + "," + i + ')"></i></td>\n\
                                        <input type="hidden" id="reqServiceDetailTableId' + vehicleSerial + i + '" name="reqServiceDetailTableId' + vehicleSerial + i + '" value="0">\n\
                                        <input type="hidden" id="takenServiceVarCode' + vehicleSerial + i + '" name="takenServiceVarCode' + vehicleSerial + i + '" value="' + serviceVariantCode + '">\n\
                                        <input type="hidden" id="reqServiceDetailsNo' + vehicleSerial + i + '" name="reqServiceDetailsNo' + vehicleSerial + i + '" value="0">\n\
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
                                    <td width="20%"><b>Quantity</b></td>\n\
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

    function editRequest(flag) {
        var takenProductCount;
        var productName = "";
        var productQuantity = "";
        var productUnitName = "";
        var takenServiceVarCount;
        var srviceQuantity = "";
        var serviceProductFlag;
        var vehicleFlag = 0;
        var vehicleArr = new Array();
        var vehicleCount = $('#vehicleCount').val();
        for (var i = 1; i <= vehicleCount; i++) {
            var vehicleId = $('#vehicleId' + i).val();
            if (typeof vehicleId !== "undefined") {
                vehicleArr.push(vehicleId);
                serviceProductFlag = 0;
                vehicleFlag = 1;
                //---------- product check ------------//
                takenProductCount = $("#takenProductCount" + i).val();
                for (var j = 1; j <= takenProductCount; j++) {
                    productName = $("#productName" + i + j).val();
                    if (typeof productName !== 'undefined') {
                        serviceProductFlag = 1;
                        if ($.trim(productName) === "") {
                            sweetAlert('Parts Name is required...!');
                            return false;
                        }
                    }
                    productQuantity = $("#productQuantity" + i + j).val();
                    if (typeof productQuantity !== 'undefined') {
                        if ($.trim(productQuantity) === "") {
                            sweetAlert('Parts Quantity is required...!');
                            return false;
                        }
                        if (!$.isNumeric(productQuantity)) {
                            sweetAlert('Parts Quantity must be a numeric value...!');
                            return false;
                        }
                    }
                    productUnitName = $("#productUnitName" + i + j).val();
                    if (typeof productUnitName !== 'undefined') {
                        if ($.trim(productUnitName) === "") {
                            sweetAlert('Parts Unit Name is required...!');
                            return false;
                        }
                    }
                }
                //--------- service check ------------//
                var vehicleIdForVehicleCount = $("#vehicleIdForVehicleCount" + vehicleId).val();
                var perVehclSerVarCodeReqNoArr = new Array();
                if (typeof vehicleIdForVehicleCount !== 'undefined') {
                    var perVehclSerVarCodeReqNoStr = $("#perVehclSerVarCodeReqNoStr" + vehicleIdForVehicleCount).val();
                    perVehclSerVarCodeReqNoArr = perVehclSerVarCodeReqNoStr.split('|');
                }

                takenServiceVarCount = $("#takenServiceVarCount" + i).val();
                var takenServiceVarArr = new Array();
                for (var j = 1; j <= takenServiceVarCount; j++) {
                    var takenServiceVarCode = $("#takenServiceVarCode" + i + j).val();

                    if (typeof takenServiceVarCode !== 'undefined') {
                        for (var k = 0; k < perVehclSerVarCodeReqNoArr.length; k++) {
                            var arr = new Array();
                            arr = perVehclSerVarCodeReqNoArr[k].split(',');
                            if (arr[0] === takenServiceVarCode) {
                                $('#reqServiceDetailsNo' + i + j).val(arr[1]);
                                //console.log(arr[1]);
                            }
                        }
                        takenServiceVarArr.push(takenServiceVarCode);
                    }

                    srviceQuantity = $("#srviceQuantity" + i + j).val();
                    if (typeof srviceQuantity !== 'undefined') {
                        serviceProductFlag = 1;
                        if ($.trim(srviceQuantity) === "") {
                            sweetAlert('Service Quantity is required...!');
                            return false;
                        }
                        if (!$.isNumeric(srviceQuantity)) {
                            sweetAlert('Service Quantity must be a numeric value...!');
                            return false;
                        }
                    }
                }

                if (serviceProductFlag === 0) {
                    sweetAlert('Please take at least one service or vehicle part...!');
                    return false;
                }
            }
        }

        if (vehicleFlag == 0) {
            sweetAlert('Please take at least one vehicle...!');
            return false;
        }

        if ($.trim($('#lastsubmitDate').val()) === "") {
            sweetAlert('Please give Quotation Last Submit Date...!');
            return false;
        }

        $('#takenVehicleStr').val(vehicleArr.join());

        $("#saveStatusFlag").val(flag);
        $("#requestForm").submit();
    }
</script>
@endpush