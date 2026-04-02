@extends('layouts.app')
@section('content')

<div class="header dashboard_from">
    <h1 class="page-title">Appointment Service</h1>
    <ul class="breadcrumb">
        <li><a href="admin/Home">Home</a></li>
        <li><a href="#">/ Master Data</a></li>
        <li><a href="#">/ Appointment Service</a></li>
    </ul>
</div>
<div class="main-content">
    <div class="row">
        <div class="col-sm-12 col-md-12">
            
            @include('admin.master-data.appointment-service.tab')

            <div class="panel panel-default"> 
                <div class="row justify-content-center text-center">

                    <!-- Service Category -->
                    <div class="col-md-2 col-sm-4 col-xs-12">
                        <div class="circle-tile">
                            <a href="#">
                                <div class="circle-tile-heading dark-blue">
                                    <i class="fa fa-bars fa-fw fa-2x"></i>
                                </div>
                            </a>
                            <div class="circle-tile-content dark-blue">
                                <div class="circle-tile-description text-faded">
                                    Service Category
                                </div>
                                <div class="circle-tile-number text-faded">
                                    {{ $serviceCategoryCount }}
                                    <span id="sparklineA"></span>
                                </div>
                                <a href="#" class="circle-tile-footer" onclick="areaRoute('master-data/call-reason')">
                                    View <i class="fa fa-chevron-circle-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Service List -->
                    <div class="col-md-2 col-sm-4 col-xs-12">
                        <div class="circle-tile">
                            <a href="#">
                                <div class="circle-tile-heading dark-blue">
                                    <i class="fa fa-list fa-fw fa-2x"></i>
                                </div>
                            </a>
                            <div class="circle-tile-content dark-blue">
                                <div class="circle-tile-description text-faded">
                                    Service List
                                </div>
                                <div class="circle-tile-number text-faded">
                                    {{ $serviceListCount }}
                                    <span id="sparklineA"></span>
                                </div>
                                <a href="#" class="circle-tile-footer" onclick="areaRoute('master-data/customer-feedback')">
                                    View <i class="fa fa-chevron-circle-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Service Variant -->
                    <div class="col-md-2 col-sm-4 col-xs-12">
                        <div class="circle-tile">
                            <a href="#">
                                <div class="circle-tile-heading dark-blue">
                                    <i class="fa fa-list fa-fw fa-2x"></i>
                                </div>
                            </a>
                            <div class="circle-tile-content dark-blue">
                                <div class="circle-tile-description text-faded">
                                    Service Variant
                                </div>
                                <div class="circle-tile-number text-faded">
                                    {{ $serviceVariantCount }}
                                    <span id="sparklineA"></span>
                                </div>
                                <a href="#" class="circle-tile-footer" onclick="areaRoute('master-data/customer-feedback')">
                                    View <i class="fa fa-chevron-circle-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@push('scripts')
<script>
    $(document).ready(function () {

        $('#datatable').DataTable({
            processing: true,
            serverSide: true,
        });

    });
@endpush
