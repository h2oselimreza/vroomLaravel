@extends('client.layouts.app')

@section('content')

<div class="block-header">
    <h2>WORKSHOP APPOINTMENT LIST</h2><br>
    <div class="breadcrumb breadcrumb-bg-blue-grey">
        <li><a href="client/Home"> Home</a></li>
        <li><a href="#"> Vehicle Maintenance</a></li>
        <li><a href="client/Appointment/appointmentList">Workshop Appointment List</a></li>

    </div>
</div>

<div class="row clearfix">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="body">
                <div class="table-custom-responsive">
                    <table class="table table-bordered table-hover custom-table dataTable">
                        <thead>
                            <tr class="bg-info">
                                <th>SL</th>
                                <th>Appointment No</th>
                                <th>Workshop</th>
                                <th>Appointment Date</th>
                                <th>Appointment Time</th>
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
                            @php $count = 1; @endphp
                        
                            @foreach ($appointmentLists as $appointmentList)
                                <tr>
                                    <td>{{ $count }}</td>
                        
                                    <td>{{ $appointmentList->appointment_no }}</td>
                        
                                    <td class="td-left">
                                        {{ $appointmentList->workshop_name }}
                                    </td>
                        
                                    <td>
                                        {{ get_date_format1($appointmentList->final_date) }}
                                    </td>
                        
                                    <td>
                                        {{ get_time_format($appointmentList->appointment_time) }}
                                    </td>
                        
                                    <td>
                                        {{ get_appointment_status($appointmentList->status, 'client') }}
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
                                                    <a href="{{ url('client/vehicle-maintenance/show-workshop-details/'. $appointmentList->appointment_no) }}">
                                                        Show
                                                    </a>
                                                </li>
                        
                                                @if ($appointmentList->status == config('constants.APPOINTMENT_PENDING'))
                                                    <li>
                                                        <a href="#"
                                                           onclick="removeAppointment('{{ $appointmentList->appointment_no }}')">
                                                            Remove
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
    function removeAppointment(appointmentNo) {

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
                url: "/client/vehicle-maintenance/delete-appointment-service/" + appointmentNo,
                type: "DELETE",
                data: {
                    _token: "{{ csrf_token() }}"
                }
            })
                    .done(function (data) {
                        hideLoader();
                        if (data == 1) {
                            swal({
                                title: "Remove Successfully",
                                text: "This Appointment is removed now",
                                type: "success",
                                closeOnConfirm: false,
                                confirmButtonText: "Ok",
                                confirmButtonColor: "#A5DC86"
                            }, function () {
                                window.location.href = "/client/vehicle-maintenance/workshop-service-list";
                            });
                        } else if (data === '2') {
                            window.location.href = "/client/vehicle-maintenance/workshop-service-list";
                        }
                    })
                    .error(function (data) {
                        swal("Oops", "We couldn't connect to the server!", "error");
                    });
        });
    }
</script>
@endpush