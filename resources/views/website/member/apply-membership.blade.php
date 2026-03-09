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
            <h3>Apply For Membership</h3>
        </div>
        <br>
        <div class="custom-panel-body">
            <div class="text-justify" style="">
                <form action="{{ route('website.member.apply-member-ship') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 wow fadeInLeft" data-wow-duration="2s" data-wow-delay="600ms">

                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Your Name" name="name" required>
                            </div>

                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Your Mobile" name="mobile" required>
                            </div>

                            <div class="form-group">
                                <input type="email" class="form-control" placeholder="Your Email" name="fromMail" required>
                            </div>

                        </div>

                        <div class="col-md-6 wow fadeInRight" data-wow-duration="2s" data-wow-delay="600ms">
                            <div class="form-group">
                                <textarea class="form-control" placeholder="Address" rows="6" name="address" required></textarea>
                            </div>
                        </div>

                        <div class="clearfix"></div>

                        <div class="col-lg-12 text-center wow zoomIn" data-wow-duration="1s" data-wow-delay="600ms">
                            <input type="submit" class="btn btn-primary" value="Send">
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection