@extends('client.layouts.app')

@section('content')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" media="screen">
<style>
    .panel-group{ margin-bottom: 0px; }
    .card .header{ padding: 10px;}
    .table-td-info
    {	
        background:#FFFFFF;
        font-size:11px;
        font-family:Verdana, Geneva, sans-serif;    
        font-weight:normal;
        padding-left:7px;
        padding-top:2px;
        padding-bottom:2px;
    }
    .bottom-border{border-bottom: 1px solid #ddd;} 
    .card .body .service-variant{
        margin-bottom: 1px;
        margin-top: 2px;
    }
    .card .body .service{
        margin-bottom: 1px;
    }
    .gallery
    {
        display: inline-block;
        margin-top: 0px;
    }
    .image-thum-custom{
        height: 200px;
    }
    .image_block{
        /*        border: 1px solid black;*/
        border-radius:3px;
        padding:5px;
        width:240px;
        float:left;
        margin:15px;
    }
    .image_block_inner{
        position:relative;
        margin-bottom:5px;
    }
    .checkbox{
        position:absolute;
        z-index:2;
        background:white;
        border-bottom-right-radious:3px;
        box-shadow: 1px 1px rgba(0,0,0,.2);
    }

    .thumbnail {
        display:table;
        border-spacing: 2px;
        border-collapse: separate;
        border-radius:10px; /* Demonstrational.. */
    }
    .thumbnail_wrapper {
        display:table-cell;
        vertical-align:middle;
    }
    .thumbnail_wrapper > img {
        width:100%;
    }
    .bg-dark-gray{
        background: #595f67;
    }
</style>

<div class="block-header">
    <h2>SET WORKSHOP APPOINTMENT</h2><br>
    <div class="breadcrumb breadcrumb-bg-blue-grey">
        <li><a href="client/Home"> Home</a></li>
        <li><a href="#"> Vehicle Maintenance</a></li>
        <li><a href="{{ route('client.vehicle-maintenance.set-workshop-appointment') }}"> Set Workshop Appointment</a></li>
    </div>
</div>

<div class="row clearfix">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="body">
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
                <button class="btn bg-blue btn-sm waves-effect" data-toggle="modal" data-target="#serviceModal" id="serviceModalShowBtn">Advanced Search</button>

                <div class="row m-t-20">
                    @php
                    $count = 1;
                    @endphp

                    @if(!empty($workshops) && count($workshops) > 0)

                        @foreach($workshops as $workshop)

                            @php
                                $divisionName = $workshop->division_en_name
                                    ? $workshop->division_en_name . ' (' . $workshop->division_bn_name . ')'
                                    : '';

                                $districtName = $workshop->district_en_name
                                    ? $workshop->district_en_name . ' (' . $workshop->district_bn_name . ')'
                                    : '';

                                $upozillaName = $workshop->upozilla_en_name
                                    ? $workshop->upozilla_en_name . ' (' . $workshop->upozilla_bn_name . ')'
                                    : '';
                            @endphp

                            <div class="col-md-3 col-sm-4 col-xs-6">
                                <div class="card">

                                    <div class="header bg-dark-gray text-center">
                                        @if(!empty($workshop->profile_image))
                                            <img class="img-border"
                                                src="{{ asset('assets/images/workshop/' . $workshop->profile_image) }}"
                                                height="100">
                                        @else
                                            <img class="img-border"
                                                src="{{ asset('assets/images/company/no_image.jpg') }}"
                                                height="100">
                                        @endif
                                    </div>

                                    <div class="body custom-body">

                                        <span class="font-14 pointer"
                                            data-toggle="tooltip"
                                            data-placement="right"
                                            title="{{ $workshop->title }}">

                                            <b>{{ \Illuminate\Support\Str::limit($workshop->title, 45) }}</b>
                                        </span>

                                        <div class="clear m-t-10"></div>

                                        <span class="pointer"
                                            data-toggle="tooltip"
                                            data-placement="right"
                                            title="{{ $workshop->address }}">

                                            {{ \Illuminate\Support\Str::limit($workshop->address, 60) }}
                                        </span>

                                    </div>

                                    <div class="clear"><hr></div>

                                    <div class="p-b-20 text-center">

                                        <button type="button"
                                                class="btn btn-sm bg-blue waves-effect"
                                                onclick="showDetails('{{ $count }}', '{{ $workshop->workshop_code }}')">
                                            Show Detail
                                        </button>

                                        <a href="{{ url('client/vehicle-maintenance/create-appointment?workshop=' . $workshop->workshop_code) }}"
                                        class="btn btn-sm bg-blue waves-effect">
                                            Set Appointment
                                        </a>

                                    </div>

                                </div>
                            </div>

                            {{-- Hidden fields --}}
                            <input type="hidden" id="title{{ $count }}" value="{{ $workshop->title }}">
                            <input type="hidden" id="email{{ $count }}" value="{{ $workshop->workshop_email }}">
                            <input type="hidden" id="website{{ $count }}" value="{{ $workshop->website }}">
                            <input type="hidden" id="address{{ $count }}" value="{{ $workshop->address }}">
                            <input type="hidden" id="division{{ $count }}" value="{{ $divisionName }}">
                            <input type="hidden" id="district{{ $count }}" value="{{ $districtName }}">
                            <input type="hidden" id="upozilla{{ $count }}" value="{{ $upozillaName }}">

                            @php $count++; @endphp

                        @endforeach

                    @else
                        <div class="text-center text-danger font-16">
                            No Workshop Found
                        </div>
                    @endif

                </div>

                <!-- --------------- workshop  details modal ---------------- -->
                <button class="btn hidden" data-toggle="modal" data-target="#workshopModal" id="workshopModalShowBtn"></button>
                <div class="modal fade" id="workshopModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title text-vroom-orange" id="workshopTitle"></h3>
                            </div>
                            <div class="modal-body">
                                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                    <div class="panel panel1 panel-default" id="otherImagePanel">
                                        <div id="imageCollapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingImage">
                                            <div class="panel-body">
                                                <div class='gallery' id="otherImgaeGallery"></div>
                                                <div id="showMoreImageLink">

                                                </div>
                                                <input type="hidden" id="imageLimitCountHidden" value="0">
                                            </div>
                                        </div>
                                    </div> 


                                    <div class="panel panel1 panel-default">
                                        <div class="panel-heading custom-panel-heading" role="tab" id="headingOne">
                                            <p class="panel-title custom-panel-title1 p-t-0 p-b-0">
                                                <a role="button" data-toggle="collapse" data-parent="#" href="#generalCollapseOne" aria-expanded="true" aria-controls="generalCollapseOne">
                                                    <i class="fa fa-tags"></i> General Information
                                                </a>
                                            </p>
                                        </div>
                                        <div id="generalCollapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                            <div class="panel-body">
                                                <div class="table-responsive">
                                                    <table border="0" cellpadding="0" cellspacing="0" align="center" width="100%">
                                                        <tr class="table-td-info">
                                                            <td width="10%" align="left" class="content-table-td"><b>Email</b></td>
                                                            <td width="2%" align="center">:</td>
                                                            <td width="38%" align="left" id="workshopEmail"></td>
                                                            <td width="10%" align="left" class="content-table-td"><b>Website</b></td>
                                                            <td width="2%" align="center">:</td>
                                                            <td width="38%" align="left" id="workshopWebsite"></td>
                                                        </tr>
                                                        <tr class="table-td-info">
                                                            <td align="left" class="content-table-td"><b>Address</b></td>
                                                            <td align="center">:</td>
                                                            <td align="left" id="workshopAddress"></td>
                                                            <td align="left" class="content-table-td"><b>Division</b></td>
                                                            <td align="center">:</td>
                                                            <td align="left" id="workshopDivision"></td>
                                                        </tr>
                                                        <tr class="table-td-info">
                                                            <td align="left" class="content-table-td"><b>District</b></td>
                                                            <td align="center">:</td>
                                                            <td align="left" id="workshopDistrict"></td>
                                                            <td align="left" class="content-table-td"><b>Upozilla</b></td>
                                                            <td align="center">:</td>
                                                            <td align="left" id="workshopUpozilla"></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>   
                                    <div class="panel panel1 panel-default">
                                        <div class="panel-heading custom-panel-heading" role="tab" id="two">
                                            <p class="panel-title custom-panel-title1 p-t-0 p-b-0">
                                                <a role="button" data-toggle="collapse" data-parent="#" href="#timeShedule" aria-expanded="true" aria-controls="timeShedule">
                                                    <i class="fa fa-clock-o"></i> Time Schedule
                                                </a>
                                            </p>
                                        </div>
                                        <div id="timeShedule" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="two">
                                            <div class="panel-body">
                                                <table border="0" cellpadding="0" cellspacing="0" align="center" width="100%">
                                                    <tr class="table-td-info">
                                                        <td width="10%" align="left" class="content-table-td"><b><span id="day1"></span></b></td>
                                                        <td width="2%" align="center">:</td>
                                                        <td width="85%" align="left" id="time1"></td>
                                                    </tr>
                                                    <?php
                                                    for ($i = 2; $i < 8; $i++) {
                                                        ?>
                                                        <tr class="table-td-info">
                                                            <td align="left" class="content-table-td"><b><span id="day<?php echo $i ?>"></span></b></td>
                                                            <td align="center">:</td>
                                                            <td align="left" id="time<?php echo $i ?>"></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    ?>
                                                </table>
                                            </div>
                                        </div>
                                    </div>



                                    <div class="panel panel1 panel-default">
                                        <div class="panel-heading custom-panel-heading" role="tab" id="three">
                                            <p class="panel-title custom-panel-title1 p-t-0 p-b-0">
                                                <a role="button" data-toggle="collapse" data-parent="#" href="#vehicleServie" aria-expanded="true" aria-controls="vehicleServie">
                                                    <i class="fa fa-car"></i> Vehicle Services
                                                </a>
                                            </p>
                                        </div>
                                        <div id="vehicleServie" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="three">
                                            <div class="panel-body">
                                                <div class="font-13" id="vehicleServieDiv">
                                                </div>
                                            </div>
                                        </div>
                                    </div>



                                    <div class="panel panel1 panel-default">
                                        <div class="panel-heading custom-panel-heading" role="tab" id="four">
                                            <p class="panel-title custom-panel-title1 p-t-0 p-b-0">
                                                <a role="button" data-toggle="collapse" data-parent="#" href="#service" aria-expanded="true" aria-controls="service">
                                                    <i class="fa fa-bars"></i> Services
                                                </a>
                                            </p>
                                        </div>
                                        <div id="service" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="four">
                                            <div class="panel-body">
                                                <div class="font-13" id="serviceDiv">

                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger waves-effect" id="modalCloseBtn" data-dismiss="modal">CLOSE</button>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- --------------- search modal -------------------- -->

                <div class="modal fade" id="serviceModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="largeModalLabel">Search Workshop</h4>
                            </div>
                            <form action="{{ route('client.vehicle-maintenance.set-workshop-appointment') }}">
                                @csrf
                                <div class="modal-body">
                                    <?php
                                    $divisionId = "";
                                    $divisionId = $searchArea['division'];
                                    $divisionName = get_division_name($divisionId);

                                    $districtId = "";
                                    $districtId = $searchArea['district'];
                                    $districtName = get_district_name($districtId);

                                    $upozillaId = "";
                                    $upozillaId = $searchArea['upozilla'];
                                    $upozillaName = get_uplozilla_name($upozillaId);
                                    ?>
                                    <div class="row">
                                        <div class="col-md-4 col-sm-4 col-xs-12">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <select class="form-control" name="division" id="division"  onchange="setDistrict(this.value)">
                                                        <?php
                                                        if ($divisionId) {
                                                            echo '<option value="' . $divisionId . '">' . $divisionName . '</option>';
                                                        } else {
                                                            echo '<option value=""></option>';
                                                        }
                                                        ?>


                                                        <?php
                                                        foreach ($divisions as $division) {
                                                            echo "<option value='$division[id]'>$division[division_en_name] ($division[division_bn_name])</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                    <div class="help-info">Division</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-4 col-xs-12">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <div id="districtDiv">
                                                        <select class="form-control" name="district" id="district" onchange="setUpozilla(this.value)">
                                                            <?php
                                                            if ($districtId) {
                                                                echo '<option value="' . $districtId . '">' . $districtName . '</option>';
                                                            } else {
                                                                echo '<option value=""></option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="help-info">District</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-4 col-xs-12">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <div id="uplozillaDiv">
                                                        <select class="form-control" name="upozilla" id="upozilla" >
                                                            <?php
                                                            if ($upozillaId) {
                                                                echo '<option value="' . $upozillaId . '">' . $upozillaName . '</option>';
                                                            } else {
                                                                echo '<option value=""></option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="help-info">Upozilla</div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                        <?php
                                        $serviceCount = 1;

                                        foreach ($distinctServices as $distinctService) {
                                            ?>
                                            <div class="panel panel1 panel-default">
                                                <div class="panel-heading custom-panel-heading" role="tab" id="headingOne">
                                                    <p class="panel-title custom-panel-title1 p-t-0 p-b-0">
                                                        <a role="button" data-toggle="collapse" data-parent="#" href="#generalCollapseOne{{$distinctService->service}}" aria-expanded="true" aria-controls="generalCollapseOne{{$distinctService->service}}">
                                                            <i class="fa fa-tags"></i> <?php echo $distinctService->service_name ?>
                                                        </a>
                                                    </p>
                                                </div>
                                                <div id="generalCollapseOne{{$distinctService->service}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                                                    <div class="panel-body">
                                                        <table class="table table-striped custom-table">
                                                            @php
                                                                $serviceVarSerial = 1;
                                                            @endphp
                                                            @foreach ($serviceVariants as $serviceVariant)
                                                        
                                                                @if ($serviceVariant->service == $distinctService->service)
                                                        
                                                                    @php
                                                                        $checked = in_array($serviceVariant->variant_code, $searchVariantArr) ? 'checked' : '';
                                                                    @endphp
                                                        
                                                                    <tr>
                                                                        <td>{{ $serviceVarSerial }}</td>
                                                        
                                                                        <td class="td-left" style="width:80%">
                                                                            {{ $serviceVariant->service_variant_name }}
                                                                        </td>
                                                        
                                                                        <td class="td-left">
                                                                            <input type="checkbox"
                                                                                name="serviceVarCheckBox{{ $serviceCount }}"
                                                                                id="serviceVarCheckBox{{ $serviceCount }}"
                                                                                class="filled-in chk-col-blue"
                                                                                {{ $checked }}>
                                                                            <label for="serviceVarCheckBox{{ $serviceCount }}"
                                                                                class="form-label"
                                                                                style="margin-bottom: -12px">
                                                                            </label>
                                                                        </td>

                                                                        <input type="hidden"
                                                                            name="serviceVariantCode{{ $serviceCount }}"
                                                                            value="{{ $serviceVariant->variant_code }}">
                                                        
                                                                        <input type="hidden"
                                                                            id="serviceVariantName{{ $serviceCount }}"
                                                                            value="{{ $serviceVariant->service_variant_name }}">
                                                                    </tr>
                                                        
                                                                    @php
                                                                        $serviceVarSerial++;
                                                                        $serviceCount++;
                                                                    @endphp
                                                        
                                                                @endif
                                                        
                                                            @endforeach
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                        <input type="hidden" name="serviceVariantCount" value="{{$serviceCount}}" >
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary waves-effect">SEARCH</button>
                                    <button type="button" class="btn btn-danger waves-effect" id="modalCloseBtn" data-dismiss="modal">CLOSE</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- ------------- ----------------- ----------------- -->
            </div>
        </div>
    </div>
</div>


@endsection
@push('scripts')
<script src="//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js"></script>
<script>

    var districtObj = @json($districts);
    var upozillaObj = @json($upozillas);

    function setDistrict(divisionId) {

        var optionStr = "<option value=''>Nothing Selected</option>";

        districtObj.districtData.forEach(function(item) {

            // FIX: division is OBJECT → use division.id OR division_id
            if (item.division_id == divisionId || (item.division && item.division.id == divisionId)) {

                optionStr += "<option value='" + item.id + "'>" +
                    item.district_en_name + " (" + item.district_bn_name + ")" +
                    "</option>";
            }
        });

        $('#districtDiv').html(`
            <select class="form-control" name="district" id="district">
                ${optionStr}
            </select>
        `);

        $('#district').on('change', function () {
            setUpozilla(this.value);
        });
    }

    function setUpozilla(districtId) {

        var optionStr = "<option value=''>Nothing Selected</option>";

        upozillaObj.upozillaData.forEach(function(item) {

            // FIX: district is likely district_id OR object
            if (item.district_id == districtId || (item.district && item.district.id == districtId)) {

                optionStr += "<option value='" + item.id + "'>" +
                    item.upozilla_en_name + " (" + item.upozilla_bn_name + ")" +
                    "</option>";
            }
        });

        $('#uplozillaDiv').html(`
            <select class="form-control" name="upozilla" id="upozilla">
                ${optionStr}
            </select>
        `);
    }

    function showDetails(count, workshopCode) {

        $('#imageLimitCountHidden').val(0);
        showLoader();

        for (let i = 1; i < 8; i++) {
            $('#day' + i).text('');
            $('#time' + i).text('');
        }

        $('#vehicleServieDiv').html("");
        $('#serviceDiv').html("");
        $('#workshopModalShowBtn').click();

        $.ajax({
            type: 'POST',
            url: '/client/vehicle-maintenance/getWorkshopInfo',
            data: {
                workshopCode: workshopCode,
                _token: "{{ csrf_token() }}"
            },
            success: function (resultObj) {

                hideLoader();

                // ❌ REMOVE: jQuery.parseJSON (Laravel already returns JSON)
                // var resultObj = jQuery.parseJSON(result);

                /* ================= TIME SCHEDULE ================= */
                let j = 1;

                resultObj.timeShedule.forEach(function (item) {

                    $('#day' + j).text(item.weekday_name);

                    let startTime = getTimeAmPmFormat(item.start_time);
                    let endTime = getTimeAmPmFormat(item.end_time);

                    if (item.weekend_status == "1") {
                        $('#time' + j).html('<span class="text-danger"><b>WEEKEND</b></span>');
                    } else {
                        $('#time' + j).text(startTime + ' To ' + endTime);
                    }

                    j++;
                });

                /* ================= VEHICLE TYPE ================= */
                let serviceVehicleStr = "<ul>";

                resultObj.serviceVehicle.forEach(function (item) {
                    serviceVehicleStr += `<li>${item.vehicle_type_name}</li>`;
                });

                serviceVehicleStr += "</ul>";

                $('#vehicleServieDiv').html(serviceVehicleStr);

                /* ================= SERVICES ================= */
                let serviceStr = "";

                resultObj.distinctService.forEach(function (serviceItem) {

                    let serviceVariantStr = "";

                    resultObj.allService.forEach(function (variantItem) {

                        if (serviceItem.service == variantItem.service) {

                            serviceVariantStr += `
                                <div class='col-md-6 col-sm-6 col-xs-12 font-12 service-variant'>
                                    <li>${variantItem.service_variant_name}</li>
                                </div>
                            `;
                        }
                    });

                    serviceStr += `
                        <div class='row'>
                            <div class='col-md-12 font-12 service'>
                                <div class='bottom-border'>
                                    <b>${serviceItem.service_name}</b>
                                </div>
                            </div>
                        </div>

                        <div class='row m-b-20'>
                            ${serviceVariantStr}
                        </div>
                    `;
                });

                $('#serviceDiv').html(serviceStr);

                /* ================= IMAGES ================= */
                let imageStr = "";
                let images = resultObj.otherImage || [];

                let loopValue = images.length > 3 ? images.length - 1 : images.length;

                for (let i = 0; i < loopValue; i++) {

                    let imagePath = images[i];

                    imageStr += `
                        <div class="image_block">
                            <div class="image_block_inner">
                                <a class="fancybox" rel="ligthbox" href="${imagePath}">
                                    <div class="thumbnail" style="height: 200px">
                                        <div class="thumbnail_wrapper">
                                            <img class="img-responsive" src="${imagePath}" />
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    `;
                }

                let imageLimitCountHidden = parseInt($('#imageLimitCountHidden').val() || 0);
                let newImageLimitCount = imageLimitCountHidden + loopValue;

                $('#imageLimitCountHidden').val(newImageLimitCount);

                if (imageStr.length > 0) {
                    $('#otherImagePanel').show();
                } else {
                    $('#otherImagePanel').hide();
                }

                $('#otherImgaeGallery').html(imageStr);

                if (images.length > 3) {
                    $('#showMoreImageLink').show();

                    $('#showMoreImageLink').html(`
                        <button class='btn btn-xs btn-primary'
                            onclick='showMoreImage("${workshopCode}", "${newImageLimitCount}")'>
                            Show More Image
                        </button>
                    `);
                } else {
                    $('#showMoreImageLink').hide();
                }
            }
        });

        /* ================= STATIC INFO ================= */
        $('#workshopTitle').text($('#title' + count).val());
        $('#workshopEmail').text($('#email' + count).val());
        $('#workshopWebsite').text($('#website' + count).val());
        $('#workshopAddress').text($('#address' + count).val());
        $('#workshopDivision').text($('#division' + count).val());
        $('#workshopDistrict').text($('#district' + count).val());
        $('#workshopUpozilla').text($('#uplozilla' + count).val());
    }

    function showMoreImage(workshopCode, previousLimit) {
        showLoader();
//        console.log(workshopCode);
//                console.log(previousLimit);
        $.ajax({
            type: 'POST',
            data: {previousLimit: previousLimit, workshopCode: workshopCode},
            url: 'client/Appointment/showWorkshopImages',
            success: function (result) {
                hideLoader();
                var resultObj = jQuery.parseJSON(result);
                //console.log(resultObj);

                console.log(resultObj.otherImage);
                var imageStr = "";
                var otherImageObjLength = resultObj.otherImage.length;
                var loopValue = otherImageObjLength;
                if (otherImageObjLength > 3) {
                    loopValue = otherImageObjLength - 1;
                }

                for (var i = 0; i < loopValue; i++) {
                    var imagePath = resultObj.otherImage[i];
                    imageStr += '<div class="image_block">\n\
                                    <div class="image_block_inner">\n\
                                        <a class="fancybox" rel="ligthbox" href="' + imagePath + '">\n\
                                            <div class="thumbnail" style="height: 200px">\n\
                                                <div class="thumbnail_wrapper">\n\
                                                    <img class="img-responsive" alt="" src="' + imagePath + '"  />\n\
                                                </div>\n\
                                            </div>\n\
                                        </a>\n\
                                    </div>\n\
                                </div>';
                }
                var imageLimitCountHidden = $('#imageLimitCountHidden').val();
                var newImageLimitCount = parseInt(imageLimitCountHidden) + i;
                $('#imageLimitCountHidden').val(newImageLimitCount);
                //console.log(newImageLimitCount);

                if (imageStr) {
                    $('#otherImagePanel').show();
                } else {
                    $('#otherImagePanel').hide();
                }
                $('#otherImgaeGallery').append(imageStr);
                if (otherImageObjLength > 3) {
                    $('#showMoreImageLink').show();
                    $('#showMoreImageLink').html("<button class='btn btn-xs btn-primary' onclick='showMoreImage(\"" + workshopCode + "\",\"" + newImageLimitCount + "\");'>Show More Image</button>");
                    //  $('#showMoreImageLink').html('<a target="_blank" href="client/Appointment/showWorkshopImages?workshop=' + workshopCode + '"><div class="text-right">Show More Images</div></a>');
                } else {
                    $('#showMoreImageLink').hide();
                }
            }
        });
    }


</script>
@endpush