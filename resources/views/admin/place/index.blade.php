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
        <a href="{{ route('admin.place.place-info.create') }}">Add Place</a>
    </div>
    <div class="row mb-3">
        <div class="col-md-2 col-sm-6 col-xs-12">

            <form action="{{ route('admin.place.place-info.index') }}"
                id="statusForm"
                method="GET">

                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select class="form-control"
                            id="statusDropDown"
                            name="statusDropDown"
                            onchange="this.form.submit()">

                        @if ($isActiveFlag == 1)
                            <option value="1">Active</option>
                            <option value="2">Inactive</option>
                        @else
                            <option value="2">Inactive</option>
                            <option value="1">Active</option>
                        @endif

                    </select>
                </div>

            </form>

        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel panel-default"> 
                <div class="table-responsive">
                   <table class="table table-bordered table-hover custom-table" id="datatable">
                        <thead>
                            <tr class="bg-primary">
                                <th>SL</th>
                                <th>Place Title/Name</th>
                                <th>Place Code</th>
                                <th>Address</th>
                                <th>Mobile</th>
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
                            </tr>
                        </tfoot>

                        <tbody>
                            @php $count = 1; @endphp

                            @foreach ($places as $place)
                                <tr>
                                    <td class="td-center">{{ $count }}</td>

                                    <td>{{ $place->title ?? '-' }}</td>

                                    <td class="td-center">{{ $place->workshop_code ?? '-' }}</td>

                                    <td>{{ $place->address ?? '-' }}</td>

                                    <td>{{ $place->place_mobile ?? '-' }}</td>

                                    <td class="td-center">
                                        @if (($place->is_active ?? 0) == 1)
                                            <span class="text-success">Active</span>
                                        @else
                                            <span class="text-danger">Inactive</span>
                                        @endif
                                    </td>

                                    <td class="td-center">
                                        <div class="btn-group">
                                            <button type="button"
                                                    class="btn btn-default btn-xs dropdown-toggle"
                                                    data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                Action
                                            </button>

                                            <ul class="dropdown-menu dropdown-menu-end">

                                                <li>
                                                    <a class="dropdown-item"
                                                    href="{{ url('admin/Places/editPlaceShow/' . $place->place_code) }}">
                                                        Edit
                                                    </a>
                                                </li>

                                                <li><hr class="dropdown-divider"></li>

                                                @if ($isActiveFlag == 1)
                                                    <li>
                                                        <a class="dropdown-item"
                                                        href="javascript:void(0);"
                                                        onclick="changeActiveStatus('{{ $place->place_code }}', '2')">
                                                            Inactive
                                                        </a>
                                                    </li>
                                                @elseif ($isActiveFlag == 2)
                                                    <li>
                                                        <a class="dropdown-item"
                                                        href="javascript:void(0);"
                                                        onclick="changeActiveStatus('{{ $place->place_code }}', '1')">
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
        //showLoader();
        $('#statusForm').submit();
    }

    function changeActiveStatus(placeCode, statusFlag) {

        let confirmText = "";
        let statusDropDown = $('#statusDropDown').val();

        if (statusFlag === '2') {
            confirmText = "Yes, Inactive it...!";
        } else if (statusFlag === '1') {
            confirmText = "Yes, Active it...!";
        }

        Swal.fire({
            title: "Are you sure?",
            text: "",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: confirmText,
            confirmButtonColor: "#ec6c62"
        }).then((result) => {

            if (result.isConfirmed) {

                showLoader();

                $.ajax({
                    url: "{{ route('admin.place.changePlaceStatus') }}",
                    type: "POST",
                    data: {
                        placeCode: placeCode,
                        statusFlag: statusFlag,
                        _method: "GET", // keep original GET logic
                        _token: "{{ csrf_token() }}"
                    },

                    success: function (data) {
                        hideLoader();

                        if (data == 1 || data == '1') {

                            Swal.fire({
                                title: "Successfully Done",
                                icon: "success",
                                confirmButtonText: "Ok",
                                confirmButtonColor: "#A5DC86"
                            }).then(() => {
                                if (statusDropDown) {
                                    window.location.href = "/admin/place/place-info?" + statusDropDown;
                                } else {
                                    window.location.href = "/place/place-info";
                                }

                            });

                        } else {
                            window.location.href = "/place/place-info";
                        }
                    },

                    error: function () {
                        hideLoader();

                        Swal.fire({
                            title: "Oops",
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
