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
    <h2>SHOW GENERAL EXPENSE</h2><br>
    <div class="breadcrumb breadcrumb-bg-blue-grey">
        <li><a href="/client/Home"> Home</a></li>
        <li><a href="#"> Expense</a></li>
        <li><a href="/client/expense/expense-without-vehicle"> Expense List</a></li>
        <li><a href="{{ route('client.expense.expense-without-vehicle.show', $expenseNo) }}"> Show General Expense</a></li>
    </div>
</div>

<div class="row clearfix">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="body">
                <?php
                foreach ($expenseSummary as $expSummary) {
                    $expenseTitle = $expSummary->expense_title;
                    $expenseDate = $expSummary->expense_date;
                    $expenseNo = $expSummary->expense_no;
                    $vendorTitle = $expSummary->vendor_title . ' (' . $expSummary->vendor . ')';
                    $vendorCode = $expSummary->vendor;
                    $vendorMobile = "";
                    $vendorType = '';
                    if (!$vendorCode) {
                        $vendorType = "Guest";
                        $vendorTitle = $expSummary->guest_name;
                        $vendorMobile = $expSummary->guest_mobile;
                    }
                }
                ?>

                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <table border="0" cellpadding="0" cellspacing="0" align="center" width="100%">
                            <tr class="table-td-info">
                                <td width="30%" align="left" class="content-table-td"><b>Expense ID</b></td>
                                <td width="2%" align="center">:</td>
                                <td width="66%" align="left">
                                    <?php echo $expenseNo ?>
                                </td>
                            </tr>
                            <tr class="table-td-info">
                                <td align="left" class="content-table-td"><b>Expense Title</b></td>
                                <td align="center">:</td>
                                <td align="left">
                                    <?php echo $expenseTitle ?>
                                </td>
                            </tr>
                            <tr class="table-td-info">
                                <td align="left" class="content-table-td"><b>Expense Date</b></td>
                                <td align="center">:</td>
                                <td align="left">
                                    <?php echo $expenseDate ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <?php
                    if (auth()->user()->customerEmployee->customer_type == config('constants.CORPORATE_CUST')) {
                        ?>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <table border="0" cellpadding="0" cellspacing="0" align="center" width="100%">
                                <tr class="table-td-info">
                                    <td width="30%" align="left" class="content-table-td"><b><?php echo $vendorType ?> Vendor Title</b></td>
                                    <td width="2%" align="center">:</td>
                                    <td width="66%" align="left">
                                        <?php echo $vendorTitle ?>
                                    </td>
                                </tr>
                                <tr class="table-td-info">
                                    <td align="left" class="content-table-td"><b>Vendor Mobile</b></td>
                                    <td align="center">:</td>
                                    <td align="left">
                                        <?php echo $vendorMobile ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    <?php } ?>

                </div>


                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="panel-group" id="vehicleGroupDiv">

                            @foreach ($takenVehicles as $takenVehicle)
                                @php
                                    $milege = 0;
                                @endphp

                                <div>
                                    <div class="panel panel-default">

                                        <div class="panel-heading">
                                            <div class="panel-title custom1-panel-title">
                                                <div class="row p-l-20 p-r-20">

                                                    <div class="float-left p-l-15 p-t-10 p-b-10">
                                                        <i class="fa fa-car"></i>
                                                        {{ $takenVehicle->registration_no }}
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="panel-body custom1-panel-body">

                                            <div>

                                                @php
                                                    $expenseStr = '';
                                                    $i = 1;
                                                    $totalAmount = 0;
                                                @endphp

                                                @foreach ($expenseDetails as $expenseDeails)

                                                    @if ($takenVehicle->vehicle == $expenseDeails->vehicle)

                                                        @php

                                                            $expenseStr .= '
                                                                <tr>
                                                                    <td class="td-left">' . $expenseDeails->expense_head_name . '</td>
                                                                    <td>' . $expenseDeails->quantity . '</td>
                                                                    <td>' . $expenseDeails->unit_name . '</td>
                                                                    <td>' . $expenseDeails->unit_price . '</td>
                                                                    <td>' . $expenseDeails->adjust . '</td>
                                                                    <td>' . $expenseDeails->amount . '</td>
                                                                    <td>' . $expenseDeails->remarks . '</td>
                                                                </tr>
                                                        ';

                                                            $i++;

                                                            $totalAmount += $expenseDeails->amount;

                                                            $milege = $expenseDeails->odometer_mileage;

                                                        @endphp

                                                    @endif

                                                @endforeach

                                                @if ($expenseStr)

                                                    <div>

                                                        <table class="table table-bordered custom-table">

                                                            <tr>
                                                                <td width="15%">
                                                                    <b>Expense Head</b>
                                                                </td>

                                                                <td width="10%">
                                                                    <b>Quantity</b>
                                                                </td>

                                                                <td width="10%">
                                                                    <b>Unit Name</b>
                                                                </td>

                                                                <td width="10%">
                                                                    <b>Price Per Unit</b>
                                                                </td>

                                                                <td width="10%">
                                                                    <b>Adjust<small> (+/-)</small></b>
                                                                </td>

                                                                <td width="10%">
                                                                    <b>Amount (BDT)</b>
                                                                </td>

                                                                <td width="25%">
                                                                    <b>Remarks</b>
                                                                </td>
                                                            </tr>

                                                            {!! $expenseStr !!}

                                                        </table>

                                                    </div>

                                                @endif

                                            </div>

                                            <div class="row">

                                                <div class="col-md-4">

                                                    <div class="form-group p-t-10">

                                                        <div class="font-13">
                                                            <b>Odometer display mileage (KM): </b>
                                                            {{ $milege }}
                                                        </div>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>
                                </div>

                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="text-right">
                            <b>Total Expense:</b> <span id="totalAmount"><?php echo number_format($totalAmount, 2) ?></span> BDT
                        </div>
                    </div>
                </div>


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

                                @foreach ($expenseFiles as $expensFile)

                                    <tr id="fileTr{{ $serial }}">

                                        <td>
                                            {{ $serial }}
                                        </td>

                                        <td class="td-left">
                                            {{ $expensFile->original_name }}
                                        </td>

                                        <td class="td-center">
                                            <a 
                                                target="_blank"
                                                href="{{ asset('assets/client/files/expense/' . $expensFile->file_name) }}"
                                            >
                                                Show
                                            </a>
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
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="created-updated">
                            <div class="float-left">
                                

                            </div>
                            <div class="float-right">
                                

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    
@endsection