@extends('client.layouts.app')

@section('content')
<style>
    .quick-link-body{
        padding-left: 0px !important;
        padding-right: 0px !important;
    }
    .custom-scrollber {
        height: 256px;
        overflow: hidden;
        overflow-y: scroll;
    }
</style>
<div class="container-fluid">
    <link rel="stylesheet" href="https://vroom24x7.com/demo/assets/select_client/css/font_awsome_v5.css">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <span class="float-left font-13">
                    <b>
                    <span class="text-vroom-orange">My Package :</span> Corporate U10V150 </b>
                </span>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 font-13" style="text-align:right">
                    <span class="float-right hidden-sm hidden-xs">
                        <b>
                        <span class="text-vroom-orange">My RM : </span>
                        <span class="pointer" data-trigger="focus" data-container="body" data-toggle="popover" tabindex="0" data-placement="bottom" title="" data-content="AE00002&lt;br&gt;8801784426243&lt;br&gt;sumon@vroom.com.bd" data-original-title="Md. Akhtaruzzaman (Sumon)"> Md. Akhtaruzzaman (Sumon)</span>
                        </b>
                    </span>
                    <span class="float-left hidden-md hidden-lg">
                        <b>
                        <span class="text-vroom-orange">My RM : </span>
                        <span class="pointer" data-trigger="focus" data-container="body" data-toggle="popover" tabindex="0" data-placement="bottom" title="" data-content="AE00002&lt;br&gt;8801784426243&lt;br&gt;sumon@vroom.com.bd" data-original-title="Md. Akhtaruzzaman (Sumon)"> Md. Akhtaruzzaman (Sumon)</span>
                        </b>
                    </span>
                </div>
            </div>
            </div>
        </div>
    </div>

    <div class="row clearfix m-t-20">
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <a href="https://vroom24x7.com/demo/client/Employee/employeeList" class="quick-link-href">
                        <div class="card quick-link-card hover-expand-effect">
                            <div class="body bg-light-green quick-link-body">
                                <div class="icon text-center">
                                    <div>
                                        <i class="material-icons font-40">person</i>
                                    </div>
                                </div>
                                <div class="text-center quick-link-text">
                                    <span class="font-13">Driver</span> 
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <a href="https://vroom24x7.com/demo/client/Vehicle/vehicleList" class="quick-link-href">

                        <div class="card quick-link-card hover-expand-effect">
                            <div class="body bg-cyan quick-link-body">
                                <div class="icon text-center">
                                    <div>
    <!--                                    <style>
                                            .custom-fa-3x {
                                                font-size: 3.4em!important;
                                            }
                                        </style>
                                        <i class="fa fa-ambulance custom-fa-3x"></i>-->
                                        <i class="material-icons font-40">directions_car</i>
                                    </div>
                                    <div class="text-center quick-link-text">
                                        Vehicle
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <a href="https://vroom24x7.com/demo/client/VehicleAssign/driverVehicleAssign" class="quick-link-href">
                        <div class="card quick-link-card hover-expand-effect">
                            <div class="body bg-light-green quick-link-body">
                                <div class="icon text-center">
                                    <div>
                                    <i class="material-icons font-40">person_pin</i>
                                    </div>
                                </div>
                                <div class="text-center quick-link-text">
                                    <span class="font-13">Driver Assign</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <a href="https://vroom24x7.com/demo/client/VehicleAssign/employeeVehicleAssign" class="quick-link-href">
                        <div class="card quick-link-card hover-expand-effect">
                            <div class="body bg-cyan quick-link-body">
                                <div class="icon text-center">
                                    <div>
                                        <i class="material-icons font-40">done_all</i>
                                    </div>
                                    <div class="text-center quick-link-text">
                                        <span class="font-13">Vehicle Assign</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <a href="https://vroom24x7.com/demo/client/Quotation/reqQuotationList" class="quick-link-href">
                        <div class="card quick-link-card hover-expand-effect">
                            <div class="body bg-light-green quick-link-body">
                                <div class="icon text-center">
                                    <div>
                                        <i class="material-icons font-40">format_list_bulleted</i>
                                    </div>
                                </div>
                                <div class="text-center quick-link-text">
                                    <span class="font-13">Quotation</span> 
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <a href="https://vroom24x7.com/demo/client/Appointment/setAppoinment" class="quick-link-href">
                        <div class="card quick-link-card hover-expand-effect">
                            <div class="body bg-cyan quick-link-body">
                                <div class="icon text-center">
                                    <div>
                                        <i class="material-icons font-40">perm_phone_msg</i>
                                    </div>
                                    <div class="text-center quick-link-text">
                                        <span class="font-13">Appointment</span> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <a href="https://vroom24x7.com/demo/client/GenHomeService/setHomeService" class="quick-link-href">
                        <div class="card quick-link-card hover-expand-effect">
                            <div class="body bg-light-green quick-link-body">
                                <div class="icon text-center">
                                    <div>
                                        <i class="material-icons font-40">home</i>
                                    </div>
                                </div>
                                <div class="text-center quick-link-text">
                                    <span class="font-13">Home Service</span>  
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <a href="https://vroom24x7.com/demo/client/Expense/expenseList" class="quick-link-href">
                        <div class="card quick-link-card hover-expand-effect">
                            <div class="body bg-cyan quick-link-body">
                                <div class="icon text-center">
                                    <div>
                                        <i class="material-icons font-40">local_atm</i>
                                    </div>
                                    <div class="text-center quick-link-text">
                                        <span class="font-13">Expense</span> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row clearfix m-t-0">

    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-teal hover-expand-effect">
            <div class="icon">
                <i class="material-icons">people</i>
            </div>
            <div class="content">
                <div class="text">Total Driver</div>
                <div id="driverCountLoader" class="div-loader-parent" style="display: none;"><div id="div-loader" class="div-loader-wrapper">
                    <div class="loader">
                        <div class="preloader pl-size-xs">
                            <div class="spinner-layer pl-vroom-orange">
                                <div class="circle-clipper left">
                                    <div class="circle"></div>
                                </div>
                                <div class="circle-clipper right">
                                    <div class="circle"></div>
                                </div>
                            </div>
                        </div>
                        <p>Please wait...</p>
                    </div>
                </div></div>
                <div class="number count-to" id="driverCountDiv">6</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-blue hover-expand-effect">
            <div class="icon">
                <i class="material-icons">directions_car</i>
            </div>
            <div class="content">
                <div class="text">Total Vehicle</div>
                <div id="vehicleCountLoader" class="div-loader-parent" style="display: none;"><div id="div-loader" class="div-loader-wrapper">
                    <div class="loader">
                        <div class="preloader pl-size-xs">
                            <div class="spinner-layer pl-vroom-orange">
                                <div class="circle-clipper left">
                                    <div class="circle"></div>
                                </div>
                                <div class="circle-clipper right">
                                    <div class="circle"></div>
                                </div>
                            </div>
                        </div>
                        <p>Please wait...</p>
                    </div>
                </div></div>
                <div class="number count-to" id="vehicleCountDiv">18</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-teal hover-expand-effect">
            <div class="icon">
                <i class="material-icons">local_shipping</i>
            </div>
            <div class="content">
                <div class="text">Total Vacant Vehicle</div>
                <div id="vacantCountLoader" class="div-loader-parent" style="display: none;"><div id="div-loader" class="div-loader-wrapper">
                    <div class="loader">
                        <div class="preloader pl-size-xs">
                            <div class="spinner-layer pl-vroom-orange">
                                <div class="circle-clipper left">
                                    <div class="circle"></div>
                                </div>
                                <div class="circle-clipper right">
                                    <div class="circle"></div>
                                </div>
                            </div>
                        </div>
                        <p>Please wait...</p>
                    </div>
                </div></div>
                <div class="number count-to" id="vacantCountDiv">17</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-blue hover-expand-effect">
            <div class="icon">
                <i class="material-icons">directions_bus</i>
            </div>
            <div class="content">
                <div class="text">Total En Route</div>
                <div id="enRouteCountLoader" class="div-loader-parent" style="display: none;"><div id="div-loader" class="div-loader-wrapper">
                    <div class="loader">
                        <div class="preloader pl-size-xs">
                            <div class="spinner-layer pl-vroom-orange">
                                <div class="circle-clipper left">
                                    <div class="circle"></div>
                                </div>
                                <div class="circle-clipper right">
                                    <div class="circle"></div>
                                </div>
                            </div>
                        </div>
                        <p>Please wait...</p>
                    </div>
                </div></div>
                <div class="number count-to" id="enRouteCountDiv">0</div>
            </div>
        </div>
    </div>
