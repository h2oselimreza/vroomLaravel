@extends('client.layouts.app')
@section('content')
<div class="block-header">
    <h2>EXPENSE DETAILS HISTORY REPORT</h2><br>
    <div class="breadcrumb breadcrumb-bg-blue-grey">
        <li><a href="#"> Home</a></li>
        <li><a href="#"> Report</a></li>
        <li><a href="{{ route('client.report.expense-details-history') }}"> Experience Details History</a></li>
    </div>
</div>
<div class="row clearfix">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="body">
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group form-float" >
                            <div class="form-line">
                                <input type="date" class="form-control" name="fromDate" id="fromDate">
                            </div>
                            <div class="help-info">From Date </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group form-float" >
                            <div class="form-line">
                                <input type="date" class="form-control" name="toDate" id="toDate" >

                            </div>
                            <div class="help-info">To Date </div>
                        </div>
                    </div>		
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-6 col-xs-12">
                        <div class="text-center">
                            <b>Vehicle List</b>
                        </div>
                        <div class="table-custom-responsive">
                            <table class="table table-bordered table-hover jq-no-sort-datatable custom-table">
                                <thead>
                                    <tr class="bg-info">
                                        <th>SL</th>
                                        <th>Registration No</th>
                                        <th>Vehicle Type</th>
                                        <th>Brand</th>
                                        <th>Brand Model</th>
                                        <th>Group</th>
                                        <th>Class</th>
                                        <th class="no-sort">
                                            <input type="checkbox" id="selectall" class="filled-in chk-col-blue" onClick="selectAll(this)" />
                                            <label for="selectall" class="form-label m-l-20 m-b--10"></label>
                                        </th>
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

                                @php
                                    $count = 1;
                                @endphp

                                @foreach ($vehicles as $vehicle)

                                    <tr>

                                        <td>
                                            {{ $count }}
                                        </td>

                                        <td class="td-left">

                                            <a
                                                target="_blank"
                                                href="{{ route('client.report.vehicle-dashboard') }}?vehicleId={{ $vehicle->vehicle_id }}"
                                            >   
                                                {{ $vehicle->registration_no }}
                                            </a>

                                        </td>
                                        <td class="td-left">
                                            {{ $vehicle->vehicle_type_name }}
                                        </td>
                                        <td class="td-left">
                                            {{ $vehicle->brand_name }}
                                        </td>
                                        <td>
                                            {{ $vehicle->brand_model_name }}
                                        </td>
                                        <td class="td-left">
                                            {{ $vehicle->vehicle_group_name }}
                                        </td>
                                        <td>
                                            {{ $vehicle->vehicle_class_name }}
                                        </td>
                                        <td>
                                            <input
                                                type="checkbox"
                                                id="vehicleCheck{{ $count }}"
                                                value="{{ $vehicle->vehicle_id }}"
                                                name="vehicleCheck[]"
                                                onclick="setCheckBox(this.value, this.id)"
                                                class="filled-in chk-col-blue"
                                            />
                                            <label
                                                for="vehicleCheck{{ $count }}"
                                                class="form-label"
                                                style="margin-bottom: -12px"
                                            ></label>
                                        </td>
                                    </tr>

                                    @php
                                        $count++;
                                    @endphp

                                @endforeach

                            </tbody>
                            </table>
                            <hr>
                            <div class="text-center">
                                <b>Expense Head</b>
                            </div>
                            <table class="table table-bordered table-hover jq-no-sort-datatable custom-table m-t-20">
                                <thead>
                                    <tr class="bg-info">
                                        <th>SL</th>
                                        <th>Expense Category</th>
                                        <th>Expense Head</th>
                                        <th class="no-sort">
                                            <input type="checkbox" id="selectAllHead" class="filled-in chk-col-blue" onClick="selectAllHead(this)" />
                                            <label for="selectAllHead" class="form-label m-l-20 m-b--10"></label>
                                        </th>
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
                                        $count = 1;
                                        $costCategory = '';
                                        $bgColor = '#efebe8';
                                    @endphp

                                    @foreach ($costHeads as $costHead)

                                        @php

                                            if ($costCategory == '') {

                                                $bgColor = '#efebe8';

                                            } elseif ($costCategory != $costHead->cost_category) {

                                                if ($bgColor == '#f7f7f7') {
                                                    $bgColor = '#efebe8';
                                                } else {
                                                    $bgColor = '#f7f7f7';
                                                }
                                            }

                                            $costCategory = $costHead->cost_category;

                                        @endphp

                                        <tr style="background-color: {{ $bgColor }}">

                                            <td class="td-center">
                                                {{ $count }}
                                            </td>

                                            <td class="td-left">
                                                {{ $costHead->category_name }}
                                            </td>

                                            <td class="td-left">
                                                {{ $costHead->cost_head }}
                                            </td>

                                            <td>

                                                <input
                                                    type="checkbox"
                                                    id="headCheck{{ $count }}"
                                                    value="{{ $costHead->cost_head_code }}"
                                                    name="headCheck[]"
                                                    onclick="setHeadCheckBox(this.value, this.id)"
                                                    class="filled-in chk-col-blue"
                                                />

                                                <label
                                                    for="headCheck{{ $count }}"
                                                    class="form-label"
                                                    style="margin-bottom: -12px"
                                                ></label>

                                            </td>

                                        </tr>

                                        @php
                                            $count++;
                                        @endphp

                                    @endforeach

                                </tbody>
                            </table>

                            <hr>
                            <div class="text-center">
                                <b>Vendor</b>
                            </div>

                            <table class="table table-bordered table-hover jq-no-sort-datatable custom-table m-t-20">
                                <thead>
                                    <tr class="bg-info">
                                        <th>SL</th>
                                        <th>Vendor Title/Name</th>
                                        <th>Vendor Code</th>
                                        <th>Address</th>
                                        <th>Mobile</th>
                                        <th class="no-sort">
                                            <input type="checkbox" id="selectAllVendor" class="filled-in chk-col-blue" onClick="selectAllVendor(this)" />
                                            <label for="selectAllVendor" class="form-label m-l-20 m-b--10"></label>
                                        </th>
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

                                    @foreach ($vendors as $vendor)

                                        <tr>

                                            <td class="td-center">
                                                {{ $count }}
                                            </td>

                                            <td class="td-left">
                                                {{ $vendor->title }}
                                            </td>

                                            <td class="td-center">
                                                {{ $vendor->vendor_code }}
                                            </td>

                                            <td class="td-left">
                                                {{ $vendor->address }}
                                            </td>

                                            <td class="td-center">
                                                {{ $vendor->vendor_mobile }}
                                            </td>

                                            <td>

                                                <input
                                                    type="checkbox"
                                                    id="vendorCheck{{ $count }}"
                                                    value="{{ $vendor->vendor_code }}"
                                                    name="vendorCheck[]"
                                                    onclick="setVendorCheckBox(this.value, this.id)"
                                                    class="filled-in chk-col-blue"
                                                />

                                                <label
                                                    for="vendorCheck{{ $count }}"
                                                    class="form-label"
                                                    style="margin-bottom: -12px"
                                                ></label>

                                            </td>

                                        </tr>

                                        @php
                                            $count++;
                                        @endphp

                                    @endforeach

                                </tbody>
                            </table>

                            <form target="_blank" action="{{route('client.report.show-expense-details-history')}}" method="POST" id="formId">
                                @csrf
                                <input type="hidden" name="vehicleIdStr" id="vehicleIdStr">
                                <input type="hidden" name="costHeadStr" id="costHeadStr">
                                <input type="hidden" name="vendorStr" id="vendorStr">
                                <input type="hidden" name="reportGroup" id="reportGroup">
                                <input type="hidden" name="fromDate" id="fromDateHidden">
                                <input type="hidden" name="toDate" id="toDateHidden">
                            </form>

                            <div class="text-left">
                                <button class="btn bg-blue waves-effect" onclick="submitForm()">Show Report</button>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    var vehicleIdArr = new Array();
    function selectAll(source) {
        checkboxes = document.getElementsByName('vehicleCheck[]');
        var vehicleCheckBoxIdArr = new Array();
        for (var i in checkboxes) {
            checkboxes[i].checked = source.checked;
            if (typeof (checkboxes[i].id) !== 'undefined') {
                vehicleCheckBoxIdArr.push(checkboxes[i].id);
            }
        }
        for (var i = 0; i < vehicleCheckBoxIdArr.length; i++) {
            if ($("#" + vehicleCheckBoxIdArr[i]).is(':checked')) {
                var itemtoRemove = $("#" + vehicleCheckBoxIdArr[i]).val();
                vehicleIdArr = jQuery.grep(vehicleIdArr, function (value) {
                    return value !== itemtoRemove;
                });
                vehicleIdArr.push($("#" + vehicleCheckBoxIdArr[i]).val());
            } else {
                var itemtoRemove = $("#" + vehicleCheckBoxIdArr[i]).val();
                vehicleIdArr = jQuery.grep(vehicleIdArr, function (value) {
                    return value !== itemtoRemove;
                });
            }
        }

    }

    function setCheckBox(vehicleId, checkBoxId) {
        if ($("#" + checkBoxId).is(':checked')) {
            var itemtoRemove = vehicleId;
            vehicleIdArr = jQuery.grep(vehicleIdArr, function (value) {
                return value !== itemtoRemove;
            });
            vehicleIdArr.push(vehicleId);
        } else {
            var itemtoRemove = vehicleId;
            vehicleIdArr = jQuery.grep(vehicleIdArr, function (value) {
                return value !== itemtoRemove;
            });
        }
    }

    var costHeadArr = new Array();
    function selectAllHead(source) {
        checkboxes = document.getElementsByName('headCheck[]');
        var costCheckBoxIdArr = new Array();
        for (var i in checkboxes) {
            checkboxes[i].checked = source.checked;
            if (typeof (checkboxes[i].id) !== 'undefined') {
                costCheckBoxIdArr.push(checkboxes[i].id);
            }
        }
        for (var i = 0; i < costCheckBoxIdArr.length; i++) {
            if ($("#" + costCheckBoxIdArr[i]).is(':checked')) {
                var itemtoRemove = $("#" + costCheckBoxIdArr[i]).val();
                costHeadArr = jQuery.grep(costHeadArr, function (value) {
                    return value !== itemtoRemove;
                });
                costHeadArr.push($("#" + costCheckBoxIdArr[i]).val());
            } else {
                var itemtoRemove = $("#" + costCheckBoxIdArr[i]).val();
                costHeadArr = jQuery.grep(costHeadArr, function (value) {
                    return value !== itemtoRemove;
                });
            }
        }

    }

    function setHeadCheckBox(costId, checkBoxId) {
        if ($("#" + checkBoxId).is(':checked')) {
            var itemtoRemove = costId;
            costHeadArr = jQuery.grep(costHeadArr, function (value) {
                return value !== itemtoRemove;
            });
            costHeadArr.push(costId);
        } else {
            var itemtoRemove = costId;
            costHeadArr = jQuery.grep(costHeadArr, function (value) {
                return value !== itemtoRemove;
            });
        }
    }
    var vendorArr = new Array();
    function selectAllVendor(source) {
        checkboxes = document.getElementsByName('vendorCheck[]');
        var vendorCheckBoxIdArr = new Array();
        for (var i in checkboxes) {
            checkboxes[i].checked = source.checked;
            if (typeof (checkboxes[i].id) !== 'undefined') {
                vendorCheckBoxIdArr.push(checkboxes[i].id);
            }
        }
        for (var i = 0; i < vendorCheckBoxIdArr.length; i++) {
            if ($("#" + vendorCheckBoxIdArr[i]).is(':checked')) {
                var itemtoRemove = $("#" + vendorCheckBoxIdArr[i]).val();
                vendorArr = jQuery.grep(vendorArr, function (value) {
                    return value !== itemtoRemove;
                });
                vendorArr.push($("#" + vendorCheckBoxIdArr[i]).val());
            } else {
                var itemtoRemove = $("#" + vendorCheckBoxIdArr[i]).val();
                vendorArr = jQuery.grep(vendorArr, function (value) {
                    return value !== itemtoRemove;
                });
            }
        }
    }

    function setVendorCheckBox(vendorCode, checkBoxId) {
        if ($("#" + checkBoxId).is(':checked')) {
            var itemtoRemove = vendorCode;
            vendorArr = jQuery.grep(vendorArr, function (value) {
                return value !== itemtoRemove;
            });
            vendorArr.push(vendorCode);
        } else {
            var itemtoRemove = vendorCode;
            vendorArr = jQuery.grep(vendorArr, function (value) {
                return value !== itemtoRemove;
            });
        }
    }


    function submitForm() {
        var fromDate = $('#fromDate').val();
        var toDate = $('#toDate').val();

        if (fromDate === "" || toDate === "") {
            sweetAlert('From Date and To Date is required...!');
            return false;
        }

        var toDateCheck = new Date(toDate);
        var fromDateCheck = new Date(fromDate);
        if (toDateCheck < fromDateCheck) {
            sweetAlert('To Date should be greater than or equal of From date...!');
            return false;
        }

        var vehicleIdStr = vehicleIdArr.join();
        var costHeadStr = costHeadArr.join();
        var vendorStr = vendorArr.join();

        $('#vehicleIdStr').val(vehicleIdStr);
        $('#costHeadStr').val(costHeadStr);
        $('#vendorStr').val(vendorStr);
        $('#fromDateHidden').val(fromDate);
        $('#toDateHidden').val(toDate);

        $("#formId").submit();
    }
</script>
@endpush