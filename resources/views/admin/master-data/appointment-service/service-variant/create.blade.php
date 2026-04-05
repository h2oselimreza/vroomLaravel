@extends('layouts.app')

@section('content')

<div class="header dashboard_from">
    <h1 class="page-title">Appointment Service Variant</h1>
    <ul class="breadcrumb">
        <li><a href="{{ url('admin/Home') }}">Home</a></li>
        <li><a href="#">/ Master Data</a></li>
        <li><a href="#">/ Manage Service Variant</a></li>
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

    @include('admin.master-data.appointment-service.tab')

    <div class="row">
        <div class="col-sm-12 col-md-6 col-xs-12">
            <div class="panel panel-default"> 
                <div class="table-responsive">
                    <div class="text-center"><h4><b>Service List</b></h4></div>
                    <br>

                    <table class="table table-bordered table-hover custom-table" id="datatable">
                        <thead>
                            <tr class="bg-primary">
                                <th>SL</th>
                                <th>Category</th>
                                <th>Service Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach ($serviceListData as $service)
                                <tr>
                                    @php 
                                        $serial = $loop->iteration; 
                                        $category = $service->category_name;
                                    @endphp
                                    <td class='td-center'>{{ $serial }}</td>
                                    <td>{{ $service->category_name }}</td>
                                    <td>{{ $service->service_name }}</td>
                                    <td class='td-center'>
                                        <button class='btn btn-primary btn-circle-vairant' onclick="setServiceVariant('{{ $serial }}')">
                                            <i class='glyphicon glyphicon-chevron-right'></i>
                                        </button>
                                        <input type="hidden" id="serviceCode{{ $serial }}" value="{{ $service->service_code }}">
                                        <input type="hidden" id="serviceName{{ $serial }}" value="{{ $service->service_name }}">
                                        <input type="hidden" id="category{{ $serial }}" value="{{ $category }}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <small>** Select Service to set variant</small>
            </div>
        </div>

        <div class="col-sm-12 col-md-6 col-xs-12">
            <div class="panel panel-default"> 
                <div class="row mb-3">
                    <div class="text-center"><h4><b>Variants</b></h4></div><br>
                    <div class="col-sm-12 col-md-6 col-xs-12">
                        <div class="form-group">
                            <label class="form-label">Service Name</label>
                            <input type="text" class="form-control" id="service" disabled>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-xs-12">
                        <div class="form-group">
                            <label class="form-label">Category</label>
                            <input type="text" class="form-control" id="category" disabled>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 col-md-12 col-xs-12">
                        {{-- Laravel requires @csrf for POST forms --}}
                        <form id="saveVariantForm" action="{{ url('admin/MasterData/saveServiceVariant') }}" method="post"> 
                            @csrf
                            <table class="table table-bordered table-hover custom-table" id="variantTable">
                                <tr class="bg-info">
                                    <th>Variant</th>
                                    <th style="width:30px"></th>
                                </tr>
                                <tr id='noDataTd'>
                                    <th colspan='5' class='text-center'>No Data</th>
                                </tr>
                            </table>
                            <input type="hidden" id="totalVariant" name="totalVariant"> 
                            <input type="hidden" id="hiddenService" name="serviceCode"> 
                            <input type="hidden" id="contenCheckVariantId" name="contenCheckVariantId"> 
                            <input type="hidden" id="contenCheckUpdateDtTm" name="contenCheckUpdateDtTm"> 
                            <input type="hidden" id="deleteVariant" name="deleteVariant" value=""> 
                            {{-- Assuming APPOINTMENT_SER is a constant or config --}}
                            <input type="hidden" id="variantType" name="variantType" value="{{ defined('APPOINTMENT_SER') ? APPOINTMENT_SER : '' }}">
                        </form>

                        <span id="addVariantDiv" style="display:none">
                            <button class="btn btn-primary" onclick="addVariant()">Add more variant</button>
                            <button class="btn btn-success" onclick="saveVariant()">Save Variant</button>
                        </span>
                        
                        <input type="hidden" id="checkFirstVariant" value="2">

                        <a id="updateVariantModal" data-toggle="modal" data-target="#myModalVariantUpdate"></a>
                        <div class="modal fade" id="myModalVariantUpdate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog modal-sm" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" id="modalCloseUpdate" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="myModalLabel">Variant Information</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Variant</label><span class="custom-text-danger"> *</span>
                                                    <input type="text" id="variantNameUpdateModal" class="form-control">
                                                </div>
                                            </div> 
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <input type="hidden" id="variantSerial">
                                        <span id="setVariantBtnDiv">
                                            <button class="btn btn-primary" onclick="setVariantValue()"> OK </button>
                                        </span>
                                        <span id="setMoreVariantBtnDiv">
                                            <button class="btn btn-primary" onclick="setMoreVariantValue()"> OK </button>
                                        </span>
                                        <button class="btn btn-danger" type="button" data-dismiss="modal" aria-label="Close">Cancel</button>
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
    $(document).ready(function () {

        $('#datatable').DataTable({
            pageLength: 10,
            ordering: true,
            searching: true
        });

    });

    
    function setServiceVariant(serial) {
        var counter = 1;
        var serviceCode = $('#serviceCode' + serial).val();
        var serviceName = $('#serviceName' + serial).val();
        var category = $('#category' + serial).val();
        //showLoader();
        $('#checkFirstVariant').val('2');

        $.ajax({
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}", // Added CSRF Token
                serviceCode: serviceCode, 
                variantType: $('#variantType').val()
            },
            url: "{{ url('admin/master-data/setServiceVariant') }}", // Laravel URL Helper
            success: function (result) {
                $("#variantTable").find("tr:not(:first)").remove();
                hideLoader();
                
                var jsonObj = jQuery.parseJSON(result);

                for (var i = 0; i < jsonObj.variants.length; i++) {
                    var variantStr = "";
                    var deleteSpan = "";
                    var variantName = jsonObj.variants[i].service_variant_name;

                    variantStr += "<td id='tdVariantName" + counter + "'>" + variantName + "</td>";
                    variantStr += "<input type='hidden' id='variantAutoIdHidden" + counter + "' name='variantAutoIdHidden" + counter + "' value='" + jsonObj.variants[i].id + "'>";
                    variantStr += "<input type='hidden' id='variantNameHidden" + counter + "' name='variantNameHidden" + counter + "' value='" + variantName + "'>";

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

                    variantStr += "<td class='text-center'><span class='pointer' onclick='updateVariantModalShow(" + counter + ")'><i class='fa fa-pencil'></i></span> " + deleteSpan + " </td>";
                    var newRow = $(document.createElement('tr')).attr("id", 'variantRow' + counter);
                    newRow.after().html(variantStr);
                    newRow.appendTo("#variantTable");
                    counter++;
                }
                $('#service').val(serviceName);
                $('#hiddenService').val(serviceCode);
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
        $('#variantSerial').val(serial);
    }

    function setVariantValue() {
        var serial = $('#variantSerial').val();
        var variantName = $.trim($('#variantNameUpdateModal').val());
        var counter = $('#totalVariant').val();
        for (var j = 1; j < counter; j++) {
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
        $('#tdVariantName' + serial).html(variantName);
        $('#variantNameHidden' + serial).val(variantName);
        $('#modalCloseUpdate').click();
        $('#checkFirstVariant').val('1');
        $('#addVariantDiv').show();
    }

    function addVariant() {
        $('#setVariantBtnDiv').hide();
        $('#setMoreVariantBtnDiv').show();
        $('#variantNameUpdateModal').val("");
        $('#updateVariantModal').click();
    }

    function setMoreVariantValue() {
        var counter = $('#totalVariant').val();
        var variantName = $.trim($('#variantNameUpdateModal').val());
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

        for (var j = 1; j < counter; j++) {
            if ($.trim($('#variantNameHidden' + j).val()).toLowerCase() === variantName.toLowerCase()) {
                sweetAlert('You have already added this variant...!');
                return false;
            }
        }

        variantStr += "<td id='tdVariantName" + counter + "'>" + variantName + "</td>";
        variantStr += "<input type='hidden' id='variantAutoIdHidden" + counter + "' name='variantAutoIdHidden" + counter + "' value='0'>";
        variantStr += "<input type='hidden' id='variantNameHidden" + counter + "' name='variantNameHidden" + counter + "' value='" + variantName + "'>";
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
        var currentAutoId = $('#variantAutoIdHidden' + counter).val();
        
        idArr.push(currentAutoId);
        if ($('#deleteVariant').val() !== "") {
            idArr.push($('#deleteVariant').val());
        }
        $('#deleteVariant').val(idArr.join());
        $("#variantRow" + counter).remove();
    }

    function saveVariant() {
        if (confirm('Are you sure ?')) {
            var varaintNameArr = new Array();
            var counter = $('#totalVariant').val();
            for (var j = 1; j < counter; j++) {
                var variantName = $('#variantNameHidden' + j).val();
                if (typeof (variantName) !== 'undefined') {
                    varaintNameArr.push(variantName);
                }
            }

            //showLoader();
            $.ajax({
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}", // Added CSRF Token
                    variantNameJson: JSON.stringify(varaintNameArr), 
                    variantType: $('#variantType').val(),
                    serviceCode: $('#hiddenService').val()
                },
                url: "{{ url('admin/MasterData/checkDupSerVariant') }}", // Laravel URL Helper
                success: function (result) {
                    hideLoader();
                    if(result === "1"){
                        $("#saveVariantForm").submit();
                    }else{
                        sweetAlert('Service variant is duplicate...!');
                    }
                }
            });
        }
    }


</script>
@endpush
