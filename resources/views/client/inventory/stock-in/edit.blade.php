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
        text-align: right;
        padding-right: 3px;
        border-bottom: 1px solid black;
    }
    .custom-form-control1{
        height: 20px;
        font-size: 12px;
        text-align: left;
        padding-left: 3px;
        border-bottom: 1px solid black;
    }
    .form-group .form-line:after{
        border-bottom: 0px;
    }

    .form-group .non-editable{
        border-bottom: 0px;
    }
</style>
<div class="block-header">
    <h2>EDIT STOCK IN</h2><br>
    <div class="breadcrumb breadcrumb-bg-blue-grey">
        <li><a href="/client/Home"> Home</a></li>
        <li><a href="#"> Inventory</a></li>
        <li><a href="{{ route('client.master-data.stock') }}"> Stock</a></li>
        <li><a href="{{ route('client.inventory.stock-in.index') }}"> Stock In</a></li>
        <li><a href="{{ route('client.inventory.stock-in.edit',$data['stockSummary'][0]->stock_summary_id) }}"> Edit Stock In</a></li>
    </div>
</div>
<div class="row clearfix">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="body">
                @include('client.inventory.tab')

                <br>
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

                <div class="panel panel-default"> 
                    <div class="panel-body">
                        <form action="{{ route('client.inventory.editStockIn') }}" method="post" id="stockInForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <div class="form-group form-float" >
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="title" max="100" id="title" value="<?php echo $data['stockSummary'][0]->title ?>">
                                            <label class="form-label"> Title </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-4 col-xs-12">
                                    <div class="form-group form-float" >
                                        <div class="form-line">
                                            <input type="text" class="form-control dateInput" name="sockInDate" id="sockInDate" value="<?php echo $data['stockSummary'][0]->stock_date ?>">
                                            <label class="form-label"> Date </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <div class="form-group form-float" >
                                        <div class="form-line">
                                            <input type="text" class="form-control" max="100" name="referenceNo" id="referenceNo" value="<?php echo $data['stockSummary'][0]->reference_no ?>">
                                            <label class="form-label"> Reference No</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-4 col-xs-12">
                                    <div class="form-group form-float" >
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="stockId" id="stockId" value="<?php echo $data['summaryId'] ?>" readonly>
                                            <label class="form-label"> Stock Id </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="table-custom-responsive">
                                        <table class="table table-bordered custom-table" id="stockInTable">
                                            <tr class="bg-info">
                                                <td width="25%"><b>Product Variant</b></td>
                                                <td width="15%"><b>Quantity</b></td>
                                                <td width="15%"><b>Unit Name</b></td>
                                                <td width="50%"><b>Remarks</b></td>
                                                <td width="10%"><b>Action</b></td>
                                            </tr>
                                            <!--  <tr id="noProductTr">
                                                <td colspan="5">No Product</td>
                                            </tr>-->
                                            @php
                                                $counter = 1;
                                            @endphp

                                            @foreach($data['stockDetails'] as $stockDetail)

                                                @php
                                                    $quantity = $stockDetail->credit_quantity - $stockDetail->debit_quantity;
                                                @endphp

                                                @if($quantity)

                                                    <tr id="stockInTr{{ $counter }}">

                                                        <td class="td-left" id="variantTd{{ $counter }}">
                                                            {{ $stockDetail->variant_name }}

                                                            <input type="hidden"
                                                                value="{{ $stockDetail->variant }}"
                                                                name="variantCode{{ $counter }}"
                                                                id="variantCode{{ $counter }}">

                                                            <input type="hidden"
                                                                value="{{ $stockDetail->stock_details_auto_id }}"
                                                                name="stockDetailsAutoId{{ $counter }}"
                                                                id="stockDetailsAutoId{{ $counter }}">
                                                        </td>

                                                        <td>
                                                            <div class="form-group form-float">
                                                                <div class="form-line non-editable">
                                                                    <input type="text"
                                                                        class="form-control custom-form-control"
                                                                        name="quantity{{ $counter }}"
                                                                        id="quantity{{ $counter }}"
                                                                        value="{{ $quantity }}"
                                                                        readonly>
                                                                </div>
                                                            </div>
                                                        </td>

                                                        <td class="td-left" id="variantUnitNameTd{{ $counter }}">
                                                            {{ $stockDetail->unit_name }}
                                                        </td>

                                                        <td>
                                                            <div class="form-group form-float">
                                                                <div class="form-line non-editable">
                                                                    <input type="text"
                                                                        class="form-control custom-form-control1"
                                                                        max="200"
                                                                        name="remarks{{ $counter }}"
                                                                        id="remarks{{ $counter }}"
                                                                        value="{{ $stockDetail->remarks }}"
                                                                        readonly>
                                                                </div>
                                                            </div>
                                                        </td>

                                                        <td>
                                                            <i class="fa fa-remove pointer text-danger"
                                                            onclick="removeProductVariant('{{ $counter }}')"></i>
                                                        </td>

                                                    </tr>

                                                    @php
                                                        $counter++;
                                                    @endphp

                                                @endif

                                            @endforeach
                                        </table>
                                        <input type="hidden" id="variantCounterHidden">
                                        <input type="hidden" name="counterHidden" id="counterHidden" value="<?php echo $counter ?>"> <!-- total variant count -->
                                        <input type="hidden" name="variantDeleteStr" id="variantDeleteStr">

                                    </div>
                                    <div class="text-right">
                                        <button type="button" class="btn bg-blue btn-xs waves-effect" onclick="addNewRowStock()" >Add Product Variant</button>
                                    </div>
                                    <div class="text-left">
                                        <button type="button" type="button" class="btn bg-blue waves-effect" onclick="updateStockIn()" >Save </button>
                                    </div>
                                    <div class="created-updated">
                                        <div class="float-left">
                                            <b>Created By: </b><?php echo get_create_update_by_name($data['stockSummary'][0]->created_by) ?>
                                            <br><b>Created Date Time: </b><?php echo get_date_time_format($data['stockSummary'][0]->created_dt_tm) ?>

                                        </div>
                                        <div class="float-right">
                                            <b>Updated By: </b><?php echo get_create_update_by_name($data['stockSummary'][0]->updated_by) ?>
                                            <br><b>Updated Date Time: </b><?php echo get_date_time_format($data['stockSummary'][0]->updated_dt_tm) ?>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </form>

                        <!-- ----------- variant modal ----------------- -->
                        <button type="button" class="btn btn-default btn-sm waves-effect hidden" id="showVarinatModalBtn" data-toggle="modal" data-target="#variantModal"></button>
                        <div class="modal fade" id="variantModal" tabindex="-1" role="dialog">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="largeModalLabel">Product Variant</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="table-custom-responsive">

                                            <table class="table table-bordered table-hover custom-table dataTable">
                                                <thead>
                                                    <tr class="bg-info">
                                                        <th width="10%">SL</th>
                                                        <th width="25%">Category</th>
                                                        <th width="15%">Product</th>
                                                        <th width="15%">Variant</th>
                                                        <th width="15%">Unit Name</th>
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
                                                    @php
                                                        $variantSerial = 1;
                                                        $productCode = '';
                                                        $bgColor = '#fcf8e3';
                                                    @endphp

                                                    @foreach($data['variants'] as $variant)

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

                                                            <td class="td-center">{{ $variantSerial }}</td>

                                                            <td>{{ $variant->category_name }}</td>

                                                            <td class="td-left">{{ $variant->product_name }}</td>

                                                            <td class="td-left">
                                                                @if($variant->variant_name == 'Default')
                                                                    <span class="text-muted"><i>{{ $variant->variant_name }}</i></span>
                                                                @else
                                                                    {{ $variant->variant_name }}
                                                                @endif
                                                            </td>

                                                            <td class="td-left">{{ $variant->unit_name }}</td>

                                                            <td>
                                                                <i class="material-icons pointer"
                                                                onclick="addVariant({{ $variantSerial }})">
                                                                    arrow_drop_down_circle
                                                                </i>
                                                            </td>

                                                            <input type="hidden"
                                                                id="variantCodeModalHidden{{ $variantSerial }}"
                                                                value="{{ $variant->variant_code }}">

                                                            <input type="hidden"
                                                                id="variantNameModalHidden{{ $variantSerial }}"
                                                                value="{{ $variant->variant_name }}">

                                                            <input type="hidden"
                                                                id="variantUnitNameModalHidden{{ $variantSerial }}"
                                                                value="{{ $variant->unit_name }}">

                                                        </tr>

                                                        @php
                                                            $variantSerial++;
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

                        <!-- ----------------------------------------------- -->


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>    
@endsection
@push('scripts')
<script>
    var counter = '<?php echo count($data['stockDetails']) ?>';
    counter++;

    function addNewRowStock() {
        $('#noProductTr').remove();
        var newRow = $(document.createElement('tr')).attr('id', 'stockInTr' + counter);
        var stockTableRowStr;
        stockTableRowStr = '<td class="td-left pointer" id="variantTd' + counter + '" onclick="showVariantModal(' + counter + ')">\n\
                                            <small class="text-muted"><i>Show Product Variant</i></small>\n\
                                           </td>\n\
                                            <input type="hidden" name="variantCode' + counter + '" id="variantCode' + counter + '">\n\
                                            <td><div class="form-group form-float" >\n\
                                                    <div class="form-line">\n\
                                                        <input type="text" class="form-control custom-form-control"  name="quantity' + counter + '" id="quantity' + counter + '">\n\
                                                    </div>\n\
                                                </div>\n\
                                            </td>\n\
                                            <td class="td-left" id="variantUnitNameTd' + counter + '">\n\
                                                \n\
                                            </td>\n\
                                            <td><div class="form-group form-float" >\n\
                                                    <div class="form-line">\n\
                                                        <input type="text" class="form-control custom-form-control1" max="200" name="remarks' + counter + '" id="remarks' + counter + '" >\n\
                                                    </div>\n\
                                                </div>\n\
                                            </td>\n\
                                            <td><i class="fa fa-remove pointer text-danger" onclick="removeProductVariant(' + counter + ')"></i></td>';
        newRow.after().html(stockTableRowStr);
        newRow.appendTo("#stockInTable");
        counter++;
    }

    function removeProductVariant(variantCounter) {
        var idArr = new Array();
        if (typeof ($('#stockDetailsAutoId' + variantCounter).val()) !== 'undefined') {
            idArr.push($('#stockDetailsAutoId' + variantCounter).val());
        }

        if ($('#variantDeleteStr').val() !== "") {
            idArr.push($('#variantDeleteStr').val());
        }


        $('#variantDeleteStr').val(idArr.join());


        $('#stockInTr' + variantCounter).remove();
        var tableRowCount = $("#stockInTable" + " tr").length;
        if (tableRowCount === 1) {
            var stockTableRowStr;
            var newRow = $(document.createElement('tr')).attr('id', 'noProductTr');
            stockTableRowStr = '<td colspan="5">No Product</td>';
            newRow.after().html(stockTableRowStr);
            newRow.appendTo("#stockInTable");
        }
    }

    function showVariantModal(variantCounter) {
        $('#variantCounterHidden').val(variantCounter);
        $('#showVarinatModalBtn').click();
    }

    function addVariant(variantSerial) {
        var variantCodeModalHidden = $('#variantCodeModalHidden' + variantSerial).val();
        var variantNameModalHidden = $('#variantNameModalHidden' + variantSerial).val();
        var variantUnitNameModalHidden = $('#variantUnitNameModalHidden' + variantSerial).val();

        var variantCounter = $('#variantCounterHidden').val();

        for (var i = 1; i < variantCounter; i++) {
            if (typeof ($('#variantCode' + i).val()) !== 'undefined') {
                if ($('#variantCode' + i).val() === variantCodeModalHidden) {
                    sweetAlert("You have already select this Product Variant...!");
                    return false;
                }
            }
        }
        $('#variantTd' + variantCounter).text(variantNameModalHidden);
        $('#variantUnitNameTd' + variantCounter).text(variantUnitNameModalHidden);
        $('#variantCode' + variantCounter).val(variantCodeModalHidden);
        $('#modalCloseBtn').click();
    }

    function updateStockIn() {
        if ($.trim($('#sockInDate').val()) === "") {
            sweetAlert('Date is required');
            return false;
        }
        var flag = 0;
        for (var i = 1; i <= counter; i++) {
            if (typeof ($('#variantCode' + i).val()) !== 'undefined') {
                flag = 1;
                if ($.trim($('#variantCode' + i).val()) === "") {
                    sweetAlert('Product Variant is required...!');
                    return false;
                }
                var quantity = $.trim($('#quantity' + i).val());
                if (quantity === "") {
                    sweetAlert('Quantity is required...!');
                    return false;
                }

                if (parseFloat(quantity) <= 0) {
                    sweetAlert('Quantity must be greater than zero...!');
                    return false;
                }

                if (!$.isNumeric(quantity)) {
                    sweetAlert('Quantity must be a numeric value...!');
                    return false;
                }
            }
        }
        if (flag === 0) {
            sweetAlert('You have to take at least one Product Variant...!');
            return false;
        }

        $('#counterHidden').val(counter);
        $('#stockInForm').submit();
    }

</script>
@endpush