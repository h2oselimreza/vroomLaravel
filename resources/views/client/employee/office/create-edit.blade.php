@extends('client.layouts.app')

@section('content')

<div class="block-header">
    <h2>ADD OFFICE</h2><br>
    <div class="breadcrumb breadcrumb-bg-blue-grey">
        <li><a href="client/Home"> Home</a></li>
        <li><a href="#"> Employee</a></li>
        <li><a href="{{ route('client.employee.index') }}"> Employee List</a></li>
        <li><a href=""> Office</a></li>
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

                <form action="{{ isset($data) ? route('client.employee.office.update', $data->id) : route('client.employee.store') }}" method="POST" id="insertForm">
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
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <select class="form-control" name="emp_type">
                                                        <option value="">Select Employee Type</option>
                                                        <option value="driver" selected>Driver</option>
                                                    </select>
                                                    <div class="help-info">Employee Type</div>
                                                </div>

                                            </div>
                                        </div>  
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group form-float" >
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="designation" id="designation" value="{{ old('designation', $data->designation ?? '') }}">
                                                    <label class="form-label"> Designation</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" class="form-control dateInput" name="first_joining_date" id="firstJoingDate" value="{{ old('designation', $data->first_joining_date ?? '') }}">
                                                    <label class="form-label"> First Joining Date</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                    <button class="btn btn-success btn-primary" type="submit">Save</button>
                </form>
            </div> <!-- end class=body -->
        </div> <!-- end class="card" -->
    </div> <!-- end class="col-xs-12 col-sm-12 col-md-12 col-lg-12" -->
</div> <!-- end class="row clearfix" -->
    
@endsection
