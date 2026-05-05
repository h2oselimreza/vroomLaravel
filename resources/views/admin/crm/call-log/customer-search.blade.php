@extends('layouts.app')
@section('content')
<style>
    .panel {
        padding: 0px; 
    }

</style>

<div class="header dashboard_from">
    <h1 class="page-title">Call Log</h1>
    <ul class="breadcrumb">
        <li><a href="#">Home</a> / </li>
        <li><a href="#">  CRM</a> / </li>
        <li><a href="#">Customer Search</a></li>
    </ul>
</div>
<div class="main-content">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    <div class="row">
        <div class="col-sm-12 col-md-12">

            <form action="/admin/crm/customer-log-search" method="post">
                @csrf
                <div class="accordion" id="customerAccordion">

                <!-- BASIC SEARCH -->
                <div class="accordion-item">

                    <h2 class="accordion-header" id="basicSearchHeading">
                        <button class="accordion-button"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#basicSearchCollapse"
                                aria-expanded="true"
                                aria-controls="basicSearchCollapse">
                            <i class="fa fa-user me-2"></i> Basic Search
                        </button>
                    </h2>

                    <div id="basicSearchCollapse"
                        class="accordion-collapse collapse show"
                        aria-labelledby="basicSearchHeading"
                        data-bs-parent="#customerAccordion">

                        <div class="accordion-body">

                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label">Customer Name</label>
                                    <input type="text" class="form-control" id="customerName" name="customerName">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Customer Mobile</label>
                                    <input type="text" class="form-control" id="customerMobile" name="customerMobile">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Customer ID</label>
                                    <input type="text" class="form-control" id="customerId" name="customerId">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- ADVANCE SEARCH -->
                <div class="accordion-item mt-3">

                    <h2 class="accordion-header" id="advanceSearchHeading">
                        <button class="accordion-button collapsed"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#advanceSearchCollapse"
                                aria-expanded="false"
                                aria-controls="advanceSearchCollapse">
                            <i class="fa fa-bars me-2"></i> Advance Search
                        </button>
                    </h2>

                    <div id="advanceSearchCollapse"
                        class="accordion-collapse collapse"
                        aria-labelledby="advanceSearchHeading"
                        data-bs-parent="#customerAccordion">

                        <div class="accordion-body">

                            <div id="vehicleServiceDiv">
                                <div id="serviceTableDiv">
                                    <input type="hidden" id="serviceVarCodeStr" value="">
                                    <input type="hidden" id="takenServiceVarCount" name="takenServiceVarCount" value="">
                                </div>
                            </div>

                            <button type="button"
                                    class="btn btn-info btn-sm save_button open-service-modal">
                                <i class="fa fa-plus"></i> Select Service
                            </button>

                            <br><br>

                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">From Date</label>
                                    <input type="text" class="form-control dateInput" id="fromDate">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">To Date</label>
                                    <input type="text" class="form-control dateInput" id="toDate">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

                <input class="btn btn-block btn-success save_button mt-3 ml-1" type="submit" value="Search">
            </form>
            <br>
            <div class="panel panel-default" style="padding:10px"> 

                <div class="row" >
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="text-center">
                            <h4><b>Search Result</b></h4>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover custom-table" id="datatable">
                                <thead>
                                    <tr class="bg-primary">
                                         <th>SL</th>
                                        <th>Name</th>
                                        <th>Individual Code</th>
                                        <th>Address</th>
                                        <th>Mobile Number</th>

                                        <?php
                                        if ($data['searchFlag'] == '2') {
                                            ?>
                                            <th>Taken Service</th>
                                            <th>Service Date</th>
                                            <?php
                                        }
                                        ?>

                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <?php
                                        if ($data['searchFlag'] == '2') {
                                            ?>
                                            <th> </th>
                                            <th></th>
                                            <?php
                                        }
                                        ?>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                @php $count = 1; @endphp

                                @foreach ($data['companies'] as $company)
                                    <tr>
                                        <td class="td-center">{{ $count }}</td>

                                        <td>{{ $company->title }}</td>
                                        <td>{{ $company->company_code }}</td>
                                        <td>{{ $company->address }}</td>
                                        <td>{{ $company->company_mobile }}</td>

                                        @if ($data['searchFlag'] == '2')
                                            <td>{{ $company->service_variant_name }}</td>
                                            <td>{{ $company->final_date }}</td>
                                        @endif

                                        <td class="td-center">
                                            @if ($company->is_active == 1)
                                                <span class="text-success">Active</span>
                                            @else
                                                <span class="text-danger">Inactive</span>
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            <div class="dropdown">
                                                <button class="btn btn-secondary btn-sm dropdown-toggle"
                                                        type="button"
                                                        data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                    Action
                                                </button>

                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item"
                                                        href="{{ url('admin/crm/make-call') }}?customerId={{ $company->company_code }}&callerType=customer"
                                                        target="_blank">
                                                            Make Call
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>

                                    @php $count++; @endphp
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

