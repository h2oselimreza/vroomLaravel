@extends('client.layouts.app')
@section('content')
<div class="block-header">
    <h2>CATEGORY</h2><br>
    <div class="breadcrumb breadcrumb-bg-blue-grey">
        <li><a href="/client/Home"> Home</a></li>
        <li><a href="#"> Master Data</a></li>
        <li><a href="/client/MasterData/inventory"> Inventory</a></li>
        <li><a href="/client/MasterData/category"> Category</a></li>
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
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <select class="form-control" id="parentCode" >
                                            <option value="1">--- Parent ---</option>
                                            <?php
                                            foreach ($activeCategories as $category) {
                                                echo "<option value='$category->category_code'>" . get_parent_category_str(array('parentCategoryCodeStr' => $category->parent_category_str, 'categoryArr' => $allCategories)) . "</option>";
                                            }
                                            ?>
                                        </select>
                                        <div class="help-info">Parent Category</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">

                                <div class="form-group form-float" >
                                    <div class="form-line">
                                        <input type="text" class="form-control" id="categoryName" maxlength="100" >
                                        <label class="form-label"> Category Name </label>
                                    </div>
                                    <label id="catNameReq-error" class="error hidden">Category Name is required</label>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary bg-blue btn-sm waves-effect" onclick="submitAddCategory()">Save Category</button>
                        <br><br>
                        <hr>
                        <div class="row" >
                            <div class="col-md-2 col-sm-6 col-xs-12 custom-div-bottom" >
                                <form action="{{ route('client.master-data.inventory-category.index') }}" id="statusForm" method="get">
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
                                    <table class="table table-bordered table-hover custom-table">
                                        <thead>
                                            <tr class="bg-info">
                                                <th>SL</th>
                                                <th>Parent Category</th>
                                                <th>Category Name</th>
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
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            @php
                                                $count = 1;
                                            @endphp

                                            @foreach ($categories as $category)

                                            @php
                                                $parentArr = explode(' / ', $category->parent_category_str);
                                                $parentCategory = "-- Parent --";

                                                array_pop($parentArr);

                                                if (!empty($parentArr)) {
                                                    $parentCategory = get_parent_category_str([
                                                        'parentCategoryCodeStr' => implode(' / ', $parentArr),
                                                        'categoryArr' => $allCategories
                                                    ]);
                                                }
                                            @endphp

                                            <tr>
                                                <td class="td-center">{{ $count }}</td>

                                                <td>{{ $parentCategory }}</td>

                                                <td>{{ $category->category_name }}</td>

                                                <td>
                                                    @if ($category->is_active == 1)
                                                        <span class="text-success">Active</span>
                                                    @else
                                                        <span class="text-danger">Inactive</span>
                                                    @endif
                                                </td>

                                                <td class="td-center">

                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                                            Action <span class="caret"></span>
                                                        </button>

                                                        <ul class="dropdown-menu pull-right">

                                                            <li>
                                                                <a data-toggle="modal" data-target="#myModal{{ $category->id }}">
                                                                    Update
                                                                </a>
                                                            </li>

                                                            <li role="separator" class="divider"></li>

                                                            @if ($isActiveFlag == 1)
                                                                <li>
                                                                    <a href="#" onclick="removeCategory('{{ $category->id }}')">
                                                                        Inactive
                                                                    </a>
                                                                </li>
                                                            @elseif ($isActiveFlag == 2)
                                                                <li>
                                                                    <a href="#" onclick="activeCategory('{{ $category->id }}')">
                                                                        Active
                                                                    </a>
                                                                </li>
                                                            @endif

                                                        </ul>
                                                    </div>

                                                </td>

                                            </tr>

                                            {{-- Modal --}}
                                            <div class="modal fade" id="myModal{{ $category->id }}" tabindex="-1" role="dialog">

                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">

                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal">
                                                                &times;
                                                            </button>
                                                            <h4 class="modal-title">Update Category Information</h4>
                                                        </div>

                                                        <div class="modal-body">

                                                            {{-- Parent Dropdown --}}
                                                            <div class="form-group">
                                                                <div class="form-line">

                                                                    <select class="form-control" id="parentCode{{ $count }}">

                                                                        <option value="{{ $category->parent_category }}">
                                                                            {{ $parentCategory }}
                                                                        </option>

                                                                        @if ($category->parent_category != 1)
                                                                            <option value="1">--- Parent ---</option>
                                                                        @endif

                                                                        @foreach ($activeCategories as $categoryModal)

                                                                            @if ($categoryModal->category_code != $category->parent_category)

                                                                                <option value="{{ $categoryModal->category_code }}">

                                                                                    {{ get_parent_category_str([
                                                                                        'parentCategoryCodeStr' => $categoryModal->parent_category_str,
                                                                                        'categoryArr' => $allCategories
                                                                                    ]) }}

                                                                                </option>

                                                                            @endif

                                                                        @endforeach

                                                                    </select>

                                                                    <div class="help-info">Parent Category</div>

                                                                </div>
                                                            </div>

                                                            {{-- Category Name --}}
                                                            <div class="form-group form-float">

                                                                <div class="form-line">
                                                                    <input type="text"
                                                                        class="form-control"
                                                                        id="categoryName{{ $count }}"
                                                                        value="{{ $category->category_name }}"
                                                                        maxlength="100">

                                                                    <label class="form-label">Category Name</label>
                                                                </div>

                                                                <label id="catNameReq-error{{ $count }}" class="error hidden">
                                                                    Category Name is required
                                                                </label>

                                                            </div>

                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="button"
                                                                    class="btn btn-primary bg-blue btn-sm waves-effect"
                                                                    onclick="updateInfo('{{ $count }}')">
                                                                Update Information
                                                            </button>
                                                        </div>

                                                        <input type="hidden" id="categoryId{{ $count }}" value="{{ $category->id }}">
                                                        <input type="hidden" id="categoryCode{{ $count }}" value="{{ $category->category_code }}">

                                                    </div>
                                                </div>
                                            </div>

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
        </div>
    </div>
