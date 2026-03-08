<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" href="{{ asset('assets/website/images/company/log_fav.png') }}" type="image/x-icon"/>
    <title>Niketan Society | Home</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="description" content="Niketan Society">
    <meta name="author" content="ArrowlinkSoft">

    <link rel="stylesheet" href="{{ asset('assets/website/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/website/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/website/css/owl.carousel.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/website/css/owl.theme.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/website/css/owl.transitions.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/website/css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/website/css/lightbox.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/website/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/website/css/responsive.css') }}">

    <script src="{{ asset('assets/website/js/modernizrr.js') }}"></script>
    <script src="{{ asset('assets/website/js/jquery-2.1.3.min.js') }}"></script>
    <script src="{{ asset('assets/website/js/bootstrap.min.js') }}"></script>

    <script>
        function checkMobileNumber(mobileNumber, fieldId) {

            if (mobileNumber.length === 13) {

                var re = new RegExp("^8801[134-9][0-9]{8}");

                if (!re.test(mobileNumber)) {
                    alert("Please enter valid mobile number...! eg. 88017XXXXXXXX");
                    document.getElementById(fieldId).value = '';
                    document.getElementById(fieldId).select();
                }

            } else {

                alert("Please enter valid mobile number...! eg. 88017XXXXXXXX");
                document.getElementById(fieldId).value = '';
                document.getElementById(fieldId).select();

            }
        }
    </script>
    <style>
        .navbar-nav li{
    position: relative;
}

.navbar-nav li ul.dropdown{
    display: none;
    position: absolute;
    left: 0;
    top: 100%;
    background: #fff;
    list-style: none;
    padding: 0;
    margin: 0;
    min-width: 220px;
    z-index: 9999;
    box-shadow: 0px 3px 8px rgba(0,0,0,0.2);
}

.navbar-nav li:hover > ul.dropdown{
    display: block;
}

.navbar-nav li ul.dropdown li{
    width: 100%;
}

.navbar-nav li ul.dropdown li a{
    display: block;
    padding: 10px 15px;
    color: #333;
}

.navbar-nav li ul.dropdown li a:hover{
    background: #f5f5f5;
}
    </style>
</head>

<body style="background-image: url('{{ asset('assets/website/images/company/background.jpg') }}');">

    {{-- Header --}}
    @include('website.layouts.header')

    {{-- Main Body --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('website.layouts.footer')

<!-- Sulfur JS Files -->
<script src="{{ asset('assets/website/js/jquery-migrate-1.2.1.min.js') }}"></script>
<script src="{{ asset('assets/website/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('assets/website/js/jquery.appear.js') }}"></script>
<script src="{{ asset('assets/website/js/jquery.fitvids.js') }}"></script>
<script src="{{ asset('assets/website/js/jquery.nicescroll.min.js') }}"></script>
<script src="{{ asset('assets/website/js/lightbox.min.js') }}"></script>
<script src="{{ asset('assets/website/js/styleswitcher.js') }}"></script>
<script src="{{ asset('assets/website/js/count-to.js') }}"></script>
<script src="{{ asset('assets/website/js/map.js') }}"></script>
<script src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script src="{{ asset('assets/website/js/script.js') }}"></script>
</body>
</html>