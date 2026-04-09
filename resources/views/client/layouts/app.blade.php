<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Admin Panel</title>
    
    <link rel="icon" href="{{ asset('assets/images/company/favicon1.png') }}" type="image/x-icon"/>

    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <link href="{{ asset('assets/select_client/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/select_client/css/waves.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/select_client/css/animate.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/select_client/css/bootstrap-tagsinput.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/select_client/css/sweetalert.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/select_client/css/bootstrap-select.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/select_client/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/select_client/css/all-themes.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/select_client/css/bootstrap-material-datetimepicker1.css') }}" rel="stylesheet" />

    <script src="{{ asset('assets/select_client/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/select_client/js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/select_bo/js/font-awesome.js') }}"></script>

    {{-- include('client.headerJs') --}}
    <script src="{{ asset('assets/select_client/js/header.js') }}"></script>

    <script>
        var environment = "{{ app()->environment() }}";
        var cookieName = (environment === 'production') ? 'vr_login_validate' : 'vrd_login_validate';

        // setInterval(function () {
        //     if (getCookie(cookieName) !== '1') {
        //         window.location.href = "{{ url('client/Home') }}";
        //     }
        // }, 3000);

        function getCookie(name) {
            var v = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
            return v ? v[2] : null;
        }
    </script>

    <style>
        .noscriptmsg{display:none}
    </style>
</head>

<noscript>
    <style type="text/css">
        .checkJsDisable {display:none;}
        .noscriptmsg{display:block}
    </style>
</noscript>

@php
    $theme = 'theme-vroom-orange';
    // Logic from CI: if($this->customerType == INDIVIDUAL_CUST) { $theme = "theme-red"; }
    if(isset($customerType) && $customerType == 'INDIVIDUAL_CUST'){
        $theme = "theme-red";
    }
@endphp

<body class="{{ $theme }}">
    <div class="noscriptmsg">
        You don't have javascript enabled. Please enable Javascript for this site...!
    </div>

    <div class="checkJsDisable">
        <div id="divTop"></div>
        
        <!-- <div class="page-loader-wrapper page-load-layout">
            <div class="loader">
                <div class="preloader">
                    <div class="spinner-layer pl-red">
                        <div class="circle-clipper left"><div class="circle"></div></div>
                        <div class="circle-clipper right"><div class="circle"></div></div>
                    </div>
                </div>
                <p>Please wait...</p>
            </div>
        </div> -->

        <div id="loader" class="page-loader-wrapper" style="display: none">
            <div class="loader">
                <div class="preloader">
                    <div class="spinner-layer pl-red">
                        <div class="circle-clipper left"><div class="circle"></div></div>
                        <div class="circle-clipper right"><div class="circle"></div></div>
                    </div>
                </div>
                <p>Please wait...</p>
            </div>
        </div>

        <!-- <div class="overlay"></div> -->

        <div class="search-bar">
            <div class="search-icon"><i class="material-icons">search</i></div>
            <input type="text" placeholder="START TYPING...">
            <div class="close-search"><i class="material-icons">close</i></div>
        </div>

        <nav class="navbar" style="background-color: #F79522">
            <div class="container-fluid">
                @include('client.layouts.tob-navbar')
            </div>
        </nav>

        <section>
            <aside id="leftsidebar" class="sidebar">

                <div class="menu">
                    @include('client.layouts.sidebar-navigation')
                </div>
                <div class="legal">
                    <div class="copyright">
                        {{ date('Y') }} <a href="javascript:void(0);" style="color: #F79522 !important">Vroom Services Limited</a>
                    </div>
                    <div class="version">
                        <b>Developed by ArrowLink™ Soft </b>
                    </div>
                </div>
            </aside>
        </section>

        <section class="content">
            <div class="container-fluid">
                {{-- Dynamic content loading --}}
                @yield('content')
            </div>
        </section>

        <script src="{{ asset('assets/select_client/js/bootstrap.js') }}"></script>
        <script src="{{ asset('assets/select_client/js/jquery.slimscroll.js') }}"></script>
        <script src="{{ asset('assets/select_client/js/bootstrap-notify.js') }}"></script>
        <script src="{{ asset('assets/select_client/js/jquery.inputmask.bundle.js') }}"></script>
        <script src="{{ asset('assets/select_client/js/waves.js') }}"></script>
        <script src="{{ asset('assets/select_client/js/jquery.countTo.js') }}"></script>
        <script src="{{ asset('assets/select_client/js/admin.js') }}"></script>
        <script src="{{ asset('assets/select_client/js/tooltips-popovers.js') }}"></script>
        <script src="{{ asset('assets/select_client/js/bootstrap-tagsinput.js') }}"></script>
        <script src="{{ asset('assets/select_client/js/dialogs.js') }}"></script>
        <script src="{{ asset('assets/select_client/js/demo.js') }}"></script>
        <script src="{{ asset('assets/select_client/js/moment.js') }}"></script>
        <script src="{{ asset('assets/select_client/js/bootstrap-material-datetimepicker1.js') }}"></script>
        @php
            $groceryOutputFlag = 1;
        @endphp
        @if($groceryOutputFlag == 0)
            <script src="{{ asset('assets/select_client/js/bootstrap-select.js') }}"></script>
            <link href="{{ asset('assets/select_client/css/dataTables.bootstrap.css') }}" rel="stylesheet" />
            <script src="{{ asset('assets/select_client/js/jquery.dataTables.js') }}"></script>
            <script src="{{ asset('assets/select_client/js/dataTables.bootstrap.js') }}"></script>
            <script src="{{ asset('assets/select_client/js/export/dataTables.buttons.min.js') }}"></script>
            <script src="{{ asset('assets/select_client/js/export/buttons.flash.min.js') }}"></script>
            <script src="{{ asset('assets/select_client/js/export/jszip.min.js') }}"></script>
            <script src="{{ asset('assets/select_client/js/export/pdfmake.min.js') }}"></script>
            <script src="{{ asset('assets/select_client/js/export/vfs_fonts.js') }}"></script>
            <script src="{{ asset('assets/select_client/js/export/buttons.html5.min.js') }}"></script>
            <script src="{{ asset('assets/select_client/js/export/buttons.print.min.js') }}"></script>
            <script src="{{ asset('assets/select_client/js/jquery-datatable.js') }}"></script>
        @endif

        <style>
            #cot_tl_fixed { position: fixed; bottom: 0px; right: 0px; }
        </style>
    </div>
</body>
</html>