<button type="button"
        class="btn btn-default btn-sm d-none"
        data-bs-toggle="modal"
        data-bs-target="#serviceModal"
        id="serviceModalShowBtn">
</button>
<div class="modal fade" id="serviceModal" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header">
                <h5 class="modal-title">Service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- BODY -->
            <div class="modal-body">

                <div class="accordion" id="serviceAccordion">

                    @php
                        $flag = 1;
                        $serviceCount = 1;
                    @endphp

                    @foreach ($data['distinctServices'] as $distinctService)

                        <div class="accordion-item">

                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapse{{ $distinctService['service'] }}">
                                    <i class="fa fa-tags me-2"></i>
                                    {{ $distinctService['service_name'] }}
                                </button>
                            </h2>

                            <div id="collapse{{ $distinctService['service'] }}"
                                 class="accordion-collapse collapse show"
                                 data-bs-parent="#serviceAccordion">

                                <div class="accordion-body">

                                    <table class="table table-striped">

                                        @php $serviceVarSerial = 1; @endphp

                                        @foreach ($data['serviceVariants'] as $serviceVariant)

                                            @if ($serviceVariant['service'] == $distinctService['service'])

                                                @php $flag = 0; @endphp

                                                <tr>
                                                    <td>{{ $serviceVarSerial }}</td>

                                                    <td style="width:70%">
                                                        {{ $serviceVariant['service_variant_name'] }}
                                                    </td>

                                                    <td style="width:20%">
                                                        BDT {{ $serviceVariant['unit_price'] }}
                                                    </td>

                                                    <td style="width:5%">
                                                        {{ $serviceVariant['unit_name'] }}
                                                    </td>

                                                    <td>
                                                        <input type="checkbox"
                                                               name="serviceVarCheckBox{{ $serviceCount }}"
                                                               id="serviceVarCheckBox{{ $serviceCount }}">
                                                    </td>

                                                    <input type="hidden"
                                                           id="serviceVariantCode{{ $serviceCount }}"
                                                           value="{{ $serviceVariant['variant_code'] }}">

                                                    <input type="hidden"
                                                           id="serviceVariantName{{ $serviceCount }}"
                                                           value="{{ $serviceVariant['service_variant_name'] }}">

                                                    <input type="hidden"
                                                           id="serviceVariantUnitName{{ $serviceCount }}"
                                                           value="{{ $serviceVariant['unit_name'] }}">

                                                    <input type="hidden"
                                                           id="serviceVariantUnitPrice{{ $serviceCount }}"
                                                           value="{{ $serviceVariant['unit_price'] }}">

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

                    @endforeach

                    <input type="hidden" id="serviceVariantCount" value="{{ $serviceCount }}">

                </div>

                @if ($flag)
                    <span class="text-danger">
                        No service has been added to Home Service
                    </span>
                @endif

            </div>

            <!-- FOOTER -->
            <div class="modal-footer">
                <button type="button"
                        class="btn btn-primary save_button"
                        onclick="setAddService()">
                    SELECT
                </button>

                <button type="button"
                        class="btn btn-secondary save_button"
                        data-bs-dismiss="modal">
                    CLOSE
                </button>
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
            columnDefs: [
                {
                    defaultContent: "-",
                    targets: "_all"
                }
            ],
            // initComplete: function () {
            //     this.api().columns().every(function () {
            //         var column = this;
            //         var select = $('<select><option value=""></option></select>')
            //                 .appendTo($(column.footer()).empty())
            //                 .on('change', function () {
            //                     var val = $.fn.dataTable.util.escapeRegex(
            //                             $(this).val()
            //                             );

            //                     column
            //                             .search(val ? '^' + val + '$' : '', true, false)
            //                             .draw();
            //                 });

            //         column.data().unique().sort().each(function (d, j) {
            //             select.append('<option value="' + d + '">' + d + '</option>')
            //         });
            //     });
            // },
        });
    });
    $(document).on('click', '.open-service-modal', function () {
        setShowServiceModal();
    });

    $(function () {

        var customerName = @json($data['customerName']);
        $("#customerName").autocomplete({
            source: customerName
        });

        var customerMobile = @json($data['customerMobile']);
        $("#customerMobile").autocomplete({
            source: customerMobile
        });

        var customerId = @json($data['customerId']);
        $("#customerId").autocomplete({
            source: customerId
        });

    });

    function setShowServiceModal() {
        var serviceVariantCount = $("#serviceVariantCount").val();
        var serviceVarCodeStr = $("#serviceVarCodeStr").val();

        // ✅ OPEN MODAL PROPERLY (Bootstrap 5)
        let modal = new bootstrap.Modal(document.getElementById('serviceModal'));
        modal.show();

        // reset checkboxes
        for (var i = 1; i < serviceVariantCount; i++) {
            $('#serviceVarCheckBox' + i).prop('checked', false);
        }

        if (serviceVarCodeStr) {
            var serviceVarCodeArr = serviceVarCodeStr.split(',');

            for (var i = 1; i < serviceVariantCount; i++) {
                if (jQuery.inArray($("#serviceVariantCode" + i).val(), serviceVarCodeArr) !== -1) {
                    $('#serviceVarCheckBox' + i).prop('checked', true);
                }
            }
        }
    }

    function setAddService() {

        var serviceVariantCount = $("#serviceVariantCount").val();

        var serviceTableStr = "";
        var serviceVarCodeArr = [];
        var takenServiceVarCount = 1;

        var i = 1;

        for (var x = 1; x < serviceVariantCount; x++) {

            if ($("#serviceVarCheckBox" + x).is(':checked')) {

                var serviceVariantCode = $("#serviceVariantCode" + x).val();
                var serviceVariantName = $("#serviceVariantName" + x).val();

                serviceTableStr += `
                    <tr id="serviceTakenTd${i}">
                        <td class="td-left">${serviceVariantName}</td>
                        <td class="td-center">
                            <i class="fa fa-remove pointer text-danger"
                            onclick="removeService(${i})"></i>

                            <input type="hidden"
                                id="takenServiceVarCode${i}"
                                name="takenServiceVarCode${i}"
                                value="${serviceVariantCode}">
                        </td>
                    </tr>
                `;

                serviceVarCodeArr.push(serviceVariantCode);
                takenServiceVarCount++;
                i++;
            }
        }

        // REMOVE OLD TABLE
        $('#serviceTableDiv').remove();

        if (serviceTableStr !== "") {

            var serviceTableDiv = `
                <div id="serviceTableDiv">
                    <table class="table table-bordered custom-table">

                        <tr class="bg-info">
                            <th colspan="2"><b>Service</b></th>
                        </tr>

                        <tr>
                            <th>Service Name</th>
                            <th>Action</th>
                        </tr>

                        ${serviceTableStr}

                    </table>

                    <input type="hidden"
                        id="serviceVarCodeStr"
                        value="${serviceVarCodeArr.join()}">

                    <input type="hidden"
                        id="takenServiceVarCount"
                        name="takenServiceVarCount"
                        value="${takenServiceVarCount}">
                </div>
            `;

            $("#vehicleServiceDiv").html(serviceTableDiv);
        }

        // Bootstrap 5 safe modal close
        let modalEl = document.getElementById('serviceModal');
        let modal = bootstrap.Modal.getInstance(modalEl);
        if (modal) modal.hide();
    }

    function removeService(serviceSerial) {
        $('#serviceTakenTd' + serviceSerial).remove();
        var serviceVarCodeArr = new Array();
        var takenServiceVarCode;
        var takenServiceVarCount = $('#takenServiceVarCount').val();
        for (var i = 1; i < takenServiceVarCount; i++) {
            takenServiceVarCode = $('#takenServiceVarCode' + i).val();

            if (typeof takenServiceVarCode !== 'undefined') {
                serviceVarCodeArr.push(takenServiceVarCode);
            }
        }

        if (serviceVarCodeArr.length !== 0) {
            $('#serviceVarCodeStr').val(serviceVarCodeArr.join());
        } else {
            $('#serviceTableDiv').remove();
        }

    }
</script>
@endpush
