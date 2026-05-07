@extends('client.layouts.app')
@section('content')
    <div class="block-header">
    <h2>EXPENSE</h2><br>
    <div class="breadcrumb breadcrumb-bg-blue-grey">
        <li><a href="/client/Home"> Home</a></li>
        <li><a href="#"> Master Data</a></li>
        <li><a href="/client/MasterData/expense"> Expense</a></li>
    </div>
</div>

<div class="row clearfix">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="body">
                @include('client.master-data.expense.tab')
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    function costRoute(flag) {
        var routeFunction;
        if (flag === 'expenseCategory') {
            routeFunction = 'expenseCategoryShow';
        } else if (flag === 'expenseHead') {
            routeFunction = 'expenseHeadShow';
        }
        window.location.href = "/client/MasterData/" + routeFunction;
    }
</script>
@endpush