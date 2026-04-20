@extends('client.layouts.app')

@section('content')

<div class="block-header">
    <h2>HOME SERVICE LIST</h2><br>
    <div class="breadcrumb breadcrumb-bg-blue-grey">
        <li><a href="client/Home"> Home</a></li>
        <li><a href="#"> Vehicle Maintenance</a></li>
        <li><a href="client/HomeService/homeServiceList">Home Service List</a></li>

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
                <div class="table-custom-responsive">
                    <table class="table table-bordered table-hover custom-table dataTable">
                        <thead>
                            <tr class="bg-info">
                                <th>SL</th>
                                <th>Home Service No</th>
                                <th>Name</th>
                                <th>Mobile No</th>
                                <th width="10%">Date</th>
                                <th>Time</th>
                                <th>Confirmed Date</th>
                                <th>Confirmed Time</th>
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
                                <th></th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @if ($appointmentLists)
                                @foreach ($appointmentLists as $index => $appointmentList)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                            
                                        <td>{{ $appointmentList->appointment_no }}</td>
                            
                                        <td class="td-left">
                                            {{ $appointmentList->name }}
                                        </td>
                            
                                        <td>{{ $appointmentList->mobile }}</td>
                            
                                        <td class="td-left">
                                            {{ get_date_format1($appointmentList->service_date) }}
                                        </td>
                            
                                        <td>
                                            {{ get_time_format($appointmentList->service_time) }}
                                        </td>
                            
                                        <td>
                                            {{ get_date_format1($appointmentList->final_date) }}
                                        </td>
                            
                                        <td>
                                            {{ get_time_format($appointmentList->appointment_time) }}
                                        </td>
                            
                                        <td>
                                            {!! get_appointment_status($appointmentList->status, 'client') !!}
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
                            
                                                    {{-- Show --}}
                                                    <li>
                                                        <a href=" {{ route('client.vehicle-maintenance.show-home-service', $appointmentList->appointment_no) }}">
                                                            Show
                                                        </a>
                                                    </li>
                            
                                                    {{-- Remove (only if pending) --}}
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
                                @endforeach
                            @else
                            <tr>
                                <td colspan="10">Data not found</td>
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
                url: "{{ url('client/vehicle-maintenance/delete-home-service') }}/" + appointmentNo,
                type: "DELETE",
                data: {
                    _token: "{{ csrf_token() }}"
                }
            })
            .done(function (data) {
                hideLoader();
                console.log(data);
                if (data == 1) {
                    swal({
                        title: "Remove Successfully",
                        text: "This Home Service is removed now",
                        type: "success",
                        closeOnConfirm: false,
                        confirmButtonText: "Ok",
                        confirmButtonColor: "#A5DC86"
                    }, function () {
                        window.location.href = "{{ url('client/vehicle-maintenance/home-service') }}";
                    });
    
                } else if (data == 2) {
                    window.location.href = "{{ url('client/vehicle-maintenance/home-service') }}";
                }
            })
            .error(function () {
                swal("Oops", "We couldn't connect to the server!", "error");
            });
    
        });
    }
</script>
@endpush