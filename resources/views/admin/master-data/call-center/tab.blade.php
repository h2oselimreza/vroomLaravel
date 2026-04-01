<script>
    function areaRoute(flag) {
        var routeFunction;
        if (flag === 'master-data/vehicle-type') {
            routeFunction = 'master-data/vehicle-type';
        } else if (flag === 'master-data/vehicle-class') {
            routeFunction = 'master-data/vehicle-class';
        } else if (flag === 'master-data/vehicle-brand') {
            routeFunction = 'master-data/vehicle-brand';
        } else if (flag === 'master-data/vehicle-color'){
            routeFunction = 'master-data/vehicle-color';
        } else if (flag === 'master-data/vehicle-condition'){
            routeFunction = 'master-data/vehicle-condition';
        } else if(flag === 'master-data/vehicle-brand-model'){
            routeFunction = 'master-data/vehicle-brand-model';
        } else if(flag == 'master-data/vehicle-group'){
            routeFunction = 'master-data/vehicle-group';
        }
        
        window.location.href = "/admin/" + routeFunction;
    }
</script>

<style>
    /* Custom 7-column layout for laptops and desktops */
    @media (min-width: 992px) {
        .row-cols-lg-7 > * {
            flex: 0 0 auto;
            width: 14.2857142857%;
        }
    }

    /* Ensure buttons fill the full width of their small columns */
    .custom-button-group {
        width: 100%;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        font-size: 13px; /* Smaller font helps fit 7 items on one line */
        padding: 10px 5px;
    }
    
    .vehicle .col {
        margin-bottom: 0px; /* Spacing for mobile view */
        margin-top: 0px;
        padding-left: 0px;
        padding-right: 0px;
    }
</style>
@php
$btnFlag = "";
@endphp
<div class="row text-center border-ccc vehicle" role="group" aria-label="Vehicle Filters">
    
    <div class="col col-md-6">
        <div class="btn-group d-block" role="group">
            <button type="button"
                onclick="areaRoute('master-data/vehicle-type')"
                class="btn btn-{{ (request()->is('admin/master-data/vehicle-type')) ? 'success' : 'default' }} custom-button-group">
                <i class="fa fa-bars"></i> <b>Call Reason</b>
            </button>
        </div>
    </div>

    <div class="col col-md-6">
        <div class="btn-group d-block" role="group">
            <button type="button"
                onclick="areaRoute('master-data/vehicle-class')"
                class="btn btn-{{ (request()->is('admin/master-data/vehicle-class')) ? 'success' : 'default' }} custom-button-group">
                <i class="fa fa-list"></i> <b>Customer Feedback</b>
            </button>
        </div>
    </div>
</div>
