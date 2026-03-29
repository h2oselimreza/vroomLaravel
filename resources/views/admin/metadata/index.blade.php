@extends('layouts.app')
@section('content')

<div class="header dashboard_from">
    <h1 class="page-title">Area</h1>
    <ul class="breadcrumb">
        <li><a href="admin/Home">Home</a></li>
        <li><a href="#">/ Master Data</a></li>
        <li><a href="admin/MasterData/area">/ Area</a></li>
    </ul>
</div>
<div class="main-content">
    <div class="row">
        <div class="col-sm-12 col-md-12">
            
            @include('admin.metadata.areaHeaderMenu')

            <div class="panel panel-default"> 
                <div class="row justify-content-center text-center">

                    <!-- category -->
                    <div class="col-md-2 col-sm-4 col-xs-12">
                        <div class="circle-tile">
                            <a href="#">
                                <div class="circle-tile-heading dark-blue">
                                    <i class="fa fa-bars fa-fw fa-2x"></i>
                                </div>
                            </a>
                            <div class="circle-tile-content dark-blue">
                                <div class="circle-tile-description text-faded">
                                    Division
                                </div>
                                <div class="circle-tile-number text-faded">
                                    {{ $areaCount['divisionCount'] }}
                                    <span id="sparklineA"></span>
                                </div>
                                <a href="#" class="circle-tile-footer" onclick="areaRoute('division')">
                                    View <i class="fa fa-chevron-circle-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- product -->
                    <div class="col-md-2 col-sm-4 col-xs-12">
                        <div class="circle-tile">
                            <a href="#">
                                <div class="circle-tile-heading dark-blue">
                                    <i class="fa fa-list fa-fw fa-2x"></i>
                                </div>
                            </a>
                            <div class="circle-tile-content dark-blue">
                                <div class="circle-tile-description text-faded">
                                    District
                                </div>
                                <div class="circle-tile-number text-faded">
                                    {{ $areaCount['districtCount'] }}
                                    <span id="sparklineA"></span>
                                </div>
                                <a href="#" class="circle-tile-footer" onclick="areaRoute('district')">
                                    View <i class="fa fa-chevron-circle-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Variants -->
                    <div class="col-md-2 col-sm-4 col-xs-12">
                        <div class="circle-tile">
                            <a href="#">
                                <div class="circle-tile-heading dark-blue">
                                    <i class="fa fa-list-alt fa-fw fa-2x"></i>
                                </div>
                            </a>
                            <div class="circle-tile-content dark-blue">
                                <div class="circle-tile-description text-faded">
                                    Upozilla
                                </div>
                                <div class="circle-tile-number text-faded">
                                    {{ $areaCount['upozillaCount'] }}
                                    <span id="sparklineA"></span>
                                </div>
                                <a href="#" class="circle-tile-footer" onclick="areaRoute('upozilla')">
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
