@extends('layouts.app')

@section('content')

<div class="header dashboard_from">
    <h1 class="page-title">
        {{ isset($data->exists) ? 'Edit Official' : 'Add Official' }}
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

                    <div class="accordion" id="employeeAccordion">

                        {{-- Company Information --}}
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#personalInfo"
                                        aria-expanded="true">
                                    Package
                                </button>
                            </h2>

                            <div id="personalInfo"
                                class="accordion-collapse collapse show"
                                data-bs-parent="#employeeAccordion">

                                <div class="accordion-body">
                                <form  action="{{ route('admin.company.office.update') }}" method="post" id="updateForm">
                                    @csrf
                                    @if($data)
                                        @method('PUT')
                                    @endif
                                        <div class="row g-3">
                                            {{-- packages --}}
                                            <div class="col-md-6">
                                                <label class="form-label">Package</label>
                                                <select name="package"
                                                    class="form-select @error('package') is-invalid @enderror" onchange="setPackageDetails(this.value)" id="package">
                                                    <option value="">Select package</option>
                                                    @foreach($package as $value)
                                                        <option value="{{ $value->package_code }}"
                                                            {{ old('package', $data->package ?? '') == $value->package_code ?'selected':'' }}>
                                                            {{ $value->package_name  }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                @error('package')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <label class="form-label">Package Details</label>
                                                <div class="panel panel-default" style="min-height: 100px">
                                                    <div id="package-heading" class="font-15 text-center font-bold">
                                                    </div>

                                                    <div class="m-t-10">
                                                        <ul class="package-details-list" id="package-details">
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <input type="hidden" name="company_code" id="company_code"
                                                        value="{{ $company_code }}">
                                                    <button class="btn btn-primary save_button" onclick="editOfficial()">Edit Official</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        {{-- Notification Permission --}}
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#contactInfo">
                                    Notification Permission
                                </button>
                            </h2>

                            <div id="contactInfo"
                                class="accordion-collapse collapse"
                                data-bs-parent="#employeeAccordion">

                                <div class="accordion-body">
                                    <form action="{{ route('admin.company.notification-permission') }}" method="POST">
                                        @csrf
                                        @if($data)
                                            @method('PUT')
                                        @endif

                                        <table class="table table-bordered custom-table">
                                            <thead>
                                                <tr class="bg-primary">
                                                    <th>SL</th>
                                                    <th>Permission Title</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($notificationPermissionsMasterData as $index => $permission)
                                                    @php
                                                        $checked = in_array($permission->code, $companyNotificationPermissions ?? []) ? 'checked' : '';
                                                    @endphp
                                                    <tr>
                                                        <td class="td-center">{{ $index + 1 }}</td>
                                                        <td>{{ $permission->title }}</td>
                                                        <td class="td-center">
                                                            <input type="checkbox"
                                                                name="notificationPermissions[]"
                                                                value="{{ $permission->code }}"
                                                                {{ $checked }}>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>

                                        <input type="hidden" name="company_code" value="{{ $company_code }}">

                                        <button class="btn btn-primary save_button" type="submit">Save</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#parentInfo">
                                    SMS Settings
                                </button>
                            </h2>

                            <div id="parentInfo"
                                class="accordion-collapse collapse"
                                data-bs-parent="#employeeAccordion">
                                @php
                                    $settingsObj = json_decode($companySettings->description); // decode to object
                                @endphp
                                <div class="accordion-body">
                                <form   action="{{ route('admin.company.setting')}}" method="post" id="companySetting">
                                    @csrf
                                    @if($data)
                                        @method('PUT')
                                    @endif
                                    <div class="row g-3">
                                        {{-- url --}}
                                        <div class="col-md-6">
                                            <label class="form-label">URL</label>
                                            <input type="text"
                                                name="url"
                                                value="{{ old('url', $settingsObj->url ?? '') }}"
                                                placeholder="Enter url"
                                                class="form-control @error('url') is-invalid @enderror">

                                            @error('url')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- vtsAppKey --}}
                                        <div class="col-md-6">
                                            <label class="form-label">Sender ID</label>
                                            <input type="text"
                                                name="senderId"
                                                value="{{ old('senderId', $settingsObj->senderId ?? '') }}"
                                                placeholder="Enter sender id"
                                                class="form-control @error('senderId') is-invalid @enderror">

                                            @error('senderId')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Username --}}
                                        <div class="col-md-6">
                                            <label class="form-label">Username</label>
                                            <input type="text"
                                                name="username"
                                                value="{{ old('username', $settingsObj->username ?? '') }}"
                                                placeholder="Username"
                                                class="form-control @error('username') is-invalid @enderror">

                                            @error('username')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        {{-- Password --}}
                                        <div class="col-md-6">
                                            <label class="form-label">Password</label>
                                            <input type="text"
                                                name="password"
                                                value="{{ old('password', $settingsObj->password ?? '') }}"
                                                placeholder="password"
                                                class="form-control @error('password') is-invalid @enderror">

                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <input type="hidden" name="company_code" value="{{ $company_code }}">

                                    <button class="btn btn-primary save_button mt-3" type="submit">Save</button>
                                </form>
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
    $(document).ready(function(){
        $('.dateInput').datepicker({
            format: 'yyyy-mm-dd',  // format compatible with Laravel date column
            autoclose: true,       // close picker after selecting a date
            todayHighlight: true,  // highlight today
            clearBtn: true,        // optional clear button
            orientation: 'bottom'  // show below the input
        });

    });

    var packageObj = JSON.parse(@json($packageJson));

    function setPackageDetails(packageCode) {
        var packageDetailsJson = "";
        var packageName = "No Package select yet";
        var detailsList = "";

        if (packageCode) {

            for (var i = 0; i < packageObj.packageDetails.length; i++) {
                if (packageObj.packageDetails[i].package_code === packageCode) {
                    packageDetailsJson = packageObj.packageDetails[i].package_details;
                    packageName = packageObj.packageDetails[i].package_name;
                    break;
                }
            }

            // ✅ FIX: safe parse (avoid crash if empty)
            var packageDetailsObj = packageDetailsJson ? JSON.parse(packageDetailsJson) : {};

            detailsList = "<li>Max User: " + (packageDetailsObj.user?.count ?? 0) + "</li>\n\
                        <li>Max Vehicle: " + (packageDetailsObj.vehicle?.count ?? 0) + "</li>";
        }

        $('#package-heading').html(packageName);
        $('#package-details').html(detailsList);
    }

    var packageCode = $('#package').val(); // or use hidden input
    if (packageCode) {
        setPackageDetails(packageCode);
    }

    function setDistrict(divisionDropDown) {
        var optionStr = "<option value=''>Nothing Selected</option>";
        for (var i = 0; i < districtObj.districtData.length; i++) {
            if (districtObj.districtData[i].division === divisionDropDown) {
                optionStr += "<option value='" + districtObj.districtData[i].id + "'>" + districtObj.districtData[i].district_en_name + " ( " + districtObj.districtData[i].district_bn_name + ") </option>";
            }
        }
        $('#districtDiv').html('<select class="form-control" name="district" id="district" onclick="setUpozilla(this.value)" onchange="setUpozilla(this.value)">' + optionStr + '</select>');
    }

    function setUpozilla(districtDropDown) {
        var optionStr = "<option  value=''>Nothing Selected</option>";
        for (var i = 0; i < upozillaObj.upozillaData.length; i++) {
            if (upozillaObj.upozillaData[i].district === districtDropDown) {
                optionStr += "<option value='" + upozillaObj.upozillaData[i].id + "'>" + upozillaObj.upozillaData[i].upozilla_en_name + " (" + upozillaObj.upozillaData[i].upozilla_bn_name + ") </option>";
            }
        }
        $('#uplozillaDiv').html('<select class="form-control" name="upozilla" id="upozilla">' + optionStr + '</select>');
    }

    function editOfficial() {
        var errorMsg = "";
        // filed id, error div id
        var fieldsArr = new Array("package|packageReq-error");  // filed id, error div id
        var inputFiledJsonData = getInputData(fieldsArr);
        if (!inputFiledJsonData) {
            errorMsg += getReuiredFiledErrorMsg();
            showErrorMsg(errorMsg);
            return false;  // required filed check
        } else {
            hideErrorDiv();
        }
        $('#updateForm').submit();
    }
</script>
@endpush
