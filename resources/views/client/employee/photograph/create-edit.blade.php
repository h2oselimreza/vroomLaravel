@extends('client.layouts.app')

@section('content')

<div class="block-header">
    <h2>ADD Photograph</h2><br>
    <div class="breadcrumb breadcrumb-bg-blue-grey">
        <li><a href="client/Home"> Home</a></li>
        <li><a href="#"> Employee</a></li>
        <li><a href="{{ route('client.employee.index') }}"> Employee List</a></li>
        <li><a href=""> Photograph</a></li>
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

                <form action="{{ isset($data) ? route('client.employee.photograph.update', $data->id) : route('client.employee.photograph.store') }}" method="POST" id="insertForm" enctype="multipart/form-data">
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
                                    <div class="row g-3">
                                        <div class="col-md-4"></div>
                                        <div class="col-md-4">
                                            <div class="align-items-center text-center">

                                                <div class="mb-2">Image (300px X 300px)</div>

                                                {{-- Show Current Image --}}
                                                @if($data->employee_image)
                                                    <img border="1"
                                                        id="blah"
                                                        src="{{ asset('assets/client/images/employee/' . $data->employee_image) }}"
                                                        class="profile-image mb-3"
                                                        style="width: 150px; height: 150px; object-fit: cover;">
                                                @else
                                                    <img border="1"
                                                        id="blah"
                                                        src="{{ asset('assets/images/company/no_image.jpg') }}"
                                                        class="profile-image mb-3"
                                                        style="width: 150px; height: 150px; object-fit: cover;">
                                                @endif

                                                <input type="file"
                                                    class="form-control w-auto"
                                                    name="image"
                                                    id="aboutImage"
                                                    onchange="imageShow(this, this.id);"
                                                    required style="
                                                    width: 216px;
                                                    margin: 0 auto;
                                                    margin-top: 5px;">

                                                <input type="hidden" name="old_image" value="{{ $data->employee_image }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4"></div>
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