</div>    
@endsection
@push('scripts')
<script>
    function submitAddCategory() {

        var errorMsg = "";

        // field id, error div id
        var fieldsArr = new Array(
            "parentCode",
            "categoryName|catNameReq-error"
        );

        var inputFiledJsonData = getInputData(fieldsArr);

        // Required field validation
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

            url: '{{ route("client.master-data.inventory-category.store") }}',

            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },

            success: function (result) {

                hideLoader();

                if (result == 1) {
                    swal({

                        title: "Successfully save",
                        type: "success",
                        closeOnConfirm: false,
                        confirmButtonText: "Ok",
                        confirmButtonColor: "#A5DC86"

                    }, function () {

                        window.location.href =
                            "{{ route('client.master-data.inventory-category.index') }}";

                    });
                }
                else if (result == 2) {
                    sweetAlert('You have already inserted this category...!');
                }
            },

            error: function () {
                hideLoader();
                Swal.fire({
                    icon: 'error',
                    title: 'Failed',
                    text: 'Something went wrong!',
                    confirmButtonText: 'Ok',
                    confirmButtonColor: '#d33'
                }).then(() => {

                    window.location.href = "{{ route('client.master-data.inventory-category.index') }}";
                });
            }
        });
    }

    function updateInfo(serial) {

        var fieldsArr = new Array(
            "parentCode" + serial,
            "categoryName" + serial + "|catNameReq-error" + serial,
            "categoryId" + serial,
            "categoryCode" + serial
        );

        var inputFiledJsonData = getInputData(fieldsArr);

        if (!inputFiledJsonData) {
            return false;
        }

        showLoader();

        $.ajax({
            type: 'PUT',
            url: "{{ route('client.master-data.inventory-category.update', ':id') }}"
            .replace(':id', 1),
            data: {
                ...inputFiledJsonData,
                serial: serial,
                _token: "{{ csrf_token() }}"
            },
            dataType: "json",

            success: function (result) {

                hideLoader();

                if (result == '1') {
                    swal({

                        title: "Successfully update",
                        type: "success",
                        closeOnConfirm: false,
                        confirmButtonText: "Ok",
                        confirmButtonColor: "#A5DC86"

                    }, function () {

                        window.location.href =
                            "{{ route('client.master-data.expense-category.index') }}";

                    });
                }
                else if (result == '2') {
                    sweetAlert('You have already inserted this category...!');
                }
            },

            error: function () {
                hideLoader();
                sweetAlert('Something went wrong!');
            }
        });
    }

    function changeStatus() {
        showLoader();
        $('#statusForm').submit();
    }

    function removeCategory(categoryId) {

        swal({
            title: "Are you sure?",
            text: "",
            type: "warning",
            showCancelButton: true,
            closeOnConfirm: false,
            confirmButtonText: "Yes, inactive it...!",
            confirmButtonColor: "#ec6c62"

        }, function () {

            showLoader();

            $.ajax({

                url: "{{ route('client.master-data.inventory-category.destroy', ':id') }}"
                        .replace(':id', categoryId),

                type: "DELETE",

                data: {
                    _token: "{{ csrf_token() }}"
                },

                dataType: "json"

            })

            .done(function (data) {

                hideLoader();

                if (data == '2' || data === 2) {

                    sweetAlert(
                        'Due to this category has child chategory, you can not remove this category...!'
                    );

                }
                else if (data == '3' || data === 3) {

                    sweetAlert(
                        'Due to this category has services, you can not remove this category...!'
                    );

                }
                else if (data == '1' || data === 1) {

                    swal({

                        title: "Remove Successfully",
                        text: "This category is inactive now",
                        type: "success",
                        closeOnConfirm: false,
                        confirmButtonText: "Ok",
                        confirmButtonColor: "#A5DC86"

                    }, function () {

                        window.location.href =
                            "{{ route('client.master-data.inventory-category.index') }}";

                    });

                }
                else if (data == '4' || data === 4) {

                    window.location.href =
                        "{{ route('client.master-data.inventory-category.index') }}";
                }

            })

            .error(function () {

                hideLoader();

                swal(
                    "Oops",
                    "We couldn't connect to the server!",
                    "error"
                );
            });
        });
    }

    function activeCategory(categoryId) {

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

                url: "{{ route('client.master-data.inventory-category.active') }}",

                type: "POST",

                data: {
                    categoryId: categoryId,
                    _token: "{{ csrf_token() }}"
                },

                dataType: "json"

            })

            .done(function (data) {

                hideLoader();

                swal({

                    title: "Active Successfully",
                    text: "This category is active now",
                    type: "success",
                    closeOnConfirm: false,
                    confirmButtonText: "Ok",
                    confirmButtonColor: "#A5DC86"

                }, function () {

                    window.location.href =
                        "{{ route('client.master-data.inventory-category.index') }}";

                });

            })

            .error(function () {

                hideLoader();

                swal(
                    "Oops", 
                    "We couldn't connect to the server!",
                    "error"
                );
            });
        });
    }

    function changeStatus() {
        showLoader();
        $('#statusForm').submit();
    }

</script>
@endpush