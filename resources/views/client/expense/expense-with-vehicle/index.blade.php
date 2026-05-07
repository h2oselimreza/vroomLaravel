@extends('client.layouts.app')

@section('content')


<div class="block-header">
    <h2>EXPENSE LIST</h2><br>
    <div class="breadcrumb breadcrumb-bg-blue-grey">
        <li><a href="/client/Home"> Home</a></li>
        <li><a href="#"> Expense</a></li>
        <li><a href="{{ route('client.expense.expense-with-vehicle.index') }}"> Expense List</a></li>
    </div>
</div>
<div class="row clearfix">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="body">
                <form  action="/client/expense/expense-with-vehicle" method="GET">
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group form-float" >
                                <div class="form-line">
                                    <input type="text" value="<?php echo $expenseNo ?>" class="form-control" name="expenseNo">
                                    <label class="form-label"> Expense No </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" value="<?php echo $title ?>" class="form-control" name="title">
                                    <label class="form-label"> Title</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="date" value="<?php echo $fromDate ?>" class="form-control" name="fromDate">
                                </div>
                                <div class="help-info">From Date </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="date" value="<?php echo $toDate ?>" class="form-control" name="toDate">
                                </div>
                                <div class="help-info">To Date </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success btn-primary">Search</button>
                    <a class="btn btn-danger" href="/client/Expense/expenseList">Clear</a>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="body">
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
                <a href="{{ route('client.expense.expense-with-vehicle.create') }}" class="btn bg-blue waves-effect">New Expense</a>
                <br><br>
                <div class="table-custom-responsive">
                    <table class="table table-bordered table-hover custom-table">
                        <thead>
                            <tr class="bg-info">
                                <th>SL</th>
                                <th>Expense ID</th>
                                <th>Expense Title</th>
                                <th>Expense Date</th>
                                <th>Total Amount</th>
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
                            </tr>
                        </tfoot>

                        @foreach ($expenseLists as $expenseList)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td class="td-left">
                                    {{ $expenseList->expense_no }}
                                </td>

                                <td class="td-left">
                                    {{ $expenseList->expense_title }}
                                </td>

                                <td>
                                    {{ \Carbon\Carbon::parse($expenseList->expense_date)->format('d-m-Y') }}
                                </td>

                                <td>
                                    {{ $expenseList->total_amount }}
                                </td>

                                <td>
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
                                                <a href="{{ route('client.expense.expense-with-vehicle.edit', $expenseList->expense_no) }}">
                                                    Edit
                                                </a>
                                            </li>

                                            <li>
                                                <a href="{{ route('client.expense.expense-with-vehicle.show', $expenseList->expense_no) }}">
                                                    Show
                                                </a>
                                            </li>

                                            <li>
                                                <a href="javascript:void(0)"
                                                onclick="removeExpense('{{ $expenseList->expense_no }}')">
                                                    Remove
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>

                    </table>
                    <div class="d-flex justify-content-between mt-3">
                        <div>
                            Showing {{ $expenseLists->firstItem() }} to {{ $expenseLists->lastItem() }}
                            of {{ $expenseLists->total() }} entries
                        </div>

                        <div>
                            {{ $expenseLists->links() }}
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
    function removeExpense(expenseNo) {
        swal({
            title: "Are you sure?",
            text: "",
            type: "warning",
            showCancelButton: true,
            closeOnConfirm: false,
            confirmButtonText: "Yes, remove it...!",
            confirmButtonColor: "#ec6c62"
        }, function () {
            showLoader();
            $.ajax({
                url: "/client/Expense/removeExpense?expenseNo=" + expenseNo,
                type: "DELETE"
            })
                    .done(function (data) {

                        hideLoader();
                        if (data === '1') {
                            swal({
                                title: "Remove Successfully",
                                text: "This Expense is removed now",
                                type: "success",
                                closeOnConfirm: false,
                                confirmButtonText: "Ok",
                                confirmButtonColor: "#A5DC86"
                            }, function () {
                                window.location.href = "/client/Expense/expenseList";
                            });
                        } else if (data === '2') {
                            window.location.href = "/client/Expense/expenseList";
                        }
                    })
                    .error(function (data) {
                        swal("Oops", "We couldn't connect to the server!", "error");
                    });
        });
    }
</script>
@endpush
