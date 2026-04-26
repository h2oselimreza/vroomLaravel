@extends('layouts.app')

@section('content')
<style>

    .panel-group{
        margin-bottom: 0px;
    }
    .custom-panel-title1{
        font-size: 13px;
        font-weight: bold;
    }

    .custom-panel-heading{
        padding: 8px 14px;

    }
    .panel-group .panel1{
        margin-bottom: 5px;
    }
    .custom1-panel-body{
        font-size: 12px;
    }
    .content-table-td{font-size: 12px}
    .custom-form-control{
        height: 20px;
        font-size: 12px;
        text-align: right;
    }
    .custom-form-control1{
        height: 20px;
        font-size: 12px;
        text-align: left;
    }
    .bottom-border{border-bottom: 1px solid #ddd;padding-bottom: 5px} 
</style>

<div class="header dashboard_from">
    <h1 class="page-title">Show Employee Home Service</h1>
    <ul class="breadcrumb">
        <li><a href="#">Home</a> / </li>
        <li><a href="#"> Employee Home Service List</a> / </li>
        <li><a href="#"> Show Employee Home Service</a></li>
    </ul>
</div>

<div class="main-content">
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel panel-default"> 
                <div class="text-center mb-3">
                    <span class="font-20"><b><?php echo $appointmentSummary->assigned_employee_name ?></b><small><i> (<?php echo $appointmentSummary->assigned_employee_mobile ?>)</i></small></span>
                </div>
                <form action="admin/EmpHomeService/updateHomeService" id="submitForm" method="post">
                    <table class="m-t-10" border="0" cellpadding="0" cellspacing="0" align="center" width="100%">
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
                            <td  align="center">:</td>
                            <td align="left" class="content-table-td"><?php echo $appointmentSummary->address ?></td>
                        </tr>
                        <tr class="table-td-info">
                            <td  align="left" class="content-table-td"><b>Preferred Date</b></td>
                            <td  align="center">:</td>
                            <td  align="left" class="content-table-td"><?php echo get_date_format1($appointmentSummary->service_date) ?></td>
                            <td align="left" class="content-table-td"><b>Preferred Time</b></td>
                            <td align="center">:</td>
                            <td align="left" class="content-table-td"><?php echo get_time_format($appointmentSummary->service_time) ?></td>
                        </tr>
                        <tr class="table-td-info">
                            <td  align="left" class="content-table-td"><b>Confirm Date</b></td>
                            <td  align="center">:</td>
                            <td  align="left" class="content-table-td"><?php echo get_date_format1($appointmentSummary->final_date) ?></td>
                            <td align="left" class="content-table-td"><b>Confirm Time</b></td>
                            <td align="center">:</td>
                            <td align="left" class="content-table-td"><?php echo get_time_format($appointmentSummary->appointment_time) ?></td>
                        </tr>
                    </table>
                    <table class="m-b-10" border="0" cellpadding="0" cellspacing="0" align="center" width="100%">
                        <tr class="table-td-info">
                            <td width="20%" align="left" class="content-table-td"><b>Customer Remarks</b></td>
                            <td width="2%" align="center">:</td>
                            <td width="77%" align="left" class="content-table-td"><?php echo $appointmentSummary->remarks ?></td>
                        </tr>
                        <tr class="table-td-info">
                            <td align="left" class="content-table-td"><b>Admin Remarks</b></td>
                            <td align="center">:</td>
                            <td align="left" class="content-table-td"><?php echo $appointmentSummary->admin_remarks ?></td>
                        </tr>
                    </table>
                    <div id="vehicleServiceDiv" class="mt-3">
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
                                    if ($appointmentSummary->status == config('constants.APPOINTMENT_START')) {
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
                                    $serviceVarCodeArr[] = $homeServiceDetail->service_variant;
                                    echo '<tr id="serviceTakenTd' . $i . '">
                                    <td class="td-left">' . $homeServiceDetail->service_variant_name . '</td>
                                    <td class="td-left">BDT ' . $homeServiceDetail->unit_price . ' Per ' . $homeServiceDetail->unit_name . '</td>';
                                    if ($appointmentSummary->status == config('constants.APPOINTMENT_START')) {
                                        echo '<td><input class="form-control custom-form-control" type = "number" min = "0" value = "' . $homeServiceDetail->quantity . '" onkeyup = "calculateGrandTotal(' . $i . ')" onchange = "calculateGrandTotal(' . $i . ')" name = "quantity' . $i . '" id = "quantity' . $i . '"></td>';
                                    } else {
                                        echo '<td class="td-center">' . $homeServiceDetail->quantity . '</td>';
                                    }
                                    echo '<td class = "td-right" id = "amountTd' . $i . '">' . $homeServiceDetail->total_amount . '</td>';
                                    if ($appointmentSummary->status == config('constants.APPOINTMENT_START')) {
                                        echo '<td class = "td-center">
                                        <i class = "fa fa-remove pointer text-danger" onclick = "removeService(' . $i . ')"></i>
                                        <input type = "hidden" id = "takenServiceVarCode' . $i . '" name = "takenServiceVarCode' . $i . '" value = "' . $homeServiceDetail->service_variant . '">
                                        <input type = "hidden" id = "takenServiceUnitPrice' . $i . '" name = "takenServiceUnitPrice' . $i . '" value = "' . $homeServiceDetail->unit_price . '">
                                        <input type = "hidden" id = "amount' . $i . '" name = "amount' . $i . '" value = "' . $homeServiceDetail->total_amount . '">
                                    </td>';
                                    }
                                    echo '</tr>';
                                    $i++;
                                }
                                ?>
                                <tr>
                                    <td colspan="3" class="td-right"><b>Total</b></td>
                                    <td class="td-right" id="totalAmount">
                                        <?php echo $appointmentSummary->grand_total ?>
                                    </td>
                                    <?php
                                    if ($appointmentSummary->status == config('constants.APPOINTMENT_START')) {
                                        echo "<td></td>";
                                    }
                                    ?>
                                </tr>
                            </table>

                            <input type="hidden" id="serviceVarCodeStr" value="<?php echo implode(',', $serviceVarCodeArr) ?>">
                            <input type="hidden" id="takenServiceVarCount" name="takenServiceVarCount" value="<?php echo $i ?>">
                        </div>
                        <input type="hidden" id="totalAmountHidden" value="<?php echo $appointmentSummary->grand_total ?>">
                    </div>
                    <?php
                    if ($appointmentSummary->status == config('constants.APPOINTMENT_START')) {
                        ?>
                        <button type="button" class="btn btn-info save_button mb-3" onclick="setShowServiceModal()"><i class="fa fa-plus"></i> More Service</button>
                    <?php } ?>
                    <div id="row">
                        <div id="col-md-12 col-sm-12 col-xs-12">
                            <table class="table table-bordered custom-table m-t-10" id="additionalServiceTable">
                                <tr class="bg-info">
                                    <?php
                                    if ($appointmentSummary->status == config('constants.APPOINTMENT_START')) {
                                        ?>
                                        <th colspan = "3"><b>Additional Bill</b></th>
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
                                                <td><input class="form-control custom-form-control1" type="text" name = "addServiceName<?php echo $count; ?>" id = "addServiceName<?php echo $count; ?>" value="<?php echo $billDetail->detail; ?>"></td>
                                                <td><input class="form-control custom-form-control" type="text" name = "addServicePrice<?php echo $count; ?>" id = "addServicePrice<?php echo $count; ?>" value="<?php echo number_format($billDetail->amount, 2); ?>" onkeyup = "updateAdditionalServiceTotal()" onchange = "updateAdditionalServiceTotal()"></td>
                                                <td class = "td-center"><i class = "fa fa-remove pointer text-danger" onclick = "removeAdditionalService('<?php echo $count ?>')"></i></td>
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
                            <input type="hidden" id="totalAdditionalBillHidden" value="<?php echo $appointmentSummary->total_additional_bill; ?>">
                        </div>
                        <?php
                        if ($appointmentSummary->status == config('constants.APPOINTMENT_START')) {
                            ?>
                            <button type="button" class="btn btn-info save_button mb-3" onclick="addAdditionalService()"><i class="fa fa-plus"></i> More Bill</button>
                            <?php
                        }
                        ?>
                    </div>
                    <input type="hidden" name="empId" id="empId" value="<?php echo $empId ?>">
                    <input type="hidden" name="appointmentNo" value="<?php echo $appointmentNo ?>">
                    <div class="row">
                        <br>
                        <div class="col-md-12">
                            <table class="" border="0" cellpadding="0" cellspacing="0" align="left" width="37%">
                                <tr class="table-td-info">
                                    <td width="20%" align="left" class="content-table-td"><b>Total Service Bill </b></td>
                                    <td width="2%" align="center">:</td>
                                    <td width="15%" align="right" class="content-table-td" id="totalServiceBillSummary"><?php echo $appointmentSummary->grand_total ?> BDT</td>
                                </tr>
                                <tr class="table-td-info">
                                    <td  align="left" class="content-table-td"><b>Total Additional Bill </b></td>
                                    <td align="center">:</td>
                                    <td  align="right" class="content-table-td" id="totalAdditionalBillSummary"><?php echo $appointmentSummary->total_additional_bill ?> BDT</td>
                                </tr>
                                <tr class="table-td-info">
                                    <td class="content-table-td bottom-border" align="left" ><b> Discount </b></td>
                                    <td class="content-table-td bottom-border" align="center">:</td>
                                    <td class="content-table-td bottom-border" align="right">
                                        <?php if ($appointmentSummary->status == config('constants.APPOINTMENT_START')) {
                                            ?>
                                            <input class="form-control custom-form-control" id="discount" name="discount" type="text" value="<?php echo $appointmentSummary->discount ?>" onkeyup = "calculateDiscount()" onchange = "calculateDiscount()">
                                            <?php
                                        } else {
                                            echo $appointmentSummary->discount.' BDT';
                                        }
                                        ?>
                                    </td>
                                </tr>

                                <tr class="table-td-info">
                                    <td  align="left" class="content-table-td"><b>Net Bill </b></td>
                                    <td align="center">:</td>
                                    <td  align="right" class="content-table-td" id="netBillSummary"><?php echo number_format($appointmentSummary->grand_total + $appointmentSummary->total_additional_bill - $appointmentSummary->discount, 2) ?> BDT</td>
                                </tr>

                            </table>
                        </div>
                    </div>
                </form>

                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        if ($appointmentSummary->status == config('constants.APPOINTMENT_START')) {
                            ?>
                            <button type="button" id="saveBtn" class="btn btn-primary save_button mt-3" onclick="saveHomeService()">Save All Changes</button>
                            <?php
                        }
                        ?>
                        <?php if ($appointmentSummary->status == config('constants.APPOINTMENT_ACCEPT')) { ?>
                            <a onclick="empHomeServiceStart('<?php echo $appointmentNo; ?>');
                                        return false;" href="#" class="btn btn-success save_button mt-3">Start Work</a>
                               <?php
                           }
                           ?>
                           <?php if ($appointmentSummary->status == config('constants.APPOINTMENT_ACCEPT') || $appointmentSummary->status == config('constants.APPOINTMENT_START')) { ?>
                            <a onclick="showRejectCommentModal();
                                        return false;" href="#" class="btn btn-danger save_button mt-3">Reject Work</a>
                               <?php
                           }
                           ?>
                           <?php
                           if ($appointmentSummary->status == config('constants.APPOINTMENT_START')) {
                               ?>
                            <a onclick="empHomeServiceComplete();
                                        return false;" href="#" class="btn btn-success save_button mt-3">Complete Work</a>
                               <?php
                           }
                           ?>
                           <?php if ($appointmentSummary->status == config('constants.APPOINTMENT_COMPLETE')) { ?>
                            <div class="row">
                                <div class="col-md-2">
                                    <label>Transaction Channel</label>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-control" id="transactionChannel">
                                        <?php
                                            foreach ($transactionChannels as $transactionChannel) {
                                                ?>
                                                <option value="<?php echo $transactionChannel['element_code'] ?>"><?php echo $transactionChannel['element'] ?></option>
                                        <?php
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <a onclick="empHomeServiceCashCollect();
                                        return false;" href="#" class="btn btn-primary">Payment Collect</a>
                                </div>
                            </div>
                           <div class="clear"></div>

                               <?php
                           }
                           ?>
                    </div>
                </div>
                <!-- --------------- service modal -------------------- -->
                <button class="btn btn-default btn-sm waves-effect d-none"
                        data-bs-toggle="modal"
                        data-bs-target="#serviceModal"
                        id="serviceModalShowBtn">
                    Add service
                </button>

                <div class="modal fade" id="serviceModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h4 class="modal-title" id="largeModalLabel">Service</h4>

                                {{-- ❗ FIX: Bootstrap 5 close button --}}
                                <button type="button" class="btn-close"
                                        data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                            </div>

                            <div class="modal-body">

                                <div class="panel-group" id="accordion">

                                    @php
                                        $flag = 1;
                                        $serviceCount = 1;
                                    @endphp

                                    @foreach ($distinctServices as $distinctService)

                                        <div class="panel panel1 panel-default">

                                            <div class="panel-heading custom-panel-heading">
                                                <p class="panel-title custom-panel-title1 p-t-0 p-b-0">

                                                    <a role="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-parent="#accordion"
                                                    href="#generalCollapseOne{{ $distinctService->service }}">

                                                        <i class="fa fa-tags"></i>
                                                        {{ $distinctService->service_name }}

                                                    </a>

                                                </p>
                                            </div>

                                            <div id="generalCollapseOne{{ $distinctService->service }}"
                                                class="panel-collapse collapse show">

                                                <div class="panel-body">

                                                    <table class="table table-striped custom-table">

                                                        @php $serviceVarSerial = 1; @endphp

                                                        @foreach ($serviceVariants as $serviceVariant)

                                                            @if ($serviceVariant->service == $distinctService->service)

                                                                @php $flag = 0; @endphp

                                                                <tr>
                                                                    <td>{{ $serviceVarSerial }}</td>

                                                                    <td style="width:80%">
                                                                        {{ $serviceVariant->service_variant_name }}
                                                                    </td>

                                                                    <td style="width:10%">
                                                                        BDT {{ $serviceVariant->unit_price }}
                                                                    </td>

                                                                    <td style="width:5%">
                                                                        {{ $serviceVariant->unit_name }}
                                                                    </td>

                                                                    <td>
                                                                        <input type="checkbox"
                                                                            name="serviceVarCheckBox{{ $serviceCount }}"
                                                                            id="serviceVarCheckBox{{ $serviceCount }}">
                                                                    </td>

                                                                    <input type="hidden"
                                                                        id="serviceVariantCode{{ $serviceCount }}"
                                                                        value="{{ $serviceVariant->variant_code }}">

                                                                    <input type="hidden"
                                                                        id="serviceVariantName{{ $serviceCount }}"
                                                                        value="{{ $serviceVariant->service_variant_name }}">

                                                                    <input type="hidden"
                                                                        id="serviceVariantUnitName{{ $serviceCount }}"
                                                                        value="{{ $serviceVariant->unit_name }}">

                                                                    <input type="hidden"
                                                                        id="serviceVariantUnitPrice{{ $serviceCount }}"
                                                                        value="{{ $serviceVariant->unit_price }}">

                                                                </tr>

                                                                @php
                                                                    $serviceVarSerial++;
                                                                    $serviceCount++;
                                                                @endphp

                                                            @endif

                                                        @endforeach

                                                    </table>

                                                </div>
                                            </div>
                                        </div>

                                    @endforeach

                                    <input type="hidden"
                                        id="serviceVariantCount"
                                        value="{{ $serviceCount }}">

                                </div>

                                @if ($flag)
                                    <span class="text-danger">
                                        No service has been add to Home Service
                                    </span>
                                @endif

                            </div>

                            <div class="modal-footer">

                                <button type="button"
                                        class="btn btn-link waves-effect"
                                        id="serviceModalSelectBtn"
                                        onclick="setAddService()">
                                    SELECT
                                </button>

                                {{-- ❗ FIX: Bootstrap 5 close --}}
                                <button type="button"
                                        class="btn btn-link waves-effect"
                                        id="serviceModalCloseBtn"
                                        data-bs-dismiss="modal">
                                    CLOSE
                                </button>

                            </div>

                        </div>
                    </div>
                </div>
                <!-- ------------- ----------------- ----------------- -->
            </div>
        </div>
    </div>
</div>

<!-- --------------- reject comment modal -------------------- -->
<div class="modal fade" id="rejectCommentModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="largeModalLabel">Add a comment </h4>
            </div>
            <div style="padding: 30px">
                <label data-error="wrong" data-success="right" for="commentReject">Comment</label><span class="text-danger">*</span><small class="custom-text-danger" style="display: none;" id="commentReject-error"> Comment is Required</small>
                <textarea id="commentReject" class="form-control validate" rows="3"></textarea>   
            </div>
            <div class="modal-footer">
                <button onclick="empHomeServiceReject();
                        return false;" type="button" class="btn btn-link waves-effect" id="commentReject">Reject</button>
                <button type="button" class="btn btn-link waves-effect" id="modalCloseBtn" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="appointmentNo" value="<?php echo $appointmentNo; ?>" >
<!-- ------------- ----------------- ----------------- -->

@endsection


@push('scripts')
<script type="text/javascript" src="{{ asset('assets/select_bo/js/moment.js') }}"></script>
<script>
    function chnageAdminHomeService() {
        if ($.trim($('#name').val()) === "" || $.trim($('#mobile').val()) === "" || $.trim($('#address').val()) === "") {
            sweetAlert('Name, Mobile, Address are required fields...!');
            return false;
        }
        $("#appointmentForm").submit();
    }
    //start
    function empHomeServiceStart(appointmentNo) {

        let inputFieldJson = {
            appointmentNo: appointmentNo,
            empId: "{{ $empId }}"
        };

        Swal.fire({
            title: "Are you sure?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, Start Home Service Request...!",
            confirmButtonColor: "#62ec6f"
        }).then((result) => {

            if (result.isConfirmed) {

                //showLoader();

                $.ajax({
                    type: 'POST',
                    url: "{{ route('admin.start-home-service') }}",
                    data: {
                        ...inputFieldJson,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (data) {

                        //hideLoader();

                        if (data === '2') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Started!',
                                text: 'Home Service Request is started...!'
                            }).then(() => {
                                window.location.href =
                                "{{ url('/admin/home/employee-home-service-details') }}"
                                + "/" + appointmentNo + "/" + empId;
                            });

                        } else if (data === '3') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Not Allowed!',
                                text: 'You can not accept this...!'
                            }).then(() => {
                                window.location.href =
                                "{{ url('/admin/home/employee-home-service-details') }}"
                                + "/" + appointmentNo + "/" + empId;
                            });

                        } else if (data === '4') {
                            Swal.fire({
                                icon: 'error',
                                title: 'No Data!',
                                text: 'No data found...!'
                            }).then(() => {
                                window.location.href =
                                "{{ url('/admin/home/employee-home-service-details') }}"
                                + "/" + appointmentNo + "/" + empId;
                            });
                        }
                    },
                    error: function () {
                        //hideLoader();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Something went wrong!'
                        }).then(() => {
                            window.location.href =
                                "{{ url('/admin/home/employee-home-service-details') }}"
                                + "/" + appointmentNo + "/" + empId;
                        });
                    }
                });
            }
        });
    }
    //reject
    function showRejectCommentModal() {
        $('#rejectCommentModal').modal('show');
        $('#commentReject').val('');
    }

    function empHomeServiceReject() {
        var comment = $.trim($('#commentReject').val());
        if (!comment) {
            showRejectCommentModal();
            $("#commentReject-error").show();
            return false;
        }
        $('#rejectCommentModal').modal('hide');
        var inputFieldJson = {};
        inputFieldJson['appointmentNo'] = $.trim($('#appointmentNo').val());
        inputFieldJson['comment'] = comment;

        swal({
            title: "Are you sure?",
            text: "",
            type: "warning",
            showCancelButton: true,
            closeOnConfirm: false,
            confirmButtonText: "Yes, Reject Home Service Request...!",
            confirmButtonColor: "#ec6c62"
        }, function () {
            swal.close();
            showLoader();
            $.ajax({
                type: 'POST',
                data: inputFieldJson,
                url: 'admin/EmpHomeService/rejectEmpHomeService'
            })
                    .done(function (data) {
                        hideLoader();
                        if (data === '2') {
                            alertRedirect("Home Service Request is rejected...!", "admin/EmpHomeService/showEmpHomeSerDetails?empId=<?php echo $empId; ?>");
                        } else if (data === '4') {
                            failAlert('No data found...!', "admin/EmpHomeService/showEmpHomeSerDetails?empId=<?php echo $empId; ?>");
                        }
                    })
                    .error(function (data) {
                        failAlert('error!!!', "admin/EmpHomeService/showEmpHomeSerDetails?empId=<?php echo $empId; ?>");
                    });
        });
    }
    //-- reject end
    //complete

    function empHomeServiceComplete() {
        var inputFieldJson = {};
        var homeServiceNo = $.trim($('#appointmentNo').val());
        inputFieldJson['appointmentNo'] = homeServiceNo;

        swal({
            title: "Are you sure?",
            text: "",
            type: "warning",
            showCancelButton: true,
            closeOnConfirm: false,
            confirmButtonText: "Yes, Complete Home Service...!",
            confirmButtonColor: "#62ec6f"
        }, function () {
            swal.close();
            showLoader();
            $.ajax({
                type: 'POST',
                data: inputFieldJson,
                url: 'admin/EmpHomeService/completeEmpHomeService'
            })
                    .done(function (data) {
                        hideLoader();
                        if (data === '2') {
                            alertRedirect("Home Service Request is completed...!", "admin/EmpHomeService/showEmpHomeSerDetailInfo?appointmentNo=" + homeServiceNo + "&empId=<?php echo $empId; ?>");
                        } else if (data === '4') {
                            failAlert('No data found...!', "admin/EmpHomeService/showEmpHomeSerDetails?empId=<?php echo $empId; ?>");
                        }
                    })
                    .error(function (data) {
                        failAlert('error!!!', "admin/EmpHomeService/showEmpHomeSerDetails?empId=<?php echo $empId; ?>");
                    });
        });
    }
    //-- complete end



    function empHomeServiceCashCollect() {
        var inputFieldJson = {};
        var homeServiceNo = $.trim($('#appointmentNo').val());
        var transactionChannel = $.trim($('#transactionChannel').val());
        inputFieldJson['appointmentNo'] = homeServiceNo;
        inputFieldJson['transactionChannel'] = transactionChannel;

        swal({
            title: "Are you sure?",
            text: "",
            type: "warning",
            showCancelButton: true,
            closeOnConfirm: false,
            confirmButtonText: "Yes, Payment Collection is completed...!",
            confirmButtonColor: "#62ec6f"
        }, function () {
            swal.close();
            showLoader();
            $.ajax({
                type: 'POST',
                data: inputFieldJson,
                url: 'admin/EmpHomeService/cashCollectEmpHomeService'
            })
                    .done(function (data) {
                        hideLoader();
                        if (data === '2') {
                            alertRedirect("Cash Collect is completed...!", "admin/EmpHomeService/showEmpHomeSerDetailInfo?appointmentNo=" + homeServiceNo + "&empId=<?php echo $empId; ?>");
                        } else if (data === '4') {
                            failAlert('No data found...!', "admin/EmpHomeService/showEmpHomeSerDetails?empId=<?php echo $empId; ?>");
                        }
                    })
                    .error(function (data) {
                        failAlert('error!!!', "admin/EmpHomeService/showEmpHomeSerDetails?empId=<?php echo $empId; ?>");
                    });
        });
    }
    //-- cash collect end
    //-- magic begins
    function setShowServiceModal() {

        let serviceVariantCount = parseInt($("#serviceVariantCount").val()) || 0;
        let serviceVarCodeStr = $("#serviceVarCodeStr").val();

        // FIX: Open Bootstrap 5 modal properly (NOT via click hack)
        let modalEl = document.getElementById('serviceModal');
        let modal = new bootstrap.Modal(modalEl);
        modal.show();

        // old: $('#serviceModalShowBtn').click(); (often fails in BS5)

        // Reset all checkboxes safely
        for (let i = 1; i <= serviceVariantCount; i++) {
            $('#serviceVarCheckBox' + i).prop('checked', false);
        }
        // If codes exist
        if (serviceVarCodeStr && serviceVarCodeStr !== 'undefined') {

            let serviceVarCodeArr = serviceVarCodeStr.split(',');

            for (let i = 1; i <= serviceVariantCount; i++) {

                let code = $("#serviceVariantCode" + i).val();

                if (serviceVarCodeArr.includes(code)) {
                    $('#serviceVarCheckBox' + i).prop('checked', true);
                } else {
                    $('#serviceVarCheckBox' + i).prop('checked', false);
                }
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
        $('#totalAmountHidden').val(totalAmount);
        showBillSummary();
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

    function saveHomeService() {

        let takenServiceVarCount;
        let serviceProductFlag = 0;

        // --------- service check ------------ //
        takenServiceVarCount = $("#takenServiceVarCount").val();

        for (let j = 1; j <= takenServiceVarCount; j++) {

            let takenServiceVarCode = $("#takenServiceVarCode" + j).val();

            if (typeof takenServiceVarCode !== 'undefined') {
                serviceProductFlag = 1;
            }

            let quantity = $("#quantity" + j).val();

            if (quantity <= 0) {
                sweetAlert('Amount must be greater than 1');
                return false;
            }
        }

        if (serviceProductFlag === 0) {
            sweetAlert('Please take at least one service...!');
            return false;
        }

        // if (checkTime($.trim($('#serviceTime').val())) === 0) {
        //     sweetAlert('Time is not in correct format...!');
        //     return false;
        // }
        let time = $.trim($('#serviceTime').val());
        if (!/^([01]\d|2[0-3]):([0-5]\d)$/.test(time)) {
            sweetAlert('Time is not in correct format...!');
            return false;
        }

        let additionalServiceCount = parseInt($('#additionalServiceCount').val());

        for (let i = 1; i < additionalServiceCount; i++) {

            let addServiceName = $('#addServiceName' + i).val();
            let addServicePrice = $('#addServicePrice' + i).val();

            if (addServiceName === "" || addServicePrice === "") {
                sweetAlert("Please insert values into additional bill details & price fields or remove excess rows...!");
                return false;
            }
        }

        $("#submitForm").submit();
    }
    //-- magic ends
    function addAdditionalService() {
        var additionalServiceCount = parseInt($('#additionalServiceCount').val());
        var additionalServiceStr = '<tr id="additionalServiceTr' + additionalServiceCount + '">\n\
                                        <td><input class="form-control custom-form-control1" type="text" name = "addServiceName' + additionalServiceCount + '" id = "addServiceName' + additionalServiceCount + '"></td>\n\
                                        <td><input class="form-control custom-form-control" type="text" name = "addServicePrice' + additionalServiceCount + '" id = "addServicePrice' + additionalServiceCount + '" onkeyup = "updateAdditionalServiceTotal()" onchange = "updateAdditionalServiceTotal()"></td>\n\
                                        <td class = "td-center"><i class = "fa fa-remove pointer text-danger" onclick = "removeAdditionalService(' + additionalServiceCount + ')"></i></td>\n\
                                    </tr>';
        $('#additionalServiceTable tr:last').before(additionalServiceStr);
        additionalServiceCount++;
        $('#additionalServiceCount').val(additionalServiceCount);
    }

    function removeAdditionalService(additionalServiceTrNo) {
        $('#additionalServiceTr' + additionalServiceTrNo).remove();
        updateAdditionalServiceTotal();

    }

    function updateAdditionalServiceTotal() {
        var additionalServiceCount = parseInt($('#additionalServiceCount').val());

        var totalAdditional = 0.00;
        for (var i = 1; i < additionalServiceCount; i++) {
            var addServicePrice = $('#addServicePrice' + i).val();
            if (!$.isNumeric(addServicePrice)) {
                $('#addServicePrice' + i).val("");
            } else {
                totalAdditional += parseFloat(addServicePrice);
            }
        }
        $('#totalAdditionalServiceAmount').text(totalAdditional.toFixed(2));
        $('#totalAdditionalBillHidden').val(totalAdditional);
        showBillSummary();
    }

    function calculateDiscount() {
        var discount = $('#discount').val();
        if (!$.isNumeric(discount)) {
            $('#discount').val("");

        } else {
            discount = parseFloat(discount);
            if (discount < 0) {
                $('#discount').val("");
            }
        }
        showBillSummary();
    }

    function showBillSummary() {
        var totalAmount = parseFloat($('#totalAmountHidden').val());
        var totalAdditionalBill = parseFloat($('#totalAdditionalBillHidden').val());
        var discount = $('#discount').val();
        if (discount !== "") {
            discount = parseFloat(discount);
        }
        $('#totalServiceBillSummary').text(totalAmount.toFixed(2) + ' BDT');
        $('#totalAdditionalBillSummary').text(totalAdditionalBill.toFixed(2) + ' BDT');
        $('#netBillSummary').text((totalAmount + totalAdditionalBill - discount).toFixed(2) + ' BDT');
        //$('#totalAdditionalBillHidden').val(totalAdditional);
    }

</script>

@endpush