@extends('client.layouts.app')

@section('content')

<div class="block-header">
    <h2>ADD VEHICLE</h2><br>
    <div class="breadcrumb breadcrumb-bg-blue-grey">
        <li><a href="client/Home"> Home</a></li>
        <li><a href="#"> Vehicle</a></li>
        <li><a href="{{ route('client.vehicle.index') }}"> Vehicle List</a></li>
        <li><a href="{{ route('client.vehicle.create') }}"> Add Vehicle</a></li>
    </div>
</div>

<div class="row clearfix">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="body">
                @include('client.vehicle.tab')
                
                <br>
                {{-- Success Message --}}
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

                <form action="{{ isset($data) ? route('client.vehicle.update', $data->id) : route('client.vehicle.store') }}" method="POST" id="insertForm">
                    @csrf
                    @if(isset($data))
                        @method('PUT')
                    @endif
                    <div class="panel-group">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="panel-title p-t-10 p-b-10 p-l-5">
                                    <i class="fa fa-bars"></i> Vehicle Details
                                </div>
                            </div>

                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group form-float" >
                                            <div class="form-line">
                                                <input type="text" class="form-control" name="registration_no" id="registrationNo" 
                                                value="{{ old('registration_no', $data->registration_no ?? '') }}">
                                                <label class="form-label"> Registration No</label>
                                            </div>
                                            @error('registration_no')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <label id="registrationNoReq-error" class="error hidden">Registration No is required</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group form-float" >
                                            <div class="form-line">
                                                <select class="form-control" name="vehicle_type" id="vehicleType">
                                                    <option value=""></option>
                                                    @foreach ($vehicleTypes as $vehicleType)
                                                        <option value="{{ $vehicleType->element_code }}"
                                                            {{ old('vehicle_type', $data->vehicle_type ?? '') == $vehicleType->element_code ? 'selected' : '' }}>
                                                            {{ $vehicleType->element }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="help-info">Vehicle Type</div>
                                            </div>
                                            @error('vehicle_type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <label id="vehicleTypeReq-error" class="error hidden">Vehicle Type is required</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <select class="form-control" name="brand" id="brand"
                                                    onchange="setBrandModel(this.value)">
                                                    <option value=""></option>
                                                    @foreach ($brands as $brand)
                                                        <option value="{{ $brand->element_code }}"
                                                            {{ old('brand', $data->brand ?? '') == $brand->element_code ? 'selected' : '' }}>
                                                            {{ $brand->element }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="help-info">Brand</div>
                                            </div>
                                            @error('brand')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <label id="brandReq-error" class="error hidden">Brand is required</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group form-float" >
                                            <div class="form-line">
                                                <div id="brandModelDiv">
                                                    <select class="form-control" name="brand_model" id="brandModel">
                                                        <option value=""></option>
                                                        {{--@if(isset($brandModels))
                                                            @foreach ($brandModels as $brandModel)
                                                                <option value="{{ $brandModel->element_code }}"
                                                                    {{ old('brand_model', $data->brand_model ?? '') == $brandModel->element_code ? 'selected' : '' }}>
                                                                    {{ $brandModel->element }}
                                                                </option>
                                                            @endforeach
                                                        @endif--}}
                                                    </select>
                                                </div>
                                                <div class="help-info">Brand Model</div>

                                            </div>
                                            @error('brand_model')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                            <label id="brandModelReq-error" class="error hidden">Brand Model is required</label>
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group form-float" >
                                            <div class="form-line">
                                                <select class="form-control" name="vehicle_class" id="vechicleClass">
                                                    <option value=""></option>
                                                    @foreach ($vehicleClasses as $vehicleClass)
                                                        <option value="{{ $vehicleClass->element_code }}"
                                                            {{ old('vehicle_class', $data->vehicle_class ?? '') == $vehicleClass->element_code ? 'selected' : '' }}>
                                                            {{ $vehicleClass->element }} ({{ $vehicleClass->element_bn }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="help-info">Vehicle Class</div>
                                            </div>
                                            @error('vehicle_class')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <label id="vechicleClassReq-error" class="error hidden">Vehicle Class is required</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group form-float" >
                                            <div class="form-line">
                                                <input type="text" class="form-control" name="vehicle_cc" id="vechcleCC" 
                                                value="{{ old('vehicle_cc', $data->vehicle_cc ?? '') }}">
                                                <label class="form-label"> Vehicle CC </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group form-float" >
                                            <div class="form-line">
                                                <input type="number" class="form-control" name="manufacturing_year" id="manufacturingYear" 
                                                value="{{ old('manufacturing_year', $data->manufacturing_year ?? '') }}">
                                                <label class="form-label"> Manufacturing Year</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group form-float" >
                                            <div class="form-line">
                                                <input type="text" class="form-control" name="manufacturing_country" id="manufacturingCountry"
                                                value="{{ old('manufacturing_country', $data->manufacturing_country ?? '') }}">
                                                <label class="form-label"> Manufacturing Country</label>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group form-float" >
                                            <div class="form-line">
                                                <input type="text" class="form-control" name="engine_no" id="engineNo"
                                                value="{{ old('engine_no', $data->engine_no ?? '') }}">
                                                <label class="form-label"> Engine No</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group form-float" >
                                            <div class="form-line">
                                                <input type="text" class="form-control" name="chasis_no" id="chasisNo"
                                                value="{{ old('chasis_no', $data->chasis_no ?? '') }}">
                                                <label class="form-label">Chassis No</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group form-float" >
                                            <div class="form-line">
                                                <select class="form-control" name="color" id="color">
                                                    <option value=""></option>
                                                    @foreach ($colors as $color)
                                                        <option value="{{ $color->element_code }}"
                                                            {{ old('color', $data->color ?? '') == $color->element_code ? 'selected' : '' }}>
                                                            {{ $color->element }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="help-info">Vehicle Color</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                         <div class="form-group form-float" >
                                            <div class="form-line">
                                                <select class="form-control" name="vehicle_group" id="group">
                                                    <option value=""></option>
                                                    @foreach ($groups as $group)
                                                        <option value="{{ $group->element_code }}"
                                                            {{ old('vehicle_group', $data->vehicle_group ?? '') == $group->element_code ? 'selected' : '' }}>
                                                            {{ $group->element }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="help-info">Vehicle Group</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingOne">
                                <div class="panel-title">
                                    <a role="button" data-toggle="collapse" data-parent="#" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        <i class="fa fa-link"></i> Vehicle Tracking System Info
                                    </a>
                                </div>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="communication_code" id="communicationCode"
                                                    value="{{ old('communication_code', $data->communication_code ?? '') }}">
                                                    <label class="form-label"> Communication Code </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <button class="btn bg-blue waves-effect" onclick="insertVehicle()">Save</button>
                <br><br>

                <span class="text-danger">
                    <small>
                        <b> 
                            {{-- $packageDetails = get_package_info(PACKAGE_VEHICLE_COUNT, $this->companyCode);
                            if ($packageDetails['success'] == 1) {
                                echo "*** You can add " . $packageDetails['vehicleCount'] . " vehicles";
                            }
                            ?>  --}}
                            
                        </b>
                    </small>
                </span>

            </div> <!-- end class=body -->
        </div> <!-- end class="card" -->
    </div> <!-- end class="col-xs-12 col-sm-12 col-md-12 col-lg-12" -->
</div> <!-- end class="row clearfix" -->

    
@endsection
@push('scripts')
    <script>
    var brandModelObj = @json($brandModels);
    $(document).ready(function () {
        var brand = $('#brand').val();
        var model = "{{ old('brand_model', $data->brand_model ?? '') }}";
        if (brand) {
            setBrandModel(brand, model);
        }
    });

    function setBrandModel(brand, selectedModel = null) {
        var optionStr = "<option value=''>Nothing Selected</option>";

        for (var i = 0; i < brandModelObj.brandModelsData.length; i++) {

            var item = brandModelObj.brandModelsData[i];

            if (item.depend_on_element === brand) {

                var selected = (selectedModel && selectedModel == item.element_code) ? 'selected' : '';

                optionStr += "<option value='" + item.element_code + "' " + selected + ">"
                    + item.element +
                    "</option>";
            }
        }

        $('#brandModelDiv').html(
            '<select class="form-control" name="brand_model" id="brandModel">' 
            + optionStr + 
            '</select>'
        );
    }


    function insertVehicle() {
        var errorMsg = "";
        // filed id, error div id
        var fieldsArr = new Array("vehicleType|vehicleTypeReq-error", "brand|brandReq-error", "brandModel|brandModelReq-error", "vechicleClass|vechicleClassReq-error", "registrationNo|registrationNoReq-error");  // filed id, error div id
        var inputFiledJsonData = getInputData(fieldsArr);
        if (!inputFiledJsonData) {
            errorMsg += getReuiredFiledErrorMsg();
            showErrorMsg(errorMsg);
            return false;  // required filed check
        } else {
            hideErrorDiv();
        }

        showLoader();
        $('#insertForm').submit();

        // $.ajax({
        //     type: 'POST',
        //     data: {registrationNo: $('#registrationNo').val(), checkFlag: 'add'},
        //     url: '#client/Vehicle/duplicateVehicleCheck',
        //     success: function (result) {
        //         hideLoader();
        //         if (result === '1') {
                    
        //         } else if (result === '2') {
        //             sweetAlert('You have already inserted this vehicle...!');
        //             return false;
        //         } else if (result === '3') {
        //             sweetAlert('You can not add vehicle because of package limit exceeds...!');
        //             return false;
        //         }
        //     }
        // });

    }

</script>
@endpush