</div>


<div class="row clearfix m-t-0">
    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
        
        <div class="info-box bg-cyan hover-expand-effect pointer" onclick="showDocDashboard('fitness')">
            <div class="icon">
                <i class="fas fa-tasks"></i>
                <!--<i class="material-icons">local_car_wash</i>-->
            </div>
            <div class="content">
                <div class="text">Upcoming Fitness</div>
                <div id="fitnessLoader" class="div-loader-parent" style="display: none;"><div id="div-loader" class="div-loader-wrapper">
                    <div class="loader">
                        <div class="preloader pl-size-xs">
                            <div class="spinner-layer pl-vroom-orange">
                                <div class="circle-clipper left">
                                    <div class="circle"></div>
                                </div>
                                <div class="circle-clipper right">
                                    <div class="circle"></div>
                                </div>
                            </div>
                        </div>
                        <p>Please wait...</p>
                    </div>
                </div></div>
                <div class="number count-to" id="fitnessCount">0</div>
            </div>
        </div>

    </div>
    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
        <div class="info-box bg-light-green hover-expand-effect pointer" onclick="showDocDashboard('taxToken')">
            <div class="icon">
                <i class="fas fa-file-alt"></i>
                <!--<i class="material-icons">attach_money</i>-->
            </div>
            <div class="content">
                <div class="text">Upcoming Tax Token</div>
                <div id="taxTokenLoader" class="div-loader-parent" style="display: none;"><div id="div-loader" class="div-loader-wrapper">
                    <div class="loader">
                        <div class="preloader pl-size-xs">
                            <div class="spinner-layer pl-vroom-orange">
                                <div class="circle-clipper left">
                                    <div class="circle"></div>
                                </div>
                                <div class="circle-clipper right">
                                    <div class="circle"></div>
                                </div>
                            </div>
                        </div>
                        <p>Please wait...</p>
                    </div>
                </div></div>
                <div class="number count-to" id="taxTokenCount">0</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
        <div class="info-box bg-cyan hover-expand-effect pointer" onclick="showDocDashboard('insurance')">
            <div class="icon">
                <i class="fas fa-car-crash"></i>
                <!--<i class="material-icons">attach_file</i>-->
            </div>
            <div class="content">
                <div class="text">Upcoming Insurance</div>
                <div id="insuranceLoader" class="div-loader-parent" style="display: none;"><div id="div-loader" class="div-loader-wrapper">
                    <div class="loader">
                        <div class="preloader pl-size-xs">
                            <div class="spinner-layer pl-vroom-orange">
                                <div class="circle-clipper left">
                                    <div class="circle"></div>
                                </div>
                                <div class="circle-clipper right">
                                    <div class="circle"></div>
                                </div>
                            </div>
                        </div>
                        <p>Please wait...</p>
                    </div>
                </div></div>
                <div class="number count-to" id="insuranceCount">0</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
        <div class="info-box bg-light-green hover-expand-effect pointer" onclick="showDocDashboard('routePermit')">
            <div class="icon">
                <i class="fas fa-road"></i>
                <!--<i class="material-icons">traffic</i>-->
            </div>
            <div class="content">
                <div class="text"><span class="font-11">Upcoming Route Permit</span></div>
                <div id="routeLoader" class="div-loader-parent" style="display: none;"><div id="div-loader" class="div-loader-wrapper">
                    <div class="loader">
                        <div class="preloader pl-size-xs">
                            <div class="spinner-layer pl-vroom-orange">
                                <div class="circle-clipper left">
                                    <div class="circle"></div>
                                </div>
                                <div class="circle-clipper right">
                                    <div class="circle"></div>
                                </div>
                            </div>
                        </div>
                        <p>Please wait...</p>
                    </div>
                </div></div>
                <div class="number count-to" id="routeCount">0</div>
            </div>
        </div>
    </div>
</div>


