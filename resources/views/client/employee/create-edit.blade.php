@extends('client.layouts.app')

@section('content')

<div class="block-header">
    <h2>ADD EMPLOYEE</h2><br>
    <div class="breadcrumb breadcrumb-bg-blue-grey">
        <li><a href="client/Home"> Home</a></li>
        <li><a href="#"> Employee</a></li>
        <li><a href=">client/Employee/employeeList"> Employee List</a></li>
        <li><a href="client/Employee/addEmpPersonalShow"> Add Employee</a></li>
    </div>
</div>
<div class="row clearfix">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="body">
                @include('client.employee.tab')
                
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

                <div id="errorDiv" class="alert alert-danger hidden">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                </div>

                <form action="{{ isset($data) ? route('client.employee.update', $data->id) : route('client.employee.store') }}" method="POST" id="insertForm">
                    @csrf
                    @if(isset($data))
                        @method('PUT')
                    @endif
                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingOne">
                                <div class="panel-title">
                                    <a role="button" data-toggle="collapse" data-parent="#" href="#personalCollapseOne" aria-expanded="true" aria-controls="personalCollapseOne">
                                        <i class="fa fa-user"></i> Personal Information
                                    </a>
                                </div>
                            </div>
                            <div id="personalCollapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">

                                                    <input 
                                                        type="text" 
                                                        class="form-control" 
                                                        name="employee_name" 
                                                        id="employeeName" 
                                                        value="{{ old('employee_name', $data->employee_name ?? '') }}">
                                                    <label class="form-label"> Full Name </label>
                                                </div>
                                                <label id="employeeNameError" class="error"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input 
                                                        type="text" 
                                                        class="form-control" 
                                                        name="national_id" 
                                                        id="nationalId"
                                                        value="{{ old('national_id', $data->national_id ?? '') }}">
                                                    <label class="form-label"> National ID No.</label>
                                                </div>
                                                <label id="nidError" class="error"></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <select class="form-control" name="gender">
                                                        <option value="">Nothing selected</option>
                                                        <option value="male" {{ old('gender', $data->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                                                        <option value="female" {{ old('gender', $data->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                                                    </select>
                                                    <div class="help-info">Gender</div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <select class="form-control" name="religion" >
                                                        <option value="">Nothing selected</option>
                                                        @foreach(['Islam','Hindu','Christian','Buddhist'] as $rel)
                                                            <option value="{{ $rel }}" {{ old('religion', $data->religion ?? '') == $rel ? 'selected' : '' }}>
                                                                {{ $rel }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <div class="help-info">Religion</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <select class="form-control" name="nationality" >
                                                        <option value="">Nothing selected</option>
                                                        <option value="Bangladeshi" {{ old('nationality', $data->nationality ?? '') == 'Bangladeshi' ? 'selected' : '' }}>Bangladeshi</option>
                                                        <option value="Other" {{ old('nationality', $data->nationality ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                                                    </select>
                                                    <div class="help-info">Nationality</div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
<!--                                                    <small class="custom-text-danger" id="dobError"></small>-->
                                                    <input type="date" class="form-control" name="dob" id="employeeDob" value="{{ old('dob', $data->dob ?? '') }}">
                                                </div>
                                                <div class="help-info">Date of Birth </div>
                                                <label id="dobError" class="error"></label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <select name="blood_group" class="form-control">
                                                        @foreach(['O+','O-','A+','A-','B+','B-','AB+','AB-'] as $bg)
                                                            <option value="{{ $bg }}" {{ old('blood_group', $data->blood_group ?? '') == $bg ? 'selected' : '' }}>
                                                                {{ $bg }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <div class="help-info">Blood Group</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <select class="form-control" name="marital_status" id="maritalStatus" onchange="setAnniversaryDate()">
                                                        <option value="">Nothing selected</option>
                                                        <option value="Single" {{ old('marital_status', $data->marital_status ?? '') == 'Single' ? 'selected' : '' }}>Single</option>
                                                        <option value="Married" {{ old('marital_status', $data->marital_status ?? '') == 'Married' ? 'selected' : '' }}>Married</option>
                                                    </select>
                                                    <div class="help-info">Marital Status</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-12" id="anniversaryDateDiv">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="date" class="form-control" name="anniversary" id="anniversaryDate" 
                                                    value="{{ old('anniversary', $data->anniversary ?? '') }}">
                                                    <!--<label class="form-label">Anniversary Date</label>-->
                                                </div>
                                                <div class="help-info">Anniversary Date</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="passport_no"
                                                    value="{{ old('passport_no', $data->passport_no ?? '') }}">
                                                    <label class="form-label"> Passport No</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="date" class="form-control" name="passposrt_expiry_date" id="selectDate2" 
                                                    value="{{ old('passposrt_expiry_date', $data->passposrt_expiry_date ?? '') }}">
                                                </div>
                                                <div class="help-info">Passport Expiry Date</div>
                                            </div>
                                        </div>		
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="driving_license_no"  id="drivingLicenseNo"
                                                    value="{{ old('driving_license_no', $data->driving_license_no ?? '') }}">
                                                    <label class="form-label"> Driving License No</label>
                                                </div>
                                                <label id="licenseNoError" class="error"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="date" class="form-control"  name="driving_license_expiry_date" id="selectDate3" 
                                                    value="{{ old('driving_license_expiry_date', $data->driving_license_expiry_date ?? '') }}">
                                                    <label class="form-label"> Driving License Expiry Date</label>
                                                </div>
                                                <div class="help-info">Driving License Expiry Date</div>
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
                                        <i class="fa fa-home"></i> Personal Contact Information
                                    </a>
                                </div>
                            </div>
                            <div id="personalContactCollapse" class="panel-collapse collapse" role="tabpanel" aria-labelledby="personalContactHeading">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
<!--                                                    <small class="custom-text-danger" id="mobilNoError"></small>-->
                                                    <input type="text" class="form-control" name="primary_mobile" id="primaryMobile" onchange="checkMobileNumber(this.value, 'primaryMobile')" 
                                                    value="{{ old('primary_mobile', $data->primary_mobile ?? '') }}">
                                                    <label class="form-label"> Primary Mobile No </label>
                                                </div>
                                                <label id="mobilNoError" class="error"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="secendary_mobile" id="secendaryMobile" onchange="checkMobileNumber(this.value, 'secendaryMobile')"  
                                                    value="{{ old('secendary_mobile', $data->secendary_mobile ?? '') }}">
                                                    <label class="form-label"> Secondary Mobile No </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="employee_tnt_phone" 
                                                    value="{{ old('employee_tnt_phone', $data->employee_tnt_phone ?? '') }}">
                                                    <label class="form-label"> Land Phone </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="email" class="form-control" name="email" id="email" onchange="checkEmail(this.value, this.id)"
                                                    value="{{ old('email', $data->email ?? '') }}">
                                                    <label class="form-label"> Email </label>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="present_address"  
                                                    value="{{ old('present_address', $data->present_address ?? '') }}">
                                                    <label class="form-label"> Present Address </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="employee_permanent_address" 
                                                    value="{{ old('employee_permanent_address', $data->employee_permanent_address ?? '') }}"/>
                                                    <label class="form-label"> Permanent Address </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="parentHeading">
                                <div class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#" href="#parentCollapse" aria-expanded="false" aria-controls="parentCollapse">
                                        <i class="fa fa-user"></i> Parent Information
                                    </a>
                                </div>
                            </div>
                            <div id="parentCollapse" class="panel-collapse collapse" role="tabpanel" aria-labelledby="parentHeading">
                                <div class="panel-body ">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="father_name" 
                                                    value="{{ old('father_name', $data->father_name ?? '') }}">
                                                    <label class="form-label"> Father's Name </label>
                                                </div>
                                            </div>	
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <select class="form-control" name="father_occupation">
                                                        <option value="">Nothing selected</option>
                                                        <?php
                                                        foreach ($occupations as $occupation) {
                                                            echo "<option value='$occupation[element_code]'>$occupation[element]</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                    <div class="help-info">Father's Occupation</div>
                                                </div>
                                            </div>	
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="father_office_address" 
                                                    value="{{ old('father_office_address', $data->father_office_address ?? '') }}">
                                                    <label class="form-label"> Father's Office Address </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="father_contact" id="fatherContactNo" onchange="checkMobileNumber(this.value, 'fatherContactNo')"
                                                    value="{{ old('father_contact', $data->father_contact ?? '') }}">
                                                    <label class="form-label"> Father's Contact No </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="mother_name" 
                                                    value="{{ old('mother_name', $data->mother_name ?? '') }}">
                                                    <label class="form-label"> Mother's Name </label>
                                                </div>
                                            </div>	
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <select class="form-control" name="mother_occupation">
                                                        <option value="">Nothing selected</option>
                                                        <?php
                                                        foreach ($occupations as $occupation) {
                                                            echo "<option value='$occupation[element_code]'>$occupation[element]</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                    <div class="help-info">Mother's Occupation</div>

                                                </div>
                                            </div>	
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="mother_office_address"  
                                                    value="{{ old('mother_office_address', $data->mother_office_address ?? '') }}">
                                                    <label class="form-label"> Mother's Office Address </label>
                                                </div>
                                            </div>	
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="mother_contact" id="motherContactNo" onchange="checkMobileNumber(this.value, 'motherContactNo')" 
                                                    value="{{ old('mother_contact', $data->mother_contact ?? '') }}">
                                                    <label class="form-label"> Mother's Contact No </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="guardianHeading">
                                <div class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#" href="#guardianCollapse" aria-expanded="false" aria-controls="guardianCollapse">
                                        <i class="fa fa-user"></i> Guardian Information
                                    </a>
                                </div>
                            </div>
                            <div id="guardianCollapse" class="panel-collapse collapse" role="tabpanel" aria-labelledby="guardianHeading">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="guardian_name"  
                                                    value="{{ old('guardian_name', $data->guardian_name ?? '') }}">
                                                    <label class="form-label"> Guardian's Name </label>
                                                </div>
                                            </div>	
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="guardian_relation"  
                                                    value="{{ old('guardian_relation', $data->guardian_relation ?? '') }}">
                                                    <label class="form-label"> Guardian's Relation </label>
                                                </div>
                                            </div>	
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="guardian_house_address"  
                                                    value="{{ old('guardian_house_address', $data->guardian_house_address ?? '') }}">
                                                    <label class="form-label"> Guardian's House Address </label>
                                                </div>
                                            </div>	
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="guardian_contact" id="guardianContactNo" onchange="checkMobileNumber(this.value, 'guardianContactNo')"
                                                    value="{{ old('guardian_contact', $data->guardian_contact ?? '') }}">
                                                    <label class="form-label"> Guardian's Contact No </label>
                                                </div>
                                            </div>	
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>


                        <div class="panel panel-default" id="spouseInformationDiv">
                            <div class="panel-heading" role="tab" id="spouseHeading">
                                <div class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#" href="#spouseCollapse" aria-expanded="false" aria-controls="spouseCollapse">
                                        <i class="fa fa-user"></i> Spouse Information
                                    </a>
                                </div>
                            </div>
                            <div id="spouseCollapse" class="panel-collapse collapse" role="tabpanel" aria-labelledby="spouseHeading">
                                <div class="panel-body ">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">

                                                    <input type="text" class="form-control" name="spouse_name"
                                                    value="{{ old('spouse_name', $data->spouse_name ?? '') }}">
                                                    <label class="form-label"> Spouse Name </label>
                                                </div>
                                            </div>	
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <select class="form-control" name="spouse_occupation">
                                                        <option value="">Nothing selected</option>
                                                        <?php
                                                        foreach ($occupations as $occupation) {
                                                            echo "<option value='$occupation[element_code]'>$occupation[element]</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                    <div class="help-info">Spouse's Occupation</div>

                                                </div>
                                            </div>	
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">

                                                    <input type="text" class="form-control" name="spouse_office_address"
                                                     value="{{ old('spouse_office_address', $data->spouse_office_address ?? '') }}">
                                                    <label class="form-label"> Spouse Office Address </label>
                                                </div>
                                            </div>	
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="spouse_contact"  id="spouseContactNo" onchange="checkMobileNumber(this.value, 'spouseContactNo')"
                                                    value="{{ old('spouse_contact', $data->spouse_contact ?? '') }}">
                                                    <label class="form-label"> Spouse Contact No </label>
                                                </div>
                                            </div>	
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="emergencyHeading">
                                <div class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#" href="#emergencyCollapse" aria-expanded="false" aria-controls="emergencyCollapse">
                                        <i class="fa fa-tag"></i> Emergency Contact Information
                                    </a>
                                </div>
                            </div>
                            <div id="emergencyCollapse" class="panel-collapse collapse" role="tabpanel" aria-labelledby="emergencyHeading">
                                <div class="panel-body ">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">

                                                    <input type="text" class="form-control" name="emer_contact_name"
                                                    value="{{ old('emer_contact_name', $data->emer_contact_name ?? '') }}">
                                                    <label class="form-label"> Emergency Contact Name </label>
                                                </div>
                                            </div>	
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="emer_conatct_mobile" id="emerConatctMobile" onchange="checkMobileNumber(this.value, 'emerConatctMobile')"
                                                    value="{{ old('emer_conatct_mobile', $data->emer_conatct_mobile ?? '') }}">
                                                    <label class="form-label"> Emergency Contact Mobile No </label>
                                                </div>
                                            </div>	
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="emer_contact_relation"
                                                    value="{{ old('emer_contact_relation', $data->emer_contact_relation ?? '') }}">
                                                    <label class="form-label"> Relationship</label>
                                                </div>
                                            </div>	
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="emer_contact_address"
                                                    value="{{ old('emer_contact_address', $data->emer_contact_address ?? '') }}">
                                                    <label class="form-label"> Address</label>
                                                </div>
                                            </div>	
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="parentHeading">
                                <div class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#" href="#referenceCollapse" aria-expanded="false" aria-controls="referenceCollapse">
                                        <i class="fa fa-users"></i> Reference Information
                                    </a>
                                </div>
                            </div>
                            <div id="referenceCollapse" class="panel-collapse collapse" role="tabpanel" aria-labelledby="referenceHeading">
                                <div class="panel-body ">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="ref_one_name"
                                                    value="{{ old('ref_one_name', $data->ref_one_name ?? '') }}">
                                                    <label class="form-label"> Person Name (Reference 1) </label>
                                                </div>
                                            </div>	
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="ref_one_mobile" id="referenceMobile1" onchange="checkMobileNumber(this.value, this.id)"
                                                    value="{{ old('ref_one_mobile', $data->ref_one_mobile ?? '') }}">
                                                    <label class="form-label"> Mobile No </label>
                                                </div>
                                            </div>	
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="ref_one_email"  id="referenceEmail1"  onchange="checkEmail(this.value, this.id)"
                                                    value="{{ old('ref_one_email', $data->ref_one_email ?? '') }}">
                                                    <label class="form-label"> Email </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="ref_one_address" id="fatherContactNo"
                                                    value="{{ old('ref_one_address', $data->ref_one_address ?? '') }}">
                                                    <label class="form-label"> Address </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="ref_two_name"
                                                    value="{{ old('ref_two_name', $data->ref_two_name ?? '') }}">
                                                    <label class="form-label"> Person Name (Reference 2) </label>
                                                </div>
                                            </div>	
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="ref_two_mobile" onchange="checkMobileNumber(this.value, this.id)"
                                                    value="{{ old('ref_two_mobile', $data->ref_two_mobile ?? '') }}">
                                                    <label class="form-label"> Mobile No </label>
                                                </div>
                                            </div>	
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="ref_two_email" onchange="checkEmail(this.value, this.id)"
                                                    value="{{ old('ref_two_email', $data->ref_two_email ?? '') }}">
                                                    <label class="form-label">Email </label>
                                                </div>
                                            </div>	
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="ref_two_address" id="motherContactNo" onchange="checkMobileNumber(this.value, 'motherContactNo')"
                                                    value="{{ old('ref_two_address', $data->ref_two_address ?? '') }}">
                                                    <label class="form-label"> Address </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </form>
                <button class="btn btn-success btn-primary" onclick="insertEmployee()">Save</button>
            </div> <!-- end class=body -->
        </div> <!-- end class="card" -->
    </div> <!-- end class="col-xs-12 col-sm-12 col-md-12 col-lg-12" -->
</div> <!-- end class="row clearfix" -->
    
@endsection
@push('scripts')
<script>
    function insertEmployee() {
        var employeeNameStr = "<strong><li>Employee Name is required</strong></li>";
        //var dateOfBirthStr = "<strong><li>Date of Birth is requried</li></strong>";
        var mobileNumberStr = "<strong><li>Primary Mobile No is requried</li></strong>";

        // var nidStr = "<strong><li>National ID No is requried</li></strong>";
        var drivingLicenseStr = "<strong><li>Driving License No is requried</li></strong>";

        var msgArray = new Array();
        var employeeName = $.trim($('#employeeName').val());
        var primaryMobile = $.trim($('#primaryMobile').val());
        //var dob = $.trim($('#employeeDob').val());

        // var nid = $.trim($('#nid').val());
        var drivingLicenseNo = $.trim($('#drivingLicenseNo').val());

        if (employeeName === "") {
            document.getElementById('employeeNameError').innerHTML = ' Employee Name is required';
            msgArray = getErrorBlockMsg(employeeNameStr, msgArray);
        } else {
            document.getElementById('employeeNameError').innerHTML = '';
        }

        if (primaryMobile === "") {
            document.getElementById('mobilNoError').innerHTML = ' Primary Mobile No is required';
            msgArray = getErrorBlockMsg(mobileNumberStr, msgArray);
        } else {
            document.getElementById('mobilNoError').innerHTML = '';
        }

        // if (dob === "") {
        //     document.getElementById('dobError').innerHTML = ' Date ob Birth is required';
        //     msgArray = getErrorBlockMsg(dateOfBirthStr, msgArray);
        // } else {
        //     document.getElementById('dobError').innerHTML = '';
        // }

        // if (nid === "") {
        //     document.getElementById('nidError').innerHTML = ' National ID No is requried';
        //     msgArray = getErrorBlockMsg(nidStr, msgArray);
        // } else {
        //     document.getElementById('nidError').innerHTML = '';
        // }

        if (drivingLicenseNo === "") {
            document.getElementById('licenseNoError').innerHTML = ' Driving License No is requried';
            msgArray = getErrorBlockMsg(drivingLicenseStr, msgArray);
        } else {
            document.getElementById('licenseNoError').innerHTML = '';
        }


        showArrayErrorMsg(msgArray);
        if (msgArray.length > 0) {
            return false;
        }

        $('#insertForm').submit();
        // showLoader();
        // $.ajax({
        //     type: 'POST',
        //     data: {employeeName: employeeName, primaryMobile: primaryMobile, checkFlag: 'add'},
        //     url: 'client/Employee/employeeDuplicateCheck',
        //     success: function (result) {
        //         hideLoader();
        //         if (result === '1') {
        //             $('#insertForm').submit();

        //         } else if (result === '2') {
        //             alert('You have already inserted this employee...!');
        //             return false;
        //         }
        //     }
        // });

    }
    function setAnniversaryDate() {

        if ($('#maritalStatus').val() === 'Single') {
            $('#anniversaryDateDiv').hide();
            $('#spouseInformationDiv').hide();
        } else {
            $('#anniversaryDateDiv').show();
            $('#spouseInformationDiv').show();
        }
    }
</script>
@endpush
