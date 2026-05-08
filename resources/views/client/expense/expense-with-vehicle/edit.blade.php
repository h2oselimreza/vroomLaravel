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
    .custom-form-control{
        height: 20px;
        font-size: 12px;
        text-align: right;
        padding-right: 3px;
    }
    .custom-form-control1{
        height: 20px;
        font-size: 12px;
        text-align: left;
        padding-left: 3px;
    }
    .custom-form-controlCenter{
        height: 20px;
        font-size: 12px;
        text-align: center;
        padding-left: 3px;
    }
    .custom-form-border{
        border: 1px solid #ddd!important;
        font-size:13px!important;
    }
</style>

<div class="block-header">
    <h2>EDIT LIST</h2><br>
    <div class="breadcrumb breadcrumb-bg-blue-grey">
        <li><a href="/client/Home"> Home</a></li>
        <li><a href="#"> Expense</a></li>
        <li><a href="/client/expense/expense-with-vehicle"> Expense List</a></li>
        <li><a href="{{ route('client.expense.expense-with-vehicle.edit', $expenseNo) }}"> Edit Expense</a></li>
    </div>
</div>

<div class="row clearfix">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="body">
                <!-- Success Message -->
                @if(session('success'))
                    <div class="alert alert-success">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                        <strong>{{ session('success') }}</strong>
                    </div>
                @endif

                <!-- Error Message -->
                @if(session('error'))
                    <div class="alert alert-danger" role="alert">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                        <strong>Error!</strong> {{ session('error') }}
                    </div>
                @endif
                <?php
                $vendorTitle = '';
                $guestName = '';
                $guestMobile = '';
                foreach ($data['expenseSummary'] as $expSummary) {
                    $expenseTitle = $expSummary->expense_title;
                    $expenseDate = $expSummary->expense_date;
                    $expenseNo = $expSummary->expense_no;
                    //$totalAmount = $expSummary['total_amount'];
                    $updateDtTm = $expSummary->updated_dt_tm;
                    $vendorTitle = $expSummary->vendor_title;
                    $vendorCode = $expSummary->vendor;

                    if (!$vendorCode) {
                        $vendorTitle = "Guest";
                        $guestName = $expSummary['guest_name'];
                        $guestMobile = $expSummary['guest_mobile'];
                    }
                }
                ?>
                <form action="{{ route('client.expense.expense-with-vehicle.update', $expenseNo) }}" 
                method="POST" 
                enctype="multipart/form-data" 
                id="expenseForm">
                @csrf
                @method('PUT')
                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="form-group form-float" >
                                <div class="form-line">
                                    <input type="text" class="form-control" value="<?php echo $expenseNo ?>" readonly>
                                    <label class="form-label"> Expense ID </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="form-group form-float" >
                                <div class="form-line">
                                    <input type="text" class="form-control" name="expenseTitle" id="expenseTitle" value="<?php echo $expenseTitle ?>" >
                                    <label class="form-label"> Expense Title </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="form-group form-float" >
                                <div class="form-line">
                                    <input type="text" class="form-control dateInput" name="expenseDate" id="expenseDate" value="<?php echo $expenseDate ?>">
                                    <label class="form-label"> Expense Date </label>
                                </div>
                            </div>
                        </div>

                        <?php
                        if ( auth()->user()->customerEmployee->customer_type == config('constants.CORPORATE_CUST')) {
                            ?>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group form-float" >
                                    <div class="form-line">
                                        <select class="form-control" name="vendor"  id="vendor" onchange="toggleGuestDiv(this.value)">
                                            <option value="<?php echo $vendorCode ?>"><?php echo $vendorTitle ?></option>
                                            <option value="">Guest</option>
                                            <?php
                                            foreach ($data['vendors'] as $vendor) {
                                                echo "<option value='$vendor->vendor_code'>$vendor->title</option>";
                                            }
                                            ?>
                                        </select>

                                    </div>
                                    <label class="help-info"> Vendor </label>
                                </div>
                            </div>
                        <?php } ?>

                    </div>

                    <div class="row" id="guestDiv" <?php if ($vendorCode) { ?> style="display: none" <?php } ?>>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group form-float" >
                                <div class="form-line">
                                    <input type="text" class="form-control" name="guestName" id="guestName" value="<?php echo $guestName ?>">
                                    <label class="form-label">Guest Vendor Name</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group form-float" >
                                <div class="form-line">
                                    <input type="text" class="form-control" name="guestMobile" id="guestMobile" onchange="checkMobileNumber(this.value, this.id)" value="<?php echo $guestMobile ?>">
                                    <label class="form-label"> Guest Vendor Mobile Number</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="panel-group" id="vehicleGroupDiv">
                                <?php
                                $vehicleCount = 1;
                                foreach ($data['takenVehicles'] as $takenVehicle) {
                                    $milege = 0;
                                    ?>
                                    <div id="vehicleDiv<?php echo $vehicleCount ?>">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <div class="panel-title custom1-panel-title">
                                                    <div class="row p-l-20 p-r-20">
                                                        <div class="float-left">
                                                            <i class="fa fa-car"></i> <?php echo $takenVehicle->registration_no ?>
                                                        </div>
                                                        <div class="float-right">
                                                            <i class="fa fa-remove pointer text-danger" onclick="removeVehicle('<?php echo $vehicleCount ?>')"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="panel-body custom1-panel-body">
                                                <div id="vehicleExpenseDiv{{ $vehicleCount }}">

                                                @php
                                                    $expenseStr = '';
                                                    $i = 1;
                                                    $totalAmount = 0;
                                                    $milege = 0;
                                                @endphp

                                                @foreach($data['expenseDetails'] as $expenseDeails)

                                                    @if($takenVehicle->vehicle == $expenseDeails->vehicle)

                                                        @php

                                                            $expenseStr .= '
                                                                <tr id="expenseTakenTr' . $vehicleCount . $i . '">

                                                                    <td class="td-left pointer"
                                                                        id="expenseHeadTd' . $vehicleCount . $i . '"
                                                                        onclick="showExpHeadModal(' . $vehicleCount . ',' . $i . ')">

                                                                        ' . $expenseDeails->expense_head_name . '

                                                                    </td>

                                                                    <input type="hidden"
                                                                        name="expenseHeadCode' . $vehicleCount . $i . '"
                                                                        id="expenseHeadCode' . $vehicleCount . $i . '"
                                                                        value="' . $expenseDeails->expense_head . '">

                                                                    <td>
                                                                        <div class="form-group form-float">
                                                                            <div class="form-line">

                                                                                <input type="text"
                                                                                    class="form-control custom-form-controlCenter"
                                                                                    onkeyup="calculateGrandTotal(' . $vehicleCount . ',' . $i . ')"
                                                                                    onchange="calculateGrandTotal(' . $vehicleCount . ',' . $i . ')"
                                                                                    name="quantity' . $vehicleCount . $i . '"
                                                                                    id="quantity' . $vehicleCount . $i . '"
                                                                                    value="' . $expenseDeails->quantity . '">

                                                                            </div>
                                                                        </div>
                                                                    </td>

                                                                    <td>
                                                                        <div class="form-group form-float">
                                                                            <div class="form-line">

                                                                                <input type="text"
                                                                                    class="form-control custom-form-control1"
                                                                                    name="unitName' . $vehicleCount . $i . '"
                                                                                    id="unitName' . $vehicleCount . $i . '"
                                                                                    value="' . $expenseDeails->unit_name . '"
                                                                                    readonly>

                                                                            </div>
                                                                        </div>
                                                                    </td>

                                                                    <td>
                                                                        <div class="form-group form-float">
                                                                            <div class="form-line">

                                                                                <input type="text"
                                                                                    class="form-control custom-form-control"
                                                                                    onkeyup="calculateGrandTotal(' . $vehicleCount . ',' . $i . ')"
                                                                                    onchange="calculateGrandTotal(' . $vehicleCount . ',' . $i . ')"
                                                                                    name="unitPrice' . $vehicleCount . $i . '"
                                                                                    id="unitPrice' . $vehicleCount . $i . '"
                                                                                    value="' . $expenseDeails->unit_price . '">

                                                                            </div>
                                                                        </div>
                                                                    </td>

                                                                    <td>
                                                                        <div class="form-group form-float">
                                                                            <div class="form-line">

                                                                                <input type="text"
                                                                                    class="form-control custom-form-control"
                                                                                    onkeyup="calculateGrandTotal(' . $vehicleCount . ',' . $i . ')"
                                                                                    onchange="calculateGrandTotal(' . $vehicleCount . ',' . $i . ')"
                                                                                    name="adjust' . $vehicleCount . $i . '"
                                                                                    id="adjust' . $vehicleCount . $i . '"
                                                                                    value="' . $expenseDeails->adjust . '">

                                                                            </div>
                                                                        </div>
                                                                    </td>

                                                                    <td>
                                                                        <div class="form-group form-float">
                                                                            <div class="form-line">

                                                                                <input type="text"
                                                                                    class="form-control custom-form-control"
                                                                                    onkeyup="calculateGrandTotal(' . $vehicleCount . ',' . $i . ')"
                                                                                    onchange="calculateGrandTotal(' . $vehicleCount . ',' . $i . ')"
                                                                                    name="amount' . $vehicleCount . $i . '"
                                                                                    id="amount' . $vehicleCount . $i . '"
                                                                                    value="' . $expenseDeails->amount . '"
                                                                                    readonly>

                                                                            </div>
                                                                        </div>
                                                                    </td>

                                                                    <td>
                                                                        <div class="form-group form-float">
                                                                            <div class="form-line">

                                                                                <input type="text"
                                                                                    class="form-control custom-form-control1"
                                                                                    name="remarks' . $vehicleCount . $i . '"
                                                                                    id="remarks' . $vehicleCount . $i . '"
                                                                                    value="' . $expenseDeails->remarks . '">

                                                                            </div>
                                                                        </div>
                                                                    </td>

                                                                    <td>
                                                                        <i class="fa fa-remove pointer text-danger"
                                                                        onclick="removeExpense(' . $vehicleCount . ',' . $i . ')"></i>
                                                                    </td>

                                                                    <input type="hidden"
                                                                        id="detailTableId' . $vehicleCount . $i . '"
                                                                        name="detailTableId' . $vehicleCount . $i . '"
                                                                        value="' . $expenseDeails->id . '">

                                                                </tr>
                                                            ';

                                                            $i++;

                                                            $totalAmount += $expenseDeails->amount;

                                                            $milege = $expenseDeails->odometer_mileage;

                                                        @endphp

                                                    @endif

                                                @endforeach

                                                @if($expenseStr)

                                                    <div id="expenseTableDiv{{ $vehicleCount }}">

                                                        <table class="table table-bordered custom-table"
                                                            id="expenseTable{{ $vehicleCount }}">

                                                            <tr>
                                                                <td width="15%"><b>Expense Head</b></td>
                                                                <td width="10%"><b>Quantity</b></td>
                                                                <td width="10%"><b>Unit Name</b></td>
                                                                <td width="10%"><b>Price Per Unit</b></td>
                                                                <td width="10%">
                                                                    <b>Adjust<small> (+/-)</small></b>
                                                                </td>
                                                                <td width="10%"><b>Amount (BDT)</b></td>
                                                                <td width="25%"><b>Remarks</b></td>
                                                                <td width="10%"><b>Action</b></td>
                                                            </tr>

                                                            {!! $expenseStr !!}

                                                            <input type="hidden"
                                                                id="takenExpenseCount{{ $vehicleCount }}"
                                                                name="takenExpenseCount{{ $vehicleCount }}"
                                                                value="{{ $i }}">

                                                        </table>

                                                    </div>

                                                @endif

                                            </div>
                                                <button type="button" class="btn bg-blue btn-xs waves-effect" onclick="showExpenseTable('<?php echo $vehicleCount ?>')" >Add Expense</button>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group p-t-10">
                                                            <div class="font-13"><b>Odometer display mileage (KM)</b></div>
                                                            <input type="number" class="form-control custom-form-border" name="mileage<?php echo $vehicleCount ?>" id="mileage<?php echo $vehicleCount ?>" value="<?php echo $milege ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <input type="hidden" name="vehicleId<?php echo $vehicleCount ?>" id="vehicleId<?php echo $vehicleCount ?>" value="<?php echo $takenVehicle->vehicle ?>">
                                        </div>
                                    </div>

                                    <?php
                                    $vehicleCount++;
                                }
                                ?>
                            </div>
                            <input type="hidden" name="vehicleCount" id="vehicleCount" value="<?php echo $vehicleCount ?>">
                            <input type="hidden" id="vehicleSerial">
                            <input type="hidden" id="takenExpenseSerial">
                            <input type="hidden" name="expenseNo" value="<?php echo $expenseNo ?>">
                            <input type="hidden" name="updateDtTm" value="<?php echo $updateDtTm ?>">


                            <input type="hidden" id="deleteVehicleStr" name="deleteVehicleStr">
                            <input type="hidden" id="deleteHeadStr" name="deleteHeadStr">
                            <input type="hidden" id="deleteFileStr" name="deleteFileStr">


                            <button type="button" class="btn btn-default btn-sm waves-effect" data-toggle="modal" data-target="#vehicleModal">Add Vehicle</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="file" class="form-control" name="expenseFile[]" id='expenseFile' onchange='checkFile(this, this.id);' multiple/>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="text-right">
                                <b>Total Expense:</b> <span id="totalAmount"><?php echo number_format($totalAmount, 2) ?></span> BDT
                            </div>
                        </div>
                    </div>
                </form>

                <div class="row">
                    <hr>
                    <div class="text-center">
                        <h4><b>Attached Files</b></h4>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="p-l-15 p-r-15 table-responsive">
                            <table class="table table-bordered table-hover custom-table dataTable">
                                <thead>
                                    <tr class="bg-info">
                                        <th>SL</th>
                                        <th>File Name</th>
                                        <th>Show</th>
                                        <th>Remove</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                   @php
                                        $serial = 1;
                                    @endphp

                                    @foreach($data['expenseFiles'] as $expensFile)

                                        <tr id="fileTr{{ $serial }}">

                                            <td>{{ $serial }}</td>

                                            <td class="td-left">
                                                {{ $expensFile->original_name }}
                                            </td>

                                            <td class="td-center">
                                                <a target="_blank"
                                                href="{{ asset('assets/client/files/expense/' . $expensFile->file_name) }}">
                                                    Show
                                                </a>
                                            </td>

                                            <td class="td-center">
                                                <i class="fa fa-remove text-danger pointer"
                                                onclick="deleteFile({{ $expensFile->id }}, {{ $serial }})">
                                                </i>
                                            </td>

                                        </tr>

                                        @php
                                            $serial++;
                                        @endphp

                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>	
                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <button type="button"  class="btn bg-blue btn-sm waves-effect" onclick="editExpense()">Save</button>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="created-updated">
                            <div class="float-left">
                                <b>Created By: </b><?php echo get_create_update_by_name($data['expenseSummary'][0]->created_by) ?>
                                <br><b>Created Date Time: </b><?php echo get_date_time_format($data['expenseSummary'][0]->created_dt_tm) ?>

                            </div>
                            <div class="float-right">
                                <b>Updated By: </b><?php echo get_create_update_by_name($data['expenseSummary'][0]->updated_by) ?>
                                <br><b>Updated Date Time: </b><?php echo get_date_time_format($data['expenseSummary'][0]->updated_dt_tm) ?>

                            </div>
                        </div>
                    </div>
                </div>

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

                                        @php
                                            $vehicleSerial = 1;
                                        @endphp

                                        @foreach($data['vehicles'] as $vehicle)

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

                                                <td class="td-left">
                                                    {{ $vehicle->vehicle_group_name }}
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

                <!-- --------------- expense head modal -------------------- -->
                <button type="button" class="btn btn-default hidden" data-toggle="modal" data-target="#expenseModal" id="expenseModalBtn"></button>
                <div class="modal fade" id="expenseModal" tabindex="-1" role="dialog">
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
                                                <th>SL</th>
                                                <th>Expense Category</th>
                                                <th>Expense Head</th>
                                                <th>Unit Name</th>
                                                <th>Unit Price</th>
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
                                            @php
                                                $count = 1;
                                            @endphp
                                            @foreach($data['costHeads'] as $costHead)

                                                <tr>

                                                    <td>{{ $count }}</td>

                                                    <td class="td-left">
                                                        {{ $costHead->category_name }}
                                                    </td>

                                                    <td class="td-left">
                                                        {{ $costHead->cost_head }}
                                                    </td>

                                                    <td class="td-center">
                                                        {{ $costHead->unit_name }}
                                                    </td>

                                                    <td class="td-right">
                                                        {{ $costHead->unit_price }}
                                                    </td>

                                                    <td>
                                                        <i class="material-icons pointer"
                                                        onclick="addExpenseHead({{ $count }})">
                                                            arrow_drop_down_circle
                                                        </i>
                                                    </td>

                                                    <input type="hidden"
                                                        id="costHeadCodeHidden{{ $count }}"
                                                        value="{{ $costHead->cost_head_code }}">

                                                    <input type="hidden"
                                                        id="costHeadNameHidden{{ $count }}"
                                                        value="{{ $costHead->cost_head }}">

                                                    <input type="hidden"
                                                        id="costUnitNameHidden{{ $count }}"
                                                        value="{{ $costHead->unit_name }}">

                                                    @php
                                                        $unitPriceMaster = ($costHead->unit_price == '0.00') ? '' : $costHead->unit_price;
                                                    @endphp

                                                    <input type="hidden"
                                                        id="costUnitPriceHidden{{ $count }}"
                                                        value="{{ $unitPriceMaster }}">

                                                </tr>

                                                @php
                                                    $count++;
                                                @endphp
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-link waves-effect" id="expenseModalCloseBtn" data-dismiss="modal">CLOSE</button>
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
    var counter = <?php echo count($data['takenVehicles']) ?>;
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
                                <div id="vehicleExpenseDiv' + counter + '">\n\
                                </div>\n\
                                <button type="button" class="btn bg-blue btn-xs waves-effect" onclick="showExpenseTable(' + counter + ')" >Add Expense</button>\n\
                                <div class="row">\n\
                                    <div class="col-md-4">\n\
                                        <div class="form-group p-t-10">\n\
                                            <div class="font-13"><b>Odometer display mileage (KM)</b></div>\n\
                                            <input type="number" class="form-control custom-form-border" name="mileage' + counter + '" id="mileage' + counter + '" >\n\
                                        </div>\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                            <input type="hidden" name="vehicleId' + counter + '" id="vehicleId' + counter + '" value="' + vehicleId + '">\n\
                        </div>';
        newRow.after().html(vehicleDiv);
        newRow.appendTo("#vehicleGroupDiv");
        $('#vehicleCount').val(counter);
        counter++;
        $('#modalCloseBtn').click();
    }

    function showExpenseTable(vehicleSerial) {
        var takenExpenseCount = $("#takenExpenseCount" + vehicleSerial).val();
        if (typeof takenExpenseCount === "undefined") {
            var expenseTableStr = '<tr id="expenseTakenTr' + vehicleSerial + '1">\n\
                                            <td class="td-left pointer" id="expenseHeadTd' + vehicleSerial + '1" onclick="showExpHeadModal(' + vehicleSerial + ',1)">\n\
                                                <small class="text-muted"><i>Show Head</i></small>\n\
                                            </td>\n\
                                            <input type="hidden" name="expenseHeadCode' + vehicleSerial + '1" id="expenseHeadCode' + vehicleSerial + '1">\n\
                                            <td><div class="form-group form-float" >\n\
                                                    <div class="form-line">\n\
                                                        <input type="text" class="form-control custom-form-controlCenter" onkeyup="calculateGrandTotal(' + vehicleSerial + ',1)" onchange="calculateGrandTotal(' + vehicleSerial + ',1)" name="quantity' + vehicleSerial + '1" id="quantity' + vehicleSerial + '1" >\n\
                                                    </div>\n\
                                                </div>\n\
                                            </td>\n\
                                            <td><div class="form-group form-float" >\n\
                                                    <div class="form-line">\n\
                                                        <input type="text" class="form-control custom-form-control1" name="unitName' + vehicleSerial + '1" id="unitName' + vehicleSerial + '1" readonly>\n\
                                                    </div>\n\
                                                </div>\n\
                                            </td>\n\
                                            <td><div class="form-group form-float" >\n\
                                                    <div class="form-line">\n\
                                                        <input type="text" class="form-control custom-form-control" onkeyup="calculateGrandTotal(' + vehicleSerial + ',1)" onchange="calculateGrandTotal(' + vehicleSerial + ',1)" name="unitPrice' + vehicleSerial + '1" id="unitPrice' + vehicleSerial + '1" >\n\
                                                    </div>\n\
                                                </div>\n\
                                            </td>\n\
                                            <td><div class="form-group form-float" >\n\
                                                    <div class="form-line">\n\
                                                        <input type="text" class="form-control custom-form-control" onkeyup="calculateGrandTotal(' + vehicleSerial + ',1)" onchange="calculateGrandTotal(' + vehicleSerial + ',1)" name="adjust' + vehicleSerial + '1" id="adjust' + vehicleSerial + '1" >\n\
                                                    </div>\n\
                                                </div>\n\
                                            </td>\n\
                                            <td><div class="form-group form-float" >\n\
                                                    <div class="form-line">\n\
                                                        <input type="text" class="form-control custom-form-control" onkeyup="calculateGrandTotal(' + vehicleSerial + ',1)" onchange="calculateGrandTotal(' + vehicleSerial + ',1)" name="amount' + vehicleSerial + '1" id="amount' + vehicleSerial + '1" readonly>\n\
                                                    </div>\n\
                                                </div>\n\
                                            </td>\n\
                                            <td><div class="form-group form-float" >\n\
                                                    <div class="form-line">\n\
                                                        <input type="text" class="form-control custom-form-control1" max="200" name="remarks' + vehicleSerial + '1" id="remarks' + vehicleSerial + '1" >\n\
                                                    </div>\n\
                                                </div>\n\
                                            </td>\n\
                                            <td><i class="fa fa-remove pointer text-danger" onclick="removeExpense(' + vehicleSerial + ',1)"></i></td>\n\
                                            <input type="hidden" name="detailTableId' + vehicleSerial + '1" value="0">\n\
                                        </tr>';
            var newRow = $(document.createElement('div')).attr("id", 'expenseTableDiv' + vehicleSerial);
            var expenseTableDiv = '<table class="table table-bordered custom-table" id="expenseTable' + vehicleSerial + '">\n\
                                    <tr>\n\
                                        <td width="15%"><b>Expense Head</b></td>\n\
                                        <td width="10%"><b>Quantity</b></td>\n\
                                        <td width="10%"><b>Unit Name</b></td>\n\
                                        <td width="10%"><b>Price Per Unit</b></td>\n\
                                        <td width="10%"><b>Adjust<small> (+/-)</small></b></td>\n\
                                        <td width="10%"><b>Amount (BDT)</b></td>\n\
                                        <td width="25%"><b>Remarks</b></td>\n\
                                        <td width="10%"><b>Action</b></td>\n\
                                    </tr>\n\
                                    ' + expenseTableStr + '\n\
                                    <input type="hidden" id="takenExpenseCount' + vehicleSerial + '" name="takenExpenseCount' + vehicleSerial + '" value="1">\n\
                                </table>';
            newRow.after().html(expenseTableDiv);
            newRow.appendTo("#vehicleExpenseDiv" + vehicleSerial);
        } else {
            takenExpenseCount++;
            var newRow = $(document.createElement('tr')).attr("id", 'expenseTakenTr' + vehicleSerial + takenExpenseCount);
            var expenseTableRowStr = '<td class="td-left pointer" id="expenseHeadTd' + vehicleSerial + takenExpenseCount + '" onclick="showExpHeadModal(' + vehicleSerial + "," + takenExpenseCount + ')">\n\
                                            <small class="text-muted"><i>Show Head</i></small>\n\
                                           </td>\n\
                                            <input type="hidden" name="expenseHeadCode' + vehicleSerial + takenExpenseCount + '" id="expenseHeadCode' + vehicleSerial + takenExpenseCount + '">\n\
                                            <td><div class="form-group form-float" >\n\
                                                    <div class="form-line">\n\
                                                        <input type="text" class="form-control custom-form-controlCenter" onkeyup="calculateGrandTotal(' + vehicleSerial + "," + takenExpenseCount + ')" onchange="calculateGrandTotal(' + vehicleSerial + "," + takenExpenseCount + ')" name="quantity' + vehicleSerial + takenExpenseCount + '" id="quantity' + vehicleSerial + takenExpenseCount + '">\n\
                                                    </div>\n\
                                                </div>\n\
                                            </td>\n\
                                            <td><div class="form-group form-float" >\n\
                                                    <div class="form-line">\n\
                                                        <input type="text" class="form-control custom-form-control1"  name="unitName' + vehicleSerial + takenExpenseCount + '" id="unitName' + vehicleSerial + takenExpenseCount + '" readonly>\n\
                                                    </div>\n\
                                                </div>\n\
                                            </td>\n\
                                            <td><div class="form-group form-float" >\n\
                                                    <div class="form-line">\n\
                                                        <input type="text" class="form-control custom-form-control" onkeyup="calculateGrandTotal(' + vehicleSerial + "," + takenExpenseCount + ')" onchange="calculateGrandTotal(' + vehicleSerial + "," + takenExpenseCount + ')" name="unitPrice' + vehicleSerial + takenExpenseCount + '" id="unitPrice' + vehicleSerial + takenExpenseCount + '">\n\
                                                    </div>\n\
                                                </div>\n\
                                            </td>\n\
                                            <td><div class="form-group form-float" >\n\
                                                    <div class="form-line">\n\
                                                        <input type="text" class="form-control custom-form-control" onkeyup="calculateGrandTotal(' + vehicleSerial + "," + takenExpenseCount + ')" onchange="calculateGrandTotal(' + vehicleSerial + "," + takenExpenseCount + ')" name="adjust' + vehicleSerial + takenExpenseCount + '" id="adjust' + vehicleSerial + takenExpenseCount + '">\n\
                                                    </div>\n\
                                                </div>\n\
                                            </td>\n\
                                            <td><div class="form-group form-float" >\n\
                                                    <div class="form-line">\n\
                                                        <input type="text" class="form-control custom-form-control" onkeyup="calculateGrandTotal(' + vehicleSerial + "," + takenExpenseCount + ')" onchange="calculateGrandTotal(' + vehicleSerial + "," + takenExpenseCount + ')" name="amount' + vehicleSerial + takenExpenseCount + '" id="amount' + vehicleSerial + takenExpenseCount + '" readonly>\n\
                                                    </div>\n\
                                                </div>\n\
                                            </td>\n\
                                            <td><div class="form-group form-float" >\n\
                                                    <div class="form-line">\n\
                                                        <input type="text" class="form-control custom-form-control1" max="200" name="remarks' + vehicleSerial + takenExpenseCount + '" id="remarks' + vehicleSerial + takenExpenseCount + '" >\n\
                                                    </div>\n\
                                                </div>\n\
                                            </td>\n\
                                            <td><i class="fa fa-remove pointer text-danger" onclick="removeExpense(' + vehicleSerial + "," + takenExpenseCount + ')"></i></td>\n\
                                            <input type="hidden" name="detailTableId' + vehicleSerial + takenExpenseCount + '" value="0">';
            newRow.after().html(expenseTableRowStr);
            newRow.appendTo("#expenseTable" + vehicleSerial);
            $("#takenExpenseCount" + vehicleSerial).val(takenExpenseCount);
        }
    }

    function removeExpense(vehicleSerial, takenExpenseCount) {
        var idArr = new Array();
        idArr.push($('#detailTableId' + vehicleSerial + takenExpenseCount).val());
        if ($('#deleteHeadStr').val() !== "") {
            idArr.push($('#deleteHeadStr').val());
        }
        $('#deleteHeadStr').val(idArr.join());


        $('#expenseTakenTr' + vehicleSerial + takenExpenseCount).remove();
        var tableRowCount = $("#expenseTable" + vehicleSerial + " tr").length;
        if (tableRowCount === 1) {
            $("#expenseTable" + vehicleSerial).remove();
        }
        grandTotal();
    }

    function calculateGrandTotal(vehicleSerial, takenExpenseCount) {

        var quantity = $('#quantity' + vehicleSerial + takenExpenseCount).val();
        var unitPrice = $('#unitPrice' + vehicleSerial + takenExpenseCount).val();
        var adjustInput = $('#adjust' + vehicleSerial + takenExpenseCount).val();

        if (!$.isNumeric(quantity)) {
            quantity = 0;
            $('#quantity' + vehicleSerial + takenExpenseCount).val('');
        }

        if (!$.isNumeric(unitPrice)) {
            unitPrice = 0;
            $('#unitPrice' + vehicleSerial + takenExpenseCount).val('');
        }

        var adjust = parseFloat(adjustInput);
        if (!(adjustInput === '-' || adjustInput === '+')) {
            if (!$.isNumeric(adjustInput)) {
                $('#adjust' + vehicleSerial + takenExpenseCount).val('');
                adjust = 0;
            }
        } else {
            adjust = 0;
        }

        var amount = (parseFloat(quantity) * parseFloat(unitPrice)) + parseFloat(adjust);
        if (!$.isNumeric(amount)) {
            $('#amount' + vehicleSerial + takenExpenseCount).val('');
        } else {
            $('#amount' + vehicleSerial + takenExpenseCount).val(amount);
        }
        grandTotal();
    }

    function grandTotal() {
        var totalAmount = 0;
        for (var i = 1; i < counter; i++) {
            var vehicleId = $('#vehicleId' + i).val();
            if (typeof vehicleId !== 'undefined') {

                var takenExpenseCount = $('#takenExpenseCount' + i).val();
                for (var j = 1; j <= takenExpenseCount; j++) {
                    var amount = $('#amount' + i + j).val();
                    if (typeof amount !== 'undefined' && amount !== "") {
                        totalAmount += parseFloat(amount);
                    }
                }
            }
        }
        totalAmount = totalAmount.toFixed(2);
        if (!$.isNumeric(totalAmount)) {
            totalAmount = '0.00';
        }
        $('#totalAmount').text(totalAmount);
    }

    function showExpHeadModal(vehicleSerial, takenExpenseCount) {
        $('#expenseModalBtn').click();
        $('#vehicleSerial').val(vehicleSerial);
        $('#takenExpenseSerial').val(takenExpenseCount);
    }

    function addExpenseHead(expenseCount) {
        var vehicleSerial = $('#vehicleSerial').val();
        var takenExpenseSerial = $('#takenExpenseSerial').val();
        var costHeadCode = $('#costHeadCodeHidden' + expenseCount).val();
        var costHeadName = $('#costHeadNameHidden' + expenseCount).val();
        var costUnitName = $('#costUnitNameHidden' + expenseCount).val();
        var costUnitPrice = $('#costUnitPriceHidden' + expenseCount).val();
        var takenExpenseCount = $("#takenExpenseCount" + vehicleSerial).val();
        for (var i = 1; i < takenExpenseCount; i++) {
            if (typeof ($('#expenseHeadCode' + vehicleSerial + i).val()) !== 'undefined') {
                if ($('#expenseHeadCode' + vehicleSerial + i).val() === costHeadCode) {
                    sweetAlert("You have already select this head for this vehicle...!");
                    return false;
                }
            }
        }


        $('#expenseHeadTd' + vehicleSerial + takenExpenseSerial).text(costHeadName);
        $('#expenseHeadCode' + vehicleSerial + takenExpenseSerial).val(costHeadCode);
        $('#unitName' + vehicleSerial + takenExpenseSerial).val(costUnitName);
        $('#unitPrice' + vehicleSerial + takenExpenseSerial).val(costUnitPrice);
        $('#quantity' + vehicleSerial + takenExpenseSerial).val('');
        $('#amount' + vehicleSerial + takenExpenseSerial).val('');
        $('#adjust' + vehicleSerial + takenExpenseSerial).val('');
        $('#expenseModalCloseBtn').click();

        grandTotal();
    }


    function removeVehicle(vehicleSerial) {
        var idArr = new Array();
        idArr.push($('#vehicleId' + vehicleSerial).val());
        if ($('#deleteVehicleStr').val() !== "") {
            idArr.push($('#deleteVehicleStr').val());
        }
        $('#deleteVehicleStr').val(idArr.join());

        $('#vehicleDiv' + vehicleSerial).remove();
        grandTotal();
    }
    function checkFile() {
        var fp = $("#expenseFile");
        var lg = fp[0].files.length; // get length
        var items = fp[0].files;
        var fileSize = 0;
        var fileExtension = ['jpeg', 'jpg', 'png', 'txt', 'doc', 'docx', 'pdf'];
        if (lg > 0) {
            for (var i = 0; i < lg; i++) {
                fileSize = fileSize + items[i].size;
                if ($.inArray(items[i].name.split('.').pop().toLowerCase(), fileExtension) === -1) {
                    sweetAlert("Only 'jpeg','jpg','png','txt','doc','docx','pdf' formats are allowed...!");
                    $('#expenseFile').val('');
                    return false;
                }
            }
            if (fileSize > 2097152) {
                sweetAlert('File size must not be more than 2 MB...!');
                $('#expenseFile').val('');
            }
        }
    }

    function deleteFile(fileId, serial) {
        var idArr = new Array();
        idArr.push(fileId);
        if ($('#deleteFileStr').val() !== "") {
            idArr.push($('#deleteFileStr').val());
        }
        $('#deleteFileStr').val(idArr.join());

        $('#fileTr' + serial).remove();
    }

    function editExpense() {
        var vehicleFlag = 0;
        for (var i = 1; i < counter; i++) {
            var vehicleId = $('#vehicleId' + i).val();
            if (typeof vehicleId !== 'undefined') {
                vehicleFlag = 1;
                var takenExpenseCount = $('#takenExpenseCount' + i).val();
                var expenseFlag = 0;
                for (var j = 1; j <= takenExpenseCount; j++) {
                    var amount = $('#amount' + i + j).val();
                    if (typeof amount !== 'undefined') {
                        expenseFlag = 1;
                        var expenseHeadCode = $('#expenseHeadCode' + i + j).val();
                        var quantity = $('#quantity' + i + j).val();
                        var unitName = $('#unitName' + i + j).val();
                        var unitPrice = $('#unitPrice' + i + j).val();
                        if (amount === "" || expenseHeadCode === "" || quantity === "" || unitName === "" || unitPrice === "") {
                            if ($('#remarks' + i + j).val().length > 200) {
                                sweetAlert('Remarks max length is 200 characters...!');
                                return false;
                            }
                            sweetAlert('Expense Head, Quantity, Unit Name and Price Per Unit are required...!');
                            return false;
                        } else {
                            if (parseFloat(quantity) <= 0 || parseFloat(unitPrice) <= 0) {
                                sweetAlert('Quantity and Price Per Unit must be greater than zero...!');
                                return false;
                            }
                        }
                    }
                }
            }
        }
        if (vehicleFlag === 0) {
            sweetAlert('Please select at least one vehicle...!');
            return false;
        }

        if (expenseFlag === 0) {
            sweetAlert('Please select at least one expense of taken vehicles...!');
            return false;
        }

        if ($.trim($('#expenseTitle').val()) === "" || $.trim($('#expenseDate').val()) === "") {
            sweetAlert('Expense Title and Expense Date is required...!');
            return false;
        }

        var vendor = $('#vendor').val();
        if (vendor === '') {
            var guestName = $('#guestName').val();
            if (guestName === '') {
                sweetAlert('Guest Vendor Name is required...!');
                return false;
            }
        }

        $('#expenseForm').submit();
    }


    function toggleGuestDiv(vendor) {
        $('guestName').val('');
        $('guestMobile').val('');
        if (vendor === '') {
            $("#guestDiv").show("fast");
        } else {
            $("#guestDiv").hide("fast");
        }
    }


</script>
@endpush
