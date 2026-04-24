@extends('layouts.app')

@section('content')

<div class="header dashboard_from">
    <h1 class="page-title">Show Home Service</h1>
    <ul class="breadcrumb">
        <li><a href="#">Home</a> / </li>
        <li><a href="#">Home Service</a> / </li>
        <li><a href="/admin/home/home-service-list">Home Service List</a> / </li>
        <li><a href="/admin/home/home-service-list/<?php echo $appointmentNo ?>/<?php echo $companyCode ?>">Show Home Service</a></li>
    </ul>
</div>
<div class="main-content">
    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Error Message -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel panel-default">

                <form action="{{ route('admin.update-home-service') }}" id="submitForm" method="post">
                    @csrf
                    <?php
                    if ($appointmentSummary->status == config('constants.APPOINTMENT_PENDING')  || $appointmentSummary->status == config('constants.APPOINTMENT_REJECT') || $appointmentSummary->status == config('constants.APPOINTMENT_COMPLETE') || $appointmentSummary->status == config('constants.APPOINTMENT_START')) {
                    ?>
                        <table class="mt-3" border="0" cellpadding="0" cellspacing="0" align="center" width="100%">
                            <tr class="table-td-info">
                                <td width="20%" align="left" class="content-table-td"><b>Home Service No</b></td>
                                <td width="2%" align="center">:</td>
                                <td width="28%" align="left" class="content-table-td"><?php echo $appointmentNo ?></td>

                                <td width="10%" align="left" class="content-table-td"><b>Name</b></td>
                                <td width="2%" align="center">:</td>
                                <td width="38%" align="left" class="content-table-td"><?php echo $appointmentSummary->name ?></td>
                            </tr>
                            <tr class="table-td-info">
                                <td align="left" class="content-table-td"><b>Mobile No</b></td>
                                <td align="center">:</td>
                                <td align="left" class="content-table-td"><?php echo $appointmentSummary->mobile ?></td>

                                <td align="left" class="content-table-td"><b>Address</b></td>
                                <td align="center">:</td>
                                <td align="left" class="content-table-td"><?php echo $appointmentSummary->address ?></td>
                            </tr>

                            <tr class="table-td-info">
                                <td align="left" class="content-table-td"><b>Preferred Date</b></td>
                                <td align="center">:</td>
                                <td align="left" class="content-table-td"><?php echo get_date_format1($appointmentSummary->service_date) ?></td>

                                <td align="left" class="content-table-td"><b>Preferred Time</b></td>
                                <td align="center">:</td>
                                <td align="left" class="content-table-td"><?php echo get_time_format($appointmentSummary->service_time) ?></td>
                            </tr>
                        </table>
                        <table class="mb-3" border="0" cellpadding="0" cellspacing="0" align="center" width="100%">
                            <tr class="table-td-info">
                                <td width="20%" align="left" class="content-table-td"><b>Client Remarks</b></td>
                                <td width="2%" align="center">:</td>
                                <td width="77%" align="left" class="content-table-td"><?php echo $appointmentSummary->remarks ?></td>
                            </tr>
                        </table>
                    <?php
                    } else if ($appointmentSummary->status == config('constants.APPOINTMENT_PROCCESSING') || $appointmentSummary->status == config('constants.APPOINTMENT_ACCEPT')) {
                    ?>
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label class="form-label"> Home Service No</label>
                                    <input type="text" class="form-control" value="<?php echo $appointmentNo ?>" readonly="">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label class="form-label"> Preferred Date & Time</label>
                                    <input type="text" class="form-control" value="<?php echo get_date_format1($appointmentSummary->service_date) . " " . get_time_format($appointmentSummary->service_time) ?>" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label class="form-label"> Name</label>
                                    <input type="text" class="form-control" name="name" id="name" value="<?php echo $appointmentSummary->name ?>">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label class="form-label"> Mobile</label>
                                    <input type="text" class="form-control" name="mobile" id="mobile" onchange="checkMobileNumber(this.value, this.id)" value="<?php echo $appointmentSummary->mobile ?>">

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label class="form-label"> Address</label>
                                    <input type="text" class="form-control" name="address" id="address" value="<?php echo $appointmentSummary->address ?>">
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <label data-error="wrong" data-success="right" for="comment">Remarks</label>
                                <textarea class="form-control" style="resize:none" rows="5" readonly><?php echo $appointmentSummary->remarks ?></textarea>
                            </div>
                        </div>
                        <br>
                        <input type="hidden" name="appointmentNo" value="<?php echo $appointmentNo ?>">
                        <input type="hidden" name="companyCode" value="<?php echo $companyCode ?>">
                    <?php
                    }
                    ?>
                    <div id="vehicleServiceDiv">
                        <div id="serviceTableDiv">
                            <table class="table table-bordered custom-table">
                                <tr class="bg-info">
                                    <th colspan="5"><b>Service</b></th>
                                </tr>
                                <tr>
                                    <th width="50%"><b>Service Name</b></th>
                                    <th width="20%"><b>Price</b></th>
                                    <th width="10%"><b>Quantity</b></th>
                                    <th width="10%"><b>Amount (BDT)</b></th>
                                    <?php
                                    if ($appointmentSummary->status == config('constants.APPOINTMENT_PROCCESSING') || $appointmentSummary->status == config('constants.APPOINTMENT_ACCEPT')) {
                                    ?>
                                        <th width="10%"><b>Action</b></th>
                                    <?php
                                    }
                                    ?>
                                </tr>
                                <?php
                                $i = 1;
                                $serviceVarCodeArr = array();
                                foreach ($homeServiceDetails as $homeServiceDetail) {
                                    $serviceVarCodeArr[] = $homeServiceDetail['service_variant'];
                                    echo '<tr id="serviceTakenTd' . $i . '">
                                    <td class="td-left">' . $homeServiceDetail['service_variant_name'] . '</td>
                                    <td class="td-left">BDT ' . $homeServiceDetail['unit_price'] . ' Per ' . $homeServiceDetail['unit_name'] . '</td>';
                                    if ($appointmentSummary->status == config('constants.APPOINTMENT_PROCCESSING') || $appointmentSummary->status == config('constants.APPOINTMENT_ACCEPT')) {
                                        echo '<td><input class="form-control custom-form-control" type = "number" min = "0" value = "' . $homeServiceDetail['quantity'] . '" onkeyup = "calculateGrandTotal(' . $i . ')" onchange = "calculateGrandTotal(' . $i . ')" name = "quantity' . $i . '" id = "quantity' . $i . '"></td>';
                                    } else {
                                        echo '<td class="td-center">' . $homeServiceDetail['quantity'] . '</td>';
                                    }

                                    echo '<td class = "td-right" id = "amountTd' . $i . '">' . $homeServiceDetail['total_amount'] . '</td>';
                                    if ($appointmentSummary->status == config('constants.APPOINTMENT_PROCCESSING') || $appointmentSummary->status == config('constants.APPOINTMENT_ACCEPT')) {
                                        echo '<td class = "td-center">
                                        <i class = "fa fa-remove pointer text-danger" onclick = "removeService(' . $i . ')"></i>
                                        <input type = "hidden" id = "takenServiceVarCode' . $i . '" name = "takenServiceVarCode' . $i . '" value = "' . $homeServiceDetail['service_variant'] . '">
                                        <input type = "hidden" id = "takenServiceUnitPrice' . $i . '" name = "takenServiceUnitPrice' . $i . '" value = "' . $homeServiceDetail['unit_price'] . '">
                                        <input type = "hidden" id = "amount' . $i . '" name = "amount' . $i . '" value = "' . $homeServiceDetail['total_amount'] . '">
                                    </td>';
                                    }
                                    echo '</tr>';
                                    $i++;
                                }
                                ?>
                                <tr>
                                    <td colspan="3" class="td-right"><b>Total</b></td>
                                    <td class="td-right" id="totalAmount"><?php echo $appointmentSummary->grand_total ?></td>
                                    <?php
                                    if ($appointmentSummary->status == config('constants.APPOINTMENT_PROCCESSING') || $appointmentSummary->status == config('constants.APPOINTMENT_ACCEPT')) {
                                        echo "<td></td>";
                                    }
                                    ?>
                                </tr>
                            </table>



                            <?php if ($appointmentSummary->status == config('constants.APPOINTMENT_CASH_COLLECT')) { ?>
                               <table class="table table-bordered custom-table m-t-10" id="additionalServiceTable">
                                    <tr class="bg-info">
                                        <?php
                                        if ($appointmentSummary->status == config('constants.APPOINTMENT_START')) {
                                        ?>
                                            <th colspan="3"><b>Additional Bill</b></th>
                                        <?php 
                                        } else {
                                        ?>
                                            <th colspan="2"><b>Additional Bill</b></th>
                                        <?php
                                        }
                                        ?>
                                    </tr>
                                    <tr>
                                        <th width="70%"><b>Bill Details</b></th>
                                        <th width="20%"><b>Amount</b></th>
                                        <?php
                                        if ($appointmentSummary->status == config('constants.APPOINTMENT_START')) {
                                        ?>
                                            <th width="10%"><b>Action</b></th>
                                        <?php } ?>
                                    </tr>   
                                    <?php
                                    $count = 1;
                                    if ($appointmentSummary->additional_bill) {
                                        $additionalBills = json_decode($appointmentSummary->additional_bill);
                                        foreach ($additionalBills as $additionalBill => $billDetail) {
                                    ?>
                                            <tr id="additionalServiceTr<?php echo $count; ?>">
                                                <?php
                                                if ($appointmentSummary->status == config('constants.APPOINTMENT_START')) {
                                                ?>
                                                    <td><input class="form-control custom-form-control1" type="text" name="addServiceName<?php echo $count; ?>" id="addServiceName<?php echo $count; ?>" value="<?php echo $billDetail->detail; ?>"></td>
                                                    <td><input class="form-control custom-form-control" type="text" name="addServicePrice<?php echo $count; ?>" id="addServicePrice<?php echo $count; ?>" value="<?php echo number_format($billDetail->amount, 2); ?>" onkeyup="updateAdditionalServiceTotal()" onchange="updateAdditionalServiceTotal()"></td>
                                                    <td class="td-center"><i class="fa fa-remove pointer text-danger" onclick="removeAdditionalService('<?php echo $count ?>')"></i></td>
                                                <?php } else {
                                                ?>
                                                    <td class="td-left"><?php echo $billDetail->detail; ?></td>
                                                    <td class="td-right"><?php echo number_format($billDetail->amount, 2); ?></td>
                                                <?php }
                                                ?>
                                            </tr>
                                    <?php
                                            $count++;
                                        }
                                    }
                                    ?>
                                    <input type="hidden" name="additionalServiceCount" id="additionalServiceCount" value="<?php echo $count; ?>">
                                    <tr>
                                        <td class="td-right"><b>Total</b></td>
                                        <td class="td-right" id="totalAdditionalServiceAmount">
                                            <?php echo $appointmentSummary->total_additional_bill; ?>

                                        </td>
                                        <?php
                                        if ($appointmentSummary->status == config('constants.APPOINTMENT_START')) {
                                        ?>
                                            <td></td>
                                        <?php } ?>
                                    </tr>
                                </table>

                                <table class="" border="0" cellpadding="0" cellspacing="0" align="left" width="37%">
                                    <tr class="table-td-info">
                                        <td width="20%" align="left" class="content-table-td"><b>Total Service Bill </b></td>
                                        <td width="2%" align="center">:</td>
                                        <td width="15%" align="right" class="content-table-td" id="totalServiceBillSummary"><?php echo $appointmentSummary->grand_total ?> BDT</td>
                                    </tr>
                                    <tr class="table-td-info">
                                        <td align="left" class="content-table-td"><b>Total Additional Bill </b></td>
                                        <td align="center">:</td>
                                        <td align="right" class="content-table-td" id="totalAdditionalBillSummary"><?php echo $appointmentSummary->total_additional_bill ?> BDT</td>
                                    </tr>
                                    <tr class="table-td-info">
                                        <td class="content-table-td bottom-border" align="left"><b> Discount </b></td>
                                        <td class="content-table-td bottom-border" align="center">:</td>
                                        <td class="content-table-td bottom-border" align="right">
                                            <?php if ($appointmentSummary->status == config('constants.APPOINTMENT_START')) {
                                            ?>
                                                <input class="form-control custom-form-control" id="discount" name="discount" type="text" value="<?php echo $appointmentSummary->discount ?>" onkeyup="calculateDiscount()" onchange="calculateDiscount()">
                                            <?php
                                            } else {
                                                echo $appointmentSummary->discount . ' BDT';
                                            }
                                            ?>
                                        </td>
                                    </tr>

                                    <tr class="table-td-info">
                                        <td align="left" class="content-table-td"><b>Net Bill </b></td>
                                        <td align="center">:</td>
                                        <td align="right" class="content-table-td" id="netBillSummary"><?php echo number_format($appointmentSummary->grand_total + $appointmentSummary->total_additional_bill - $appointmentSummary->discount, 2) ?> BDT</td>
                                    </tr>

                                </table>
                                <div style="clear: both;"></div>

                            <?php } ?>

                            <input type="hidden" id="totalAdditionalBillHidden" value="<?php echo $appointmentSummary->total_additional_bill; ?>">
                            <input type="hidden" id="serviceVarCodeStr" value="<?php echo implode(',', $serviceVarCodeArr) ?>">
                            <input type="hidden" id="takenServiceVarCount" name="takenServiceVarCount" value="<?php echo $i ?>">
                        </div>

                    </div>
                    <?php
                    if ($appointmentSummary->status == config('constants.APPOINTMENT_PROCCESSING') || $appointmentSummary->status == config('constants.APPOINTMENT_ACCEPT')) {
                    ?>
                        <button type="button" class="btn btn-info btn-xs save_button" onclick="setShowServiceModal()"><i class="fa fa-plus"></i> More Service</button>
                    <?php } ?>

                    <?php
                    if ($appointmentSummary->status == config('constants.APPOINTMENT_PROCCESSING') || $appointmentSummary->status == config('constants.APPOINTMENT_ACCEPT')) {
                    ?>
                        <br> <br>
                        <div class="row">
                            <div class="col-sm-6 col-md-6 col-xs-12">
                                <div class="form-group">
                                    <label class="form-label">Confirm Date</label>
                                    <input type="text" class="form-control dateInput" name="confirmDate" id="confirmDate" value="<?php echo $appointmentSummary->final_date ?>">
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 col-xs-12">
                                <div class="form-group">
                                    <label class="form-label">Confirm Time</label>
                                    <input type="text" class="form-control timepicker" name="confirmTime" id="confirmTime" value="<?php echo get_time_format($appointmentSummary->appointment_time) ?>">
                                </div>

                            </div>
                        </div>

                </form>
                <button type="button" id="saveBtn" class="btn btn-primary save_button mb-2 mt-3" onclick="saveHomeService()">Save All Changes</button>
            <?php
                    }
            ?>
            <hr><hr>
            <?php if ($appointmentSummary->status == config('constants.APPOINTMENT_ACCEPT') || $appointmentSummary->status == config('constants.APPOINTMENT_PROCCESSING')) { ?>
                <label class="form-label" data-error="wrong" data-success="right" for="comment">Vroom Comment</label>
                <textarea id="homeServiceComment" style="resize:none" class="form-control validate" rows="5"></textarea>
            <?php } ?>
            <?php if ($appointmentSummary->status == config('constants.APPOINTMENT_PENDING')) { ?>
                <a onclick="homeServiceProcess('<?php echo $appointmentNo; ?>');
                            return false;" href="#" class="btn btn-primary save_button mt-2">Process This Home Service</a>
            <?php
            } else if ($appointmentSummary->status == config('constants.APPOINTMENT_PROCCESSING') && $appointmentSummary->final_date && $appointmentSummary->appointment_time) {
            ?>
                <a onclick="homeServiceAccept('<?php echo $appointmentNo; ?>');
                            return false;" href="#" class="btn btn-success">Accept This Home Service</a>
                <a onclick="homeServiceReject('<?php echo $appointmentNo; ?>');
                            return false;" href="#" class="btn btn-primary btn-danger save_button mt-3">Reject This Home Service</a>
            <?php
            } else if ($appointmentSummary->status == config('constants.APPOINTMENT_PROCCESSING') || $appointmentSummary->status == config('constants.APPOINTMENT_ACCEPT')) {
            ?>
                <a onclick="homeServiceReject('<?php echo $appointmentNo; ?>');
                            return false;" href="#" class="btn btn-primary btn-danger save_button mt-3">Reject This Home Service</a>
            <?php } ?>
            <br><br>

            <small class="text-danger"><b>*** After giving confirm date and time, you can accept this home service</b></small>

            <!-- --------------- service modal -------------------- -->
            <button class="btn btn-default btn-sm waves-effect hidden" data-toggle="modal" data-target="#serviceModal" id="serviceModalShowBtn">Add service</button>
            <div class="modal fade" id="serviceModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h4 class="modal-title">Service</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">

                            <div class="accordion" id="accordion">

                                <?php
                                $flag = 1;
                                $serviceCount = 1;
                                foreach ($distinctServices as $distinctService) {
                                ?>

                                    <div class="accordion-item">

                                        <h2 class="accordion-header" id="heading<?php echo $distinctService['service'] ?>">
                                            <button class="accordion-button collapsed"
                                                    type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#generalCollapseOne<?php echo $distinctService['service'] ?>"
                                                    aria-expanded="false"
                                                    aria-controls="generalCollapseOne<?php echo $distinctService['service'] ?>">
                                                
                                                <i class="fa fa-tags me-2"></i>
                                                <?php echo $distinctService['service_name'] ?>
                                            </button>
                                        </h2>

                                        <div id="generalCollapseOne<?php echo $distinctService['service'] ?>"
                                            class="accordion-collapse collapse"
                                            data-bs-parent="#accordion">

                                            <div class="accordion-body">

                                                <table class="table table-striped custom-table">
                                                    <?php
                                                    $serviceVarSerial = 1;
                                                    foreach ($serviceVariants as $serviceVariant) {
                                                        if ($serviceVariant['service'] == $distinctService['service']) {
                                                            $flag = 0;
                                                            echo "<tr>";
                                                            echo "<td>$serviceVarSerial</td>";
                                                            echo "<td class='td-left' style='width:80%'>" . $serviceVariant['service_variant_name'] . "</td>";
                                                            echo "<td class='td-right' style='width:10%'>BDT " . $serviceVariant['unit_price'] . "</td>";
                                                            echo "<td class='td-left' style='width:5%'>" . $serviceVariant['unit_name'] . "</td>";
                                                            echo "<td class='td-left'>";
                                                            echo "<input type='checkbox' name='serviceVarCheckBox$serviceCount' id='serviceVarCheckBox$serviceCount'>";
                                                            echo "</td>";

                                                            echo "<input type='hidden' id='serviceVariantCode$serviceCount' value='$serviceVariant[variant_code]'>";
                                                            echo "<input type='hidden' id='serviceVariantName$serviceCount' value='$serviceVariant[service_variant_name]'>";
                                                            echo "<input type='hidden' id='serviceVariantUnitName$serviceCount' value='$serviceVariant[unit_name]'>";
                                                            echo "<input type='hidden' id='serviceVariantUnitPrice$serviceCount' value='$serviceVariant[unit_price]'>";

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

                                <?php } ?>

                            </div>

                            <input type="hidden" id="serviceVariantCount" value="<?php echo $serviceCount ?>">

                            <?php if ($flag) { ?>
                                <span class="text-danger">No service has been add to Home Service</span>
                            <?php } ?>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary save_button" onclick="setAddService()">SELECT</button>
                            <button type="button" class="btn btn-secondary save_button" data-bs-dismiss="modal">CLOSE</button>
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
    function confirmDateTimeSubmit() {
        var confirmDate = $.trim($('#confirmDate').val());
        var confirmTime = $.trim($('#confirmTime').val());


        if (confirmDate === "" || confirmTime === "") {
            sweetAlert('Confirm Date and Time is required');
            return false;
        }
        $('#confirmDateTimeForm').submit();
    }

    function chnageAdminHomeService() {
        if ($.trim($('#name').val()) === "" || $.trim($('#mobile').val()) === "" || $.trim($('#address').val()) === "") {
            sweetAlert('Name, Mobile, Address are required fields...!');
            return false;
        }
        $("#appointmentForm").submit();
    }

    function homeServiceProcess(appointmentNo) {

        let inputFieldJson = {
            appointmentNo: appointmentNo
        };

        swal({
            title: "Are you sure?",
            text: "",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willProcess) => {

            if (!willProcess) return;

            //showLoader();

            $.ajax({
                type: 'POST',
                url: '/admin/home/home-service-process',
                data: {
                    ...inputFieldJson,
                    _token: "{{ csrf_token() }}"
                }
            })
            .done(function(result) {
                //hideLoader();
                if (result == 2) {

                    Swal.fire({
                        icon: 'success',
                        title: 'Processing!',
                        text: 'Home Service Request is processing...!',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = "/admin/home/home-service-list";
                    });

                } 
                else if (result == 3) {

                    Swal.fire({
                        icon: 'error',
                        title: 'Not Allowed!',
                        text: 'Due to this request is not in pending status, you can not process this!'
                    }).then(() => {
                        window.location.href = "/admin/home/home-service-list";
                    });

                } 
                else if (result == 4) {

                    Swal.fire({
                        icon: 'error',
                        title: 'Not Found!',
                        text: 'No data found...!'
                    }).then(() => {
                        window.location.href = "/admin/home/home-service-list";
                    });

                } 
            })
            .fail(function() {
                //hideLoader();
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Something went wrong!'
                }).then(() => {
                    window.location.href = "/admin/home/home-service-list";
                });
            });

        });
    }

    //accept
    function homeServiceAccept(appointmentNo) {
        var comment = $.trim($('#homeServiceComment').val());
        var inputFieldJson = {};
        inputFieldJson['appointmentNo'] = appointmentNo;
        if (!comment) {
            comment = null;
        }
        inputFieldJson['comment'] = comment;
        swal({
            title: "Are you sure?",
            text: "",
            type: "warning",
            showCancelButton: true,
            closeOnConfirm: false,
            confirmButtonText: "Yes, Accept Home Service Request...!",
            confirmButtonColor: "#62ec6f"
        }, function() {
            swal.close();
            //showLoader();
            $.ajax({
                    type: 'POST',
                    data: inputFieldJson,
                    url: 'admin/AdminHomeService/acceptHomeService'
                })
                .done(function(data) {
                    hideLoader();
                    if (data === '2') {
                        successAlert("Home Service Request is accepted...!", "admin/AdminHomeService/homeServiceList");
                    } else if (data === '3') {
                        failAlert('You can not accept this...!', "admin/AdminHomeService/homeServiceList");
                    } else if (data === '4') {
                        failAlert('No data found...!', "admin/AdminHomeService/homeServiceList");
                    }
                })
                .error(function(data) {
                    failAlert('error!!!', "admin/hr/PromotionApplication/promotionRaisedByOthers");
                });
        });
    }
    //reject
    function homeServiceReject(appointmentNo) {
        var comment = $.trim($('#homeServiceComment').val());
        var inputFieldJson = {};
        inputFieldJson['appointmentNo'] = appointmentNo;
        if (!comment) {
            sweetAlert('Please enter a comment...!');
            return false;
        }
        inputFieldJson['comment'] = comment;
        swal({
            title: "Are you sure?",
            text: "",
            type: "warning",
            showCancelButton: true,
            closeOnConfirm: false,
            confirmButtonText: "Yes, Reject Home Service Request...!",
            confirmButtonColor: "#ec6c62"
        }, function() {
            swal.close();
            showLoader();
            $.ajax({
                    type: 'POST',
                    data: inputFieldJson,
                    url: 'admin/AdminHomeService/rejectHomeService'
                })
                .done(function(data) {
                    hideLoader();
                    if (data === '2') {
                        successAlert("Home Service Request is rejected...!", "admin/AdminHomeService/homeServiceList");
                    } else if (data === '3') {
                        failAlert('Due to this request is not in processing status, you can not reject this...!', "admin/AdminHomeService/homeServiceList");
                    } else if (data === '4') {
                        failAlert('No data found...!', "admin/AdminHomeService/homeServiceList");
                    }
                })
                .error(function(data) {
                    failAlert('error!!!', "admin/hr/PromotionApplication/promotionRaisedByOthers");
                });
        });
    }
    //-- magic begins
    function setShowServiceModal() {

        var serviceVariantCount = parseInt($("#serviceVariantCount").val()) || 0;
        var serviceVarCodeStr = $("#serviceVarCodeStr").val() || "";

        // open modal (Bootstrap 5 safe way)
        $('#serviceModal').modal('show');

        // reset all checkboxes
        for (let i = 1; i < serviceVariantCount; i++) {
            $('#serviceVarCheckBox' + i).prop('checked', false);
        }

        if (serviceVarCodeStr !== "") {

            let serviceVarCodeArr = serviceVarCodeStr.split(',');

            for (let i = 1; i < serviceVariantCount; i++) {

                let currentCode = $("#serviceVariantCode" + i).val();

                let isChecked = serviceVarCodeArr.includes(currentCode);

                $('#serviceVarCheckBox' + i).prop('checked', isChecked);
            }
        }
    }

    function setAddService() {
        var serviceVariantCount = $("#serviceVariantCount").val();
        var serviceVariantCode;
        var serviceVariantName;
        var serviceVariantUnitName;
        var serviceVariantUnitPrice;
        var serviceTableStr = "";
        var serviceVarCodeArr = new Array();
        var takenServiceVarCount = 1;

        var totalserviceVariantUnitPrice = 0.00;

        var takenServieVarCountFinal = $("#takenServiceVarCount").val();
        if (typeof takenServieVarCountFinal === 'undefined') {
            takenServieVarCountFinal = 0;
        }
        var quantity;
        var amount;
        var i = 1;
        for (var x = 1; x < serviceVariantCount; x++) {
            if ($("#serviceVarCheckBox" + x).is(':checked')) {
                serviceVariantCode = $("#serviceVariantCode" + x).val();
                serviceVariantName = $("#serviceVariantName" + x).val();

                serviceVariantUnitName = $("#serviceVariantUnitName" + x).val();
                serviceVariantUnitPrice = $("#serviceVariantUnitPrice" + x).val();
                quantity = 1;
                amount = (parseFloat(quantity) * parseFloat(serviceVariantUnitPrice));

                for (var j = 1; j < takenServieVarCountFinal; j++) {
                    if ($('#takenServiceVarCode' + j).val() === serviceVariantCode) {
                        quantity = $("#quantity" + j).val();

                        amount = (parseFloat(quantity) * parseFloat(serviceVariantUnitPrice));
                    }
                }

                serviceTableStr += '<tr id="serviceTakenTd' + i + '">\n\
                                        <td class="td-left">' + serviceVariantName + '</td>\n\
                                        <td class="td-left">BDT ' + serviceVariantUnitPrice + ' Per ' + serviceVariantUnitName + '</td>\n\
                                        <td><input class="form-control custom-form-control" type="number" min="0" value="' + quantity + '" onkeyup="calculateGrandTotal(' + i + ')" onchange="calculateGrandTotal(' + i + ')" name="quantity' + i + '" id="quantity' + i + '"></td>\n\
                                        <td class="td-right" id="amountTd' + i + '">' + amount.toFixed(2) + '</td>\n\
                                        <td class="td-center"><i class="fa fa-remove pointer text-danger" onclick="removeService(' + i + ')"></i>\n\
                                        <input type="hidden" id="takenServiceVarCode' + i + '" name="takenServiceVarCode' + i + '" value="' + serviceVariantCode + '">\n\
                                        <input type="hidden" id="takenServiceUnitPrice' + i + '" name="takenServiceUnitPrice' + i + '" value="' + serviceVariantUnitPrice + '">\n\
                                        <input type="hidden" id="amount' + i + '" name="amount' + i + '" value="' + amount + '"></td>\n\
                                    </tr>';
                serviceVarCodeArr.push(serviceVariantCode);
                takenServiceVarCount++;
                i++;
            }
        }
        $('#serviceTableDiv').remove();
        if (serviceTableStr !== "") {
            var newRow = $(document.createElement('div')).attr("id", 'serviceTableDiv');
            var serviceTableDiv = '<table class="table table-bordered custom-table">\n\
                                <tr class="bg-info">\n\
                                    <th colspan="5"><b>Service</b></th>\n\
                                </tr>\n\
                                <tr>\n\
                                    <th width="50%"><b>Service Name</b></th>\n\
                                    <th width="20%"><b>Price</b></th>\n\
                                    <th width="10%"><b>Quantity</b></th>\n\
                                    <th width="10%"><b>Amount</b></th>\n\
                                    <th width="10%"><b>Action</b></th>\n\
                                </tr>\n\
                                ' + serviceTableStr + '\n\
                                <tr>\n\
                                    <td colspan="3" class="td-right"><b>Total</b></td>\n\
                                    <td class="td-right" id="totalAmount">' + totalserviceVariantUnitPrice + '</td>\n\
                                    <td></td>\n\
                                </tr>\n\
                                <input type="hidden" id="serviceVarCodeStr' + '" value="' + serviceVarCodeArr.join() + '">\n\
                                <input type="hidden" id="takenServiceVarCount' + '" name="takenServiceVarCount' + '" value="' + takenServiceVarCount + '">\n\
                            </table>';
            newRow.after().html(serviceTableDiv);
            newRow.appendTo("#vehicleServiceDiv");
        }
        $('#serviceModalCloseBtn').click();
        //        console.log(totalserviceVariantUnitPrice);
        grandTotal();
    }


    function calculateGrandTotal(takenService) {
        var quantity = $('#quantity' + takenService).val();
        var unitPrice = $('#takenServiceUnitPrice' + takenService).val();
        if (!$.isNumeric(quantity)) {
            quantity = 0;
            $('#quantity' + takenService).val('');
        }

        if (!$.isNumeric(unitPrice)) {
            unitPrice = 0;
            $('#takenServiceUnitPrice' + takenService).val('');
        }

        var amount = (parseFloat(quantity) * parseFloat(unitPrice));
        if (!$.isNumeric(amount)) {
            $('#amountTd' + takenService).text('0.00');
            $('#amount' + takenService).val('');
        } else {
            $('#amountTd' + takenService).text(amount.toFixed(2));
            $('#amount' + takenService).val(amount);
        }
        grandTotal();
    }

    function grandTotal() {
        var totalAmount = 0;
        var takenServiceVarCount = $('#takenServiceVarCount').val();
        for (var j = 1; j <= takenServiceVarCount; j++) {
            var amount = $('#amount' + j).val();
            if (typeof amount !== 'undefined' && amount !== "") {
                totalAmount += parseFloat(amount);
            }
        }

        totalAmount = totalAmount.toFixed(2);
        if (!$.isNumeric(totalAmount)) {
            totalAmount = '0.00';
        }
        $('#totalAmount').text(totalAmount);
    }



    function removeService(serviceSerial) {
        $('#serviceTakenTd' + serviceSerial).remove();
        var serviceVarCodeArr = new Array();
        var takenServiceVarCode;
        var takenServiceVarCount = $('#takenServiceVarCount').val();
        for (var i = 1; i < takenServiceVarCount; i++) {
            takenServiceVarCode = $('#takenServiceVarCode' + i).val();

            if (typeof takenServiceVarCode !== 'undefined') {
                serviceVarCodeArr.push(takenServiceVarCode);
            }
        }

        if (serviceVarCodeArr.length !== 0) {
            $('#serviceVarCodeStr').val(serviceVarCodeArr.join());
        } else {
            $('#serviceTableDiv').remove();
        }
        grandTotal();
    }

    // function saveHomeService() {
    //     var takenServiceVarCount;
    //     var serviceProductFlag;
    //     serviceProductFlag = 0;
    //     //--------- service check ------------//
    //     takenServiceVarCount = $("#takenServiceVarCount").val();
    //     for (var j = 1; j <= takenServiceVarCount; j++) {
    //         var takenServiceVarCode = $("#takenServiceVarCode" + j).val();
    //         if (typeof takenServiceVarCode !== 'undefined') {
    //             serviceProductFlag = 1;
    //         }
    //         var quantity = $("#quantity" + j).val();
    //         if (quantity <= 0) {
    //             sweetAlert('Amount must be greater than 1');
    //             return false;
    //         }
    //     }

    //     if (serviceProductFlag === 0) {
    //         sweetAlert('Please take at least one service...!');
    //         return false;
    //     }

    //     if ($.trim($('#name').val()) === "" || $.trim($('#mobile').val()) === "" || $.trim($('#address').val()) === "") {
    //         sweetAlert('Name, Mobile, Address are required fields...!');
    //         return false;
    //     }
    //     if (checkTime($.trim($('#serviceTime').val())) === 0) {
    //         sweetAlert('Time is not in correct format...!');
    //         return false;
    //     }
    //     $("#submitForm").submit();
    // }

    function saveHomeService() {

        let takenServiceVarCount = parseInt($("#takenServiceVarCount").val()) || 0;
        let serviceProductFlag = 0;

        // --------- service check ------------ //
        for (let j = 1; j <= takenServiceVarCount; j++) {

            let takenServiceVarCode = $("#takenServiceVarCode" + j).val();

            if (typeof takenServiceVarCode !== 'undefined' && takenServiceVarCode !== "") {
                serviceProductFlag = 1;
            }

            let quantity = 2; //parseFloat($("#quantity" + j).val());

            if (!quantity || quantity <= 0) {
                Swal.fire({
                    icon: 'warning',
                    text: 'Amount must be greater than 0'
                });
                return false;
            }
        }

        // --------- at least one service required ------------ //
        if (serviceProductFlag === 0) {
            Swal.fire({
                icon: 'warning',
                text: 'Please take at least one service...!'
            });
            return false;
        }

        // --------- required fields ------------ //
        if ($.trim($('#name').val()) === "" ||
            $.trim($('#mobile').val()) === "" ||
            $.trim($('#address').val()) === "") {

            Swal.fire({
                icon: 'warning',
                text: 'Name, Mobile, Address are required fields...!'
            });
            return false;
        }

        // --------- time validation ------------ //
        let serviceTime = $.trim($('#serviceTime').val());

        if (serviceTime && typeof checkTime === "function") {
            if (checkTime(serviceTime) === 0) {
                Swal.fire({
                    icon: 'warning',
                    text: 'Time is not in correct format...!'
                });
                return false;
            }
        }

        // --------- submit form ------------ //
        $("#submitForm").submit();
    }
    //-- magic ends

    $('.dateInput').datepicker({
        format: 'yyyy-mm-dd',  // format compatible with Laravel date column
        autoclose: true,       // close picker after selecting a date
        todayHighlight: true,  // highlight today
        clearBtn: true,        // optional clear button
        orientation: 'bottom'  // show below the input
    });

    $('.timepicker').timepicker({
        showMeridian: true,
        defaultTime: false,
        explicitMode: true   // ✅ IMPORTANT FIX
    });
</script>
@endpush