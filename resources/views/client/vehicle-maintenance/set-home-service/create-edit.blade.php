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
</style>

<div class="block-header">
    <h2>SET NEW HOME SERVICE</h2><br>
    <div class="breadcrumb breadcrumb-bg-blue-grey">
        <li><a href="#client/Home"> Home</a></li>
        <li><a href="#">Vehicle Maintenance</a></li>
        <li><a href="#"> Set New Home Service</a></li>
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
                <form action="{{ route('client.vehicle-maintenance.add-new-home-service') }}" method="post" id="homeServiceForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="panel-body custom1-panel-body">
                                <div id="vehicleServiceDiv">
                                </div>
                                <button type="button" class="btn bg-blue btn-xs waves-effect" onclick="setShowServiceModal()" >Add Service</button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group form-float" >
                                <div class="form-line">
                                    <input type="text" class="form-control"  name="name" id="name" value="<?php echo $companyInfo->primary_contact_person ?>">
                                    <label class="form-label"> Name</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group form-float" >
                                <div class="form-line">
                                    <input type="text" class="form-control"  name="mobile" id="mobile" onchange="checkMobileNumber(this.value, this.id)" value="<?php echo $companyInfo->primary_contact_mobile ?>" >
                                    <label class="form-label"> Mobile</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-6 col-xs-12">
                            <div class="form-group form-float" >
                                <div class="form-line">
                                    <input type="text" class="form-control dateInput"  name="serviceDate" id="serviceDate" value="<?php echo date('Y-m-d') ?>">
                                    <label class="form-label">Set Date</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-6 col-xs-12">
                            <div class="form-group form-float" >
                                <div class="form-line demo-masked-input">
                                    <input type="text" class="form-control time12"  name="serviceTime" id="serviceTime" placeholder="Ex: 11:59 pm" value="<?php echo date('h:i A') ?>">
                                    <!--<label class="form-label"> Time</label>-->
                                </div>
                                <div class="help-info">Set Time</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group form-float" >
                                <div class="form-line">
                                    <input type="text" class="form-control"  name="address" id="address" value="<?php echo $companyInfo->address ?>">
                                    <label class="form-label"> Address</label>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group form-float" >
                                <label class="form-label">Remarks </label>
                                <div class="form-line">
                                    <textarea class="form-control" name="remarks" ></textarea>
                                </div>
                            </div>
                            <button type="button"  class="btn bg-blue btn-sm waves-effect m-t-30" onclick="setHomeService()">Set Home Service</button>
                        </div>
                    </div>
                </form>
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
                            
                                    @php
                                        $flag = 1;
                                        $serviceCount = 1;
                                    @endphp
                            
                                    @foreach ($distinctServices as $distinctService)
                            
                                        <div class="panel panel1 panel-default">
                            
                                            <div class="panel-heading custom-panel-heading" role="tab">
                                                <p class="panel-title custom-panel-title1 p-t-0 p-b-0">
                            
                                                    <a role="button"
                                                       data-toggle="collapse"
                                                       data-parent="#accordion"
                                                       href="#generalCollapseOne{{ $distinctService->service }}"
                                                       aria-expanded="true"
                                                       aria-controls="generalCollapseOne{{ $distinctService->service }}">
                            
                                                        <i class="fa fa-tags"></i>
                                                        {{ $distinctService->service_name }}
                            
                                                    </a>
                                                </p>
                                            </div>
                            
                                            <div id="generalCollapseOne{{ $distinctService->service }}"
                                                 class="panel-collapse collapse in"
                                                 role="tabpanel">
                            
                                                <div class="panel-body">
                            
                                                    <table class="table table-striped custom-table">
                            
                                                        @php
                                                            $serviceVarSerial = 1;
                                                        @endphp
                            
                                                        @foreach ($serviceVariants as $serviceVariant)
                            
                                                            @if ($serviceVariant->service == $distinctService->service)
                            
                                                                @php $flag = 0; @endphp
                            
                                                                <tr>
                                                                    <td>{{ $serviceVarSerial }}</td>
                            
                                                                    <td class="td-left" style="width:80%">
                                                                        {{ $serviceVariant->service_variant_name }}
                                                                    </td>
                            
                                                                    <td class="td-right" style="width:10%">
                                                                        BDT {{ $serviceVariant->unit_price }}
                                                                    </td>
                            
                                                                    <td class="td-left" style="width:5%">
                                                                        {{ $serviceVariant->unit_name }}
                                                                    </td>
                            
                                                                    <td class="td-left">
                                                                        <input type="checkbox"
                                                                               name="serviceVarCheckBox{{ $serviceCount }}"
                                                                               id="serviceVarCheckBox{{ $serviceCount }}"
                                                                               class="filled-in chk-col-blue">
                            
                                                                        <label for="serviceVarCheckBox{{ $serviceCount }}"
                                                                               class="form-label"
                                                                               style="margin-bottom: -12px">
                                                                        </label>
                                                                    </td>
                            
                                                                    {{-- Hidden fields --}}
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
                            
                                </div>
                            
                                {{-- Hidden counter --}}
                                <input type="hidden" id="serviceVariantCount" value="{{ $serviceCount }}">
                            
                                {{-- No data message --}}
                                @if ($flag == 1)
                                    <span class="text-danger">No service has been added to Home Service</span>
                                @endif
                            
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
    $(function () {
        var $demoMaskedInput = $('.demo-masked-input');
        $demoMaskedInput.find('.time12').inputmask('hh:mm t', {placeholder: '__:__ _m', alias: 'time12', hourFormat: '12'});
    });
