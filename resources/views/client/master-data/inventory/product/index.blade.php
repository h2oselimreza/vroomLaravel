@extends('client.layouts.app')
@section('content')
<div class="block-header">
    <h2>PRODUCT</h2><br>
    <div class="breadcrumb breadcrumb-bg-blue-grey">
        <li><a href="/client/Home"> Home</a></li>
        <li><a href="#"> Master Data</a></li>
        <li><a href="/client/MasterData/inventory"> Inventory</a></li>
        <li><a href="/client/MasterData/product"> Product</a></li>
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
                                <a href="{{ route('client.master-data.inventory-product.create') }}" class="btn btn-primary bg-blue btn-sm waves-effect">Add Product</a>
                            </div>
                        </div>

                        <hr>
                        <div class="row" >
                            <div class="col-md-2 col-sm-6 col-xs-12 custom-div-bottom" >
                                <form action="{{ route('client.master-data.inventory-product.index') }}" id="statusForm" method="get">
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

                        <div class="row" >
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="table-custom-responsive">
                                    <table class="table table-bordered table-hover custom-table dataTable">
                                        <thead>
                                            <tr class="bg-info">
                                                <th>SL</th>
                                                <th>Product Name</th>
                                                <th>Category</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>SL</th>
                                                <th>Product Name</th>
                                                <th>Category</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            @php
                                                $serial = 1;
                                            @endphp

                                            @foreach ($products as $product)

                                            <tr>

                                                <td class="td-center">{{ $serial }}</td>

                                                <td class="td-left">{{ $product->product_name }}</td>

                                                <td>
                                                    {{ get_parent_category_str([
                                                        'parentCategoryCodeStr' => $product->parent_category_str,
                                                        'categoryArr' => $categories
                                                    ]) }}
                                                </td>

                                                <td class="td-center">

                                                    <div class="btn-group">

                                                        <button type="button"
                                                                class="btn btn-default btn-xs dropdown-toggle"
                                                                data-toggle="dropdown">

                                                            Action <span class="caret"></span>

                                                        </button>

                                                        <ul class="dropdown-menu pull-right">

                                                            <li>
                                                                <a href="{{ route('client.master-data.inventory-product.edit', $product->product_code) }}">
                                                                    Update
                                                                </a>
                                                            </li>

                                                            <li>
                                                                <a data-toggle="modal"
                                                                data-target="#myModal{{ $product->id }}">
                                                                    Show Details
                                                                </a>
                                                            </li>

                                                            <li role="separator" class="divider"></li>

                                                            @if ($isActiveFlag == 1)

                                                                <li>
                                                                    <a href="#"
                                                                    onclick="removeProduct('{{ $product->id }}')">
                                                                        Inactive
                                                                    </a>
                                                                </li>

                                                            @elseif ($isActiveFlag == 2)

                                                                <li>
                                                                    <a href="#"
                                                                    onclick="activeProduct('{{ $product->id }}')">
                                                                        Active
                                                                    </a>
                                                                </li>

                                                            @endif

                                                        </ul>

                                                    </div>

                                                </td>

                                            </tr>

                                            {{-- Modal --}}
                                            <div class="modal fade"
                                                id="myModal{{ $product->id }}"
                                                tabindex="-1"
                                                role="dialog">

                                                <div class="modal-dialog" role="document">

                                                    <div class="modal-content">

                                                        <div class="modal-header">

                                                            <button type="button"
                                                                    class="close"
                                                                    data-dismiss="modal">

                                                                <span>&times;</span>

                                                            </button>

                                                            <h4 class="modal-title">Product Details</h4>

                                                        </div>

                                                        <div class="modal-body">

                                                            {!! $product->product_details !!}

                                                        </div>

                                                        <div class="modal-footer"></div>

                                                    </div>

                                                </div>

                                            </div>

                                            @php
                                                $serial++;
                                            @endphp

                                            @endforeach

                                            </tbody>
                                    </table>
                                </div>
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

    function changeStatus() {
        showLoader();
        $('#statusForm').submit();
    }
    function removeProduct(productId) {
        swal({
            title: "Are you sure?",
            text: "If you inactive this product, all product variants will be inactive...!",
            type: "warning",
            showCancelButton: true,
            closeOnConfirm: false,
            confirmButtonText: "Yes, inactive it...!",
            confirmButtonColor: "#ec6c62"
        }, function () {
            showLoader();
            $.ajax({
                url: "/client/MasterData/removeProduct?productId=" + productId,
                type: "DELETE"
            })
                    .done(function (data) {
                        hideLoader();
                        swal({
                            title: "Inactive Successfully",
                            text: "This product is inactive now",
                            type: "success",
                            closeOnConfirm: false,
                            confirmButtonText: "Ok",
                            confirmButtonColor: "#A5DC86"
                        }, function () {
                            window.location.href = "/client/MasterData/product";
                        });

                    })
                    .error(function (data) {
                        swal("Oops", "We couldn't connect to the server!", "error");
                    });
        });
    }

    function activeProduct(prodcutId) {
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
                url: "/client/MasterData/activeProduct?productId=" + prodcutId,
                type: "DELETE"
            })
                    .done(function (data) {
                        hideLoader();

                        swal({
                            title: "Active Successfully",
                            text: "This product is active now",
                            type: "success",
                            closeOnConfirm: false,
                            confirmButtonText: "Ok",
                            confirmButtonColor: "#A5DC86"
                        }, function () {
                            window.location.href = "/client/MasterData/product/2";
                        });

                    })
                    .error(function (data) {
                        swal("Oops", "We couldn't connect to the server!", "error");
                    });
        });
    }
</script>
@endpush