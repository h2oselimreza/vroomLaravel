@extends('client.layouts.app')

@section('content')

<style>
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
</style>


<div class="block-header">
    <h2>VEHICLE LOCATION</h2><br>
    <div class="breadcrumb breadcrumb-bg-blue-grey">
        <li><a href="client/Home"> Home</a></li>
        <li><a href="#"> Pool</a></li>
        <li><a href="client/VehicleAssign/showCurrentLocation?vehicleId=<?php echo $vehicleId ?>"> Vehicle Location</a></li>
    </div>
</div>

<div class="row clearfix">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="body">
                <?php
                
                if ($companyInfo[0]->map_api_key == "") {
                    ?>
                    <div class="alert alert-danger">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <strong>There is no Google Map API Key</strong>
                    </div>
                    <?php
                }
                ?>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="float-right">
                            <button class="btn btn-primary waves-effect" onclick="mapLoad()"><i class="material-icons">refresh</i></button>
                        </div>
                    </div>
                </div>
                <div id="map" style="height: 100vh"></div>

            </div>
        </div>
    </div>
</div>

    
@endsection
@push('scripts')
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?php echo $companyInfo[0]->map_api_key ?>&libraries=places"></script>

<script type="text/javascript">

let vehicleId = "{{ $vehicleId }}";
    let apiKey = "{{ $companyInfo[0]->map_api_key ?? '' }}";

    $(function () {
        mapLoad();
    });

    function mapLoad() {

        if (!apiKey || !vehicleId) {
            return false;
        }

        showLoader();

        $.ajax({
            type: "POST",
            url: "{{ route('client.pool.single-vehicle-location-data') }}",
            data: {
                vehicleId: vehicleId,
                _token: "{{ csrf_token() }}"
            },
            success: function (result) {

                hideLoader();

                if (result.success === 2) {
                    sweetAlert('No VTS APP Key found');
                    return;
                }

                if (result.success === 3) {
                    sweetAlert('No Vehicle found');
                    return;
                }

                showMap(result);
            },
            error: function () {
                hideLoader();
                sweetAlert('Something went wrong');
            }
        });
    }

    function showMap(dataObj) {
        var locations = dataObj.location;
//        [
//            [23.839315, 90.256481],
//            [23.839315, 90.256481],
//            [23.839315, 90.256481],
//            [23.839315, 90.256481],
//            [23.839315, 90.256481]
//        ];

        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 17,
            center: new google.maps.LatLng(locations[0][0], locations[0][1]),  // 23.839315, 90.256481
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });
        var infoWindowContent = dataObj.infoContent;
//        [
//            ['<div class="info_content">' +
//                        '<h3>London Eye</h3>' +
//                        '<p>The London Eye is a giant Ferris wheel situated on the banks of the River Thames. The entire structure is 135 metres (443 ft) tall and the wheel has a diameter of 120 metres (394 ft).</p>' + '</div>'],
//            ['<div class="info_content">' +
//                        '<h3>London Eye</h3>' +
//                        '<p>The London Eye is a giant Ferris wheel situated on the banks of the River Thames. The entire structure is 135 metres (443 ft) tall and the wheel has a diameter of 120 metres (394 ft).</p>' + '</div>'],
//            ['<div class="info_content">' +
//                        '<h3>London Eye</h3>' +
//                        '<p>The London Eye is a giant Ferris wheel situated on the banks of the River Thames. The entire structure is 135 metres (443 ft) tall and the wheel has a diameter of 120 metres (394 ft).</p>' + '</div>'],
//            ['<div class="info_content">' +
//                        '<h3>London Eye</h3>' +
//                        '<p>The London Eye is a giant Ferris wheel situated on the banks of the River Thames. The entire structure is 135 metres (443 ft) tall and the wheel has a diameter of 120 metres (394 ft).</p>' + '</div>'],
//            ['<div class="info_content">' +
//                        '<h3>Palace of Westminster</h3>' +
//                        '<p>The Palace of Westminster is the meeting place of the House of Commons and the House of Lords, the two houses of the Parliament of the United Kingdom. Commonly known as the Houses of Parliament after its tenants.</p>' +
//                        '</div>']
//        ];

        var infowindow = new google.maps.InfoWindow(), marker, i;

        var marker, i;

        for (i = 0; i < locations.length; i++) {
            marker = new google.maps.Marker({
                position: new google.maps.LatLng(locations[i][0], locations[i][1]),
                map: map
            });

            google.maps.event.addListener(marker, 'mouseover', (function (marker, i) {
                return function () {
                    infowindow.setContent(infoWindowContent[i][0]);
                    infowindow.open(map, marker);
                }
            })(marker, i));
        }
    }
</script>
@endpush
