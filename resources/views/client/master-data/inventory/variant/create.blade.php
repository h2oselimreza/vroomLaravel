@extends('client.layouts.app')
@section('content')
<div class="block-header">
    <h2>MANAGER VARIANT</h2><br>
    <div class="breadcrumb breadcrumb-bg-blue-grey">
        <li><a href="/client/Home"> Home</a></li>
        <li><a href="#"> Master Data</a></li>
        <li><a href="/client/MasterData/inventory"> Inventory</a></li>
        <li><a href="/client/MasterData/variant"> Variant</a></li>
        <li><a href="/client/MasterData/manageVariant"> Manage Variant</a></li>
    </div>
</div>
<div class="row clearfix">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="body">
                 @include('client.master-data.inventory.tab')

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
                <div id="errorDiv" class="alert alert-danger hidden">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                </div>

                <div class="row">
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="text-center"><h4><b>Products</b></h4></div><br>
                                <style>
                                    .dataTables_wrapper .col-sm-3{
                                        margin-left: -140px;
                                    }
                                    @media (max-width: 768px) {
                                        .dataTables_wrapper .col-sm-3{
                                            margin-left:0px;
                                        }
                                    }
                                </style>
                                <div class="table-custom-responsive">
                                    <table class="table table-bordered table-hover custom-table dataTable">
                                        <thead>
                                            <tr class="bg-info">
                                                <th>SL</th>
                                                <th>Product</th>
                                                <th>Category</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>SL</th>
                                                <th>Product</th>
                                                <th>Category</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>

                                        @php
                                            $serial = 1;
                                        @endphp

                                        @foreach ($products as $product)

                                            @php
                                                $category = get_parent_category_str([
                                                    'parentCategoryCodeStr' => $product->parent_category_str,
                                                    'categoryArr' => $categories
                                                ]);
                                            @endphp

                                            <tr>

                                                <td class="td-center">{{ $serial }}</td>

                                                <td class="td-left" style="width:120px">
                                                    {{ $product->product_name }}
                                                </td>

                                                <td class="td-left">
                                                    {{ $category }}
                                                </td>

                                                <td class="td-center">

                                                    <button class="btn btn-primary bg-blue btn-sm waves-effect btn-circle-vairant"
                                                            onclick="setProductVariant('{{ $serial }}')">

                                                        <i class="fa fa-angle-right" style="top:1px;"></i>

                                                    </button>

                                                    <input type="hidden"
                                                        id="productCode{{ $serial }}"
                                                        value="{{ $product->product_code }}">

                                                    <input type="hidden"
                                                        id="productName{{ $serial }}"
                                                        value="{{ $product->product_name }}">

                                                    <input type="hidden"
                                                        id="category{{ $serial }}"
                                                        value="{{ $category }}">

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
                    </div>


                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="text-center"><h4><b>Variants</b></h4></div><br>
                                    <div class="col-sm-12 col-md-6 col-xs-12">
                                        <div class="form-group form-float" >
                                            <div class="form-line">
                                                <input type="text" class="form-control" id="product" disabled>
                                                <!--<label  class="form-label">Product</label>-->
                                            </div>
                                            <div class="help-info">Product</div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-xs-12">
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <input type="text" class="form-control" id="category" disabled>
                                                <!--<label  class="form-label">Category</label>-->
                                            </div>
                                            <div class="help-info">Category</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12 col-md-12 col-xs-12">
                                        <form id="saveVariantForm" action="{{ route('client.master-data.inventory-product-variant-store') }}" method="post"> 
                                            @csrf
                                            <table class="table table-bordered table-hover custom-table" id="variantTable">
                                                <tr class="bg-info">
                                                    <th style="width:100px">Variant</th>
                                                    <th style="width:80px">Model</th>
                                                    <th style="width:80px">Code</th>
                                                    <th style="width:80px">Unit Name</th>
                                                    <th>Details</th>
                                                    <th style="width:37px"></th>
                                                </tr>
                                                <tr id='noDataTd'>
                                                    <th colspan='6' class='text-center'>No Data</th>
                                                </tr>
                                            </table>
                                            <input type="hidden" id="totalVariant" name="totalVariant"> 
                                            <input type="hidden" id="hiddenProduct" name="productCode"> 
                                            <input type="hidden" id="contenCheckVariantId" name="contenCheckVariantId"> 
                                            <input type="hidden" id="contenCheckUpdateDtTm" name="contenCheckUpdateDtTm"> 
                                            <input type="hidden" id="deleteVariant" name="deleteVariant" value=""> 
                                            <input type="hidden" id="variantType" name="variantType" value="{{ config('constants.PURCHASE') }}">
                                        </form>
                                        <!--<button type="button" class="btn btn-success " onclick="saveVariant()">Save Variant</button>-->
                                        <span id="addVariantDiv" style="display:none">
                                            <button class="btn btn-primary " onclick="addVariant()">Add more variant</button>
                                            <button class="btn btn-success " onclick="saveVariant()">Save Variant</button>
                                        </span>
                                        <!--<input type="hidden" id="totalVariant">-->
                                        <input type="hidden" id="checkFirstVariant" value="2">


                                        <a id="updateVariantModal" data-toggle="modal" data-target="#myModalVariantUpdate"></a>
                                        <div class="modal fade" id="myModalVariantUpdate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" id="modalCloseUpdate" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                        <h4 class="modal-title" id="myModalLabel">Variant Information</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group form-float" >
                                                                    <div class="form-line">
                                                                        <input type="text" id="variantNameUpdateModal" class="form-control">
                                                                    </div>
                                                                    <div class="help-info">Variant Name</div>
                                                                </div>
                                                            </div> 
                                                            <div class="col-md-6">
                                                                <div class="form-group form-float" >
                                                                    <div class="form-line">
                                                                        <input type="text" id="modelUpdateModal" class="form-control">
                                                                    </div>
                                                                    <div class="help-info">Model</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group form-float" >
                                                                    <div class="form-line">
                                                                        <input type="text" id="displayCodeUpdateModal" class="form-control">
                                                                    </div>
                                                                    <div class="help-info">Code</div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group form-float" >
                                                                    <div class="form-line">
                                                                        <input type="text" id="unitNameUpdateModal" class="form-control">
                                                                    </div>
                                                                    <div class="help-info">Unit Name</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">    
                                                            <div class="col-md-12">
                                                                <div class="form-group form-float" >
                                                                    <div class="form-line">
                                                                        <textarea id="detailsUpdateModal" class="form-control" name="txtdesc" rows="4"></textarea>
                                                                        <label  class="form-label">Details</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="hidden" id="variantSerial">
                                                        <span id="setVariantBtnDiv">
                                                            <button class="btn btn-primary bg-blue btn-sm waves-effect" onclick="setVariantValue()"> OK </button>
                                                        </span>
                                                        <span id="setMoreVariantBtnDiv">
                                                            <button class="btn btn-primary bg-blue btn-sm waves-effect" onclick="setMoreVariantValue()"> OK </button>
                                                        </span>
                                                        <button class="btn btn-danger bg-blue btn-sm waves-effect" type="button" data-dismiss="modal" aria-label="Close">CANCEL</button>
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
        </div>
    </div>
