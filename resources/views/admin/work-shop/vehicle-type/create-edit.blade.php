@extends('layouts.app')

@section('content')

<div class="header dashboard_from">
    <h1 class="page-title">
        {{ isset($data->exists) ? 'Edit Vehicle Type' : 'Add Workshop' }}
    </h1>
</div>

@php
    $path = request()->path();
    $lastPart = collect(explode('/', $path))->last();
@endphp
<div class="container">
    <div class="card shadow">
        <div class="card-body">
            <!-- Nav Tabs -->
            @include('admin.work-shop.tab')
            {{-- Success Message --}}
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Validation Errors --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Tab Content -->
            <div class="tab-content" id="employeeTabContent">

                <div class="tab-pane fade show active"
                    id="personal"
                    role="tabpanel">
                    @php
                        $isEdit = isset($data);
                    @endphp
                    <div class="accordion" id="employeeAccordion">

                        {{-- MARM Automobiles pp --}}
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#personalInfo"
                                        aria-expanded="true">
                                    MARM Automobiles pp
                                </button>
                            </h2>

                            <div id="personalInfo"
                                class="accordion-collapse collapse show"
                                data-bs-parent="#employeeAccordion">

                                <div class="accordion-body">

                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <form action="{{ route('admin.workshop-vehicle-type.update',$data->workshop_code) }}" method="post" id="vehicleTypeForm">
                                            @csrf
                                             @method('PUT')
                                            <div class="table-responsive">
                                                <table class="table table-bordered custom-table">
                                                    <thead>
                                                        <tr class="bg-info">
                                                            <th width="30">Serial</th>
                                                            <th>Vehicle Type</th>
                                                            <th class="text-center">Selection</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php $serial = 1; @endphp
                                                        @foreach ($vehicleTypes as $vehicleType)
                                                            @php
                                                                $checkBoxFlag = '';
                                                                $vehicleTypeId = '';
                                                                
                                                                // Keeping your existing nested loop logic
                                                                foreach ($workshopVehicleTypes as $workshopVehicleType) {
                                                                    // Check if it's an array or object based on your controller output
                                                                    $vtCode = is_array($workshopVehicleType) ? $workshopVehicleType->vehicle_type : $workshopVehicleType->vehicle_type;
                                                                    $vtId = is_array($workshopVehicleType) ? $workshopVehicleType->id : $workshopVehicleType->id;

                                                                    if ($vtCode == $vehicleType->element_code) {
                                                                        $checkBoxFlag = 'checked';
                                                                        $vehicleTypeId = $vtId;
                                                                    }
                                                                }
                                                            @endphp

                                                            <tr>
                                                                <td class="td-center">{{ $serial }}</td>
                                                                <td>{{ $vehicleType->element }}</td>
                                                                <td class="td-center">
                                                                    <input type="checkbox" name="vehicleTypeCheckBox{{ $serial }}" 
                                                                        id="vehicleTypeCheckBox{{ $serial }}" 
                                                                        onclick="vehicleTypeCheck({{ $serial }})" {{ $checkBoxFlag }}>
                                                                </td>
                                                                
                                                                {{-- Hidden Fields --}}
                                                                <input type="hidden" name="vehicleTypeCode{{ $serial }}" id="vehicleTypeCode{{ $serial }}" value="{{ $vehicleType->element_code }}">
                                                                <input type="hidden" name="vehicleTypeName{{ $serial }}" id="vehicleTypeName{{ $serial }}" value="{{ $vehicleType->element }}">
                                                                <input type="hidden" name="vehicleTypeId{{ $serial }}" id="vehicleTypeId{{ $serial }}" value="{{ $vehicleTypeId }}">
                                                            </tr>
                                                            @php $serial++; @endphp
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>

                                            <input type="hidden" name="workshopCode" value="{{ $workshopCode }}">
                                            {{-- $serial now holds the total count after the loop --}}
                                            <input type="hidden" name="vehicleTypeSerial" id="vehicleTypeSerial" value="{{ $serial - 1 }}">
                                            <input type="hidden" name="removeVehicleTypeIdStr" id="removeVehicleTypeIdStr">
                                        </form>

                                        <button class="btn btn-primary save_button" onclick="saveVehicleType()">Save Vehicle Type</button>
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
    var removeVehicleTypeArr = new Array();
    function vehicleTypeCheck(serial) {
        if (!$('#vehicleTypeCheckBox' + serial).is(':checked')) {
            var vehicleTypeId = $('#vehicleTypeId' + serial).val();
            if (vehicleTypeId !== "") {
                removeVehicleTypeArr.push(vehicleTypeId);
                removeVehicleTypeArr = jQuery.unique(removeVehicleTypeArr);
            }
        } else {
            var vehicleTypeId = $('#vehicleTypeId' + serial).val();
            if (vehicleTypeId !== "") {
                removeVehicleTypeArr.splice($.inArray(vehicleTypeId, removeVehicleTypeArr), 1);
            }
        }
        // console.log(serial);
    }

    function saveVehicleType() {
        showLoader();
        var flag = 0;
        var serial = $('#vehicleTypeSerial').val();
        //console.log(serial);
        for (var i = 1; i < serial; i++) {
            if ($('#vehicleTypeCheckBox' + i).is(':checked')) {
                //console.log(1);
                flag = 1;
            }
        }
        
        if(flag === 1){
            $('#removeVehicleTypeIdStr').val(removeVehicleTypeArr.join());
             $('#vehicleTypeForm').submit();
        }else{
            hideLoader();
            sweetAlert('Please select at least one vehicle type...!');
        }
    }
    function showLoader(){}
</script>
@endpush