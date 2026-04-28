@extends('layouts.app')
@section('content')

<div class="header dashboard_from">
    <h1 class="page-title">Individual Account List</h1>
    <ul class="breadcrumb">
        <li><a href="#">Home</a> / </li>
        <li><a href="#">  Individual Customer</a> / </li>
        <li><a href="#">Individual Account List</a></li>
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
    <div class="add-button">
        <a href="{{ route('admin.individual.individual-account.create') }}">New Individual Account</a>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel panel-default"> 
                <div class="table-responsive">
                    <table class="table table-bordered table-hover custom-table" id="datatable">
                        <thead>
                            <tr class="bg-primary">
                                <th>SL</th>
                                <th>Account Name</th>
                                <th>Individual Code</th>
                                <th>Address</th>
                                <th>Mobile</th>
                                <th>Group</th>
                                <th>Package</th>
                                <th>Membership Card</th> <!-- new -->
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
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @php $count = 1; @endphp

                            @foreach ($companies as $company)
                                <tr>
                                    <td class="td-center">{{ $count }}</td>
                                    <td>{{ $company->title ?? '-' }}</td>
                                    <td>{{ $company->company_code ?? '-' }}</td>
                                    <td>{{ $company->address ?? '-' }}</td>
                                    <td>{{ $company->company_mobile ?? '-' }}</td>
                                    <td>{{ $company->user_group_name ?? '-' }}</td>
                                    <td>{{ $company->package_name ?? '-' }}</td>

                                    <td class="td-center">
                                        {{ ($company->membership_card ?? '-') }}
                                        {{ get_card_type_by_card(($company->membership_card ?? '-'), 'card_number') }}
                                    </td>

                                    <td class="td-center">
                                        @if (($company->is_active ?? $company['is_active']) == 1)
                                            <span class="text-success">Active</span>
                                        @else
                                            <span class="text-danger">Inactive</span>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        <div class="btn-group">
                                            <button type="button"
                                                    class="btn btn-default btn-xs dropdown-toggle"
                                                    data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                Action
                                            </button>

                                            <ul class="dropdown-menu dropdown-menu-end">
                                                @if ($isActiveFlag == 1)
                                                    <li>
                                                        <a href="javascript:void(0);"
                                                        class="dropdown-item"
                                                        onclick="companyStatusChange('{{ $company->company_code }}', '2')">
                                                            Inactive
                                                        </a>
                                                    </li>
                                                @elseif ($isActiveFlag == 2)
                                                    <li>
                                                        <a href="javascript:void(0);"
                                                        class="dropdown-item"
                                                        onclick="companyStatusChange('{{ $company->company_code }}', '1')">
                                                            Active
                                                        </a>
                                                    </li>
                                                @endif
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

    function changeStatus() {
        showLoader();
        $('#statusForm').submit();
    }

    function companyStatusChange(companyCode, statusFlag) {

        let confirmText = "";
        let btnText = "";
        let statusDropDown = $('#statusDropDown').val();

        if (statusFlag == '2') {
            btnText = "Yes, Inactivate it!";
        } else if (statusFlag == '1') {
            btnText = "Yes, Activate it!";
        }

        Swal.fire({
            title: "Are you sure?",
            text: confirmText,
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: btnText,
            confirmButtonColor: "#ec6c62"
        }).then((result) => {

            if (result.isConfirmed) {

                //showLoader();

                $.ajax({
                    url: "{{ route('admin.individual.individual-account.changeCompanyStatus') }}", // ✅ Laravel route
                    type: "POST",
                    data: {
                        companyCode: companyCode,
                        statusFlag: statusFlag,
                        _method: "GET", // simulate GET
                        _token: "{{ csrf_token() }}"
                    },

                    success: function (data) {
                        //hideLoader();

                        if (data == 1 || data == '1') {

                            Swal.fire({
                                title: "Successfully Done",
                                icon: "success",
                                confirmButtonText: "Ok",
                                confirmButtonColor: "#A5DC86"
                            }).then(() => {

                                if (statusDropDown) {
                                    window.location.href = "/admin/individual/individual-account?status=" + statusDropDown;
                                } else {
                                    window.location.href = "/admin/individual/individual-account";
                                }

                            });

                        } else {
                            window.location.href = "/admin/Individual/individualAccount";
                        }
                    },

                    error: function () {
                        //hideLoader();

                        Swal.fire({
                            title: "Oops!",
                            text: "We couldn't connect to the server!",
                            icon: "error"
                        });
                    }
                });
            }
        });
    }
</script>
@endpush