</script>
<script>
    function setShowServiceModal() {
        var serviceVariantCount = $("#serviceVariantCount").val();
        var serviceVarCodeStr = $("#serviceVarCodeStr").val();
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
                                        <td class="td-right">BDT ' + serviceVariantUnitPrice + ' Per ' + serviceVariantUnitName + '</td>\n\
                                        <td>\n\
                                            <div class="form-group form-float" >\n\
                                                <div class="form-line">\n\
                                                    <input class="form-control custom-form-control" type="number" min="0" value="' + quantity + '" onkeyup="calculateGrandTotal(' + i + ')" onchange="calculateGrandTotal(' + i + ')" name="quantity' + i + '" id="quantity' + i + '">\n\
                                                </div>\n\
                                            </div>\n\
                                        </td>\n\
                                        <td class="td-right" id="amountTd' + i + '">' + amount.toFixed(2) + '</td>\n\
                                        <td><i class="fa fa-remove pointer text-danger" onclick="removeService(' + i + ')"></i>\n\
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
                                    <td colspan="5"><b>Service</b></td>\n\
                                </tr>\n\
                                <tr>\n\
                                    <td width="50%"><b>Service Name</b></td>\n\
                                    <td width="20%"><b>Price</b></td>\n\
                                    <td width="10%"><b>Quantity</b></td>\n\
                                    <td width="10%"><b>Amount</b></td>\n\
                                    <td width="10%"><b>Action</b></td>\n\
                                </tr>\n\
                                ' + serviceTableStr + '\n\
                                <input type="hidden" id="serviceVarCodeStr' + '" value="' + serviceVarCodeArr.join() + '">\n\
                                <input type="hidden" id="takenServiceVarCount' + '" name="takenServiceVarCount' + '" value="' + takenServiceVarCount + '">\n\
                            </table>\n\
                            <div class="row">\n\
                                <div class="col-md-12 col-sm-12 col-xs-12">\n\
                                    <div class="text-right">\n\
                                    <b>Total: </b>BDT <span id="totalAmount">' + totalserviceVariantUnitPrice + '</span>\n\
                                    </div>\n\
                                </div>\n\
                            </div>';
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


    function setHomeService() {
        var takenServiceVarCount;
        var serviceProductFlag;
                serviceProductFlag = 0;
                //--------- service check ------------//
                takenServiceVarCount = $("#takenServiceVarCount").val();
                for (var j = 1; j <= takenServiceVarCount; j++) {
                    var takenServiceVarCode = $("#takenServiceVarCode" + j).val();
                    if (typeof takenServiceVarCode !== 'undefined') {
                        serviceProductFlag = 1;
                    }
                    var quantity = $("#quantity" + j).val();
                    if(quantity <= 0) {
                        sweetAlert('Amount must be greater than 1');
                        return false;
                    }
                }

                if (serviceProductFlag === 0) {
                    sweetAlert('Please take at least one service...!');
                    return false;
                }

        if ($.trim($('#name').val()) === "" || $.trim($('#mobile').val()) === "" || $.trim($('#serviceDate').val()) === "" || $.trim($('#serviceTime').val()) === "" || $.trim($('#address').val()) === "") {
            sweetAlert('Name, Mobile, Address, Date and Time are required fields...!');
            return false;
        }
        if (checkTime($.trim($('#serviceTime').val())) === 0) {
            sweetAlert('Time is not in correct format...!');
            return false;
        }
        $("#homeServiceForm").submit();
    }
</script>
@endpush