<div class="row clearfix">
    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
        <div class="card">
            <div class="body bg-blue-grey custom-scrollber status-card-body">
                <div class="m-b--35 font-bold">Vehicle Dashboard</div>
                <div id="vehicleDashBoardLoader" class="div-loader-parent" style="display: none;"><div id="div-loader" class="div-loader-wrapper">
                    <div class="loader">
                        <div class="preloader pl-size-sm">
                            <div class="spinner-layer pl-vroom-orange">
                                <div class="circle-clipper left">
                                    <div class="circle"></div>
                                </div>
                                <div class="circle-clipper right">
                                    <div class="circle"></div>
                                </div>
                            </div>
                        </div>
                        <p>Please wait...</p>
                    </div>
                </div></div>
                <ul class="dashboard-stat-list" id="vehicleListUl"><li><a href="https://vroom24x7.com/demo/client/Home/vehicleDashboard?vehicleId=VHCL-00036" style="color:#FFF;text-decoration:none">Dhaka Metro DA 20-1629 <span class="font-11"><i>Hyundai Sonata</i></span></a></li><li><a href="https://vroom24x7.com/demo/client/Home/vehicleDashboard?vehicleId=VHCL-00078" style="color:#FFF;text-decoration:none">Dhaka Metro GA 15-4444 <span class="font-11"><i>Ford Aspire</i></span></a></li><li><a href="https://vroom24x7.com/demo/client/Home/vehicleDashboard?vehicleId=VHCL-00030" style="color:#FFF;text-decoration:none">Dhaka Metro GA 15-5555 <span class="font-11"><i>Honda Integra</i></span></a></li><li><a href="https://vroom24x7.com/demo/client/Home/vehicleDashboard?vehicleId=VHCL-00011" style="color:#FFF;text-decoration:none">Dhaka Metro Ga-019282 <span class="font-11"><i>Toyota Corolla X</i></span></a></li><li><a href="https://vroom24x7.com/demo/client/Home/vehicleDashboard?vehicleId=VHCL-00012" style="color:#FFF;text-decoration:none">Dhaka Metro Ka-455564 <span class="font-11"><i>Audi A1</i></span></a></li><li><a href="https://vroom24x7.com/demo/client/Home/vehicleDashboard?vehicleId=VHCL-00035" style="color:#FFF;text-decoration:none">Dhaka Metro KA15-0332 <span class="font-11"><i>Haval H2</i></span></a></li><li><a href="https://vroom24x7.com/demo/client/Home/vehicleDashboard?vehicleId=VHCL-00038" style="color:#FFF;text-decoration:none">Dhaka Metro KA15-0335 <span class="font-11"><i>Toyota Corolla X</i></span></a></li><li><a href="https://vroom24x7.com/demo/client/Home/vehicleDashboard?vehicleId=VHCL-00029" style="color:#FFF;text-decoration:none">Dhaka Metro-GA-22-7768 <span class="font-11"><i>Toyota Allion</i></span></a></li><li><a href="https://vroom24x7.com/demo/client/Home/vehicleDashboard?vehicleId=VHCL-00025" style="color:#FFF;text-decoration:none">Dhaka-Metro-CAA-71-1843 <span class="font-11"><i>Nissan Civilian</i></span></a></li><li><a href="https://vroom24x7.com/demo/client/Home/vehicleDashboard?vehicleId=VHCL-00026" style="color:#FFF;text-decoration:none">Dhaka-Metro-CAA-71-2440 <span class="font-11"><i>Nissan Civilian</i></span></a></li><li><a href="https://vroom24x7.com/demo/client/Home/vehicleDashboard?vehicleId=VHCL-00031" style="color:#FFF;text-decoration:none">Dhaka-Metro-CHA-56-1559 <span class="font-11"><i>Toyota Hiace</i></span></a></li><li><a href="https://vroom24x7.com/demo/client/Home/vehicleDashboard?vehicleId=VHCL-00032" style="color:#FFF;text-decoration:none">Dhaka-Metro-Cha-56-1876 <span class="font-11"><i>Toyota Hiace</i></span></a></li><li><a href="https://vroom24x7.com/demo/client/Home/vehicleDashboard?vehicleId=VHCL-00027" style="color:#FFF;text-decoration:none">Dhaka-Metro-GA-20-4106 <span class="font-11"><i>Toyota Axio</i></span></a></li><li><a href="https://vroom24x7.com/demo/client/Home/vehicleDashboard?vehicleId=VHCL-00028" style="color:#FFF;text-decoration:none">Dhaka-Metro-GA-20-4107 <span class="font-11"><i>Toyota Axio</i></span></a></li><li><a href="https://vroom24x7.com/demo/client/Home/vehicleDashboard?vehicleId=VHCL-00034" style="color:#FFF;text-decoration:none">Dhaka-Metro-GA-29-1557 <span class="font-11"><i>Toyota Corolla</i></span></a></li><li><a href="https://vroom24x7.com/demo/client/Home/vehicleDashboard?vehicleId=VHCL-00037" style="color:#FFF;text-decoration:none">Dhaka-Metro-GA-29-1559 <span class="font-11"><i>Hyundai Tucson</i></span></a></li><li><a href="https://vroom24x7.com/demo/client/Home/vehicleDashboard?vehicleId=VHCL-00033" style="color:#FFF;text-decoration:none">Dhaka-Metro-GA-33-4455 <span class="font-11"><i>BMW  X7</i></span></a></li><li><a href="https://vroom24x7.com/demo/client/Home/vehicleDashboard?vehicleId=VHCL-00024" style="color:#FFF;text-decoration:none">Dhaka-Metro-GHA-17-0822 <span class="font-11"><i>Audi A3</i></span></a></li></ul>
            </div>
        </div>
    </div>

    
    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
        <div class="card">
            <div class="body bg-cyan custom-scrollber status-card-body">
                <div class="m-b--35 font-bold">Request For Quotation</div>
                <ul class="dashboard-stat-list">
                    <li>Draft
                        <span class="pull-right">
                            0                        </span>
                    </li>
                    <li>Pending
                        <span class="pull-right">
                            10                        </span>
                    </li>
                    <li>Processing
                        <span class="pull-right">
                            2                        </span>
                    </li>
                    <li>Submitted
                        <span class="pull-right">
                            0                        </span>
                    </li>
                    <li>Approved
                        <span class="pull-right">
                            1                        </span>
                    </li>
                    <li>Rejected
                        <span class="pull-right">
                            1                        </span>
                    </li>

                </ul>
            </div>
        </div>
    </div>


    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
        <div class="card">
            <div class="body bg-blue-grey custom-scrollber status-card-body">
                <div class="font-bold m-b--35">Appointment</div>
                <ul class="dashboard-stat-list">
                    <li>Pending
                        <span class="pull-right">
                            6                        </span>
                    </li>
                    <li>Processing
                        <span class="pull-right">
                            1                        </span>
                    </li>
                    <li>Accepted
                        <span class="pull-right">
                            1                        </span>
                    </li>
                    <li>Completed
                        <span class="pull-right">
                            0                        </span>
                    </li>
                    <li>Rejected
                        <span class="pull-right">
                            0                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
        <div class="card">
            <div class="body bg-cyan custom-scrollber status-card-body">
                <div class="m-b--35 font-bold">Home Service</div>
                <ul class="dashboard-stat-list">
                    <li>Pending
                        <span class="pull-right">
                            0                        </span>
                    </li>
                    <li>Processing
                        <span class="pull-right">
                            1                        </span>
                    </li>
                    <li>Accepted
                        <span class="pull-right">
                            2                        </span>
                    </li>
                    <li>Started
                        <span class="pull-right">
                            0                        </span>
                    </li>
                    <li>Completed
                        <span class="pull-right">
                            1                        </span>
                    </li>
                    <li>Paid
                        <span class="pull-right">
                            1                        </span>
                    </li>
                    <li>Rejected
                        <span class="pull-right">
                            0                        </span>
                    </li>

                </ul>
            </div>
        </div>
    </div>

</div>

