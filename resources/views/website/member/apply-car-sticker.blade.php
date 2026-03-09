@extends('website.layouts.single-page')
@section('main-content')
<div class="col-md-9">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="heading-custom-panel" style="">
        <!--         <div class="panel-heading">
                     Contact Us
                 </div>-->
        <br>
        <div class="text-center">
            <h3>Apply For Car Sticker</h3>
        </div>
        <br>
        <div class="custom-panel-body">
            <div class="text-justify" style="">
                <form action="{{ route('website.member.apply-car-sticker') }}" method="POST">
                    @csrf
                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Name" name="name" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Member ID" name="member_id" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control" onchange="checkMobileNumber(this.value, 'mobile_no')" id="mobile_no" placeholder="Mobile No" name="mobile_no" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="email" class="form-control" placeholder="Email" name="email" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Vehicle Registration Number" name="reg_no" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Vehicle Brand Name" name="brand_name" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Vehicle Model" name="model" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Driving License Brand Name" name="license_no" required>
                            </div>
                        </div>

                        <div class="clearfix"></div>

                        <div class="col-lg-12 text-center wow zoomIn" data-wow-duration="1s" data-wow-delay="600ms">
                            <input type="submit" class="btn btn-primary" value="Submit">
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection