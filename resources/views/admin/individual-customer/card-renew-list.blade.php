@extends('layouts.app')
@section('content')

<div class="header dashboard_from">
    <h1 class="page-title">Card Renew</h1>
    <ul class="breadcrumb">
        <li><a href="#">Home</a> / </li>
        <li><a href="#">  Individual Customer</a> / </li>
        <li><a href="#">Card Renew</a></li>
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
            <div class="panel panel-default"> 
                <div class="table-responsive">

                    <table class="table table-bordered table-hover custom-table" id="datatable">
                        <thead>
                            <tr class="bg-primary">
                                <th>SL</th>
                                <th>Account Name</th>
                                <th>Group</th>
                                <th>Package</th>
                                <th>Membership Card</th>
                                <th>Validity <br> Month</th>
                                <th>Activation <br> Date Time</th>
                                <th>Validation <br>Date Time</th>
                                <th>Card Status</th>
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
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @php $count = 1; @endphp

                            @foreach ($companies as $company)

                                @php
                                    $statusName = "<span class='text-muted'>Not expired</span>";

                                    $validDtTm = \Carbon\Carbon::parse($company->valid_dt_tm);
                                    $todayDtTm = \Carbon\Carbon::now();

                                    if ($todayDtTm->gt($validDtTm)) {
                                        $statusName = "<b class='text-danger'>Expired</b>";
                                    }
                                @endphp

                                <tr>
                                    <td class="td-center">{{ $count }}</td>

                                    <td>
                                        {{ $company->company_code }} <br>
                                        {{ $company->title }} <br>
                                        {{ $company->company_mobile }}
                                    </td>

                                    <td>{{ $company->user_group_name }}</td>

                                    <td>{{ $company->package_name }}</td>

                                    <td class="td-center">
                                        {{ $company->membership_card }} <br>
                                        {!! get_card_type_by_card($company->membership_card, 'card_number') !!}
                                    </td>

                                    <td class="td-center" id="prevalidityMonth{{ $count }}">
                                        {{ $company->validity_month }}
                                    </td>

                                    <td class="td-center" id="preActivationDtTm{{ $count }}">
                                        {{ get_date_time_format($company->activation_dt_tm) }}
                                    </td>

                                    <td class="td-center" id="preValidationDtTm{{ $count }}">
                                        {{ get_date_time_format($company->valid_dt_tm) }}
                                    </td>

                                    <td class="td-center" id="preStatus{{ $count }}">
                                        {!! $statusName !!}
                                    </td>

                                    <td class="td-center">
                                        <div class="btn-group">
                                            <button type="button"
                                                    class="btn btn-secondary btn-sm dropdown-toggle"
                                                    data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                Action
                                            </button>

                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a href="javascript:void(0);"
                                                    class="dropdown-item"
                                                    onclick="renewFormShow('{{ $company->company_code }}', '{{ $count }}')">
                                                        Renew
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
@endsection
@push('scripts')
<script>
    $(document).ready(function () {
        $('#datatable').DataTable({
        pageLength: 10,
        ordering: true,
        searching: true
        });
    });

</script>
@endpush
