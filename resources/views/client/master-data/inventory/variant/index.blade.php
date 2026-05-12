@extends('client.layouts.app')
@section('content')
<div class="block-header">
    <h2>PRODUCT VARIANT</h2><br>
    <div class="breadcrumb breadcrumb-bg-blue-grey">
        <li><a href="/client/Home"> Home</a></li>
        <li><a href="#"> Master Data</a></li>
        <li><a href="/client/MasterData/inventory"> Inventory</a></li>
        <li><a href="/client/MasterData/variant"> Variant</a></li>
    </div>
</div>
<div class="row clearfix">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="body">
                @include('client.master-data.inventory.tab')

                <br>
                <!-- Success Message -->
                @if(session('success'))
                    <div class="alert alert-success">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                        <strong>{{ session('success') }}</strong>
                    </div>
                @endif

                <!-- Error Message -->
                @if(session('error'))
                    <div class="alert alert-danger" role="alert">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                        <strong>Error!</strong> {{ session('error') }}
                    </div>
                @endif
                <div id="errorDiv" class="alert alert-danger hidden">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                </div>

                <div class="panel panel-default"> 
                    <div class="panel-body">
                        <div class="row" >
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <a href="{{ route('client.master-data.inventory-product-variant.create') }}" class="btn btn-primary bg-blue btn-sm waves-effect">Manage Variants</a>
                            </div>
                        </div>
                        <hr>
                        <div class="row" >
                            <div class="col-md-2 col-sm-6 col-xs-12 custom-div-bottom" >
                                <form action="{{ route('client.master-data.inventory-product-variant.index') }}" id="statusForm" method="get">
                                    <div class="form-group form-float" >
                                        <div class="form-line">
                                            <select class="form-control" id="statusDropDown" name="statusDropDown" onchange="changeStatus()">
                                                <?php
                                                if ($isActiveFlag == 1) {
                                                    echo "<option value='1'>Active</option>";
                                                    echo "<option value='2'>Inactive</option>";
                                                } else {
                                                    echo "<option value='2'>Inactive</option>";
                                                    echo "<option value='1'>Active</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="table-custom-responsive">
                                    <table class="table table-bordered table-hover custom-table dataTable">
                                        <thead>
                                            <tr class="bg-info">
                                                <th>SL</th>
                                                <th>Category</th>
                                                <th>Product</th>
                                                <th>Variant</th>
                                                <th>Status</th>
                                                <?php
                                                if ($isActiveFlag == 2) {
                                                    echo "<th>Action</th>";
                                                }
                                                ?>

                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                        <tbody>

                                            @php
                                                $serial = 1;
                                                $productCode = '';
                                                $bgColor = '#fcf8e3';
                                            @endphp

                                            @foreach ($variants as $variant)

                                                @php
                                                    if ($productCode == '') {
                                                        $bgColor = '#fcf8e3';
                                                    } elseif ($productCode != $variant->product) {
                                                        if ($bgColor == '#e5eaec') {
                                                            $bgColor = '#fcf8e3';
                                                        } else {
                                                            $bgColor = '#e5eaec';
                                                        }
                                                    }

                                                    $productCode = $variant->product;
                                                @endphp

                                                <tr style="background-color: {{ $bgColor }}">

                                                    <td class="td-center">{{ $serial }}</td>

                                                    <td>{{ $variant->category_name }}</td>

                                                    <td class="td-left">{{ $variant->product_name }}</td>

                                                    <td class="td-left">

                                                        @if ($variant->variant_name == 'Default')
                                                            <i class="text-muted">{{ $variant->variant_name }}</i>
                                                        @else
                                                            {{ $variant->variant_name }}
                                                        @endif

                                                    </td>

                                                    <td>

                                                        @if ($variant->is_active == 1)
                                                            <span class="text-success">Active</span>
                                                        @else
                                                            <span class="text-danger">Inactive</span>
                                                        @endif

                                                    </td>

                                                    @if ($isActiveFlag == 2)

                                                        <td class="td-center">

                                                            <div class="btn-group">

                                                                <button type="button"
                                                                        class="btn btn-default btn-xs dropdown-toggle"
                                                                        data-toggle="dropdown">

                                                                    Action <span class="caret"></span>

                                                                </button>

                                                                <ul class="dropdown-menu pull-right">

                                                                    <li>
                                                                        <a href="#"
                                                                        onclick="activeVariant('{{ $variant->id }}')">
                                                                            Active
                                                                        </a>
                                                                    </li>

                                                                </ul>

                                                            </div>

                                                        </td>

                                                    @endif

                                                </tr>

                                                @php
                                                    $serial++;
                                                @endphp

                                            @endforeach

                                            </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <small>** Variant Default means there is no Product Variant added yet</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    function changeStatus() {
        showLoader();
        $('#statusForm').submit();
    }

    function activeVariant(variantId) {

        swal({
            title: "Are you sure?",
            text: "",
            type: "warning",
            showCancelButton: true,
            closeOnConfirm: false,
            confirmButtonText: "Yes, active it...!",
            confirmButtonColor: "#ec6c62"
        }, function () {

            showLoader();

            $.ajax({
                url: "{{ route('client.master-data.product-variant-active') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    variantId: variantId
                }
            })

            .done(function (data) {

                hideLoader();

                swal({
                    title: "Active Successfully",
                    text: "This product variant is active now",
                    type: "success",
                    closeOnConfirm: false,
                    confirmButtonText: "Ok",
                    confirmButtonColor: "#A5DC86"
                }, function () {

                    window.location.href = "{{ route('client.master-data.inventory-product-variant.index') }}";
                });

            })

            .fail(function (xhr) {

                hideLoader();

                swal("Oops", "We couldn't connect to the server!", "error");
            });
        });
    }
</script>
@endpush