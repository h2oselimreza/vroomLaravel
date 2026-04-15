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

                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                    @foreach ($vehicleDetails as $vehicleDetail)
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingOne">
                                <div class="panel-title">
                                    <a role="button" data-toggle="collapse" data-parent="#" href="#registrationCollapseOne" aria-expanded="true" aria-controls="registrationCollapseOne">
                                        <i class="fa fa-link"></i> Registration
                                    </a>
                                </div>
                            </div>
                            <div id="registrationCollapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                                <form action="{{ route('client.documentation.updateRegistration') }}" method="POST"  enctype="multipart/form-data" id="registrationForm">
                                    @csrf
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <div class="form-group form-float" >
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" value="{{ $vehicleDetail->registration_no }}" readonly>
                                                        <label class="form-label"> Registration No </label>
                                                    </div>
                                                    <!--<label id="employeeNameError" class="error"></label>-->
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <div class="form-group form-float" >
                                                    <div class="form-line">
                                                        <input type="date" class="form-control"  name="registrationDate" id="registrationDate" value="{{ $vehicleDetail->registration_date }}">
                                                    </div>
                                                    <div class="help-info">Registration Date</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <input type="number" class="form-control" name="seatCapacity" value="{{ $vehicleDetail->seat_capacity }}">
                                                        <label class="form-label"> Seat Capacity</label>

                                                    </div>
                                                </div>
                                            </div>
                                            <?php if (auth()->user()?->customerEmployee?->customer_type == config('constants.CORPORATE_CUST')) { ?>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <input type="file" class="form-control" name="vehicleFile[]" id='vehicleFile' onchange='checkFile(this, this.id);' multiple/>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php } ?>
                                        </div>
                                        <div class="row">
                                            <input type="hidden" name="vehicleId" id="vehicleId" value="<?php echo $vehicleId ?>">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input type="submit" class="btn bg-blue waves-effect" value="Save">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                @if (auth()->user()?->customerEmployee?->customer_type == config('constants.CORPORATE_CUST'))
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="text-center"><h4><b>Uploaded File List</b></h4></div>
                                            <div class="p-l-15 p-r-15">
                                                <table class="table table-bordered table-hover custom-table dataTable">
                                                    <thead>
                                                        <tr class="bg-info">
                                                            <th>SL</th>
                                                            <th>File Name</th>
                                                            <th>Show</th>
                                                            <th>Remove</th>
                                                        </tr>
                                                    </thead>
                                                    <tfoot>
                                                        <tr>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                        </tr>
                                                    </tfoot>
                                                    <tbody>
                                                        @php $serial = 1; @endphp

                                                        @forelse ($vehicleFiles as $vehicleFile)
                                                            @if ($vehicleFile->file_type == config('constants.REGISTRATION_FILE'))
                                                                <tr>
                                                                    <td>{{ $serial++ }}</td>

                                                                    <td class="td-left">
                                                                        {{ $vehicleFile->original_name }}
                                                                    </td>

                                                                    <td class="td-center">
                                                                        <a target="_blank"
                                                                        href="{{ asset('assets/client/vehicle/files/' . $vehicleFile->file_name) }}">
                                                                            Show
                                                                        </a>
                                                                    </td>

                                                                    <td class="td-center">
                                                                        <i class="material-icons pointer text-danger"
                                                                        onclick="deleteFile('{{ $vehicleFile->id }}')">
                                                                            delete_forever
                                                                        </i>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @empty
                                                            <tr>
                                                                <td colspan="4" class="text-center">No files found</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>	
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingTwo">
                                <div class="panel-title">
                                    <a role="button" data-toggle="collapse" data-parent="#" href="#fitnessCollapse" aria-expanded="true" aria-controls="fitnessCollapse">
                                        <i class="fa fa-link"></i> Fitness
                                    </a>
                                </div>
                            </div>
                            <div id="fitnessCollapse" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                                <form action="{{ route('client.documentation.updateFitness') }}" method="POST"  enctype="multipart/form-data" id="fitnessForm">
                                    @csrf    
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <div class="form-group form-float" >
                                                    <div class="form-line">
                                                        <input type="date" class="form-control" name="fitnessIssueDate" id="fitnessIssueDate"  value="{{ $vehicleDetail->fitness_issue_date }}">
                                                    </div>
                                                    <div class="help-info">Issue Date</div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <div class="form-group form-float" >
                                                    <div class="form-line">
                                                        <input type="number" class="form-control"  name="fitnessRenewFee" id="fitnessRenewFee" value="{{ $vehicleDetail->fitness_renew_fee }}">
                                                        <label class="form-label"> Renew Fee</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 col-sm-4 col-xs-12">
                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <input type="date" class="form-control" name="validityFromDate" id="validityFromDate" value="{{ $vehicleDetail->fitness_validity_from_date }}">
                                                    </div>
                                                    <div class="help-info">Validity From Date</div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-4 col-xs-12">
                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <input type="date" class="form-control" name="validityToDate" id="validityToDate" value="{{ $vehicleDetail->fitness_validity_todate }}">
                                                    </div>
                                                    <div class="help-info">Validity To Date</div>
                                                </div>
                                            </div>
                                            <?php if (auth()->user()?->customerEmployee?->customer_type == config('constants.CORPORATE_CUST')) { ?>
                                            <div class="col-md-4 col-sm-4 col-xs-12">
                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <input type="file" class="form-control" name="vehicleFile[]" id='vehicleFile' onchange='checkFile(this, this.id);' multiple/>

                                                    </div>
                                                </div>
                                            </div>
                                            <?php } ?>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <button type="button" class="btn bg-blue waves-effect" onclick="saveFitness()">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="vehicleId" id="vehicleId" value="<?php echo $vehicleId ?>">
                                </form>

                                <?php if (auth()->user()?->customerEmployee?->customer_type == config('constants.CORPORATE_CUST')) { ?>
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="p-l-15 p-r-15">
                                                <table class="table table-bordered table-hover custom-table dataTable">
                                                    <thead>
                                                        <tr class="bg-info">
                                                            <th>SL</th>
                                                            <th>File Name</th>
                                                            <th>Show</th>
                                                            <th>Remove</th>
                                                        </tr>
                                                    </thead>
                                                    <tfoot>
                                                        <tr>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                        </tr>
                                                    </tfoot>
                                                    <tbody>
                                                        @php $serial = 1; @endphp

                                                        @if ($vehicleFiles)
                                                            @foreach ($vehicleFiles as $vehicleFile)
                                                                @if ($vehicleFile->file_type == config('constants.FITNESS_FILE'))
                                                                    <tr>
                                                                        <td>{{ $serial++ }}</td>

                                                                        <td class="td-left">
                                                                            {{ $vehicleFile->original_name }}
                                                                        </td>

                                                                        <td class="td-center">
                                                                            <a target="_blank"
                                                                            href="{{ asset('assets/client/vehicle/files/' . $vehicleFile->file_name) }}">
                                                                                Show
                                                                            </a>
                                                                        </td>

                                                                        <td class="td-center">
                                                                            <i class="material-icons pointer text-danger"
                                                                            onclick="deleteFile('{{ $vehicleFile->id }}')">
                                                                                delete_forever
                                                                            </i>
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            <tr>
                                                                <td colspan="4" class="text-center">No files found</td>
                                                            </tr>
                                                        @endif
                                                </tbody>
                                                </table>
                                            </div>
                                        </div>	
                                    </div>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingThree">
                                <div class="panel-title">
                                    <a role="button" data-toggle="collapse" data-parent="#" href="#taxCollapse" aria-expanded="true" aria-controls="taxCollapse">
                                        <i class="fa fa-link"></i> Tax Token
                                    </a>
                                </div>
                            </div>
                            <div id="taxCollapse" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                                <form action="{{ route('client.documentation.updateTaxToken') }}" method="POST"  enctype="multipart/form-data" id="taxTokenForm">
                                    @csrf
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <div class="form-group form-float" >
                                                    <div class="form-line">
                                                        <input type="date" class="form-control" name="taxIssueDate" id="taxIssueDate"  value="{{ $vehicleDetail->tax_fee_issue_date }}">
                                                       
                                                    </div>
                                                    <div class="help-info">Issue Date</div>
                                                    <!--<label id="employeeNameError" class="error"></label>-->
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <div class="form-group form-float" >
                                                    <div class="form-line">
                                                        <input type="number" class="form-control"  name="taxFee" id="taxFee" value="{{ $vehicleDetail->tax_fee }}">
                                                        <label class="form-label"> Tax Fee</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 col-sm-4 col-xs-12">
                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <input type="date" class="form-control" name="texPeriodFromDate" id="texPeriodFromDate" value="{{ $vehicleDetail->tax_period_from_date }}">
                                                    </div>
                                                    <div class="help-info">Tax Period From Date</div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-4 col-xs-12">
                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <input type="date" class="form-control" name="texPeriodToDate" id="texPeriodToDate" value="{{ $vehicleDetail->tax_period_to_date }}">
                                                    </div>
                                                    <div class="help-info">Tax Period To Date</div>
                                                </div>
                                            </div>
                                            <?php if (auth()->user()?->customerEmployee?->customer_type == config('constants.CORPORATE_CUST')) { ?>
                                            <div class="col-md-4 col-sm-4 col-xs-12">
                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <input type="file" class="form-control" name="vehicleFile[]" id='vehicleFile' onchange='checkFile(this, this.id);' multiple/>

                                                    </div>
                                                </div>
                                            </div>
                                            <?php } ?>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <button type="button" class="btn bg-blue waves-effect" onclick="saveTaxToken()">Save</button>

                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="vehicleId" id="vehicleId" value="<?php echo $vehicleId ?>">

                                </form>

                                    <?php if (auth()->user()?->customerEmployee?->customer_type == config('constants.CORPORATE_CUST')) { ?>
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="p-l-15 p-r-15">
                                                <table class="table table-bordered table-hover custom-table dataTable">
                                                    <thead>
                                                        <tr class="bg-info">
                                                            <th>SL</th>
                                                            <th>File Name</th>
                                                            <th>Show</th>
                                                            <th>Remove</th>
                                                        </tr>
                                                    </thead>
                                                    <tfoot>
                                                        <tr>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                        </tr>
                                                    </tfoot>
                                                    <tbody>
                                                        @php $serial = 1; @endphp

                                                        @foreach ($vehicleFiles as $vehicleFile)
                                                            @if ($vehicleFile->file_type == config('constants.TAXTOKEN_FILE'))
                                                                <tr>
                                                                    <td>{{ $serial }}</td>

                                                                    <td class="td-left">
                                                                        {{ $vehicleFile->original_name }}
                                                                    </td>

                                                                    <td class="td-center">
                                                                        <a target="_blank" 
                                                                        href="{{ asset('assets/files/vehicle/' . $vehicleFile->file_name) }}">
                                                                            Show
                                                                        </a>
                                                                    </td>

                                                                    <td class="td-center">
                                                                        <i class="material-icons pointer text-danger"
                                                                        onclick="deleteFile({{ $vehicleFile->id }})">
                                                                            delete_forever
                                                                        </i>
                                                                    </td>
                                                                </tr>

                                                                @php $serial++; @endphp
                                                            @endif
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>	
                                    </div>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingFour">
                                <div class="panel-title">
                                    <a role="button" data-toggle="collapse" data-parent="#" href="#insuranceCollapse" aria-expanded="true" aria-controls="insuranceCollapse">
                                        <i class="fa fa-link"></i> Insurance
                                    </a>
                                </div>
                            </div>
                            <div id="insuranceCollapse" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
                                <form action="{{ route('client.documentation.updateInsurance') }}" method="POST"  enctype="multipart/form-data" id="insuranceForm">
                                    @csrf
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-4 col-sm-4 col-xs-12">
                                                <div class="form-group form-float" >
                                                    <div class="form-line">
                                                        <input type="date" class="form-control" name="insuranceIssueDate" id="insuranceIssueDate"  value="{{ $vehicleDetail->insurance_issue_date }}">
                                                    </div>
                                                    <div class="help-info">Issue Date</div>
                                                    <!--<label id="employeeNameError" class="error"></label>-->
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-4 col-xs-12">
                                                <div class="form-group form-float" >
                                                    <div class="form-line">
                                                        <input type="number" class="form-control"  name="preAmount" id="preAmount" value="{{ $vehicleDetail->insurance_pre_amount }}">
                                                        <label class="form-label"> Premium Amount</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-4 col-xs-12">
                                                <div class="form-group form-float" >
                                                    <div class="form-line">
                                                        <input type="text" class="form-control"  name="insuranceNature" id="insuranceNature" value="{{ $vehicleDetail->insurance_nature }}">
                                                        <label class="form-label"> Nature of Insurance</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4 col-sm-4 col-xs-12">
                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" name="companyName" id="companyName" value="{{ $vehicleDetail->insurance_company }}">
                                                        <label class="form-label"> Company Name</label>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-4 col-xs-12">
                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" name="contactPerson" id="contactPerson" value="{{ $vehicleDetail->insurance_contact_person }}">
                                                        <label class="form-label"> Contact Person</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-4 col-xs-12">
                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" name="mobileNo" id="mobileNo" onchange="checkMobileNumber(this.value, this.id)" value="{{ $vehicleDetail->insurance_mobile }}">
                                                        <label class="form-label"> Mobile No</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4 col-sm-4 col-xs-12">
                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <input type="date" class="form-control" name="validityFromDate" id="insuValidityFromDate" value="{{ $vehicleDetail->insurance_form_date }}">
                                                    </div>
                                                    <div class="help-info">Validity From Date</div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-4 col-xs-12">
                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <input type="date" class="form-control" name="validityToDate" id="insuValidityToDate" value="{{ $vehicleDetail->insurance_to_date }}">
                                                    </div>
                                                    <div class="help-info">Validity To Date</div>
                                                </div>
                                            </div>
                                            <?php if (auth()->user()?->customerEmployee?->customer_type == config('constants.CORPORATE_CUST')) { ?>
                                            <div class="col-md-4 col-sm-4 col-xs-12">
                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <input type="file" class="form-control" name="vehicleFile[]" id='vehicleFile' onchange='checkFile(this, this.id);' multiple/>

                                                    </div>
                                                </div>
                                            </div>
                                            <?php } ?>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <button type="button" class="btn bg-blue waves-effect" onclick="saveInsurance()">Save</button>

                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="vehicleId" id="vehicleId" value="<?php echo $vehicleId ?>">

                                </form>

                                    <?php if (auth()->user()?->customerEmployee?->customer_type == config('constants.CORPORATE_CUST')) { ?>
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="p-l-15 p-r-15">
                                                <table class="table table-bordered table-hover custom-table dataTable">
                                                    <thead>
                                                        <tr class="bg-info">
                                                            <th>SL</th>
                                                            <th>File Name</th>
                                                            <th>Show</th>
                                                            <th>Remove</th>
                                                        </tr>
                                                    </thead>
                                                    <tfoot>
                                                        <tr>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                        </tr>
                                                    </tfoot>
                                                    <tbody>
                                                        @php $serial = 1; @endphp

                                                        @foreach ($vehicleFiles as $vehicleFile)
                                                            @if ($vehicleFile->file_type == config('constants.INSURANCE_FILE'))
                                                                <tr>
                                                                    <td>{{ $serial }}</td>

                                                                    <td class="td-left">
                                                                        {{ $vehicleFile->original_name }}
                                                                    </td>

                                                                    <td class="td-center">
                                                                        <a target="_blank" 
                                                                        href="{{ asset('assets/files/vehicle/' . $vehicleFile->file_name) }}">
                                                                            Show
                                                                        </a>
                                                                    </td>

                                                                    <td class="td-center">
                                                                        <i class="material-icons pointer text-danger"
                                                                        onclick="deleteFile({{ $vehicleFile->id }})">
                                                                            delete_forever
                                                                        </i>
                                                                    </td>
                                                                </tr>

                                                                @php $serial++; @endphp
                                                            @endif
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>	
                                    </div>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingFive">
                                <div class="panel-title">
                                    <a role="button" data-toggle="collapse" data-parent="#" href="#routeCollapse" aria-expanded="true" aria-controls="routeCollapse">
                                        <i class="fa fa-link"></i> Route Permit
                                    </a>
                                </div>
                            </div>
                            <div id="routeCollapse" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFive">
                                <form action="{{ route('client.documentation.updateRoutePermit') }}" method="POST"  enctype="multipart/form-data" id="routeForm">
                                    @csrf
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-4 col-sm-4 col-xs-12">
                                                <div class="form-group form-float" >
                                                    <div class="form-line">
                                                        <input type="date" class="form-control" name="routeIssueDate" id="routeIssueDate"  value="{{ $vehicleDetail->route_issue_date }}">
                                                    </div>
                                                     <div class="help-info">Issue Date</div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-4 col-xs-12">
                                                <div class="form-group form-float" >
                                                    <div class="form-line">
                                                        <input type="text" class="form-control"  name="permitNo" id="permitNo" value="{{ $vehicleDetail->permit_no }}">
                                                        <label class="form-label"> Route Permit No</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-4 col-xs-12">
                                                <div class="form-group form-float" >
                                                    <div class="form-line">
                                                        <input type="number" class="form-control"  name="permitFee" id="permitFee" value="{{ $vehicleDetail->permit_fee }}">
                                                        <label class="form-label"> Route Permit Fee</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" name="routeArea" id="routeArea" value="{{ $vehicleDetail->route_area }}">
                                                        <label class="form-label"> Route/Area</label>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-4 col-xs-12">
                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" name="tyreNumber" id="tyreNumber" value="{{ $vehicleDetail->tyre_number }}">
                                                        <label class="form-label"> No Of Tyre</label>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row">
                                            <div class="col-md-4 col-sm-4 col-xs-12">
                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <input type="date" class="form-control" name="routeValidityFromDate" id="routeValidityFromDate" value="{{ $vehicleDetail->route_form_date }}">
                                                        

                                                    </div>
                                                    <div class="help-info">Permit Period From Date</div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-4 col-xs-12">
                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <input type="date" class="form-control" name="routeValidityToDate" id="routeValidityToDate" value="{{ $vehicleDetail->route_to_date }}">
                                                       
                                                    </div>
                                                    <div class="help-info">Permit Period To Date</div>
                                                </div>
                                            </div>
                                            <?php if (auth()->user()?->customerEmployee?->customer_type == config('constants.CORPORATE_CUST')) { ?>
                                            <div class="col-md-4 col-sm-4 col-xs-12">
                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <input type="file" class="form-control" name="vehicleFile[]" id='vehicleFile' onchange='checkFile(this, this.id);' multiple/>

                                                    </div>
                                                </div>
                                            </div>
                                            <?php } ?>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <button type="button" class="btn bg-blue waves-effect" onclick="saveRoute()">Save</button>

                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="vehicleId" id="vehicleId" value="<?php echo $vehicleId ?>">

                                </form>

                                    <?php if (auth()->user()?->customerEmployee?->customer_type == config('constants.CORPORATE_CUST')) { ?>
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="p-l-15 p-r-15">
                                                <table class="table table-bordered table-hover custom-table dataTable">
                                                    <thead>
                                                        <tr class="bg-info">
                                                            <th>SL</th>
                                                            <th>File Name</th>
                                                            <th>Show</th>
                                                            <th>Remove</th>
                                                        </tr>
                                                    </thead>
                                                    <tfoot>
                                                        <tr>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                        </tr>
                                                    </tfoot>
                                                    <tbody>
                                                        @php $serial = 1; @endphp

                                                        @foreach ($vehicleFiles as $vehicleFile)
                                                            @if ($vehicleFile->file_type == config('constants.ROUTE_FILE'))
                                                                <tr>
                                                                    <td>{{ $serial }}</td>

                                                                    <td class="td-left">
                                                                        {{ $vehicleFile->original_name }}
                                                                    </td>

                                                                    <td class="td-center">
                                                                        <a target="_blank" 
                                                                        href="{{ asset('assets/files/vehicle/' . $vehicleFile->file_name) }}">
                                                                            Show
                                                                        </a>
                                                                    </td>

                                                                    <td class="td-center">
                                                                        <i class="material-icons pointer text-danger"
                                                                        onclick="deleteFile({{ $vehicleFile->id }})">
                                                                            delete_forever
                                                                        </i>
                                                                    </td>
                                                                </tr>

                                                                @php $serial++; @endphp
                                                            @endif
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>	
                                    </div>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingSix">
                                <div class="panel-title">
                                    <a role="button" data-toggle="collapse" data-parent="#" href="#otherCollapse" aria-expanded="true" aria-controls="otherCollapse">
                                        <i class="fa fa-link"></i> Other Information
                                    </a>
                                </div>
                            </div>
                            <div id="otherCollapse" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSix">
                                <form action="{{ route('client.documentation.updateOtherInfo') }}" method="POST"  enctype="multipart/form-data" id="otherInfoForm">
                                    @csrf
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-4 col-sm-4 col-xs-12">
                                                <div class="form-group form-float" >
                                                    <div class="form-line">
                                                        <input type="date" class="form-control" name="otherPurchaseDate" id="otherPurchaseDate"  value="{{ $vehicleDetail->purchase_date }}">
                                                    </div>
                                                     <div class="help-info">Purchase Date</div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-4 col-xs-12">
                                                <div class="form-group form-float" >
                                                    <div class="form-line">
                                                        <input type="number" class="form-control"  name="otherPurchaseCost" id="otherPurchaseCost" value="{{ $vehicleDetail->purchase_cost }}">
                                                        <label class="form-label"> Purchase Cost</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-4 col-xs-12">
                                                <div class="form-group form-float" >
                                                    <div class="form-line">
                                                        <input type="number" class="form-control"  name="otherDpy" id="otherDpy" value="{{ $vehicleDetail->dpy }}">
                                                        <label class="form-label"> Depreciation Per Year (%)</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        @php
                                                            $purchaseCost = optional($vehicleDetail)->purchase_cost ?? 0;
                                                            $presentBookValue = optional($vehicleDetail)->present_book_value ?? 0;

                                                            if ($purchaseCost > 0 && $presentBookValue < 1) {
                                                                $presentBookValue = 1.0;
                                                            }
                                                        @endphp
                                                        <input type="text" class="form-control" name="otherPresentBookValue" id="otherPresentBookValue" value="<?php echo number_format($presentBookValue,2)?>">
                                                        <label class="form-label"> Present Book Value</label>

                                                    </div>
                                                </div>
                                            </div>
                                            <?php if (auth()->user()?->customerEmployee?->customer_type == config('constants.CORPORATE_CUST')) { ?>
                                            <div class="col-md-4 col-sm-4 col-xs-12">
                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <input type="file" class="form-control" name="vehicleFile[]" id='vehicleFile' onchange='checkFile(this, this.id);' multiple/>

                                                    </div>
                                                </div>
                                            </div>
                                            <?php } ?>

                                        </div>

 
                                        <div class="row">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <button type="button" class="btn bg-blue waves-effect" onclick="saveOtherInfo()">Save</button>

                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="vehicleId" id="vehicleId" value="<?php echo $vehicleId ?>">

                                </form>

                                    <?php if (auth()->user()?->customerEmployee?->customer_type == config('constants.CORPORATE_CUST')) { ?>
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="p-l-15 p-r-15">
                                                <table class="table table-bordered table-hover custom-table dataTable">
                                                    <thead>
                                                        <tr class="bg-info">
                                                            <th>SL</th>
                                                            <th>File Name</th>
                                                            <th>Show</th>
                                                            <th>Remove</th>
                                                        </tr>
                                                    </thead>
                                                    <tfoot>
                                                        <tr>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                        </tr>
                                                    </tfoot>
                                                    <tbody>
                                                        @php $serial = 1; @endphp

                                                        @foreach ($vehicleFiles as $vehicleFile)
                                                            @if ($vehicleFile->file_type == config('constants.OTHER_INFO_FILE'))
                                                                <tr>
                                                                    <td>{{ $serial }}</td>

                                                                    <td class="td-left">
                                                                        {{ $vehicleFile->original_name }}
                                                                    </td>

                                                                    <td class="td-center">
                                                                        <a target="_blank"
                                                                        href="{{ asset('assets/files/vehicle/' . $vehicleFile->file_name) }}">
                                                                            Show
                                                                        </a>
                                                                    </td>

                                                                    <td class="td-center">
                                                                        <i class="material-icons pointer text-danger"
                                                                        onclick="deleteFile({{ $vehicleFile->id }})">
                                                                            delete_forever
                                                                        </i>
                                                                    </td>
                                                                </tr>

                                                                @php $serial++; @endphp
                                                            @endif
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>  
                                    </div>
                                <?php } ?>
                            </div>
                        </div>

                    </div>
                    @endforeach
                </div>
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

    function saveFitness() {
        var validityFromDate = $('#validityFromDate').val();
        var validityToDate = $('#validityToDate').val();
        if (validityFromDate === "") {
            if (validityToDate !== "") {
                sweetAlert('From Date is required...!');
                return false;
            }
        }

        if (validityToDate === "") {
            if (validityFromDate !== "") {
                sweetAlert('To Date is required...!');
                return false;
            }
        }

        if (validityFromDate !== "" && validityToDate !== "") {
            var fromDate = new Date(validityFromDate);
            var toDate = new Date(validityToDate);
            if (toDate < fromDate) {
                sweetAlert('To Date should be greater than from date...!');
                return false;
            }
        }

        if ($('#fitnessRenewFee').val() !== "") {
            if (!$.isNumeric($('#fitnessRenewFee').val())) {
                sweetAlert('Fitness Renew Fee must be a numeric value...!');
                return false;
            }
        }
        $('#fitnessForm').submit();
    }

    function saveTaxToken() {
        var texPeriodFromDate = $('#texPeriodFromDate').val();
        var texPeriodToDate = $('#texPeriodToDate').val();
        if (texPeriodFromDate === "") {
            if (texPeriodToDate !== "") {
                sweetAlert('From Date is required...!');
                return false;
            }
        }

        if (texPeriodToDate === "") {
            if (texPeriodFromDate !== "") {
                sweetAlert('To Date is required...!');
                return false;
            }
        }

        if (texPeriodFromDate !== "" && texPeriodToDate !== "") {
            var fromDate = new Date(texPeriodFromDate);
            var toDate = new Date(texPeriodToDate);
            if (toDate < fromDate) {
                sweetAlert('To Date should be greater than from date...!');
                return false;
            }
        }

        if ($('#taxFee').val() !== "") {
            if (!$.isNumeric($('#taxFee').val())) {
                sweetAlert('Tax Fee must be a numeric value...!');
                return false;
            }
        }
        $('#taxTokenForm').submit();

    }

    function saveInsurance() {
        var validityFromDate = $('#insuValidityFromDate').val();
        var validityToDate = $('#insuValidityToDate').val();
        if (validityFromDate === "") {
            if (validityToDate !== "") {
                sweetAlert('From Date is required...!');
                return false;
            }
        }

        if (validityToDate === "") {
            if (validityFromDate !== "") {
                sweetAlert('To Date is required...!');
                return false;
            }
        }

        if (validityFromDate !== "" && validityToDate !== "") {
            var fromDate = new Date(validityFromDate);
            var toDate = new Date(validityToDate);
            if (toDate < fromDate) {
                sweetAlert('To Date should be greater than from date...!');
                return false;
            }
        }
        if ($('#preAmount').val() !== "") {
            if (!$.isNumeric($('#preAmount').val())) {
                sweetAlert('Premium amount must be a numeric value...!');
                return false;
            }
        }
        $('#insuranceForm').submit();
    }

    function saveRoute() {
        var validityFromDate = $('#routeValidityFromDate').val();
        var validityToDate = $('#routeValidityToDate').val();
        if (validityFromDate === "") {
            if (validityToDate !== "") {
                sweetAlert('From Date is required...!');
                return false;
            }
        }

        if (validityToDate === "") {
            if (validityFromDate !== "") {
                sweetAlert('To Date is required...!');
                return false;
            }
        }

        if (validityFromDate !== "" && validityToDate !== "") {
            var fromDate = new Date(validityFromDate);
            var toDate = new Date(validityToDate);
            if (toDate < fromDate) {
                sweetAlert('To Date should be greater than from date...!');
                return false;
            }
        }
        if ($('#permitFee').val() !== "") {
            if (!$.isNumeric($('#permitFee').val())) {
                sweetAlert('Route Permit Fee must be a numeric value...!');
                return false;
            }
        }

        if ($('#tyreNumber').val() !== "") {
            if (!$.isNumeric($('#tyreNumber').val())) {
                sweetAlert('Route Permit must be a numeric value...!');
                return false;
            }
        }
        $('#routeForm').submit();
    }

    function saveOtherInfo() {
        var purchaseDate = $('#otherPurchaseDate').val();
        if (purchaseDate === "") {
            if (validityToDate !== "") {
                sweetAlert('Purchase Date is required...!');
                return false;
            }
        }


       
        if ($('#otherPurchaseCost').val() !== "") {
            if (!$.isNumeric($('#otherPurchaseCost').val())) {
                sweetAlert('Purchase Cost must be a numeric value...!');
                return false;
            }
        }

        if ($('#otherDpy').val() !== "") {
            if (!$.isNumeric($('#otherDpy').val())) {
                sweetAlert('Depreciation Per Year must be a numeric value...!');
                return false;
            }
        }
        $('#otherInfoForm').submit();
    }

    function checkFile() {
        var fp = $("#vehicleFile");
        var lg = fp[0].files.length; // get length
        var items = fp[0].files;
        var fileSize = 0;
        var fileExtension = ['jpeg', 'jpg', 'png', 'txt', 'doc', 'docx', 'pdf'];
        if (lg > 0) {
            for (var i = 0; i < lg; i++) {
                fileSize = fileSize + items[i].size;
                if ($.inArray(items[i].name.split('.').pop().toLowerCase(), fileExtension) === -1) {
                    sweetAlert("Only 'jpeg','jpg','png','txt','doc','docx','pdf' formats are allowed...!");
                    $('#vehicleFile').val('');
                    return false;
                }
            }
            if (fileSize > 2097152) {
                sweetAlert('File size must not be more than 2 MB...!');
                $('#vehicleFile').val('');
            }
        }
    }

    function deleteFile(vehicleFileId) {

        var vehicleId = $('#vehicleId').val();

        swal({
            title: "Are you sure?",
            text: "",
            type: "warning",
            showCancelButton: true,
            closeOnConfirm: false,
            confirmButtonText: "Yes, remove it...!",
            confirmButtonColor: "#ec6c62"
        }, function () {

            showLoader();

            $.ajax({
                url: "{{ route('client.vehicle.file.remove') }}",
                type: "DELETE",
                data: {
                    vehicleFileId: vehicleFileId,
                    vehicleId: vehicleId,
                    _token: "{{ csrf_token() }}"
                },
                success: function (res) {

                    hideLoader();

                    if (res.status == 1) {

                        swal({
                            title: "Removed Successfully",
                            text: "This file has been removed",
                            type: "success",
                            closeOnConfirm: false,
                            confirmButtonText: "Ok",
                            confirmButtonColor: "#A5DC86"
                        }, function () {

                            window.location.href =
                                "{{ route('client.documentation.edit', ':id') }}"
                                    .replace(':id', vehicleId);
                        });

                    } else {

                        window.location.href =
                            "{{ route('client.documentation.edit', ':id') }}"
                                .replace(':id', vehicleId);
                    }
                },
                error: function () {
                    hideLoader();
                    swal("Oops", "We couldn't connect to the server!", "error");
                }
            });

        });
    }

</script>
@endpush
