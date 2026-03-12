@extends('layouts.app')
@section('content')

<div class="header dashboard_from">
    <h1 class="page-title">Employee Bulk SMS</h1>
    <ul class="breadcrumb">
        <li><a href="#"> Home</a></li>
        <li><a href="#">/ SMS</a></li>
        <li><a href="#">/ Employee Bulk SMS</a></li>
    </ul>
</div>
<div class="main-content">
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel panel-default member_bulk_sms">
                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <table class="headingTable">
                            <tr class="bg-info ">
                                <th>Designation :</th>
                                <td>
                                    {{ $designation ? get_common_table_name_str($designation, 'emp_designation') : 'N/A' }}
                                </td>
                            </tr>
                        </table>
                        <br>
                        <form action="{{ route('admin.send-employee-custom-bulk-msg') }}" method="POST" id="customMsgForm">
                            @csrf
                            <div class="form-group" id="writeMsgId">
                                <label class="form-label" for="">Write Message*</label><br>
                                <textarea class="form-control" id="textAreaMsg" name="customMsg"
                                          style="width:100%;resize: none;height:150px;"></textarea>
                            </div>
                            <div style="display:none">
                                <input type="text" name="employeeIdStr" value="{{ $employeeIdStr }}">
                                <input type="text" id="designation" name="designation" value="{{ $designation }}">
                                <input type="text" name="checkBulkEmployeeFlag" value="{{ $checkBulkEmployeeFlag }}">
                            </div>
                        </form>
                        <br>
                        <button class="btn btn-primary save_button" onclick="sendMessage()">Send Message</button>
                    </div>

                    <div class="col-sm-6 col-md-6">
                        @if($checkBulkEmployeeFlag == 2)
                            <div class="text-center">
                                <h4><b>Total Employee:</b> {{ count($employees) }}</h4>
                                (Except Selected Duplicate Employee)
                                <br> <br>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover custom-table" id="dataTable">
                                    <thead>
                                        <tr class="bg-primary">
                                            <th>SL</th>
                                            <th>Employee ID</th>
                                            <th>Employee Name</th>
                                            <th>Designation</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>SL</th>
                                            <th>Employee ID</th>
                                            <th>Employee Name</th>
                                            <th>Designation</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        @php $count = 1; @endphp
                                        @foreach($employees as $employee)
                                            <tr>
                                                <td class='td-center'>{{ $count }}</td>
                                                <td>{{ $employee['employee_id'] }}</td>
                                                <td>{{ $employee['employee_name'] }}</td>
                                                <td>{{ $employee['designation_name'] }}</td>
                                            </tr>
                                            @php $count++; @endphp
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script language="JavaScript">
    function sendMessage() {
        if ($.trim($('#textAreaMsg').val()) === "") {
            alert('Message body is required...!');
            return false;
        }
        if (confirm('Are you sure ?')) {
            $("#customMsgForm").submit();
        }
    }
</script>
@endpush
