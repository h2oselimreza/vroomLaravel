@extends('layouts.app')

@section('content')

<div class="header dashboard_from">
    <h1 class="page-title">
        {{ isset($data->exists) ? 'Edit Company' : 'Add Company' }}
    </h1>
</div>

@php
    $path = request()->path(); // returns 'admin/employee-office-info'
    $lastPart = collect(explode('/', $path))->last();
@endphp
<div class="container">
    <div class="card shadow">
        <div class="card-body">
            <!-- Nav Tabs -->
            @include('admin.corporate_customer.nav-tab')
            {{-- Success Message --}}
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
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
                    <form action="{{ $isEdit 
                        ? route('admin.company-modules.update', $data->id) 
                        : route('admin.company-modules.store') }}"
                        method="POST">

                    @csrf

                    @if($isEdit)
                        @method('PUT')
                    @endif

                        <div class="accordion" id="employeeAccordion">

                            {{-- Company Information --}}
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#personalInfo"
                                            aria-expanded="true">
                                        Company Information
                                    </button>
                                </h2>

                                <div id="personalInfo"
                                    class="accordion-collapse collapse show"
                                    data-bs-parent="#employeeAccordion">

                                    <div class="accordion-body">

                                        <div class="row g-3">
                                            {{-- Full Name --}}
                                            <div class="col-md-6">
                                                <label class="form-label">Company Title/Name</label>
                                                <input type="text"
                                                    name="title"
                                                    value="{{ old('title', $data->title ?? '') }}"
                                                    placeholder="Enter Title"
                                                    class="form-control @error('title') is-invalid @enderror">

                                                @error('title')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- Address --}}
                                            <div class="col-md-6">
                                                <label class="form-label">Address</label>
                                                <input type="text"
                                                    name="address"
                                                    value="{{ old('address', $data->address ?? '') }}"
                                                    placeholder="Enter national ID number"
                                                    class="form-control @error('address') is-invalid @enderror">

                                                @error('address')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- email --}}
                                            <div class="col-md-6">
                                                <label class="form-label">Email</label>
                                                <input type="text"
                                                    name="company_email"
                                                    value="{{ old('company_email', $data->company_email ?? '') }}"
                                                    placeholder="Email"
                                                    class="form-control @error('company_email') is-invalid @enderror">

                                                @error('company_email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- Website --}}
                                            <div class="col-md-6">
                                                <label class="form-label">Website</label>
                                                <input type="text"
                                                    name="website"
                                                    value="{{ old('website', $data->website ?? '') }}"
                                                    placeholder="Website"
                                                    class="form-control @error('website') is-invalid @enderror">

                                                @error('website')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- companyMobile --}}
                                            <div class="col-md-6">
                                                <label class="form-label">Mobile</label>
                                                <input type="text"
                                                    name="company_mobile"
                                                    value="{{ old('company_mobile', $data->company_mobile ?? '') }}"
                                                    placeholder="Mobile"
                                                    class="form-control @error('company_mobile') is-invalid @enderror">

                                                @error('company_mobile')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- Land Phone --}}
                                            <div class="col-md-6">
                                                <label class="form-label">Land Phone</label>
                                                <input type="text"
                                                    name="company_land_phone"
                                                    value="{{ old('company_land_phone', $data->company_land_phone ?? '') }}"
                                                    placeholder="Mobile"
                                                    class="form-control @error('company_land_phone') is-invalid @enderror">

                                                @error('company_land_phone')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            {{-- Division --}}
                                            <div class="col-md-6">
                                                <label class="form-label">Division</label>
                                                <select name="division"
                                                    class="form-select @error('division') is-invalid @enderror" id="division">
                                                    <option value="">Select Division</option>
                                                    @foreach($divisions as $division)
                                                        <option value="{{ $division->id }}"
                                                            {{ old('religion', $data->division ?? '')==$division->id?'selected':'' }}>
                                                            {{ $division->division_en_name }} ({{ $division->division_bn_name }})
                                                        </option>
                                                    @endforeach
                                                </select>

                                                @error('division')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- District --}}
                                            <div class="col-md-6">
                                                <label class="form-label">District</label>
                                                <select name="district"
                                                    class="form-select @error('district') is-invalid @enderror" id="district">
                                                        <option value="">Select District</option>
                                                </select>
                                                @error('district')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- Upozilla --}}
                                            <div class="col-md-6">
                                                <label class="form-label">Upozilla</label>
                                                <select name="upozilla"
                                                    class="form-select @error('upozilla') is-invalid @enderror" id="upozilla">
                                                    <option value="">Select District</option>
                                                </select>

                                                @error('upozilla')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            @if ($isEdit)
                                                <input type="hidden" id="selected_district" value="{{ $data->district }}">
                                                <input type="hidden" id="selected_upazila" value="{{ $data->upozilla }}">
                                            @endif

                                            {{-- Postal Code --}}
                                            <div class="col-md-6">
                                                <label class="form-label">Postal Code</label>
                                                <input type="text"
                                                    name="postal_code"
                                                    value="{{ old('postal_code', $data->postal_code ?? '') }}"
                                                    placeholder="Postal code"
                                                    class="form-control @error('postal_code') is-invalid @enderror">

                                                @error('postal_code')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- Latitude --}}
                                            <div class="col-md-6">
                                                <label class="form-label">Latitude</label>
                                                <input type="text"
                                                    name="latitude"
                                                    value="{{ old('latitude', $data->latitude ?? '') }}"
                                                    placeholder="Latitude"
                                                    class="form-control @error('latitude') is-invalid @enderror">

                                                @error('latitude')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- longitude --}}
                                            <div class="col-md-6">
                                                <label class="form-label">longitude</label>
                                                <input type="text"
                                                    name="longitude"
                                                    value="{{ old('longitude', $data->longitude ?? '') }}"
                                                    placeholder="longitude"
                                                    class="form-control @error('longitude') is-invalid @enderror">

                                                @error('longitude')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Contact Information --}}
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed"
                                            type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#contactInfo">
                                        Contact Information
                                    </button>
                                </h2>

                                <div id="contactInfo"
                                    class="accordion-collapse collapse"
                                    data-bs-parent="#employeeAccordion">

                                    <div class="accordion-body">

                                        <div class="row g-3">

                                            {{-- primaryContactPerson --}}
                                            <div class="col-md-6">
                                                <label class="form-label">Primary Contact Person</label>
                                                <input type="text"
                                                    name="primary_contact_person"
                                                    value="{{ old('primary_contact_person', $data->primary_contact_person ?? '') }}"
                                                    placeholder="Enter primary mobile number"
                                                    class="form-control @error('primary_contact_person') is-invalid @enderror">

                                                @error('primary_contact_person')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- primaryContactDesignation --}}
                                            <div class="col-md-6">
                                                <label class="form-label">Designation</label>
                                                <input type="text"
                                                    name="primary_contact_designation"
                                                    value="{{ old('primary_contact_designation', $data->primary_contact_designation ?? '') }}"
                                                    placeholder="Enter primary mobile number"
                                                    class="form-control @error('primary_contact_designation') is-invalid @enderror">

                                                @error('primary_contact_designation')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- Mobile --}}
                                            <div class="col-md-6">
                                                <label class="form-label">Mobile</label>
                                                <input type="text"
                                                    name="primary_contact_mobile"
                                                    value="{{ old('primary_contact_mobile', $data->primary_contact_mobile ?? '') }}"
                                                    placeholder="Mobile"
                                                    class="form-control @error('primary_contact_mobile') is-invalid @enderror">

                                                @error('primary_contact_mobile')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- primaryContactEmail --}}
                                            <div class="col-md-6">
                                                <label class="form-label">Email</label>
                                                <input type="email"
                                                    name="primary_contact_email"
                                                    value="{{ old('primary_contact_email', $data->primary_contact_email ?? '') }}"
                                                    placeholder="Enter email"
                                                    class="form-control @error('primary_contact_email') is-invalid @enderror">

                                                @error('primary_contact_email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- Secondary Contact Person --}}
                                            <div class="col-md-6">
                                                <label class="form-label">Secondary Contact Person</label>
                                                <input type="text"
                                                    name="second_contact_person"
                                                    value="{{ old('second_contact_person', $data->second_contact_person ?? '') }}"
                                                    placeholder="Enter present address"
                                                    class="form-control @error('second_contact_person') is-invalid @enderror">

                                                @error('second_contact_person')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- Designation --}}
                                            <div class="col-md-6">
                                                <label class="form-label">Designation</label>
                                                <input type="text"
                                                    name="second_contact_designation"
                                                    value="{{ old('second_contact_designation', $data->second_contact_designation ?? '') }}"
                                                    placeholder="Enter permanent address"
                                                    class="form-control @error('second_contact_designation') is-invalid @enderror">

                                                @error('second_contact_designation')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- Mobile --}}
                                            <div class="col-md-6">
                                                <label class="form-label">Mobile</label>
                                                <input type="text"
                                                    name="second_contact_mobile"
                                                    value="{{ old('second_contact_mobile', $data->second_contact_mobile ?? '') }}"
                                                    placeholder="Mobile"
                                                    class="form-control @error('second_contact_mobile') is-invalid @enderror">

                                                @error('second_contact_mobile')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- primaryContactEmail --}}
                                            <div class="col-md-6">
                                                <label class="form-label">Email</label>
                                                <input type="email"
                                                    name="second_contact_email"
                                                    value="{{ old('second_contact_email', $data->second_contact_email ?? '') }}"
                                                    placeholder="Enter email"
                                                    class="form-control @error('second_contact_email') is-invalid @enderror">

                                                @error('second_contact_email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                        </div>


                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed"
                                            type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#parentInfo">
                                        Vehicle Tracking System Info
                                    </button>
                                </h2>

                                <div id="parentInfo"
                                    class="accordion-collapse collapse"
                                    data-bs-parent="#employeeAccordion">

                                    <div class="accordion-body">

                                        <div class="row g-3">
                                            {{-- VTS Company --}}
                                            <div class="col-md-6">
                                                <label class="form-label">VTS Company</label>
                                                <select name="vts_company"
                                                        class="form-select @error('vts_company') is-invalid @enderror">
                                                    <option value="easy_trax">
                                                            Easy Trax
                                                        </option>
                                                </select>

                                                @error('vts_company')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- vtsAppKey --}}
                                            <div class="col-md-6">
                                                <label class="form-label">VTS Company APP Key</label>
                                                <input type="text"
                                                    name="vts_app_key"
                                                    value="{{ old('vts_app_key', $data->vts_app_key ?? '') }}"
                                                    placeholder="VTS APP Key"
                                                    class="form-control @error('vts_app_key') is-invalid @enderror">

                                                @error('vts_app_key')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- Google Map API Key --}}
                                            <div class="col-md-6">
                                                <label class="form-label">Google Map API Key</label>
                                                <input type="text"
                                                    name="map_api_key"
                                                    value="{{ old('map_api_key', $data->map_api_key ?? '') }}"
                                                    placeholder="Google Map API Key"
                                                    class="form-control @error('map_api_key') is-invalid @enderror">

                                                @error('map_api_key')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-success save_button">
                                {{ $isEdit ? 'Update Company' : 'Save Company' }}
                            </button>
                        </div>

                    </form>                    
                </div>

            </div>

        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function(){
        $('.dateInput').datepicker({
            format: 'yyyy-mm-dd',  // format compatible with Laravel date column
            autoclose: true,       // close picker after selecting a date
            todayHighlight: true,  // highlight today
            clearBtn: true,        // optional clear button
            orientation: 'bottom'  // show below the input
        });

        let selectedDistrict = $('#selected_district').val();
       $('#division').on('change', function () {

        let division_id = $(this).val();

        $('#district').html('<option>Loading...</option>');
        $('#upozilla').html('<option>Select Upazila</option>');

        if (division_id) {
            $.ajax({
                url: '/admin/get-districts/' + division_id,
                type: 'GET',
                success: function (data) {

                    let option = '<option value="">Select District</option>';

                    data.forEach(function (item) {

                        // ✅ Set selected district on edit
                        let selected = (item.id == selectedDistrict) ? 'selected' : '';

                        option += `<option value="${item.id}" ${selected}>
                            ${item.district_en_name} (${item.district_bn_name})
                        </option>`;
                    });

                    $('#district').html(option);

                    // 🔥 IMPORTANT: trigger next dropdown
                    if (selectedDistrict) {
                        $('#district').trigger('change');
                    }
                }
            });
        }
    });

    let selectedUpazila = $('#selected_upazila').val();

    // ✅ District → Upazila
    $('#district').on('change', function () {

        let district_id = $(this).val();

        $('#upozilla').html('<option>Loading...</option>');

        if (district_id) {
            $.ajax({
                url: '/admin/get-upazilas/' + district_id,
                type: 'GET',
                success: function (data) {

                    let option = '<option value="">Select Upazila</option>';

                    data.forEach(function (item) {

                        // ✅ Set selected on edit
                        let selected = (item.id == selectedUpazila) ? 'selected' : '';

                        option += `<option value="${item.id}" ${selected}>
                            ${item.upozilla_en_name} (${item.upozilla_bn_name})
                        </option>`;
                    });

                    $('#upozilla').html(option);
                }
            });
        }
    });

     // 🔥 AUTO LOAD on edit page
    if ($('#division').val()) {
        $('#division').trigger('change');
    }

    });
</script>
@endpush
