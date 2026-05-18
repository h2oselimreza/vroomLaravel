@extends('client.layouts.app')
@section('content')
<div class="block-header">
    <h2>INVENTORY REPORT</h2><br>
    <div class="breadcrumb breadcrumb-bg-blue-grey">
        <li><a href="/client/Home"> Home</a></li>
        <li><a href="#"> Report</a></li>
        <li><a href="/client/Inventory/stock"> Inventory</a></li>
    </div>
</div>
<div class="row clearfix">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="body">
                @include('client.report.inventory.tab')
                <br>
                <div class="table-custom-responsive">
                    <table class="table table-bordered table-hover custom-table dataTable">
                        <thead>
                            <tr class="bg-info">
                                <th>SL</th>
                                <th>Registration No</th>
                                <th>Vehicle Type</th>
                                <th>Brand</th>
                                <th>Brand Model</th>
                                <th>Group </th>
                                <th>Class</th>

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
                                <th></th>

                                <th></th>
                            </tr>
                        </tfoot>
                        <tbody>
                        @php
                            $count = 1;
                        @endphp

                        @foreach ($vehicles as $vehicle)

                            <tr>

                                <td>{{ $count }}</td>

                                <td class="td-left">
                                    <a
                                        target="_blank"
                                        href="{{ url('client/Home/vehicleDashboard') }}?vehicleId={{ $vehicle->vehicle_id }}"
                                    >
                                        {{ $vehicle->registration_no }}
                                    </a>
                                </td>

                                {{-- <td class="td-left">{{ $vehicle->registration_no }}</td> --}}

                                <td class="td-left">
                                    {{ $vehicle->vehicle_type_name }}
                                </td>

                                <td class="td-left">
                                    {{ $vehicle->brand_name }}
                                </td>

                                <td class="td-left">
                                    {{ $vehicle->brand_model_name }}
                                </td>

                                <td class="td-left">
                                    {{ $vehicle->vehicle_group_name }}
                                </td>

                                <td>
                                    {{ $vehicle->vehicle_class_name }}
                                </td>

                                <td>
                                    <button
                                        type="button"
                                        class="btn btn-primary btn-xs"
                                        onclick="showLastProduct('{{ $vehicle->vehicle_id }}')"
                                    >
                                        Show
                                    </button>
                                </td>

                            </tr>

                            @php
                                $count++;
                            @endphp

                        @endforeach
                    </tbody>
                    </table>



                    <button type="button" class="btn btn-default btn-sm waves-effect hidden" id="showVarinatModalBtn" data-toggle="modal" data-target="#variantModal"></button>
                    <div class="modal fade" id="variantModal" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="largeModalLabel">Product Variant</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="table-custom-responsive">

                                        <table class="table table-bordered table-hover custom-table" id="product-datatable" style="width: 100%">
                                            <thead>
                                                <tr class="bg-info">
                                                    <th width="5%">SL</th>
                                                    <th width="20%">Category</th>
                                                    <th width="15%">Product</th>
                                                    <th width="15%">Variant</th>
                                                    <th width="10%">Quantity</th>
                                                    <th width="15%">Unit Name</th>
                                                    <th width="20%">Last Date</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
                <span class="text-danger"><small>*** Click to show the last date of taken products</small></span>

            </div>
        </div>
    </div>
</div>    
@endsection
@push('scripts')
<script>
    function showLastProduct(vehicle) {
        $('#showVarinatModalBtn').click();
        $('#product-datatable').DataTable({
            "bDestroy": true,
            "ajax": '/client/report/get-vehicle-last-product?vehicle=' + vehicle,
            "deferRender": true,
            responsive: true,
            initComplete: function () {
                this.api().columns().every(function () {
                    var column = this;
                    var select = $('<select><option value=""></option></select>')
                            .appendTo($(column.footer()).empty())
                            .on('change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                        $(this).val()
                                        );

                                column
                                        .search(val ? '^' + val + '$' : '', true, false)
                                        .draw();
                            });

                    column.data().unique().sort().each(function (d, j) {
                        select.append('<option value="' + d + '">' + d + '</option>')
                    });
                });
            }
        });
    }
</script>
@endpush