<style>
    .vehicle-assign-table tr td{
        /*width: 50%;*/
        padding: 5px!important;
        font-size: 13.5px!important;
    }

    .vehicle-assign-table tr th{
        /*width: 50%;*/
        padding: 5px!important;
        font-size: 13.5px!important;
        text-align: left;
    }
</style>


<div class="row clearfix">
    <!-- Task Info -->
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <div class="card">
            <div class="header bg-teal">
                <h2>Vehicle Assigned to Driver</h2>
            </div>
            <div class="body">
                <div class="table-responsive custom-scrollber vehicle-assign-div">
                    <div id="vehicleDriverLoader" class="div-loader-parent" style="display: none;"><div id="div-loader" class="div-loader-wrapper">
                    <div class="loader">
                        <div class="preloader pl-size-sm">
                            <div class="spinner-layer pl-vroom-orange">
                                <div class="circle-clipper left">
                                    <div class="circle"></div>
                                </div>
                                <div class="circle-clipper right">
                                    <div class="circle"></div>
                                </div>
                            </div>
                        </div>
                        <p>Please wait...</p>
                    </div>
                </div></div>
                    <table class="table table-hover vehicle-assign-table" id="vehicleDriverTable" style="display: table;">
                        <thead>
                            <tr>
                                <th>Vehicle</th>
                                <th>Driver</th>
                            </tr>
                        </thead>
                        <tbody id="driverTableBody"><tr>
                                            <td>Dhaka Metro DA 20-1629<br><span class="font-11"><i>Hyundai Sonata</i></span></td>
                                            <td>Nur Alam</td>
                                        </tr><tr>
                                            <td>Dhaka Metro GA 15-4444<br><span class="font-11"><i>Ford Aspire</i></span></td>
                                            <td><span class="text-danger"><i><small>N/A</small></i></span></td>
                                        </tr><tr>
                                            <td>Dhaka Metro GA 15-5555<br><span class="font-11"><i>Honda Integra</i></span></td>
                                            <td>Rashik Raj</td>
                                        </tr><tr>
                                            <td>Dhaka Metro Ga-019282<br><span class="font-11"><i>Toyota Corolla X</i></span></td>
                                            <td><span class="text-danger"><i><small>N/A</small></i></span></td>
                                        </tr><tr>
                                            <td>Dhaka Metro Ka-455564<br><span class="font-11"><i>Audi A1</i></span></td>
                                            <td><span class="text-danger"><i><small>N/A</small></i></span></td>
                                        </tr><tr>
                                            <td>Dhaka Metro KA15-0332<br><span class="font-11"><i>Haval H2</i></span></td>
                                            <td><span class="text-danger"><i><small>N/A</small></i></span></td>
                                        </tr><tr>
                                            <td>Dhaka Metro KA15-0335<br><span class="font-11"><i>Toyota Corolla X</i></span></td>
                                            <td><span class="text-danger"><i><small>N/A</small></i></span></td>
                                        </tr><tr>
                                            <td>Dhaka Metro-GA-22-7768<br><span class="font-11"><i>Toyota Allion</i></span></td>
                                            <td><span class="text-danger"><i><small>N/A</small></i></span></td>
                                        </tr><tr>
                                            <td>Dhaka-Metro-CAA-71-1843<br><span class="font-11"><i>Nissan Civilian</i></span></td>
                                            <td><span class="text-danger"><i><small>N/A</small></i></span></td>
                                        </tr><tr>
                                            <td>Dhaka-Metro-CAA-71-2440<br><span class="font-11"><i>Nissan Civilian</i></span></td>
                                            <td><span class="text-danger"><i><small>N/A</small></i></span></td>
                                        </tr><tr>
                                            <td>Dhaka-Metro-CHA-56-1559<br><span class="font-11"><i>Toyota Hiace</i></span></td>
                                            <td>Joshim</td>
                                        </tr><tr>
                                            <td>Dhaka-Metro-Cha-56-1876<br><span class="font-11"><i>Toyota Hiace</i></span></td>
                                            <td><span class="text-danger"><i><small>N/A</small></i></span></td>
                                        </tr><tr>
                                            <td>Dhaka-Metro-GA-20-4106<br><span class="font-11"><i>Toyota Axio</i></span></td>
                                            <td><span class="text-danger"><i><small>N/A</small></i></span></td>
                                        </tr><tr>
                                            <td>Dhaka-Metro-GA-20-4107<br><span class="font-11"><i>Toyota Axio</i></span></td>
                                            <td><span class="text-danger"><i><small>N/A</small></i></span></td>
                                        </tr><tr>
                                            <td>Dhaka-Metro-GA-29-1557<br><span class="font-11"><i>Toyota Corolla</i></span></td>
                                            <td><span class="text-danger"><i><small>N/A</small></i></span></td>
                                        </tr><tr>
                                            <td>Dhaka-Metro-GA-29-1559<br><span class="font-11"><i>Hyundai Tucson</i></span></td>
                                            <td>Sojib Hasan</td>
                                        </tr><tr>
                                            <td>Dhaka-Metro-GA-33-4455<br><span class="font-11"><i>BMW  X7</i></span></td>
                                            <td><span class="text-danger"><i><small>N/A</small></i></span></td>
                                        </tr><tr>
                                            <td>Dhaka-Metro-GHA-17-0822<br><span class="font-11"><i>Audi A3</i></span></td>
                                            <td>Ashik Sarkar</td>
                                        </tr></tbody>
                    </table>

                </div>
                <div class="text-center p-t-20 font-12">
                    <b>
                        <span class="text-success p-r-10">Assigned: <span id="driverAssignedCount">5</span></span>
                    </b>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <div class="card">
            <div class="header bg-blue">
                <h2>Vehicle Assigned to Person</h2>
            </div>
            <div class="body">
                <div class="table-responsive custom-scrollber vehicle-assign-div">
                    <div id="vehiclePersonLoader" class="div-loader-parent" style="display: none;"><div id="div-loader" class="div-loader-wrapper">
                    <div class="loader">
                        <div class="preloader pl-size-sm">
                            <div class="spinner-layer pl-vroom-orange">
                                <div class="circle-clipper left">
                                    <div class="circle"></div>
                                </div>
                                <div class="circle-clipper right">
                                    <div class="circle"></div>
                                </div>
                            </div>
                        </div>
                        <p>Please wait...</p>
                    </div>
                </div></div>
                    <table class="table table-hover vehicle-assign-table" id="vehiclePersonTable" style="display: table;">
                        <thead>
                            <tr>
                                <th style="width:50%">Vehicle</th>  <!-- new -->
                                <th style="width:30%">Person</th>   <!-- new -->
                                <th style="width:20%">Status</th>   <!-- new -->
                            </tr>
                        </thead>
                        <tbody id="personTableBody"><tr>
                                            <td>Dhaka Metro DA 20-1629<br><span class="font-11"><i>Hyundai Sonata</i></span></td>
                                            <td></td>
                                            <td><span class="text-danger"><i><small>Vacant</small></i></span></td>
                                        </tr><tr>
                                            <td>Dhaka Metro GA 15-4444<br><span class="font-11"><i>Ford Aspire</i></span></td>
                                            <td></td>
                                            <td><span class="text-danger"><i><small>Vacant</small></i></span></td>
                                        </tr><tr>
                                            <td>Dhaka Metro GA 15-5555<br><span class="font-11"><i>Honda Integra</i></span></td>
                                            <td></td>
                                            <td><span class="text-danger"><i><small>Vacant</small></i></span></td>
                                        </tr><tr>
                                            <td>Dhaka Metro Ga-019282<br><span class="font-11"><i>Toyota Corolla X</i></span></td>
                                            <td></td>
                                            <td><span class="text-danger"><i><small>Vacant</small></i></span></td>
                                        </tr><tr>
                                            <td>Dhaka Metro Ka-455564<br><span class="font-11"><i>Audi A1</i></span></td>
                                            <td></td>
                                            <td><span class="text-danger"><i><small>Vacant</small></i></span></td>
                                        </tr><tr>
                                            <td>Dhaka Metro KA15-0332<br><span class="font-11"><i>Haval H2</i></span></td>
                                            <td></td>
                                            <td><span class="text-danger"><i><small>Vacant</small></i></span></td>
                                        </tr><tr>
                                            <td>Dhaka Metro KA15-0335<br><span class="font-11"><i>Toyota Corolla X</i></span></td>
                                            <td></td>
                                            <td><span class="text-danger"><i><small>Vacant</small></i></span></td>
                                        </tr><tr>
                                            <td>Dhaka Metro-GA-22-7768<br><span class="font-11"><i>Toyota Allion</i></span></td>
                                            <td></td>
                                            <td><span class="text-danger"><i><small>Vacant</small></i></span></td>
                                        </tr><tr>
                                            <td>Dhaka-Metro-CAA-71-1843<br><span class="font-11"><i>Nissan Civilian</i></span></td>
                                            <td></td>
                                            <td><span class="text-danger"><i><small>Vacant</small></i></span></td>
                                        </tr><tr>
                                            <td>Dhaka-Metro-CAA-71-2440<br><span class="font-11"><i>Nissan Civilian</i></span></td>
                                            <td></td>
                                            <td><span class="text-danger"><i><small>Vacant</small></i></span></td>
                                        </tr><tr>
                                            <td>Dhaka-Metro-CHA-56-1559<br><span class="font-11"><i>Toyota Hiace</i></span></td>
                                            <td></td>
                                            <td><span class="text-danger"><i><small>Vacant</small></i></span></td>
                                        </tr><tr>
                                            <td>Dhaka-Metro-Cha-56-1876<br><span class="font-11"><i>Toyota Hiace</i></span></td>
                                            <td></td>
                                            <td><span class="text-danger"><i><small>Vacant</small></i></span></td>
                                        </tr><tr>
                                            <td>Dhaka-Metro-GA-20-4106<br><span class="font-11"><i>Toyota Axio</i></span></td>
                                            <td></td>
                                            <td><span class="text-danger"><i><small>Vacant</small></i></span></td>
                                        </tr><tr>
                                            <td>Dhaka-Metro-GA-20-4107<br><span class="font-11"><i>Toyota Axio</i></span></td>
                                            <td></td>
                                            <td><span class="text-danger"><i><small>Vacant</small></i></span></td>
                                        </tr><tr>
                                            <td>Dhaka-Metro-GA-29-1557<br><span class="font-11"><i>Toyota Corolla</i></span></td>
                                            <td></td>
                                            <td><span class="text-danger"><i><small>Vacant</small></i></span></td>
                                        </tr><tr>
                                            <td>Dhaka-Metro-GA-29-1559<br><span class="font-11"><i>Hyundai Tucson</i></span></td>
                                            <td></td>
                                            <td><span class="text-danger"><i><small>Vacant</small></i></span></td>
                                        </tr><tr>
                                            <td>Dhaka-Metro-GA-33-4455<br><span class="font-11"><i>BMW  X7</i></span></td>
                                            <td></td>
                                            <td><span class="text-danger"><i><small>Vacant</small></i></span></td>
                                        </tr><tr>
                                            <td>Dhaka-Metro-GHA-17-0822<br><span class="font-11"><i>Audi A3</i></span></td>
                                            <td>Shuvo Chowdhuri</td>
                                            <td><span class="text-success"><i><small>Assigned</small></i></span></td>
                                        </tr></tbody>
                    </table>
                </div>
                <div class="text-center p-t-20 font-12">
                    <b>
                        <span class="text-success p-r-10">Assigned: <span id="assignedCount">1</span></span>
                        <span class="text-danger p-r-10">Vacant: <span id="vacantCount">17</span></span>
                        <span class="text-success"> En Route: <span id="enRouteCount">0</span> </span>
                    </b>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://vroom24x7.com/demo/assets/select_client/js/highChart.js"></script>
