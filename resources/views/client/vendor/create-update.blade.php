@extends('client.layouts.app')

@section('content')

<div class="block-header">
    <h2>ADD VENDOR</h2><br>
    <div class="breadcrumb breadcrumb-bg-blue-grey">
        <li><a href="/client/Home"> Home</a></li>
        <li><a href="#"> Master Data</a></li>
        <li><a href="/client/MasterData/vendor"> Vendor List</a></li>
        <li><a href="/client/MasterData/addVendorShow"> Add Vendor</a></li>
    </div>
</div>
<div class="row clearfix">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="body">
                <?php
                // $this->data['btnFlag'] = 'generalnfo';
                // $this->data['vendorCode'] = "";
                // $this->load->view('client/masterData/vendorHeaderMenu', $this->data);
                ?>
                @include('client.vendor.tab')

                <br>
                <div id="errorDiv" class="alert alert-danger hidden">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                
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

                <form 
                    action="{{ isset($vendor) 
                        ? route('client.vendor.venor-list.update', $vendor->vendor_code) 
                        : route('client.vendor.venor-list.store') }}" 
                    method="POST" 
                    id="insertForm">

                    @csrf

                    @if(isset($vendor))
                        @method('PUT')
                    @endif
                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingOne">
                                <div class="panel-title">
                                    <a role="button" data-toggle="collapse" data-parent="#" href="#personalCollapseOne" aria-expanded="true" aria-controls="personalCollapseOne">
                                        <i class="fa fa-user"></i> Vendor Information
                                    </a>
                                </div>
                            </div>
                            <div id="personalCollapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="title" id="title"
                                                    value="{{ old('title', $vendor->title ?? '') }}">
                                                    <label class="form-label">Vendor Title/Name</label>
                                                </div>
                                                <span class="error">
                                                    @error('title')
                                                        {{ $message }}
                                                    @enderror
                                                </span>
                                                <label id="titleReq-error" class="error"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="address"
                                                    value="{{ old('address', $vendor->address ?? '') }}">
                                                    <label class="form-label">Address</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="email"
                                                    value="{{ old('email', $vendor->vendor_email ?? '') }}">
                                                    <label class="form-label">Email</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="website" id="vendorMobile"
                                                    value="{{ old('website', $vendor->website ?? '') }}">
                                                    <label class="form-label">Website</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="vendor_mobile" id="vendorMobile" onchange="checkMobileNumber(this.value, this.id)"
                                                    value="{{ old('vendor_mobile', $vendor->vendor_mobile ?? '') }}">
                                                    <label class="form-label">Mobile</label>
                                                </div>
                                                <span class="error">
                                                    @error('vendor_mobile')
                                                        {{ $message }}
                                                    @enderror
                                                </span>
                                                <label id="vendorMobileReq-error" class="error"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="vendor_land_phone" id="vendorLandPhone"
                                                    value="{{ old('vendor_land_phone', $vendor->vendor_land_phone ?? '') }}">
                                                    <label class="form-label">Land Phone</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <select class="form-control" name="division" onchange="setDistrict(this.value)" id="division">
                                                        <option value="">--  Select  Division--</option>
                                                        @foreach ($divisions ?? [] as $division)
                                                            <option value="{{ $division['id'] }}"
                                                                {{ isset($vendor) && $vendor->division == $division['id'] ? 'selected' : '' }}>
                                                                {{ $division['division_en_name'] }} ({{ $division['division_bn_name'] }})
                                                            </option>
                                                        @endforeach

                                                    </select>
                                                    <div class="help-info">Division</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <div id="districtDiv">
                                                        <select class="form-control" name="district" id="district" >
                                                            <option value="">--  Select  District--</option>
                                                        </select>
                                                    </div>
                                                    <div class="help-info">District</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12" id="anniversary_date_div">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <div id="uplozillaDiv">
                                                        <select class="form-control" name="upozilla" id="upozilla" >
                                                            <option value="">--  Select  Upozilla--</option>
                                                        </select>
                                                    </div>
                                                    <div class="help-info">Upozilla</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="postal_code"
                                                    value="{{ old('postal_code', $vendor->postal_code ?? '') }}">
                                                    <label class="form-label"> Postal Code</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="latitude"
                                                    value="{{ old('latitude', $vendor->latitude ?? '') }}">
                                                    <label class="form-label"> Latitude</label>
                                                </div>
                                            </div>
                                        </div>		
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="longitude"
                                                    value="{{ old('longitude', $vendor->longitude ?? '') }}">
                                                    <label class="form-label">Longitude</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>	
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="personalContactHeading">
                                <div class="panel-title">
                                    <a class="collapsed" id="personalContactLink" role="button" data-toggle="collapse" data-parent="#" href="#personalContactCollapse" aria-expanded="false" aria-controls="personalContactCollapse">
                                        <i class="fa fa-home"></i> Contact Information
                                    </a>
                                </div>
                            </div>
                            <div id="personalContactCollapse" class="panel-collapse collapse" role="tabpanel" aria-labelledby="personalContactHeading">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="primary_contact_person"
                                                    value="{{ old('primary_contact_person', $vendor->primary_contact_person ?? '') }}">
                                                    <label class="form-label"> Primary Contact Person </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="primary_contact_designation"
                                                    value="{{ old('primary_contact_designation', $vendor->primary_contact_designation ?? '') }}">
                                                    <label class="form-label">Designation</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="primary_contact_mobile" id="primaryContactMobile"  onchange="checkMobileNumber(this.value, this.id)"
                                                    value="{{ old('primary_contact_mobile', $vendor->primary_contact_mobile ?? '') }}">
                                                    <label class="form-label">Mobile</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="primary_contact_email" id="primaryContactEmail" onchange="checkEmail(this.value, this.id)"
                                                    value="{{ old('primary_contact_email', $vendor->primary_contact_email ?? '') }}">
                                                    <label class="form-label"> Email </label>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="second_contact_person"
                                                    value="{{ old('second_contact_person', $vendor->second_contact_person ?? '') }}">
                                                    <label class="form-label">Secondary Contact Person</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="second_contact_designation"
                                                    value="{{ old('second_contact_designation', $vendor->second_contact_designation ?? '') }}">
                                                    <label class="form-label"> Designation</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="second_contact_mobile" id="secondaryContactMobile"  onchange="checkMobileNumber(this.value, this.id)"
                                                    value="{{ old('second_contact_mobile', $vendor->second_contact_mobile ?? '') }}">
                                                    <label class="form-label">Mobile</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="second_contact_email" id="secondaryContactEmail" onchange="checkEmail(this.value, this.id)"
                                                    value="{{ old('second_contact_email', $vendor->second_contact_email ?? '') }}">
                                                    <label class="form-label">Email</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <button class="btn btn-success btn-primary" onclick="addVendor()">Save</button>
            </div> <!-- end class=body -->
        </div> <!-- end class="card" -->
    </div> <!-- end class="col-xs-12 col-sm-12 col-md-12 col-lg-12" -->
