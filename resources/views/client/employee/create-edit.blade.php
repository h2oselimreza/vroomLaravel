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
                        {{ session('success') }}
                    </div>
                @endif
                {{-- Validation Errors --}}
                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <div id="errorDiv" class="alert alert-danger hidden">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                </div>

                <form  action="{{ route('client.employee.store') }}" method="POST" id="insertForm">
                    @csrf
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

                                                    <input type="text" class="form-control" name="employee_name" id="employeeName"   required>
                                                    <label class="form-label"> Full Name </label>
                                                </div>
                                                <label id="employeeNameError" class="error"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="national_id" id="nationalId">
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
                                                        <option value="male">Male</option>
                                                        <option value="female">Female</option>
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
                                                        <option value="Islam">Islam</option>
                                                        <option value="Hindu">Hindu</option>
                                                        <option value="Christian">Christian</option>
                                                        <option value="Buddhist">Buddhist</option>
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
                                                        <option value="Bangladeshi">Bangladeshi</option>
                                                        <option value="Other">Other</option>
                                                    </select>
                                                    <div class="help-info">Nationality</div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
<!--                                                    <small class="custom-text-danger" id="dobError"></small>-->
                                                    <input type="date" class="form-control" name="dob" id="employeeDob" >
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
                                                    <select class="form-control" name="blood_group" >
                                                        <option value="">Nothing selected</option>
                                                        <option value="O+">O+</option>
                                                        <option value="O-">O-</option>
                                                        <option value="A+">A+</option>
                                                        <option value="A-">A-</option>
                                                        <option value="B+">B+</option>
                                                        <option value="B-">B-</option>
                                                        <option value="AB+">AB+</option>
                                                        <option value="AB-">AB-</option>
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
                                                        <option value="Single">Single</option>
                                                        <option value="Married">Married</option>
                                                    </select>
                                                    <div class="help-info">Marital Status</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-12" id="anniversaryDateDiv">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="date" class="form-control" name="anniversary" id="anniversaryDate">
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
                                                    <input type="text" class="form-control" name="passport_no">
                                                    <label class="form-label"> Passport No</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="date" class="form-control" name="passposrt_expiry_date" id="selectDate2" >
                                                </div>
                                                <div class="help-info">Passport Expiry Date</div>
                                            </div>
                                        </div>		
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="driving_license_no"  id="drivingLicenseNo">
                                                    <label class="form-label"> Driving License No</label>
                                                </div>
                                                <label id="licenseNoError" class="error"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="date" class="form-control"  name="driving_license_expiry_date" id="selectDate3" >
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
                                                    <input type="text" class="form-control" name="primary_mobile" id="primaryMobile" onchange="checkMobileNumber(this.value, 'primaryMobile')"  required>
                                                    <label class="form-label"> Primary Mobile No </label>
                                                </div>
                                                <label id="mobilNoError" class="error"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="secendary_mobile" id="secendaryMobile" onchange="checkMobileNumber(this.value, 'secendaryMobile')"  >
                                                    <label class="form-label"> Secondary Mobile No </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="employee_tnt_phone" >
                                                    <label class="form-label"> Land Phone </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="email" class="form-control" name="email" id="email" onchange="checkEmail(this.value, this.id)">
                                                    <label class="form-label"> Email </label>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="present_address"  >
                                                    <label class="form-label"> Present Address </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="employee_permanent_address" >
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
                                                    <input type="text" class="form-control" name="father_name" >
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
                                                    <input type="text" class="form-control" name="father_office_address"   >
                                                    <label class="form-label"> Father's Office Address </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="father_contact" id="fatherContactNo" onchange="checkMobileNumber(this.value, 'fatherContactNo')">
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

                                                    <input type="text" class="form-control" name="mother_name" >
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
                                                    <input type="text" class="form-control" name="mother_office_address"  >
                                                    <label class="form-label"> Mother's Office Address </label>
                                                </div>
                                            </div>	
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="mother_contact" id="motherContactNo" onchange="checkMobileNumber(this.value, 'motherContactNo')" >
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
                                                    <input type="text" class="form-control" name="guardian_name"  >
                                                    <label class="form-label"> Guardian's Name </label>
                                                </div>
                                            </div>	
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">

                                                    <input type="text" class="form-control" name="guardian_relation"  >
                                                    <label class="form-label"> Guardian's Relation </label>
                                                </div>
                                            </div>	
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">

                                                    <input type="text" class="form-control" name="guardian_house_address"  >
                                                    <label class="form-label"> Guardian's House Address </label>
                                                </div>
                                            </div>	
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="guardian_contact" id="guardianContactNo" onchange="checkMobileNumber(this.value, 'guardianContactNo')"  >
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

                                                    <input type="text" class="form-control" name="spouse_name"  >
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

                                                    <input type="text" class="form-control" name="spouse_office_address" >
                                                    <label class="form-label"> Spouse Office Address </label>
                                                </div>
                                            </div>	
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="spouse_contact"  id="spouseContactNo" onchange="checkMobileNumber(this.value, 'spouseContactNo')"  >
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

                                                    <input type="text" class="form-control" name="emer_contact_name"  >
                                                    <label class="form-label"> Emergency Contact Name </label>
                                                </div>
                                            </div>	
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">

                                                    <input type="text" class="form-control" name="emer_conatct_mobile" id="emerConatctMobile" onchange="checkMobileNumber(this.value, 'emerConatctMobile')">
                                                    <label class="form-label"> Emergency Contact Mobile No </label>
                                                </div>
                                            </div>	
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="emer_contact_relation"  >
                                                    <label class="form-label"> Relationship</label>
                                                </div>
                                            </div>	
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="emer_contact_address"  >
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
                                                    <input type="text" class="form-control" name="ref_one_name" >
                                                    <label class="form-label"> Person Name (Reference 1) </label>
                                                </div>
                                            </div>	
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="ref_one_mobile" id="referenceMobile1" onchange="checkMobileNumber(this.value, this.id)">
                                                    <label class="form-label"> Mobile No </label>
                                                </div>
                                            </div>	
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="ref_one_email"  id="referenceEmail1"  onchange="checkEmail(this.value, this.id)" >
                                                    <label class="form-label"> Email </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="ref_one_address" id="fatherContactNo" >
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

                                                    <input type="text" class="form-control" name="ref_two_name" >
                                                    <label class="form-label"> Person Name (Reference 2) </label>
                                                </div>
                                            </div>	
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="ref_two_mobile" onchange="checkMobileNumber(this.value, this.id)">
                                                    <label class="form-label"> Mobile No </label>
                                                </div>
                                            </div>	
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="ref_two_email" onchange="checkEmail(this.value, this.id)" >
                                                    <label class="form-label">Email </label>
                                                </div>
                                            </div>	
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="ref_two_address" id="motherContactNo" onchange="checkMobileNumber(this.value, 'motherContactNo')" >
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