<style>
    .highcharts-credits{
        display:none;
    }
</style>

<script language="JavaScript">
    $(document).ready(function () {
        Highcharts.chart('costCategoryChatDiv', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: ''
            },
            tooltip: {
                headerFormat: '<span style="font-size:11px">Expense Category</span><br>',
                pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}</b><br/>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '{point.percentage:.1f} % of Total'
                    },
                    showInLegend: true
                }
            },
            series: [{
                    name: '',
                    colorByPoint: true,
                    data: []                }]
        });


        Highcharts.chart('costMonthChatDiv', {
            chart: {
                type: 'column'
            },
            title: {
                text: ''
            },
            xAxis: {
                categories: [
                    'Jan',
                    'Feb',
                    'Mar',
                    'Apr',
                    'May',
                    'Jun',
                    'Jul',
                    'Aug',
                    'Sep',
                    'Oct',
                    'Nov',
                    'Dec'
                ],
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Expense (BDT)'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.y:.1f} BDT</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [{
                    name: 'Month',
                    data: [0,0,0,0,0,0,0,0,0,0,0,0]                }]
        });

    });
  
    function changeYear(year, flag) {
        if(flag === 'month') {
            $('#monthWiseYear').val(year);
            $('#categoryWiseYear').val('2026');
        } else if (flag === 'category') {
            $('#categoryWiseYear').val(year);
            $('#monthWiseYear').val('2026');
        }
        $('#yearForm').submit();
    }
</script>

