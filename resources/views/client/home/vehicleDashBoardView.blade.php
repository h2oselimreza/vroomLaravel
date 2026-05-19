@extends('client.layouts.app')
@section('content')
<style>
    .table-td-info
    {	
        background:#FFFFFF;
        font-size:12px;
        font-family:Verdana, Geneva, sans-serif;    
        font-weight:normal;
        padding-left:7px;
        padding-top:2px;
        padding-bottom:2px;
    }
    .highcharts-credits{
        display:none;
    }
</style>

<div class="block-header">
    <h2>VEHICLE DASHBOARD</h2><br>
    <div class="breadcrumb breadcrumb-bg-blue-grey">
        <li><a href="#"> Home</a></li>
        <li><a href="{{ route('client.report.vehicle-dashboard') }}?vehicleId=<?php echo $vehicleId ?>"> Vehicle Dashboard</a></li>
    </div>
</div>
<div class="row clearfix">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="text-center font-17 p-b-15">
            <b> <?php echo $vehicleInfo->registration_no ?></b>
        </div>
    </div>

    <?php
    $col = '6';

    if ( auth()->user()->customerEmployee->customer_type ==  config('constants.CORPORATE_CUST') ) {
        $col = '4';
    }
    ?>

    <!-- ------------ about vehicle ------------ -->
    <div class="col-lg-<?php echo $col ?> col-md-<?php echo $col ?> col-sm-6 col-xs-12">
        <div class="card" style="min-height: 220px;">
            <div class="header bg-teal">
                <h2>About Vehicle</h2>
            </div>
            <div class="body vehicle-card-body custom-scrollber">
                <table border="0" cellpadding="0" cellspacing="0" align="center" width="100%">
                    <tr class="table-td-info">
                        <td width="39%" align="left" class="content-table-td"><b>Vehicle Type</b></td>
                        <td width="1%" align="center">:</td>
                        <td width="60%" align="left" class="p-l-5"><?php echo $vehicleInfo->vehicle_type_name ?></td>
                    </tr>
                    <tr class="table-td-info">
                        <td align="left" class="content-table-td"><b>Brand</b></td>
                        <td align="center">:</td>
                        <td align="left" class="p-l-5"><?php echo $vehicleInfo->brand_name ?></td>
                    </tr>
                    <tr class="table-td-info">
                        <td align="left" class="content-table-td"><b>Brand Model</b></td>
                        <td align="center">:</td>
                        <td align="left" class="p-l-5"><?php echo $vehicleInfo->brand_model_name ?></td>
                    </tr>
                    <tr class="table-td-info">
                        <td align="left" class="content-table-td"><b>Vehicle CC</b></td>
                        <td align="center">:</td>
                        <td align="left" class="p-l-5"><?php echo $vehicleInfo->vehicle_cc ?></td>
                    </tr>
                    <tr class="table-td-info">
                        <td align="left" class="content-table-td"><b>Vehicle Color</b></td>
                        <td align="center">:</td>
                        <td align="left" class="p-l-5"><?php echo $vehicleInfo->color_name ?></td>
                    </tr>
                    <tr class="table-td-info">
                        <td align="left" class="content-table-td"><b>Engine No</b></td>
                        <td align="center">:</td>
                        <td align="left" class="p-l-5"><?php echo $vehicleInfo->engine_no ?></td>
                    </tr>
                    <tr class="table-td-info">
                        <td align="left" class="content-table-td"><b>Chassis No</b></td>
                        <td align="center">:</td>
                        <td align="left" class="p-l-5"><?php echo $vehicleInfo->chasis_no ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- --------------- Driver ---------------- -->
    <div class="col-lg-<?php echo $col ?> col-md-<?php echo $col ?> col-sm-6 col-xs-12">
        <div class="card" style="min-height: 220px;">
            <div class="header bg-cyan">
                <h2>Driver</h2>
            </div>
            <div class="body vehicle-card-body custom-scrollber">
                <table border="0" cellpadding="0" cellspacing="0" align="center" width="100%">
                    <tr class="table-td-info">
                        <td width="39%" align="left" class="content-table-td"><b>Name</b></td>
                        <td width="1%" align="center">:</td>
                        <td width="60%" align="left" class="p-l-5"><?php echo $vehicleInfo->driver_name ?></td>
                    </tr>
                    <tr class="table-td-info">
                        <td align="left" class="content-table-td"><b>Mobile</b></td>
                        <td align="center">:</td>
                        <td align="left" class="p-l-5"><?php echo $vehicleInfo->driver_mobile ?></td>
                    </tr>
                    <tr class="table-td-info">
                        <td align="left" class="content-table-td"><b>ID</b></td>
                        <td align="center">:</td>
                        <td align="left" class="p-l-5"><?php echo $vehicleInfo->driver_id ?></td>
                    </tr>

                    <tr class="table-td-info">
                        <td width="39%" align="left" class="content-table-td"><b>License No</b></td>
                        <td width="1%" align="center">:</td>
                        <td width="60%" align="left" class="p-l-5"><?php echo $vehicleInfo->driving_license_no ?></td>
                    </tr>
                    <tr class="table-td-info">
                        <td align="left" class="content-table-td"><b>Expiry Date</b></td>
                        <td align="center">:</td>
                        <td align="left" class="p-l-5"><?php echo get_date_format1($vehicleInfo->driving_license_expiry_date) ?></td>
                    </tr>

                </table>
            </div>
        </div>
    </div>

    <?php
    if (auth()->user()->customerEmployee->customer_type == config('constants.CORPORATE_CUST')) {
        ?>
        
        <!-- -------------- pull person ------------ -->
        <div class="col-lg-<?php echo $col ?> col-md-<?php echo $col ?> col-sm-6 col-xs-12">
            <div class="card" style="min-height: 220px;">
                <div class="header bg-teal">
                    <span style="font-size:16px">Used By <small><i>(<?php echo get_vehicle_assign_type_name($vehicleInfo->assign_type) ?>)</i></small></span>
                </div>
                <div class="body vehicle-card-body custom-scrollber">

                    <?php
                    if ($vehicleInfo->assign_type == 'vacant') {
                        ?>
                        <table border="0" cellpadding="0" cellspacing="0" align="center" width="100%">
                            <tr class="table-td-info">
                                <td width="49%" align="left" class="content-table-td"><b>Current Location</b></td>
                                <td width="1%" align="center">:</td>
                                <td width="50%" align="left" class="p-l-5"><?php echo $vehicleInfo->pull_current_location ?></td>
                            </tr>
                            <tr class="table-td-info">
                                <td width="49%" align="left" class="content-table-td"><b>Last Receive Date</b></td>
                                <td width="1%" align="center">:</td>
                                <td width="50%" align="left" class="p-l-5"><?php echo get_date_format1($vehicleInfo->pull_receive_date) ?></td>
                            </tr>
                        </table>
                        <?php
                    } else {
                        ?>

                        <table border="0" cellpadding="0" cellspacing="0" align="center" width="100%">
                            <tr class="table-td-info">
                                <td width="39%" align="left" class="content-table-td"><b>Name</b></td>
                                <td width="1%" align="center">:</td>
                                <td width="60%" align="left" class="p-l-5"><?php echo $vehicleInfo->pull_emp_name ?></td>
                            </tr>
                            <tr class="table-td-info">
                                <td align="left" class="content-table-td"><b>ID No</b></td>
                                <td align="center">:</td>
                                <td align="left" class="p-l-5"><?php echo $vehicleInfo->pull_id_no ?></td>
                            </tr>
                            <tr class="table-td-info">
                                <td align="left" class="content-table-td"><b>Department</b></td>
                                <td align="center">:</td>
                                <td align="left" class="p-l-5"><?php echo $vehicleInfo->pull_department ?></td>
                            </tr>

                            <tr class="table-td-info">
                                <td width="39%" align="left" class="content-table-td"><b>Designation</b></td>
                                <td width="1%" align="center">:</td>
                                <td width="60%" align="left" class="p-l-5"><?php echo $vehicleInfo->pull_designation ?></td>
                            </tr>
                            <tr class="table-td-info">
                                <td align="left" class="content-table-td"><b>Receive Date</b></td>
                                <td align="center">:</td>
                                <td align="left" class="p-l-5"><?php echo get_date_format1($vehicleInfo->pull_receive_date) ?></td>
                            </tr>
                            <tr class="table-td-info">
                                <td align="left" class="content-table-td"><b>Route</b></td>
                                <td align="center">:</td>
                                <td align="left" class="p-l-5"><?php echo $vehicleInfo->pull_route ?></td>
                            </tr>

                        </table>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    <?php }
    ?>
    <!-- -------------- Fitenss  ------------ -->
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="card">
            <div class="header bg-light-green">
                <h2>Fitness</h2>
            </div>
            <div class="body vehicle-card-body custom-scrollber">
                <table border="0" cellpadding="0" cellspacing="0" align="center" width="100%">
                    <tr class="table-td-info">
                        <td width="39%" align="left" class="content-table-td"><b>Issue Date</b></td>
                        <td width="1%" align="center">:</td>
                        <td width="60%" align="left" class="p-l-5"><?php echo $vehicleInfo->fitness_issue_date ?></td>
                    </tr>
                    <tr class="table-td-info">
                        <td align="left" class="content-table-td"><b>Renew Fee(BDT)</b></td>
                        <td align="center">:</td>
                        <td align="left" class="p-l-5"><?php echo $vehicleInfo->fitness_renew_fee ?></td>
                    </tr>
                    <tr class="table-td-info">
                        <td align="left" class="content-table-td"><b>Valid From</b></td>
                        <td align="center">:</td>
                        <td align="left" class="p-l-5"><?php echo get_date_format1($vehicleInfo->fitness_validity_from_date) ?></td>
                    </tr>

                    <tr class="table-td-info">
                        <td width="39%" align="left" class="content-table-td"><b>Valid To</b></td>
                        <td width="1%" align="center">:</td>
                        <td width="60%" align="left" class="p-l-5"><?php echo get_date_format1($vehicleInfo->fitness_validity_todate) ?></td>
                    </tr>


                </table>
            </div>
        </div>
    </div>


    <!-- -------------- Tax Token  ------------ -->
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="card">
            <div class="header bg-blue-grey">
                <h2>Tax Token</h2>
            </div>
            <div class="body vehicle-card-body custom-scrollber">
                <table border="0" cellpadding="0" cellspacing="0" align="center" width="100%">
                    <tr class="table-td-info">
                        <td width="39%" align="left" class="content-table-td"><b>Issue Date</b></td>
                        <td width="1%" align="center">:</td>
                        <td width="60%" align="left" class="p-l-5"><?php echo $vehicleInfo->tax_fee_issue_date ?></td>
                    </tr>
                    <tr class="table-td-info">
                        <td align="left" class="content-table-td"><b>Tax Fee(BDT)</b></td>
                        <td align="center">:</td>
                        <td align="left" class="p-l-5"><?php echo $vehicleInfo->tax_fee ?></td>
                    </tr>
                    <tr class="table-td-info">
                        <td align="left" class="content-table-td"><b>Period From</b></td>
                        <td align="center">:</td>
                        <td align="left" class="p-l-5"><?php echo get_date_format1($vehicleInfo->tax_period_from_date) ?></td>
                    </tr>

                    <tr class="table-td-info">
                        <td width="39%" align="left" class="content-table-td"><b>Period To</b></td>
                        <td width="1%" align="center">:</td>
                        <td width="60%" align="left" class="p-l-5"><?php echo get_date_format1($vehicleInfo->tax_period_to_date) ?></td>
                    </tr>


                </table>
            </div>
        </div>
    </div>

    <!-- -------------- Insurance  ------------ -->
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="card">
            <div class="header bg-blue-grey">
                <h2>Insurance</h2>
            </div>
            <div class="body vehicle-card-body custom-scrollber">
                <table border="0" cellpadding="0" cellspacing="0" align="center" width="100%">
                    <tr class="table-td-info">
                        <td width="39%" align="left" class="content-table-td"><b>Issue Date</b></td>
                        <td width="1%" align="center">:</td>
                        <td width="60%" align="left" class="p-l-5"><?php echo get_date_format1($vehicleInfo->insurance_issue_date) ?></td>
                    </tr>
                    <tr class="table-td-info">
                        <td align="left" class="content-table-td"><b>Premium Amount(BDT)</b></td>
                        <td align="center">:</td>
                        <td align="left" class="p-l-5"><?php echo $vehicleInfo->insurance_pre_amount ?></td>
                    </tr>
                    <tr class="table-td-info">
                        <td align="left" class="content-table-td"><b>Insurance Nature</b></td>
                        <td align="center">:</td>
                        <td align="left" class="p-l-5"><?php echo $vehicleInfo->insurance_nature ?></td>
                    </tr>

                    <tr class="table-td-info">
                        <td width="39%" align="left" class="content-table-td"><b>Company Name</b></td>
                        <td width="1%" align="center">:</td>
                        <td width="60%" align="left" class="p-l-5"><?php echo $vehicleInfo->insurance_company ?></td>
                    </tr>

                    <tr class="table-td-info">
                        <td width="39%" align="left" class="content-table-td"><b>Contact Person</b></td>
                        <td width="1%" align="center">:</td>
                        <td width="60%" align="left" class="p-l-5"><?php echo $vehicleInfo->insurance_contact_person ?></td>
                    </tr>


                    <tr class="table-td-info">
                        <td width="39%" align="left" class="content-table-td"><b>Mobile No</b></td>
                        <td width="1%" align="center">:</td>
                        <td width="60%" align="left" class="p-l-5"><?php echo $vehicleInfo->insurance_mobile ?></td>
                    </tr>


                    <tr class="table-td-info">
                        <td width="39%" align="left" class="content-table-td"><b>Valid From</b></td>
                        <td width="1%" align="center">:</td>
                        <td width="60%" align="left" class="p-l-5"><?php echo get_date_format1($vehicleInfo->insurance_form_date) ?></td>
                    </tr>

                    <tr class="table-td-info">
                        <td width="39%" align="left" class="content-table-td"><b>Valid To</b></td>
                        <td width="1%" align="center">:</td>
                        <td width="60%" align="left" class="p-l-5"><?php echo get_date_format1($vehicleInfo->insurance_to_date) ?></td>
                    </tr>


                </table>
            </div>
        </div>
    </div>

    <!-- -------------- Route Permit  ------------ -->
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="card">
            <div class="header bg-light-green">
                <h2>Route Permit</h2>
            </div>
            <div class="body vehicle-card-body custom-scrollber">
                <table border="0" cellpadding="0" cellspacing="0" align="center" width="100%">
                    <tr class="table-td-info">
                        <td width="39%" align="left" class="content-table-td"><b>Issue Date</b></td>
                        <td width="1%" align="center">:</td>
                        <td width="60%" align="left" class="p-l-5"><?php echo get_date_format1($vehicleInfo->route_issue_date) ?></td>
                    </tr>
                    <tr class="table-td-info">
                        <td align="left" class="content-table-td"><b>Route Permit No</b></td>
                        <td align="center">:</td>
                        <td align="left" class="p-l-5"><?php echo $vehicleInfo->permit_no ?></td>
                    </tr>
                    <tr class="table-td-info">
                        <td align="left" class="content-table-td"><b>Route Permit Fee(BDT)</b></td>
                        <td align="center">:</td>
                        <td align="left" class="p-l-5"><?php echo $vehicleInfo->permit_fee ?></td>
                    </tr>

                    <tr class="table-td-info">
                        <td width="39%" align="left" class="content-table-td"><b>Route/Area</b></td>
                        <td width="1%" align="center">:</td>
                        <td width="60%" align="left" class="p-l-5"><?php echo $vehicleInfo->route_area ?></td>
                    </tr>

                    <tr class="table-td-info">
                        <td width="39%" align="left" class="content-table-td"><b>No Of Tyre</b></td>
                        <td width="1%" align="center">:</td>
                        <td width="60%" align="left" class="p-l-5"><?php echo $vehicleInfo->tyre_number ?></td>
                    </tr>





                    <tr class="table-td-info">
                        <td width="39%" align="left" class="content-table-td"><b>Permit Period From</b></td>
                        <td width="1%" align="center">:</td>
                        <td width="60%" align="left" class="p-l-5"><?php echo get_date_format1($vehicleInfo->route_form_date) ?></td>
                    </tr>

                    <tr class="table-td-info">
                        <td width="39%" align="left" class="content-table-td"><b>Permit Period To</b></td>
                        <td width="1%" align="center">:</td>
                        <td width="60%" align="left" class="p-l-5"><?php echo get_date_format1($vehicleInfo->route_to_date) ?></td>
                    </tr>


                </table>
            </div>
        </div>
    </div>
</div>

<?php
if ($expensecheckFlag) {
    ?>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <div class="card" >
                <div class="table-responsive">
                    <div class="text-center p-t-10 p-l-20 p-b-10 font-17">
                        Month Wise Total Expense of This Vehicle
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <div class="form-line">
                                <select class="form-control" id="yearMonthWise" onchange="changeYear(this.value, 'month')">
                                    <option value="<?php echo $monthWiseYear ?>"><?php echo $monthWiseYear ?></option>
                                    <?php
                                    foreach ($yearLists as $yearList) {
                                        if ($monthWiseYear != $yearList->year) {
                                            echo "<option value='$yearList->year'>$yearList->year</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div id="costMonthChatDiv" >
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <div class="card" >
                <div class="table-responsive">
                    <div class="text-center p-t-10 p-l-20 p-b-10 font-17">
                        Category Wise Expense of This Vehicle
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <div class="form-line">
                                <select class="form-control" id="yearCategoryWise" onchange="changeYear(this.value, 'category')">
                                    <option value="<?php echo $categoryWiseYear ?>"><?php echo $categoryWiseYear ?></option>
                                    <?php
                                    foreach ($yearLists as $yearList) {
                                        if ($categoryWiseYear != $yearList->year) {
                                            echo "<option value='$yearList->year'>$yearList->year</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div id="costCategoryChatDiv" >
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="/client/Home/" method="post" id="yearForm">
        <input type="hidden" name="categoryWiseYear" id="categoryWiseYear">
        <input type="hidden" name="monthWiseYear" id="monthWiseYear">
    </form>

<?php
}?>
    
@endsection
@push('scripts')
<script src="{{ asset('assets/select_client/js/highChart.js') }}"></script>
<script language="JavaScript">
    $(document).ready(function () {
        Highcharts.chart('costCategoryChatDiv', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: ''
            },
            tooltip: {
                headerFormat: '<span style="font-size:11px">Expense Category</span><br>',
                pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}</b><br/>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '{point.percentage:.1f} % of Total'
                    },
                    showInLegend: true
                }
            },
            series: [{
                    name: '',
                    colorByPoint: true,
                    data: <?php echo $costCategotyGraph ?>
                }]
        });


        Highcharts.chart('costMonthChatDiv', {
            chart: {
                type: 'column'
            },
            title: {
                text: ''
            },
            xAxis: {
                categories: [
                    'Jan',
                    'Feb',
                    'Mar',
                    'Apr',
                    'May',
                    'Jun',
                    'Jul',
                    'Aug',
                    'Sep',
                    'Oct',
                    'Nov',
                    'Dec'
                ],
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Expense (BDT)'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.y:.1f} BDT</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [{
                    name: 'Month',
                    data: <?php echo $costMonthGraph ?>
                }]
        });

    });

    function changeYear(year, flag) {
        if (flag === 'month') {
            $('#monthWiseYear').val(year);
            $('#categoryWiseYear').val('');
        } else if (flag === 'category') {
            $('#categoryWiseYear').val(year);
            $('#monthWiseYear').val('');
        }
        $('#yearForm').submit();
    }
</script>
@endpush