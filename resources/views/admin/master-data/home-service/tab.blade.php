<script>
    function areaRoute(flag) {
        var routeFunction;
        if (flag === 'master-data/home-service-category') {
            routeFunction = 'master-data/home-service-category';
        } else if (flag === 'master-data/home-service-list') {
            routeFunction = 'master-data/home-service-list';
        } else if (flag === 'master-data/home-service-variant'){
            routeFunction = 'master-data/home-service-variant';
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
    
    <div class="col col-md-4">
        <div class="btn-group d-block" role="group">
            <button type="button"
                onclick="areaRoute('master-data/home-service-category')"
                class="btn btn-{{ (request()->is('admin/master-data/home-service-category')) ? 'success' : 'default' }} custom-button-group">
                <i class="fa fa-list"></i> <b>Service Category</b>
            </button>
        </div>
    </div>

    <div class="col col-md-4">
        <div class="btn-group d-block" role="group">
            <button type="button"
                onclick="areaRoute('master-data/home-service-list')"
                class="btn btn-{{ (request()->is('admin/master-data/home-service-list')) ? 'success' : 'default' }} custom-button-group">
                <i class="fa fa-list"></i> <b>Service List</b>
            </button>
        </div>
    </div>

    <div class="col col-md-4">
        <div class="btn-group d-block" role="group">
            <button type="button"
                onclick="areaRoute('master-data/home-service-variant')"
                class="btn btn-{{ (request()->is('admin/master-data/home-service-variant') || request()->is('admin/master-data/home-service-variant/create')) ? 'success' : 'default' }} custom-button-group">
                <i class="fa fa-list"></i> <b>Service Variant</b>
            </button>
        </div>
    </div>
</div>
