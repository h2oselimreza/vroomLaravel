@extends('client.layouts.app')
@section('content')
<div class="block-header">
    <h2>ADD PRODUCT</h2><br>
    <div class="breadcrumb breadcrumb-bg-blue-grey">
        <li><a href="/client/Home"> Home</a></li>
        <li><a href="#"> Master Data</a></li>
        <li><a href="/client/MasterData/inventory"> Inventory</a></li>
        <li><a href="/client/MasterData/product"> Product</a></li>
        <li><a href="/client/MasterData/addProductShow"> Add Product</a></li>
    </div>
</div>
<div class="row clearfix">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="body">
                @include('client.master-data.inventory.tab')
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

                <div class="panel panel-default m-t-20"> 
                    <div class="panel-body">
                        <div class="row" >
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <select class="form-control" id="categoryCode" name="categoryCode">
                                            <option value="">--- Select ---</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->category_code }}"
                                                    @selected(($product->category ?? null) == $category->category_code)>
                                                    {{ get_parent_category_str([
                                                        'parentCategoryCodeStr' => $category->parent_category_str,
                                                        'categoryArr' => $categories
                                                    ]) }}
                                                </option>
                                            @endforeach

                                        </select>
                                        <div class="help-info">Category</div>
                                    </div>
                                    <label id="catReq-error" class="error hidden">Category is required</label>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">

                                <div class="form-group form-float" >
                                    <div class="form-line">
                                        <input type="text" class="form-control" id="productName" maxlength="200" value="{{ $product->product_name ?? null}}">
                                        <label class="form-label"> Product Name </label>
                                    </div>
                                    <label id="productNameReq-error" class="error hidden">Product Name is required</label>
                                </div>
                            </div>
                        </div>

                        <div class="row" >
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group form-float" >
                                    <div class="form-line">
                                        <textarea id="productDetails" class="form-control" rows="6">{{ $product->product_details ?? null}}</textarea>
                                        <label class="form-label"> Product Details </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary bg-blue btn-sm waves-effect" onclick="{{ $product ? 'submitEditProduct()' : 'submitAddProduct()' }}">Add Product</button>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    function submitAddProduct() {

        var errorMsg = "";

        var fieldsArr = new Array(
            "categoryCode|catReq-error",
            "productName|productNameReq-error",
            "productDetails"
        );

        var inputFiledJsonData = getInputData(fieldsArr);

        if (!inputFiledJsonData) {

            errorMsg += getReuiredFiledErrorMsg();

            showErrorMsg(errorMsg);

            return false;

        } else {

            hideErrorDiv();
        }

        showLoader();

        $.ajax({
            type: 'POST',
            data: inputFiledJsonData,
            url: '{{ route("client.master-data.inventory-product.store") }}',
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            success: function (result) {

                hideLoader();
                console.log(result);
                if (result == 1) {
                    swal({
                        icon: 'success',
                        title: 'Success',
                        text: 'Successfully saved',
                        confirmButtonText: 'OK'

                    }, function () {

                        window.location.href =
                            "{{ route('client.master-data.inventory-product.index') }}";

                    });

                } else if (result == 2) {
                     swal({
                        title: "Duplicate Product",
                        type: "warning",
                        closeOnConfirm: false,
                        confirmButtonText: "Ok",
                        confirmButtonColor: "#A5DC86"

                    }, function () {

                        window.location.href =
                            "{{ route('client.master-data.inventory-product.index') }}";

                    });

                } else {

                    Swal.fire({
                        icon: 'error',
                        title: 'Failed',
                        text: 'Something went wrong!',
                        confirmButtonText: 'OK'
                    });
                }
            },

            error: function () {

                hideLoader();

                Swal.fire({
                    icon: 'error',
                    title: 'Server Error',
                    text: 'Please try again later!',
                    confirmButtonText: 'OK'
                });
            }
        });
    }

    function submitEditProduct() {
        var errorMsg = "";

        var fieldsArr = new Array(
            "categoryCode|catReq-error",
            "productName|productNameReq-error",
            "productCode",
            "productDetails"
        );

        var inputFiledJsonData = getInputData(fieldsArr);

        if (!inputFiledJsonData) {

            errorMsg += getReuiredFiledErrorMsg();

            showErrorMsg(errorMsg);

            return false;

        } else {

            hideErrorDiv();
        }

        var productCode = '{{ $product->product_code}}';

        var url = "{{ route('client.master-data.inventory-product.update', $product->product_code) }}";
        showLoader();

        $.ajax({

            type: 'put',
            data: inputFiledJsonData,
            url : url,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },

            success: function (result) {

                hideLoader();
                console.log(result);

                if (result == '1') {

                    swal({
                        title: "Success",
                        text: "Successfully saved",
                        type: "success",
                        confirmButtonText: "OK",
                        confirmButtonColor: "#A5DC86"
                    }, function () {

                        window.location.href =
                            "{{ route('client.master-data.inventory-product.index') }}";
                    });

                } else if (result == '2') {

                    swal({
                        title: "Duplicate Product",
                        text: "You have already inserted this product...!",
                        type: "warning",
                        confirmButtonText: "OK",
                        confirmButtonColor: "#F8BB86"
                    });

                } else {

                    swal({
                        title: "Failed",
                        text: "Something went wrong!",
                        type: "error",
                        confirmButtonText: "OK",
                        confirmButtonColor: "#DD6B55"
                    }, function () {

                        window.location.href =
                            "{{ route('client.master-data.inventory-product.index') }}";
                    });
                }
            },

            error: function () {

                hideLoader();

                swal({
                    title: "Server Error",
                    text: "Please try again later!",
                    type: "error",
                    confirmButtonText: "OK",
                    confirmButtonColor: "#DD6B55"
                });
            }
        });
    }
</script>
@endpush