</div>    
@endsection
@push('scripts')
<script>
    function setProductVariant(serial) {

        var counter = 1;

        var productCode = $('#productCode' + serial).val();
        var productName = $('#productName' + serial).val();
        var category = $('#category' + serial).val();

        showLoader();

        $('#checkFirstVariant').val('2');

        $.ajax({
            type: 'POST',

            data: {
                productCode: productCode,
                variantType: $('#variantType').val(),
                _token: "{{ csrf_token() }}"
            },

            url: "{{ route('client.master-data.setProductVariant') }}",

            success: function (result) {

                $("#variantTable").find("tr:not(:first)").remove();

                hideLoader();

                // Laravel already returns JSON, no need jQuery.parseJSON if controller uses response()->json()
                var jsonObj = (typeof result === 'string')
                    ? JSON.parse(result)
                    : result;

                for (var i = 0; i < jsonObj.variants.length; i++) {

                    var variantStr = "";
                    var deleteSpan = "";

                    var variantName = jsonObj.variants[i].variant_name;

                    var model = jsonObj.variants[i].model !== null
                        ? jsonObj.variants[i].model
                        : "";

                    var displayCode = jsonObj.variants[i].display_code !== null
                        ? jsonObj.variants[i].display_code
                        : "";

                    var unitName = jsonObj.variants[i].unit_name !== null
                        ? jsonObj.variants[i].unit_name
                        : "";

                    var variantDetails = jsonObj.variants[i].details !== null
                        ? jsonObj.variants[i].details
                        : "";

                    variantStr += "<td id='tdVariantName" + counter + "'>" + variantName + "</td>";
                    variantStr += "<td id='tdModel" + counter + "' >" + model + "</td>";
                    variantStr += "<td id='tdDisplayCode" + counter + "' >" + displayCode + "</td>";
                    variantStr += "<td id='tdUnitName" + counter + "' >" + unitName + "</td>";
                    variantStr += "<td id='tdDetails" + counter + "' >" + variantDetails + "</td>";


                    variantStr += "<input type='hidden' id='variantAutoIdHidden" + counter + "' name='variantAutoIdHidden" + counter + "' value='" + jsonObj.variants[i].id + "'>";
                    variantStr += "<input type='hidden' id='variantNameHidden" + counter + "' name='variantNameHidden" + counter + "' value='" + variantName + "'>";
                    variantStr += "<input type='hidden' id='modelHidden" + counter + "' name='modelHidden" + counter + "' value='" + model + "'>";
                    variantStr += "<input type='hidden' id='displayCodeHidden" + counter + "' name='displayCodeHidden" + counter + "' value='" + displayCode + "'>";
                    variantStr += "<input type='hidden' id='unitNameHidden" + counter + "' name='unitNameHidden" + counter + "' value='" + unitName + "'>";
                    variantStr += "<input type='hidden' id='detailsHidden" + counter + "' name='detailsHidden" + counter + "' value='" + variantDetails + "'>";

                    if (i !== 0) {
                        deleteSpan = " <span class='pointer' onclick='remvoveVariant(" + counter + ")'> <i class='fa fa-close'></i></span> ";
                    }

                    if (i === 0) {

                        $('#contenCheckVariantId').val(jsonObj.variants[i].id);
                        $('#contenCheckUpdateDtTm').val(jsonObj.variants[i].updated_dt_tm);

                        if (variantName !== 'Default') {
                            $('#addVariantDiv').show();
                        } else {
                            $('#addVariantDiv').hide();
                        }
                    }

                    variantStr += "<td class='text-center'>"
                                + "<span class='pointer' onclick='updateVariantModalShow(" + counter + ")'><i class='fa fa-pencil'></i></span>"
                                + deleteSpan +
                                "</td>";

                    var newRow = $("<tr>", { id: "variantRow" + counter });

                    newRow.html(variantStr);

                    $("#variantTable").append(newRow);

                    counter++;
                }

                $('#product').val(productName);
                $('#hiddenProduct').val(productCode);
                $('#category').val(category);
                $('#totalVariant').val(counter);
            }
        });
    }

    function updateVariantModalShow(serial) {
        $('#setMoreVariantBtnDiv').hide();
        $('#setVariantBtnDiv').show();
        $('#updateVariantModal').click();
        $('#variantNameUpdateModal').val($('#variantNameHidden' + serial).val());
        $('#modelUpdateModal').val($('#modelHidden' + serial).val());
        $('#displayCodeUpdateModal').val($('#displayCodeHidden' + serial).val());
        $('#unitNameUpdateModal').val($('#unitNameHidden' + serial).val());
        $('#detailsUpdateModal').val($('#detailsHidden' + serial).val());
        $('#variantSerial').val(serial);
    }

    function setVariantValue() {
        var serial = $('#variantSerial').val();

        var variantName = $.trim($('#variantNameUpdateModal').val());
        var model = $.trim($('#modelUpdateModal').val());
        var displayCode = $.trim($('#displayCodeUpdateModal').val());
        var unitName = $.trim($('#unitNameUpdateModal').val());
        var details = $.trim($('#detailsUpdateModal').val());
        var counter = $('#totalVariant').val();

        for (var j = 1; j <= counter; j++) {
            if (j !== parseInt(serial)) {

                if ($.trim($('#variantNameHidden' + j).val()).toLowerCase() === variantName.toLowerCase()) {
                    sweetAlert('You have already added this variant...!');
                    return false;
                }
            }

        }


        if (variantName === 'Default') {
            sweetAlert('You can not set variant name "Default"...!');
            return false;
        }

        if (unitName === '') {
            sweetAlert('Unit name is required...!');
            return false;
        }

        $('#tdVariantName' + serial).html(variantName);
        $('#tdModel' + serial).html(model);
        $('#tdDisplayCode' + serial).html(displayCode);
        $('#tdUnitName' + serial).html(unitName);
        $('#tdDetails' + serial).html(details);

        $('#variantNameHidden' + serial).val(variantName);
        $('#modelHidden' + serial).val(model);
        $('#displayCodeHidden' + serial).val(displayCode);
        $('#unitNameHidden' + serial).val(unitName);
        $('#detailsHidden' + serial).val(details);
        $('#modalCloseUpdate').click();
        $('#checkFirstVariant').val('1');
        $('#addVariantDiv').show();
    }

    function addVariant() {
        $('#setVariantBtnDiv').hide();
        $('#setMoreVariantBtnDiv').show();
        $('#variantNameUpdateModal').val("");
        $('#modelUpdateModal').val("");
        $('#displayCodeUpdateModal').val("");
        $('#unitNameUpdateModal').val("");
        $('#detailsUpdateModal').val('');
        $('#updateVariantModal').click();
    }

    function setMoreVariantValue() {
        var counter = $('#totalVariant').val();
        var variantName = $.trim($('#variantNameUpdateModal').val());
        var model = $.trim($('#modelUpdateModal').val());
        var displayCode = $.trim($('#displayCodeUpdateModal').val());
        var unitName = $.trim($('#unitNameUpdateModal').val());
        var details = $.trim($('#detailsUpdateModal').val());
        var deleteSpan = " <span class='pointer' onclick='remvoveVariant(" + counter + ")'> <i class='fa fa-close'></i></span> ";
        var variantStr = "";

        if (variantName === '') {
            $('#modalCloseUpdate').click();
            return false;
        }

        if (variantName === 'Default') {
            sweetAlert('You can not set variant name "Default"...!');
            return false;
        }

        if (unitName === '') {
            sweetAlert('Unit name is required...!');
            return false;
        }

        for (var j = 1; j < counter; j++) {
            if ($.trim($('#variantNameHidden' + j).val()).toLowerCase() === variantName.toLowerCase()) {
                sweetAlert('You have already added this variant...!');
                return false;
            }
        }

        variantStr += "<td id='tdVariantName" + counter + "'>" + variantName + "</td>";
        variantStr += "<td id='tdModel" + counter + "' >" + model + "</td>";
        variantStr += "<td id='tdDisplayCode" + counter + "' >" + displayCode + "</td>";
        variantStr += "<td id='tdUnitName" + counter + "' >" + unitName + "</td>";
        variantStr += "<td id='tdDetails" + counter + "' >" + details + "</td>";

        variantStr += "<input type='hidden' id='variantAutoIdHidden" + counter + "' name='variantAutoIdHidden" + counter + "' value='0'>";
        variantStr += "<input type='hidden' id='variantNameHidden" + counter + "' name='variantNameHidden" + counter + "' value='" + variantName + "'>";
        variantStr += "<input type='hidden' id='modelHidden" + counter + "' name='modelHidden" + counter + "' value='" + model + "'>";
        variantStr += "<input type='hidden' id='displayCodeHidden" + counter + "' name='displayCodeHidden" + counter + "' value='" + displayCode + "'>";
        variantStr += "<input type='hidden' id='unitNameHidden" + counter + "' name='unitNameHidden" + counter + "' value='" + unitName + "'>";
        variantStr += "<input type='hidden' id='detailsHidden" + counter + "' name='detailsHidden" + counter + "' value='" + details + "'>";
        variantStr += "<td class='text-center'><span class='pointer' onclick='updateVariantModalShow(" + counter + ")'><i class='fa fa-pencil'></i></span> " + deleteSpan + " </td>";
        var newRow = $(document.createElement('tr')).attr("id", 'variantRow' + counter);

        newRow.after().html(variantStr);
        newRow.appendTo("#variantTable");
        counter++;
        $('#totalVariant').val(counter);
        $('#modalCloseUpdate').click();
    }

    function remvoveVariant(counter) {

        var idArr = new Array();
        idArr.push($('#variantAutoIdHidden' + counter).val());
        if ($('#deleteVariant').val() !== "") {
            idArr.push($('#deleteVariant').val());
        }
        $('#deleteVariant').val(idArr.join());
        $("#variantRow" + counter).remove();
    }


    function saveVariant() {

        let varaintNameArr = [];
        let counter = $('#totalVariant').val();

        for (let j = 1; j < counter; j++) {

            let variantName = $('#variantNameHidden' + j).val();

            if (typeof (variantName) !== 'undefined') {
                varaintNameArr.push(variantName);
            }
        }

        showLoader();

        $.ajax({
            type: 'POST',

            url: '{{ route("client.master-data.check-dup-variant") }}',

            data: {
                _token: '{{ csrf_token() }}',
                variantNameJson: JSON.stringify(varaintNameArr),
                variantType: $('#variantType').val(),
                productCode: $('#hiddenProduct').val()
            },

            success: function (result) {

                hideLoader();

                if (result == 1) {

                    $('#saveVariantForm').submit();

                } else {

                    sweetAlert('Product variant is duplicate...!');
                }
            },

            error: function (xhr) {

                hideLoader();

                sweetAlert('Something went wrong. Please try again.');
            }
        });
    }

</script>
@endpush