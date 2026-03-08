@extends('website.layouts.web')
@section('content')
<div class="mt-3">
    <div class="container">

        <div class="row">
            @yield('main-content')
            <div class="col-md-3">
                 @include('website.layouts.sidebar')
            </div><!-----Right Side------>
        </div>

    </div>
</div>
<br>

<!-- ------ Important Website Links ---------------- -->

<div class="container">
    <div class="row">
        <div class="col-md-12" >
            <div class="heading-custom-panel" style="">
                <div class="panel-heading">
                    Important Website Links
                </div>
                <div class="website-links-panel" >
                    <ul id="website-links">
                        <marquee  onmouseover="this.stop();" onmouseout="this.start();">
                            <li class="text-center">
                                <a href="http://www.rab.gov.bd/" target="_blank"><img src="{{ asset('assets/website/images/importantWebsiteLogo/1.png') }}" style="width:100px"></a>
                            </li>
                            <li class="text-center">
                                <a href="https://dmp.gov.bd/" target="_blank"><img src="{{ asset('assets/website/images/importantWebsiteLogo/2.png') }}" style="width:100px"></a>
                            </li>
                            <li class="text-center">
                                <a href="http://www.police.gov.bd/" target="_blank"><img src="{{ asset('assets/website/images/importantWebsiteLogo/3.png') }}" style="width:100px"></a>
                            </li>
                            <li class="text-center">
                                <a href="https://www.army.mil.bd/" target="_blank"><img src="{{ asset('assets/website/images/importantWebsiteLogo/4.png') }}" style="width:100px"></a>
                            </li>
                            <li class="text-center">
                                <a href="http://www.navy.mil.bd/" target="_blank"><img src="{{ asset('assets/website/images/importantWebsiteLogo/5.png') }}" style="width:100px"></a>
                            </li>
                            <li class="text-center">
                                <a href="https://www.baf.mil.bd/" target="_blank"><img src="{{ asset('assets/website/images/importantWebsiteLogo/6.png') }}" style="width:100px"></a>
                            </li>
                            <li class="text-center">
                                <a href="http://www.fireservice.gov.bd/" target="_blank"><img src="{{ asset('assets/website/images/importantWebsiteLogo/7.png') }}" style="width:100px"></a>
                            </li>
                            <li class="text-center">
                                <a href="http://www.btcl.com.bd/" target="_blank"><img src="{{ asset('assets/website/images/importantWebsiteLogo/8.png') }}" style="width:100px"></a>
                            </li>
                            <li class="text-center">
                                <a href="https://www.titasgas.org.bd/" target="_blank"><img src="{{ asset('assets/website/images/importantWebsiteLogo/9.png') }}" style="width:100px"></a>
                            </li>
                            <li class="text-center">
                                <a href="https://dwasa.org.bd/" target="_blank"><img src="{{ asset('assets/website/images/importantWebsiteLogo/10.png') }}" style="width:100px"></a>
                            </li>
                            <li class="text-center">
                                <a href="http://www.bangladesh.gov.bd/" target="_blank"><img src="{{ asset('assets/website/images/importantWebsiteLogo/11.png') }}" style="width:100px"></a>
                            </li>
                            <li class="text-center">
                                <a href="https://www.desco.org.bd/" target="_blank"><img src="{{ asset('assets/website/images/importantWebsiteLogo/12.png') }}" style="width:100px"></a>
                            </li>
                            <li class="text-center">
                                <a href="http://a2i.pmo.gov.bd/" target="_blank"><img src="{{ asset('assets/website/images/importantWebsiteLogo/13.png') }}" style="width:100px"></a>
                            </li>
                            <li class="text-center">
                                <a href="http://www.du.ac.bd/" target="_blank"><img src="{{ asset('assets/website/images/importantWebsiteLogo/14.png') }}" style="width:100px"></a>
                            </li>
                            <li class="text-center">
                                <a href="http://www.nu.edu.bd/home/" target="_blank"><img src="{{ asset('assets/website/images/importantWebsiteLogo/15.png') }}" style="width:100px"></a>
                            </li>
                            <li class="text-center">
                                <a href="http://www.dncc.gov.bd/" target="_blank"><img src="{{ asset('assets/website/images/importantWebsiteLogo/16.png') }}" style="width:100px"></a>
                            </li>
                        </marquee>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection