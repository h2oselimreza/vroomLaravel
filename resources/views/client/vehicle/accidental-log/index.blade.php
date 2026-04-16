@extends('client.layouts.app')

@section('content')

<div class="block-header">
    <h2>ACCIDENTAL LIST</h2><br>
    <div class="breadcrumb breadcrumb-bg-blue-grey">
        <li><a href="#"> Home</a></li>
        <li><a href="#"> Vehicle</a></li>
        <li><a href="{{ route('client.accidental-log.index') }}"> Accidental Log</a></li>
    </div>
</div>

<div class="row clearfix">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="body">
                 @if(session('success'))
                    <div class="alert alert-success">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                        <strong>{{ session('success') }}</strong>
                    </div>
                @endif
                {{-- Validation Errors --}}
                @if(session('error'))
                    <div class="alert alert-danger">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                        <strong>{{ session('error') }}</strong>
                    </div>
                @endif
                <a href="{{ route('client.accidental-log.create') }}" class="btn bg-blue waves-effect">Add Vehicle</a>
                <!--<button class="btn bg-blue waves-effect">Add Employee</button>-->
                <br><br>
                <div class="table-custom-responsive">
                    <table class="table table-bordered table-hover custom-table dataTable">
                        <thead>
                            <tr class="bg-info">
                                <th>SL</th>
                                <th>Vehicle No</th>
                                <th>Driver Name</th>
                                <th>Accident Place</th>
                                <th>Accident Datetime</th>
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
                        <tbody>
                            @if(isset($data) && count($data) > 0)
                                @php $count = 1; @endphp

                                @foreach($data as $log)
                                    <tr>
                                        <td>{{ $count }}</td>
                                        <td class="td-left">
                                            <a target="_blank" href="{{ url('client/Home/vehicleDashboard?vehicleId=' . $log->vehicle_id) }}">
                                                {{ $log->registration_no }}
                                            </a>
                                        </td>
                                        <td>{{ $log->employee_name }}</td>
                                        <td>{{ $log->place }}</td>
                                        <td>{{ \Carbon\Carbon::parse($log->accident_date_time)->format('M d, Y h:i A') }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Action <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu pull-right">
                                                    <li>
                                                        <a href="{{ route('client.accidental-log.edit', $log->id) }}">Update</a>
                                                    </li>
                                                    <li role="separator" class="divider"></li>
                                                    <li>
                                                        <a href="javascript:void(0);" onclick="deleteLog('{{ $log->id }}')">Delete</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    @php $count++; @endphp
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="text-center">No data found</td>
                                </tr>
                            @endif
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
    function deleteLog(id) {
        swal({
            title: "Are you sure?",
            text: "This action cannot be undone",
            type: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!",
            confirmButtonColor: "#ec6c62",
            closeOnConfirm: false
        }, function () {

            showLoader();

            $.ajax({
                url: `/client/vehicle/accidental-log/${id}`,
                type: "POST",
                data: {
                    _method: "DELETE",
                    _token: "{{ csrf_token() }}"
                }
            })
            .done(function (response) {

                hideLoader();

                if (response.status === true) {
                    swal({
                        title: "Deleted Successfully",
                        text: "This log has been deleted",
                        type: "success",
                        confirmButtonColor: "#A5DC86"
                    }, function () {
                        window.location.href = "/client/vehicle/accidental-log";
                    });
                } else {
                    swal("Error", "Something went wrong", "error");
                }
            })
            .fail(function () {
                hideLoader();
                swal("Oops", "We couldn't connect to the server!", "error");
            });
        });
    }
</script>
@endpush
