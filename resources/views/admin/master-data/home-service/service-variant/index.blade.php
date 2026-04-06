@extends('layouts.app')

@section('content')

<div class="header dashboard_from">
    <h1 class="page-title">Home service variant</h1>
    <ul class="breadcrumb">
        <li><a href="{{ url('admin/Home') }}">Home</a></li>
        <li><a href="#">/ Master Data</a></li>
        <li><a href="#">/ Home service variant</a></li>
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

            @include('admin.master-data.home-service.tab')

            <div class="panel panel-default"> 
                <div class="add-button">
                    <a href="{{ route('admin.modules.master-data.home-service-variant.create') }}">Manage Variants</a>
                </div>
                <div class="table-responsive">

                    <table class="table table-bordered table-hover custom-table" id="datatable">
                        <thead>
                            <tr class="bg-primary">
                                <th class="text-center">SL</th>
                                <th class="text-center">Service</th>
                                <th class="text-start">Category</th>
                                <th class="text-start">Service Name</th>
                                <th class="text-center">Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if ($data)
                                @foreach ($data as $value)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $value->category->category_name }}</td>
                                        <td>{{ $value->service_name  }}</td>
                                        <td class="text-center">{{ ($value->is_active) ? 'Active':'Inactive' }}</td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown">
                                                    Action
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a href="{{ $value ? route('admin.modules.master-data.home-service-list.edit', $value->service_code ) : '#' }}" 
                                                        class="d-block ps-3">
                                                            <span class="ui-button-text">Update</span>
                                                        </a>                                    
                                                    </li>
                                                    <li class="mt-2">
                                                        <form action="{{ route('admin.modules.master-data.home-service-list.toggle', $value->service_code) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="d-block ps-3 active_button">
                                                                <span>
                                                                    {{ $value->is_active ? 'Inactive' : 'Active' }}
                                                                </span>
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="text-center">No Data Found</td>
                                </tr>
                            @endif
                        </tbody>

                        <tfoot>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>

                    </table>

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
        showLoader();
        $('#checkFirstVariant').val('2');

        $.ajax({
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}", // Added CSRF Token
                serviceCode: serviceCode, 
                variantType: $('#variantType').val()
            },
            url: "{{ url('admin/MasterData/setServiceVariant') }}", // Laravel URL Helper
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

            showLoader();
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