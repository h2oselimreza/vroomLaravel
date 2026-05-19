@extends('client.layouts.app')
@section('content')
<div class="block-header">
    <h2>STOCK OUT REPORT</h2><br>
    <div class="breadcrumb breadcrumb-bg-blue-grey">
        <li><a href="/client/Home"> Home</a></li>
        <li><a href="#"> Report</a></li>
        <li><a href="/client/Report/inventoryReport"> Inventory</a></li>
        <li><a href="/client/Report/stockOut"> Stock Out Report</a></li>
    </div>
</div>
<div class="row clearfix">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="body">
                @include('client.report.inventory.tab')
                <br>
                <div class="panel panel-default"> 
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group form-float" >
                                    <div class="form-line">
                                        <input type="text" class="form-control dateInput" name="fromDate" id="fromDate">
                                        <label class="form-label"> From Date</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group form-float" >
                                    <div class="form-line">
                                        <input type="text" class="form-control dateInput" name="toDate" id="toDate" >
                                        <label class="form-label"> To Date</label>
                                    </div>
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
                                                            href="{{ url('client/Home/vehicleDashboard') }}?vehicleId={{ $vehicle->vehicle_id }}"
                                                        >
                                                            {{ $vehicle->registration_no }}
                                                        </a>

                                                    </td>

                                                    {{-- Old code --}}
                                                    {{-- <td class='td-left'>{{ $vehicle->registration_no }}</td> --}}

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
                                        <b>Product Variant</b>
                                    </div>
                                    <table class="table table-bordered table-hover jq-no-sort-datatable custom-table m-t-20">
                                        <thead>
                                            <tr class="bg-info">
                                                <th width="10%">SL</th>
                                                <th width="25%">Category</th>
                                                <th width="15%">Product</th>
                                                <th width="15%">Variant</th>
                                                <th width="15%">Unit Name</th>
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
                                                <th></th>
                                                <th></th>

                                            </tr>
                                        </tfoot>
                                        <tbody>

                                            @php
                                                $variantSerial = 1;
                                                $productCode = '';
                                                $bgColor = '#fcf8e3';
                                            @endphp

                                            @foreach ($variants as $variant)

                                                @php

                                                    if ($productCode == '') {

                                                        $bgColor = '#fcf8e3';

                                                    } elseif ($productCode != $variant->product) {

                                                        if ($bgColor == '#e5eaec') {
                                                            $bgColor = '#fcf8e3';
                                                        } else {
                                                            $bgColor = '#e5eaec';
                                                        }
                                                    }

                                                    $productCode = $variant->product;

                                                @endphp

                                                <tr style="background-color: {{ $bgColor }}">

                                                    <td class="td-center">
                                                        {{ $variantSerial }}
                                                    </td>

                                                    <td>
                                                        {{ $variant->category_name }}
                                                    </td>

                                                    <td class="td-left">
                                                        {{ $variant->product_name }}
                                                    </td>

                                                    @if ($variant->variant_name == 'Default')

                                                        <td class="text-muted td-left">
                                                            <i>{{ $variant->variant_name }}</i>
                                                        </td>

                                                    @else

                                                        <td class="td-left">
                                                            {{ $variant->variant_name }}
                                                        </td>

                                                    @endif

                                                    <td class="td-left">
                                                        {{ $variant->unit_name }}
                                                    </td>

                                                    <td>

                                                        <input
                                                            type="checkbox"
                                                            id="variantCheck{{ $variantSerial }}"
                                                            value="{{ $variant->variant_code }}"
                                                            name="variantCheck[]"
                                                            onclick="setVariantCheckBox(this.value, this.id)"
                                                            class="filled-in chk-col-blue"
                                                        />

                                                        <label
                                                            for="variantCheck{{ $variantSerial }}"
                                                            class="form-label"
                                                            style="margin-bottom: -12px"
                                                        ></label>

                                                    </td>

                                                </tr>

                                                @php
                                                    $variantSerial++;
                                                @endphp

                                            @endforeach

                                        </tbody>
                                    </table>

                                    <form target="_blank" action="{{ route('client.report.inventory-stock-out-report') }}" method="POST" id="formId">
                                        @csrf
                                        <input type="hidden" name="vehicleIdStr" id="vehicleIdStr">
                                        <input type="hidden" name="variantStr" id="variantStr">
                                        <input type="hidden" name="fromDate" id="fromDateHidden">
                                        <input type="hidden" name="toDate" id="toDateHidden">
                                        <input type="hidden" name="reportGroup" id="reportGroup">
                                        <input type="hidden" name="dateWiseFlag" id="dateWiseFlag">
                                    </form>
                                    <div class="text-left">
                                        <button class="btn bg-blue waves-effect" onclick="submitForm()">Show Report</button>
                                    </div>
                                </div> 
                            </div>


                            <button class="btn hidden" data-toggle="modal" id="groupByModalBtn" data-target="#groupByModal" ></button>
                            <div class="modal fade" id="groupByModal" tabindex="-1" role="dialog">
                                <div class="modal-dialog modal-sm" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="largeModalLabel">Select Group</h4>
                                            <br>
                                            Please select any following item for grouping the report
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <div class="demo-radio-button" >
                                                        <input name="groupByRadio" type="radio" id="vehicleRadio" class="with-gap radio-col-brown" checked>
                                                        <label class="form-label" for="vehicleRadio"> Vehicle </label>

                                                        <input name="groupByRadio" type="radio" id="productRadio" class="with-gap radio-col-brown">
                                                        <label class="form-label" for="productRadio"> Product </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <div class="demo-switch-title">Date Wise</div>
                                                    <div class="switch">
                                                        <label>
                                                            <input type="checkbox" id="dateModalFlag">
                                                            <span class="lever switch-col-deep-orange"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn bg-blue btn-primary waves-effect" id="serviceModalSelectBtn" onclick="showReport()">Show Report</button>
                                            <button type="button" class="btn bg-blue btn-danger waves-effect" id="serviceModalCloseBtn" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
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

    var variantArr = new Array();
    function selectAllHead(source) {
        checkboxes = document.getElementsByName('variantCheck[]');
        var variantCheckBoxIdArr = new Array();
        for (var i in checkboxes) {
            checkboxes[i].checked = source.checked;
            if (typeof (checkboxes[i].id) !== 'undefined') {
                variantCheckBoxIdArr.push(checkboxes[i].id);
            }
        }
        for (var i = 0; i < variantCheckBoxIdArr.length; i++) {
            if ($("#" + variantCheckBoxIdArr[i]).is(':checked')) {
                var itemtoRemove = $("#" + variantCheckBoxIdArr[i]).val();
                variantArr = jQuery.grep(variantArr, function (value) {
                    return value !== itemtoRemove;
                });
                variantArr.push($("#" + variantCheckBoxIdArr[i]).val());
            } else {
                var itemtoRemove = $("#" + variantCheckBoxIdArr[i]).val();
                variantArr = jQuery.grep(variantArr, function (value) {
                    return value !== itemtoRemove;
                });
            }
        }

    }

    function setVariantCheckBox(variantId, checkBoxId) {
        if ($("#" + checkBoxId).is(':checked')) {
            var itemtoRemove = variantId;
            variantArr = jQuery.grep(variantArr, function (value) {
                return value !== itemtoRemove;
            });
            variantArr.push(variantId);
        } else {
            var itemtoRemove = variantId;
            variantArr = jQuery.grep(variantArr, function (value) {
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
        var variantStr = variantArr.join();

        if (variantArr.length === 0) {
            sweetAlert('Please select at least  one product variant...!');
            return false;
        }

        $('#vehicleIdStr').val(vehicleIdStr);
        $('#variantStr').val(variantStr);
        $('#fromDateHidden').val(fromDate);
        $('#toDateHidden').val(toDate);

        //console.log(variantStr);
        if (variantArr.length !== 0 && vehicleIdArr.length !== 0) {
            $('#groupByModalBtn').click();
        } else {
            $("#formId").submit();
        }
    }

    function showReport() {
        var flag = "";
        if ($('#vehicleRadio').is(':checked')) {
            flag = 'vehicleWise';
        }
        if ($('#productRadio').is(':checked')) {
            flag = 'productWise';
        }
        var dateFlag = 0;
        if ($('#dateModalFlag').is(':checked')) {
            dateFlag = 1;
        }

        $('#reportGroup').val(flag);
        $('#dateWiseFlag').val(dateFlag);
        $("#formId").submit();
    }
</script>
@endpush