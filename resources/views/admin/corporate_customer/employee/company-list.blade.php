@extends('layouts.app')
@section('content')

<div class="header dashboard_from">
    <h1 class="page-title">Company List</h1>
    <ul class="breadcrumb">
        <li><a href="#">Home</a></li>
        <li><a href="#">/ Corporate Customer</a></li>
        <li><a href="#">/ Company List</a></li>
    </ul>
</div>

<div class="main-content">

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Error Message -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Add Button -->
    <div class="add-button mb-3">
        <a href="{{ route('admin.company-modules.create') }}" class="btn btn-primary">Add Company</a>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover custom-table dataTable" id="datatable">
                        <thead>
                            <tr class="bg-primary text-white">
                                <th>SL</th>
                                <th>Company Title/Name</th>
                                <th>Company Code</th>
                                <th>Address</th>
                                <th>Mobile</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($companies))
                                @foreach ($companies as $company)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $company->title }}</td>
                                        <td>{{ $company->company_code }}</td>
                                        <td>{{ $company->address }}</td>
                                        <td>{{ $company->mobile }}</td>
                                        <td class="text-center">
                                            @if($company->status == 1)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.company-employee.index') }}"
                                                style="color: blue; text-decoration: underline;">
                                                Employee List
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="8" class="text-center">No Data Found</td>
                                </tr>
                            @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>SL</th>
                                <th>Company Title/Name</th>
                                <th>Company Code</th>
                                <th>Address</th>
                                <th>Mobile</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hidden Delete Form -->
<form id="delete-form" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@push('scripts')
<script>
$(document).ready(function () {
    // Initialize DataTable
    $('#datatable').DataTable({
        pageLength: 10,
        ordering: true,
        searching: true,
    });
});

// Delete record confirmation
function deleteRecord(url) {
    if(confirm('Are you sure you want to delete this record?')) {
        let form = document.getElementById('delete-form');
        form.action = url;
        form.submit();
    }
}
</script>
@endpush