</div> <!-- end class="row clearfix" -->
    
@endsection
@push('scripts')
<script>
    var districtObj = @json(['districtData' => $districts]);
    var upozillaObj = @json(['upozillaData' => $upozillas]);

    document.addEventListener("DOMContentLoaded", function () {

        let division = "{{ $vendor->division }}";
        let district = "{{ $vendor->district }}";
        let upozilla = "{{ $vendor->upozilla }}";

        // Step 1: set district
        setDistrict(division, district);

        // Step 2: set upozilla (after district ready)
        setTimeout(function () {
            setUpozilla(district, upozilla);
        }, 100);
    });

    function setDistrict(divisionDropDown, selectedDistrict = null) {

        var optionStr = "<option value=''>Nothing Selected</option>";

        for (var i = 0; i < districtObj.districtData.length; i++) {

            var item = districtObj.districtData[i];

            if (item.division.id == divisionDropDown) {

                var selected = (selectedDistrict && selectedDistrict == item.id) ? "selected" : "";

                optionStr += "<option value='" + item.id + "' " + selected + ">" +
                    item.district_en_name +
                    " ( " + item.district_bn_name + " ) </option>";
            }
        }

        $('#districtDiv').html(
            '<select class="form-control" name="district" id="district" ' +
            'onchange="setUpozilla(this.value)">' +
            optionStr +
            '</select>'
        );
    }

    function setUpozilla(districtDropDown, selectedUpozilla = null) {

        var optionStr = "<option value=''>Nothing Selected</option>";

        for (var i = 0; i < upozillaObj.upozillaData.length; i++) {

            var item = upozillaObj.upozillaData[i];

            if (item.district_id == districtDropDown) {

                var selected = (selectedUpozilla && selectedUpozilla == item.id) ? "selected" : "";

                optionStr += "<option value='" + item.id + "' " + selected + ">" +
                    item.upozilla_en_name +
                    " ( " + item.upozilla_bn_name + " ) </option>";
            }
        }

        $('#uplozillaDiv').html(
            '<select class="form-control" name="upozilla" id="upozilla">' +
            optionStr +
            '</select>'
        );
    }
    
    
    function addVendor() {
        var vendorTitleStr = "<strong><li>Vendor Name is required</strong></li>";
        var mobileNumberStr = "<strong><li>Mobile No is required</li></strong>";

        var msgArray = new Array();
        var title = $.trim($('#title').val());
        var mobile = $.trim($('#vendorMobile').val());
        
        if (title === "") {
            document.getElementById('titleReq-error').innerHTML = 'Vendor Name is required';
            msgArray = getErrorBlockMsg(vendorTitleStr, msgArray);
        } else {
            document.getElementById('titleReq-error').innerHTML = '';
        }
        
        if (mobile === "") {
            document.getElementById('vendorMobileReq-error').innerHTML = 'Mobile No is required';
            msgArray = getErrorBlockMsg(mobileNumberStr, msgArray);
        } else {
            document.getElementById('vendorMobileReq-error').innerHTML = '';
        }
        
        showArrayErrorMsg(msgArray);
        if (msgArray.length > 0) {
            return false;
        }
        
        showLoader();
        $('#insertForm').submit();
    }
</script>
@endpush