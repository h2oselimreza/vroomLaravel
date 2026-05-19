@extends('client.layouts.app')
@section('content')
<div class="block-header">
    <h2>STOCK IN REPORT</h2><br>
    <div class="breadcrumb breadcrumb-bg-blue-grey">
        <li><a href="/client/Home"> Home</a></li>
        <li><a href="#"> Report</a></li>
        <li><a href="/client/Report/inventoryReport"> Inventory</a></li>
        <li><a href="/client/Report/stockIn"> Stock In Report</a></li>
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
                                <div class="table-custom-responsive">
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

                                    <form target="_blank" action="{{ route('client.report.inventory-stock-in-report') }}" method="POST" id="formId">
                                        @csrf
                                        <input type="hidden" name="variantStr" id="variantStr">
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
    </div>
</div>
@endsection
@push('scripts')
<script>
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


        var variantStr = variantArr.join();

        if (variantArr.length === 0) {
            sweetAlert('Please select at least one product variant...!');
            return false;
        }


        $('#variantStr').val(variantStr);
        $('#fromDateHidden').val(fromDate);
        $('#toDateHidden').val(toDate);

        //console.log(variantStr);
        $("#formId").submit();
    }
</script>
@endpush