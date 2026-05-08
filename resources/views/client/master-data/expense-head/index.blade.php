@extends('client.layouts.app')
@section('content')
    <div class="block-header">
    <h2>EXPENSE HEAD</h2><br>
    <div class="breadcrumb breadcrumb-bg-blue-grey">
        <li><a href="/client/Home"> Home</a></li>
        <li><a href="#"> Master Data</a></li>
        <li><a href="/client/MasterData/inventory"> Inventory</a></li>
        <li><a href="/client/MasterData/expense"> Expense</a></li>
        <li><a href="/client/MasterData/expenseHeadShow"> Expense Head</a></li>
    </div>
</div>

<div class="row clearfix">
    <div class="col-sm-12 col-md-12 col-xs-12">
        <div class="card">
            <div class="body">
                @include('client.master-data.expense.tab')
                <br>

                <div id="errorDiv" class="alert alert-danger hidden">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                </div>
                <div class="panel panel-default"> 
                    <div class="panel-body">
                        <div class="row" >
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <select class="form-control" id="category" >
                                            <option value="">-- Select Category --</option>
                                            <?php
                                            foreach ($categories as $category) {
                                                echo "<option value='$category->category_code'>$category->category_name</option>";
                                            }
                                            ?>
                                        </select>
                                        <div class="help-info">Expense Category</div>
                                    </div>
                                    <label id="categoryReq-error" class="error hidden">Category is required</label>

                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group form-float" >
                                    <div class="form-line">
                                        <input type="text" class="form-control" id="costHeadName" maxlength="200">
                                        <label class="form-label"> Expense Head </label>
                                    </div>
                                    <label id="costHeadNameReq-error" class="error hidden"> Expense Head is required</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group form-float" >
                                    <div class="form-line">
                                        <input type="text" class="form-control" id="unitName" maxlength="50">
                                        <label class="form-label"> Unit Name </label>
                                    </div>
                                    <label id="unitNameReq-error" class="error hidden"> Unit Name is required</label>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group form-float" >
                                    <div class="form-line">
                                        <input type="number" class="form-control" id="unitPrice">
                                        <label class="form-label"> Unit Price (BDT) </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary bg-blue btn-sm waves-effect" onclick="submitAddCostHead()">Save Expense Head</button>
                        <br>
                        <hr>

                        <div class="row" >
                            <div class="col-md-2 col-sm-6 col-xs-12">
                                <form action="{{ route('client.master-data.expense-head.index') }}" id="statusForm" method="get">
                                    @csrf
                                    <div class="form-group">
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
                                    <table class="table table-bordered table-hover custom-table" id="dataTable">
                                        <thead>
                                            <tr class="bg-info">
                                                <th>SL</th>
                                                <th>Expense Category</th>
                                                <th>Expense Head</th>
                                                <th>Unit Name</th>
                                                <th>Unit Price (BDT)</th>
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
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            @php
                                                $count = 1;
                                            @endphp

                                            @foreach ($costHeads as $costHead)
                                                <tr>
                                                    <td class="td-center">{{ $count }}</td>
                                                    <td>{{ $costHead->category_name }}</td>
                                                    <td>{{ $costHead->cost_head }}</td>
                                                    <td>{{ $costHead->unit_name }}</td>
                                                    <td>{{ $costHead->unit_price }}</td>

                                                    <td>
                                                        @if ($costHead->is_active == 1)
                                                            <span class="text-success">Active</span>
                                                        @else
                                                            <span class="text-danger">Inactive</span>
                                                        @endif
                                                    </td>

                                                    <td class="td-center">
                                                        <div class="btn-group">
                                                            <button type="button"
                                                                    class="btn btn-default btn-xs dropdown-toggle"
                                                                    data-toggle="dropdown"
                                                                    aria-haspopup="true"
                                                                    aria-expanded="false">
                                                                Action <span class="caret"></span>
                                                            </button>

                                                            <ul class="dropdown-menu pull-right">
                                                                <li>
                                                                    <a data-toggle="modal"
                                                                    data-target="#myModal{{ $costHead->id }}">
                                                                        Edit
                                                                    </a>
                                                                </li>

                                                                <li role="separator" class="divider"></li>

                                                                @if ($isActiveFlag == 1)
                                                                    <li>
                                                                        <a href="#"
                                                                        onclick="changeHeadStatus('{{ $costHead->id }}','inactive')">
                                                                            Inactive
                                                                        </a>
                                                                    </li>
                                                                @elseif ($isActiveFlag == 2)
                                                                    <li>
                                                                        <a href="#"
                                                                        onclick="changeHeadStatus('{{ $costHead->id }}','active')">
                                                                            Active
                                                                        </a>
                                                                    </li>
                                                                @endif
                                                            </ul>
                                                        </div>
                                                    </td>

                                                    {{-- MODAL --}}
                                                    <div class="modal fade"
                                                        id="myModal{{ $costHead->id }}"
                                                        tabindex="-1"
                                                        role="dialog"
                                                        aria-labelledby="myModalLabel">

                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">

                                                                <div class="modal-header">
                                                                    <button type="button"
                                                                            class="close"
                                                                            data-dismiss="modal"
                                                                            aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>

                                                                    <h4 class="modal-title" id="myModalLabel">
                                                                        Edit Expense Head Information
                                                                    </h4>
                                                                </div>

                                                                <div class="modal-body">

                                                                    {{-- Category --}}
                                                                    <div class="form-group">
                                                                        <div class="form-line">
                                                                            <select class="form-control"
                                                                                    id="category{{ $count }}">
                                                                                <option value="{{ $costHead->cost_category }}">
                                                                                    {{ $costHead->category_name }}
                                                                                </option>

                                                                                @foreach ($categories as $categoryModal)
                                                                                    <option value="{{ $categoryModal->category_code }}">
                                                                                        {{ $categoryModal->category_name }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>

                                                                            <div class="help-info">Expense Category</div>
                                                                        </div>
                                                                    </div>

                                                                    {{-- Cost Head --}}
                                                                    <div class="form-group form-float">
                                                                        <div class="form-line">
                                                                            <input type="text"
                                                                                class="form-control"
                                                                                id="costHeadName{{ $count }}"
                                                                                maxlength="200"
                                                                                value="{{ $costHead->cost_head }}">

                                                                            <label class="form-label">Expense Head</label>
                                                                        </div>

                                                                        <label id="costHeadNameReq-error{{ $count }}"
                                                                            class="error hidden">
                                                                            Expense Head is required
                                                                        </label>
                                                                    </div>

                                                                    {{-- Unit Name --}}
                                                                    <div class="form-group form-float">
                                                                        <div class="form-line">
                                                                            <input type="text"
                                                                                class="form-control"
                                                                                id="unitName{{ $count }}"
                                                                                maxlength="50"
                                                                                value="{{ $costHead->unit_name }}">

                                                                            <label class="form-label">Unit Name (BDT)</label>
                                                                        </div>

                                                                        <label id="unitNameReq-error{{ $count }}"
                                                                            class="error hidden">
                                                                            Unit Name is required
                                                                        </label>
                                                                    </div>

                                                                    {{-- Unit Price --}}
                                                                    <div class="form-group form-float">
                                                                        <div class="form-line">
                                                                            <input type="number"
                                                                                class="form-control"
                                                                                id="unitPrice{{ $count }}"
                                                                                value="{{ $costHead->unit_price }}">

                                                                            <label class="form-label">Unit Price</label>
                                                                        </div>
                                                                    </div>

                                                                </div>

                                                                <div class="modal-footer">
                                                                    <button type="submit"
                                                                            class="btn btn-primary bg-blue btn-sm waves-effect"
                                                                            onclick="updateInfo('{{ $count }}')">
                                                                        Edit Information
                                                                    </button>
                                                                </div>

                                                                <input type="hidden"
                                                                    id="costHeadId{{ $count }}"
                                                                    value="{{ $costHead->id }}">

                                                                <input type="hidden"
                                                                    id="costHeadCode{{ $count }}"
                                                                    value="{{ $costHead->cost_head_code }}">

                                                            </div>
                                                        </div>
                                                    </div>

                                                </tr>

                                                @php
                                                    $count++;
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

    function submitAddCostHead() {

        var errorMsg = "";

        // field id, error div id
        var fieldsArr = new Array(
            "category|categoryReq-error",
            "costHeadName|costHeadNameReq-error",
            "unitName|unitNameReq-error"
        );

        var inputFiledJsonData = getInputData(fieldsArr);

        // required field check
        if (!inputFiledJsonData) {
            errorMsg += getReuiredFiledErrorMsg();
            showErrorMsg(errorMsg);
            return false;
        } else {
            hideErrorDiv();
        }

        var unitPrice = $('#unitPrice').val();

        if (unitPrice !== "") {
            if (!$.isNumeric(unitPrice)) {
                sweetAlert("Invalid unit price");
                return false;
            }
        }

        inputFiledJsonData['unitPrice'] = unitPrice;

        showLoader();

        $.ajax({
            type: 'POST',
            url: "{{ route('client.master-data.expense-head.store') }}",
            data: {
                ...inputFiledJsonData,
                _token: "{{ csrf_token() }}"
            },
            dataType: "json",

            success: function (result) {

                hideLoader();
                console.log(result);
                if (result.data == '1') {
                    swal({
                        title: "Successfully update",
                        type: "success",
                        closeOnConfirm: false,
                        confirmButtonText: "Ok",
                        confirmButtonColor: "#A5DC86"

                    }, function () {

                        window.location.href =
                            "{{ route('client.master-data.expense-head.index') }}";
                    });
                }
                else if (result.data == 2) {
                    sweetAlert('You have already inserted this cost head...!');
                }
            },

            error: function (xhr) {
                hideLoader();
                sweetAlert("Something went wrong!");
                if (xhr.status == 422) {
                    // Laravel validation errors (optional future-proofing)
                    let errors = xhr.responseJSON.errors;

                    $.each(errors, function (key, value) {
                        console.log(key + " : " + value[0]);
                    });

                } else {
                    sweetAlert("Something went wrong!");
                }
            }
        });
    }

    function updateInfo(serial) {

        var fieldsArr = new Array(
            "category" + serial + "",
            "costHeadName" + serial + "|costHeadNameReq-error" + serial + "",
            "unitName" + serial + "|unitNameReq-error" + serial + "",
            "unitPrice" + serial + "",
            "costHeadId" + serial + "",
            "costHeadCode" + serial
        );

        var inputFiledJsonData = getInputData(fieldsArr);

        if (!inputFiledJsonData) {
            return false; // required field check
        }

        showLoader();

        $.ajax({
            type: 'PUT',
            url: "{{ route('client.master-data.expense-head.update', ':id') }}"
            .replace(':id', 1),
            data: {
                ...inputFiledJsonData,
                serial: serial,
                _token: "{{ csrf_token() }}"
            },
            dataType: "json",

            success: function (result) {

                hideLoader();

                if (result === '1') {
                    swal({

                        title: "Successfully update",
                        type: "success",
                        closeOnConfirm: false,
                        confirmButtonText: "Ok",
                        confirmButtonColor: "#A5DC86"

                    }, function () {

                        window.location.href =
                            "{{ route('client.master-data.expense-head.index') }}";

                    });

                } else if (result == '2') {
                    sweetAlert('You have already inserted this expense head...!');

                } else if (result == '3') {
                    swal({

                        title: "Successfully save, but unit name has not updated because you have already made entry in expense....!",
                        type: "success",
                        closeOnConfirm: false,
                        confirmButtonText: "Ok",
                        confirmButtonColor: "#A5DC86"

                    }, function () {

                        window.location.href =
                            "{{ route('client.master-data.expense-head.index') }}";

                    });
                }
            },

            error: function (xhr) {
                hideLoader();

                if (xhr.status === 422) {
                    // Laravel validation errors (optional future-proofing)
                    let errors = xhr.responseJSON.errors;

                    $.each(errors, function (key, value) {
                        console.log(key + " : " + value[0]);
                    });

                } else {
                    sweetAlert("Something went wrong!");
                }
            }
        });
    }

    function changeHeadStatus(costHeadId, status) {
        swal({
            title: "Are you sure?",
            text: "You are about to change the status of this item.",
            type: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, change it!",
            closeOnConfirm: false,
            confirmButtonColor: "#ec6c62"
        }, function () {
            showLoader();

            $.ajax({
                url: "/client/vendor/expense-change-status",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}" 
                },
                data: {
                    costHeadId: costHeadId,
                    status: status
                }
            })
            .done(function (response) {
                hideLoader();
                if (response.success) {
                    swal({
                        title: "Removed Successfully",
                        text: response.message || "This expense head is inactive now",
                        type: "success",
                        closeOnConfirm: false,
                        confirmButtonText: "Ok",
                        confirmButtonColor: "#A5DC86"
                    }, function () {
                        window.location.href = "/client/vendor/expense-head";
                    });
                } else {
                    swal("Error", response.message || "Something went wrong!", "error");
                }
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                hideLoader();
                // Handle 419 (Session Expired) or 500 (Server Error)
                let errorMsg = (jqXHR.status === 419) ? "Session expired. Please refresh." : "We couldn't connect to the server!";
                swal("Oops", errorMsg, "error");
            });
        });
    }

    function activeCostHead(costHeadId) {
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
                url: "/client/MasterData/activeExpenseHead?costHeadId=" + costHeadId,
                type: "DELETE"
            })
                    .done(function (data) {
                        hideLoader();

                        swal({
                            title: "Active Successfully",
                            text: "This expense head is active now",
                            type: "success",
                            closeOnConfirm: false,
                            confirmButtonText: "Ok",
                            confirmButtonColor: "#A5DC86"
                        }, function () {
                            window.location.href = "/client/MasterData/expenseHeadShow/2";
                        });

                    })
                    .error(function (data) {
                        swal("Oops", "We couldn't connect to the server!", "error");
                    });
        });
    }



    function changeStatus() {
        showLoader();
        $('#statusForm').submit();
    }


</script>
@endpush