<div class="row clearfix">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <div class="card">
            <div class="table-responsive">
                <div class="text-center p-t-10 p-l-20 p-b-10 font-18">
                    Month Wise Total Expense
                </div>

                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <div class="form-group">
                        <div class="form-line focused">
                            <div class="btn-group bootstrap-select form-control"><button type="button" class="btn dropdown-toggle btn-default" data-toggle="dropdown" data-id="yearMonthWise" title="2026"><span class="filter-option pull-left">2026</span>&nbsp;<span class="bs-caret"><span class="caret"></span></span></button><div class="dropdown-menu open"><ul class="dropdown-menu inner" role="menu"><li data-original-index="0" class="selected"><a tabindex="0" class="" style="" data-tokens="null"><span class="text">2026</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li><li data-original-index="1"><a tabindex="0" class="" style="" data-tokens="null"><span class="text">2024</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li><li data-original-index="2"><a tabindex="0" class="" style="" data-tokens="null"><span class="text">2023</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li><li data-original-index="3"><a tabindex="0" class="" style="" data-tokens="null"><span class="text">2022</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li><li data-original-index="4"><a tabindex="0" class="" style="" data-tokens="null"><span class="text">2021</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li><li data-original-index="5"><a tabindex="0" class="" style="" data-tokens="null"><span class="text">2020</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li><li data-original-index="6"><a tabindex="0" class="" style="" data-tokens="null"><span class="text">2019</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li></ul></div><select class="form-control" id="yearMonthWise" onchange="changeYear(this.value, 'month')" tabindex="-98">
                                <option value="2026">2026</option>
                                <option value="2024">2024</option><option value="2023">2023</option><option value="2022">2022</option><option value="2021">2021</option><option value="2020">2020</option><option value="2019">2019</option>                            </select></div>
                        </div>
                    </div>
                </div>
                <div id="costMonthChatDiv" data-highcharts-chart="1"><div id="highcharts-s4skdoe-2" class="highcharts-container " style="position: relative; overflow: hidden; width: 565px; height: 1840px; text-align: left; line-height: normal; z-index: 0; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);"><svg version="1.1" class="highcharts-root" style="font-family:&quot;Lucida Grande&quot;, &quot;Lucida Sans Unicode&quot;, Arial, Helvetica, sans-serif;font-size:12px;" xmlns="http://www.w3.org/2000/svg" width="565" height="1840" viewBox="0 0 565 1840"><desc>Created with Highcharts 5.0.9</desc><defs><clipPath id="highcharts-s4skdoe-3"><rect x="0" y="0" width="494" height="1752" fill="none"></rect></clipPath></defs><rect fill="#ffffff" class="highcharts-background" x="0" y="0" width="565" height="1840" rx="0" ry="0"></rect><rect fill="none" class="highcharts-plot-background" x="61" y="10" width="494" height="1752"></rect><g class="highcharts-grid highcharts-xaxis-grid "><path fill="none" class="highcharts-grid-line" d="M 101.5 10 L 101.5 1762" opacity="1"></path><path fill="none" class="highcharts-grid-line" d="M 142.5 10 L 142.5 1762" opacity="1"></path><path fill="none" class="highcharts-grid-line" d="M 184.5 10 L 184.5 1762" opacity="1"></path><path fill="none" class="highcharts-grid-line" d="M 225.5 10 L 225.5 1762" opacity="1"></path><path fill="none" class="highcharts-grid-line" d="M 266.5 10 L 266.5 1762" opacity="1"></path><path fill="none" class="highcharts-grid-line" d="M 307.5 10 L 307.5 1762" opacity="1"></path><path fill="none" class="highcharts-grid-line" d="M 348.5 10 L 348.5 1762" opacity="1"></path><path fill="none" class="highcharts-grid-line" d="M 389.5 10 L 389.5 1762" opacity="1"></path><path fill="none" class="highcharts-grid-line" d="M 430.5 10 L 430.5 1762" opacity="1"></path><path fill="none" class="highcharts-grid-line" d="M 472.5 10 L 472.5 1762" opacity="1"></path><path fill="none" class="highcharts-grid-line" d="M 513.5 10 L 513.5 1762" opacity="1"></path><path fill="none" class="highcharts-grid-line" d="M 554.5 10 L 554.5 1762" opacity="1"></path><path fill="none" class="highcharts-grid-line" d="M 60.5 10 L 60.5 1762" opacity="1"></path></g><g class="highcharts-grid highcharts-yaxis-grid "><path fill="none" stroke="#e6e6e6" stroke-width="1" class="highcharts-grid-line" d="M 61 886.5 L 555 886.5" opacity="1"></path></g><rect fill="none" class="highcharts-plot-border" x="61" y="10" width="494" height="1752"></rect><g class="highcharts-axis highcharts-xaxis "><path fill="none" class="highcharts-tick" stroke="#ccd6eb" stroke-width="1" d="M 101.5 1762 L 101.5 1772" opacity="1"></path><path fill="none" class="highcharts-tick" stroke="#ccd6eb" stroke-width="1" d="M 142.5 1762 L 142.5 1772" opacity="1"></path><path fill="none" class="highcharts-tick" stroke="#ccd6eb" stroke-width="1" d="M 184.5 1762 L 184.5 1772" opacity="1"></path><path fill="none" class="highcharts-tick" stroke="#ccd6eb" stroke-width="1" d="M 225.5 1762 L 225.5 1772" opacity="1"></path><path fill="none" class="highcharts-tick" stroke="#ccd6eb" stroke-width="1" d="M 266.5 1762 L 266.5 1772" opacity="1"></path><path fill="none" class="highcharts-tick" stroke="#ccd6eb" stroke-width="1" d="M 307.5 1762 L 307.5 1772" opacity="1"></path><path fill="none" class="highcharts-tick" stroke="#ccd6eb" stroke-width="1" d="M 348.5 1762 L 348.5 1772" opacity="1"></path><path fill="none" class="highcharts-tick" stroke="#ccd6eb" stroke-width="1" d="M 389.5 1762 L 389.5 1772" opacity="1"></path><path fill="none" class="highcharts-tick" stroke="#ccd6eb" stroke-width="1" d="M 430.5 1762 L 430.5 1772" opacity="1"></path><path fill="none" class="highcharts-tick" stroke="#ccd6eb" stroke-width="1" d="M 472.5 1762 L 472.5 1772" opacity="1"></path><path fill="none" class="highcharts-tick" stroke="#ccd6eb" stroke-width="1" d="M 513.5 1762 L 513.5 1772" opacity="1"></path><path fill="none" class="highcharts-tick" stroke="#ccd6eb" stroke-width="1" d="M 555.5 1762 L 555.5 1772" opacity="1"></path><path fill="none" class="highcharts-tick" stroke="#ccd6eb" stroke-width="1" d="M 60.5 1762 L 60.5 1772" opacity="1"></path><path fill="none" class="highcharts-axis-line" stroke="#ccd6eb" stroke-width="1" d="M 61 1762.5 L 555 1762.5"></path></g><g class="highcharts-axis highcharts-yaxis "><text x="28.799999713897705" text-anchor="middle" transform="translate(0,0) rotate(270 28.799999713897705 886)" class="highcharts-axis-title" style="color:#666666;fill:#666666;" y="886"><tspan>Expense (BDT)</tspan></text><path fill="none" class="highcharts-axis-line" d="M 61 10 L 61 1762"></path></g><path fill="none" class="highcharts-crosshair highcharts-crosshair-category undefined" stroke="rgba(204,214,235,0.25)" stroke-width="41.166666666666664" visibility="visible" d="M 81.5 10 L 81.5 1762"></path><g class="highcharts-series-group"><g class="highcharts-series highcharts-series-0 highcharts-column-series highcharts-color-0 highcharts-tracker highcharts-series-hover" transform="translate(61,10) scale(1 1)" clip-path="url(#highcharts-s4skdoe-3)"><rect x="13" y="877" width="15" height="0" fill="rgb(149,206,255)" class="highcharts-point highcharts-color-0 highcharts-point-hover"></rect><rect x="54" y="877" width="15" height="0" fill="#7cb5ec" class="highcharts-point highcharts-color-0 "></rect><rect x="96" y="877" width="15" height="0" fill="#7cb5ec" class="highcharts-point highcharts-color-0 "></rect><rect x="137" y="877" width="15" height="0" fill="#7cb5ec" class="highcharts-point highcharts-color-0"></rect><rect x="178" y="877" width="15" height="0" fill="#7cb5ec" class="highcharts-point highcharts-color-0"></rect><rect x="219" y="877" width="15" height="0" fill="#7cb5ec" class="highcharts-point highcharts-color-0"></rect><rect x="260" y="877" width="15" height="0" fill="#7cb5ec" class="highcharts-point highcharts-color-0"></rect><rect x="301" y="877" width="15" height="0" fill="#7cb5ec" class="highcharts-point highcharts-color-0"></rect><rect x="343" y="877" width="15" height="0" fill="#7cb5ec" class="highcharts-point highcharts-color-0 "></rect><rect x="384" y="877" width="15" height="0" fill="#7cb5ec" class="highcharts-point highcharts-color-0"></rect><rect x="425" y="877" width="15" height="0" fill="#7cb5ec" class="highcharts-point highcharts-color-0"></rect><rect x="466" y="877" width="15" height="0" fill="#7cb5ec" class="highcharts-point highcharts-color-0"></rect></g><g class="highcharts-markers highcharts-series-0 highcharts-column-series highcharts-color-0 highcharts-series-hover" transform="translate(61,10) scale(1 1)" clip-path="none"></g></g><g class="highcharts-legend" transform="translate(245,1796)"><rect fill="none" class="highcharts-legend-box" rx="0" ry="0" x="0" y="0" width="74" height="29" visibility="visible"></rect><g><g><g class="highcharts-legend-item highcharts-column-series highcharts-color-0 highcharts-series-0" transform="translate(8,3)"><text x="21" style="color:#333333;font-size:12px;font-weight:bold;cursor:pointer;fill:#333333;" text-anchor="start" y="15">Month</text><rect x="2" y="4" width="12" height="12" fill="#7cb5ec" rx="6" ry="6" class="highcharts-point"></rect></g></g></g></g><g class="highcharts-axis-labels highcharts-xaxis-labels "><text x="81.58333333333333" style="color:#666666;cursor:default;font-size:11px;fill:#666666;" text-anchor="middle" transform="translate(0,0)" y="1781" opacity="1">Jan</text><text x="122.74999999999999" style="color:#666666;cursor:default;font-size:11px;fill:#666666;" text-anchor="middle" transform="translate(0,0)" y="1781" opacity="1">Feb</text><text x="163.91666666666666" style="color:#666666;cursor:default;font-size:11px;fill:#666666;" text-anchor="middle" transform="translate(0,0)" y="1781" opacity="1">Mar</text><text x="205.08333333333331" style="color:#666666;cursor:default;font-size:11px;fill:#666666;" text-anchor="middle" transform="translate(0,0)" y="1781" opacity="1">Apr</text><text x="246.25000000000003" style="color:#666666;cursor:default;font-size:11px;fill:#666666;" text-anchor="middle" transform="translate(0,0)" y="1781" opacity="1">May</text><text x="287.4166666666667" style="color:#666666;cursor:default;font-size:11px;fill:#666666;" text-anchor="middle" transform="translate(0,0)" y="1781" opacity="1">Jun</text><text x="328.5833333333333" style="color:#666666;cursor:default;font-size:11px;fill:#666666;" text-anchor="middle" transform="translate(0,0)" y="1781" opacity="1">Jul</text><text x="369.75" style="color:#666666;cursor:default;font-size:11px;fill:#666666;" text-anchor="middle" transform="translate(0,0)" y="1781" opacity="1">Aug</text><text x="410.91666666666663" style="color:#666666;cursor:default;font-size:11px;fill:#666666;" text-anchor="middle" transform="translate(0,0)" y="1781" opacity="1">Sep</text><text x="452.0833333333333" style="color:#666666;cursor:default;font-size:11px;fill:#666666;" text-anchor="middle" transform="translate(0,0)" y="1781" opacity="1">Oct</text><text x="493.24999999999994" style="color:#666666;cursor:default;font-size:11px;fill:#666666;" text-anchor="middle" transform="translate(0,0)" y="1781" opacity="1">Nov</text><text x="534.4166666666666" style="color:#666666;cursor:default;font-size:11px;fill:#666666;" text-anchor="middle" transform="translate(0,0)" y="1781" opacity="1">Dec</text></g><g class="highcharts-axis-labels highcharts-yaxis-labels "><text x="46" style="color:#666666;cursor:default;font-size:11px;fill:#666666;" text-anchor="end" transform="translate(0,0)" y="890" opacity="1"><tspan>0</tspan></text></g><text x="555" class="highcharts-credits" text-anchor="end" style="cursor:pointer;color:#999999;font-size:9px;fill:#999999;" y="1835">Highcharts.com</text><g class="highcharts-label highcharts-tooltip highcharts-color-0" style="cursor:default;pointer-events:none;white-space:nowrap;" transform="translate(31,827)" opacity="1" visibility="visible"><path fill="none" class="highcharts-label-box highcharts-tooltip-box" d="M 3.5 0.5 L 100.5 0.5 C 103.5 0.5 103.5 0.5 103.5 3.5 L 103.5 50.5 C 103.5 53.5 103.5 53.5 100.5 53.5 L 56.5 53.5 50.5 59.5 44.5 53.5 3.5 53.5 C 0.5 53.5 0.5 53.5 0.5 50.5 L 0.5 3.5 C 0.5 0.5 0.5 0.5 3.5 0.5" isShadow="true" stroke="#000000" stroke-opacity="0.049999999999999996" stroke-width="5" transform="translate(1, 1)"></path><path fill="none" class="highcharts-label-box highcharts-tooltip-box" d="M 3.5 0.5 L 100.5 0.5 C 103.5 0.5 103.5 0.5 103.5 3.5 L 103.5 50.5 C 103.5 53.5 103.5 53.5 100.5 53.5 L 56.5 53.5 50.5 59.5 44.5 53.5 3.5 53.5 C 0.5 53.5 0.5 53.5 0.5 50.5 L 0.5 3.5 C 0.5 0.5 0.5 0.5 3.5 0.5" isShadow="true" stroke="#000000" stroke-opacity="0.09999999999999999" stroke-width="3" transform="translate(1, 1)"></path><path fill="none" class="highcharts-label-box highcharts-tooltip-box" d="M 3.5 0.5 L 100.5 0.5 C 103.5 0.5 103.5 0.5 103.5 3.5 L 103.5 50.5 C 103.5 53.5 103.5 53.5 100.5 53.5 L 56.5 53.5 50.5 59.5 44.5 53.5 3.5 53.5 C 0.5 53.5 0.5 53.5 0.5 50.5 L 0.5 3.5 C 0.5 0.5 0.5 0.5 3.5 0.5" isShadow="true" stroke="#000000" stroke-opacity="0.15" stroke-width="1" transform="translate(1, 1)"></path><path fill="rgba(247,247,247,0.85)" class="highcharts-label-box highcharts-tooltip-box" d="M 3.5 0.5 L 100.5 0.5 C 103.5 0.5 103.5 0.5 103.5 3.5 L 103.5 50.5 C 103.5 53.5 103.5 53.5 100.5 53.5 L 56.5 53.5 50.5 59.5 44.5 53.5 3.5 53.5 C 0.5 53.5 0.5 53.5 0.5 50.5 L 0.5 3.5 C 0.5 0.5 0.5 0.5 3.5 0.5" stroke="#7cb5ec" stroke-width="1"></path></g></svg><div class="highcharts-label highcharts-tooltip" style="position: absolute; left: 31px; top: 827px; opacity: 1; pointer-events: none; visibility: visible;"><span style="font-family: &quot;Lucida Grande&quot;, &quot;Lucida Sans Unicode&quot;, Arial, Helvetica, sans-serif; font-size: 12px; position: absolute; white-space: nowrap; color: rgb(51, 51, 51); margin-left: 0px; margin-top: 0px; left: 8px; top: 8px;"><span style="font-size:10px">Jan</span><table><tbody><tr><td style="color:#7cb5ec;padding:0">Month: </td><td style="padding:0"><b>0.0 BDT</b></td></tr></tbody></table></span></div></div></div>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <div class="card">
            <div class="table-responsive">
                <div class="text-center p-t-10 p-l-20 p-b-10 font-18">
                    Category Wise Expense
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <div class="form-group">
                        <div class="form-line focused">
                            <div class="btn-group bootstrap-select form-control"><button type="button" class="btn dropdown-toggle btn-default" data-toggle="dropdown" data-id="yearCategoryWise" title="2026"><span class="filter-option pull-left">2026</span>&nbsp;<span class="bs-caret"><span class="caret"></span></span></button><div class="dropdown-menu open"><ul class="dropdown-menu inner" role="menu"><li data-original-index="0" class="selected"><a tabindex="0" class="" style="" data-tokens="null"><span class="text">2026</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li><li data-original-index="1"><a tabindex="0" class="" style="" data-tokens="null"><span class="text">2024</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li><li data-original-index="2"><a tabindex="0" class="" style="" data-tokens="null"><span class="text">2023</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li><li data-original-index="3"><a tabindex="0" class="" style="" data-tokens="null"><span class="text">2022</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li><li data-original-index="4"><a tabindex="0" class="" style="" data-tokens="null"><span class="text">2021</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li><li data-original-index="5"><a tabindex="0" class="" style="" data-tokens="null"><span class="text">2020</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li><li data-original-index="6"><a tabindex="0" class="" style="" data-tokens="null"><span class="text">2019</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li></ul></div><select class="form-control" id="yearCategoryWise" onchange="changeYear(this.value, 'category')" tabindex="-98">
                                <option value="2026">2026</option>
                                <option value="2024">2024</option><option value="2023">2023</option><option value="2022">2022</option><option value="2021">2021</option><option value="2020">2020</option><option value="2019">2019</option>                            </select></div>
                        </div>
                    </div>
                </div>
                <div id="costCategoryChatDiv" data-highcharts-chart="0"><div id="highcharts-s4skdoe-0" class="highcharts-container " style="position: relative; overflow: hidden; width: 565px; height: 1840px; text-align: left; line-height: normal; z-index: 0; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);"><svg version="1.1" class="highcharts-root" style="font-family:&quot;Lucida Grande&quot;, &quot;Lucida Sans Unicode&quot;, Arial, Helvetica, sans-serif;font-size:12px;" xmlns="http://www.w3.org/2000/svg" width="565" height="1840" viewBox="0 0 565 1840"><desc>Created with Highcharts 5.0.9</desc><defs><clipPath id="highcharts-s4skdoe-1"><rect x="0" y="0" width="545" height="1815" fill="none"></rect></clipPath></defs><rect fill="#ffffff" class="highcharts-background" x="0" y="0" width="565" height="1840" rx="0" ry="0"></rect><rect fill="none" class="highcharts-plot-background" x="10" y="10" width="545" height="1815"></rect><rect fill="none" class="highcharts-plot-border" x="10" y="10" width="545" height="1815"></rect><g class="highcharts-series-group"><g class="highcharts-series highcharts-series-0 highcharts-pie-series highcharts-color-undefined highcharts-tracker" transform="translate(10,10) scale(1 1)" style="cursor:pointer;"></g><g class="highcharts-markers highcharts-series-0 highcharts-pie-series highcharts-color-undefined " transform="translate(10,10) scale(1 1)"></g></g><g class="highcharts-data-labels highcharts-series-0 highcharts-pie-series highcharts-color-undefined highcharts-tracker" transform="translate(10,10) scale(1 1)" opacity="1" style="cursor:pointer;" visibility="visible"></g><g class="highcharts-legend"><rect fill="none" class="highcharts-legend-box" rx="0" ry="0" x="0" y="0" width="8" height="8" visibility="hidden"></rect><g><g></g></g></g><text x="555" class="highcharts-credits" text-anchor="end" style="cursor:pointer;color:#999999;font-size:9px;fill:#999999;" y="1835">Highcharts.com</text></svg></div></div>
            </div>
        </div>
    </div>
</div>

</div>


<script>
    function showDocDashboard(flag) {
        window.location.href = "{{ url('client/Home/showDocDashboard') }}?value=" + flag;
    }
    
    $(function () {
        // Popover initialize
        $('[data-toggle="popover"]').popover({
            html: true
        });
    });
</script>
@endsection