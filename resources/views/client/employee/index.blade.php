@extends('client.layouts.app')

@section('content')

    <div class="block-header">
      <h2>EMPLOYEE LIST</h2>
      <br>
      <div class="breadcrumb breadcrumb-bg-blue-grey">
        <li>
          <a href="#"> Home</a>
        </li>
        <li>
          <a href="#"> Employee</a>
        </li>
        <li>
          <a href="#"> Employee List</a>
        </li>
      </div>
    </div>

    <div class="row clearfix">
      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
          <div class="body">
            <a href="{{ route('client.employee.create') }}" class="btn bg-blue waves-effect">Add Employee</a>
            <br>
            <br>
            <div class="table-custom-responsive">
              <form target="_blank" action="#" method="POST" id="formId">
                <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                  <div class="row">
                    <div class="col-sm-12">
                      <table class="table table-bordered table-hover jq-no-sort-datatable custom-table dataTable">
                        <thead>
                          <tr class="bg-info" role="row">
                            <th>SL</th>
                            <th>Employee ID</th>
                            <th>Employee Name</th>
                            <th>Designation</th>
                            <th>Contact No</th>
                            <th>Employee Type</th>
                            <th>Status</th>
                            <th>Action</th>
                            <th>
                              <input type="checkbox" id="selectall" class="filled-in chk-col-blue" onclick="selectAll(this)">
                              <label for="selectall" class="form-label m-l-20 m-b--10"></label>
                            </th>
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
                        @if ($data)
                            @foreach ($data as $value)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $value->employee_id }}</td>
                                    <td>{{ $value->employee_name }}</td>
                                    <td>{{ $value->designation }}</td>
                                    <td>{{ $value->primary_mobile }}</td>
                                    <td>{{ $value->customer_type }}</td>
                                    <td>
                                        <span class="text-success">{{ ($value->is_active == 1) ? 'Active':'Inactive' }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button 
                                                type="button"
                                                class="btn btn-default btn-xs dropdown-toggle"
                                                data-toggle="dropdown"
                                                aria-haspopup="true"
                                                aria-expanded="false"
                                            >
                                                Action <span class="caret"></span>
                                            </button>

                                            <ul class="dropdown-menu pull-right">
                                                <li>
                                                    <a href="{{ route('client.employee.edit', $value->id) }}" class="waves-effect waves-block">
                                                        Update
                                                    </a>
                                                </li>

                                                <li role="separator" class="divider"></li>

                                                <li>
                                                    @php
                                                        $statusText = $value->is_active == 1 ? 'Inactive' : 'Active';
                                                    @endphp
                                                    <form action="{{ route('client.employee.edit', $value->id) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="_method" value="PATCH">
                                                        <button type="submit" class="dropdown-item d-flex align-items-center">
                                                            <i class="fa fa-toggle-on me-2"></i> {{ $statusText }}
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>

                                    <td>
                                        <input
                                            type="checkbox"
                                            id="employeeCheck"
                                            value="{{ $value->id }}"
                                            name="employeeCheck[]"
                                            onclick="setCheckBox(this.value, this.id)"
                                            class="filled-in chk-col-blue"
                                        >

                                        <label 
                                            for="employeeCheck"
                                            class="form-label"
                                            style="margin-bottom: -12px"
                                        ></label>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td>Data not found</td>
                            </tr>
                        @endif
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <input type="hidden" name="employeeIdStr" id="employeeIdStr">
              </form>
              <div class="text-right">
                <button class="btn bg-blue waves-effect" onclick="submitForm()">Show Employee Profile</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
@endsection
@push('scripts')
<script>
        function inactiveEmployee(employeeId) {
            swal({
                title: "Are you sure?",
                text: "",
                type: "warning",
                showCancelButton: true,
                closeOnConfirm: false,
                confirmButtonText: "Yes, inactive it...!",
                confirmButtonColor: "#ec6c62"
            }, function () {
                showLoader();
                $.ajax({
                    url: "https://vroom24x7.com/demo/client/Employee/changeEmployeeStatus?employeeId=" + employeeId + "&status=0",
                    type: "DELETE"
                })


                        .done(function (data) {
                            console.log(data);
                            hideLoader();
                            if (data === '1') {
                                swal({
                                    title: "Inactive Successfully",
                                    text: "This employee is inactive now",
                                    type: "success",
                                    closeOnConfirm: false,
                                    confirmButtonText: "Ok",
                                    confirmButtonColor: "#A5DC86"
                                }, function () {
                                    window.location.href = "https://vroom24x7.com/demo/client/Employee/employeeList";
                                });
                            } else if (data === '2') {
                                window.location.href = "https://vroom24x7.com/demo/client/Employee/employeeList";
                            }
                        })
                        .error(function (data) {
                            swal("Oops", "We couldn't connect to the server!", "error");
                        });
            });
        }

        function activeEmployee(employeeId) {
            swal({
                title: "Are you sure?",
                text: "",
                type: "warning",
                showCancelButton: true,
                closeOnConfirm: false,
                confirmButtonText: "Yes, active it...!",
                confirmButtonColor: "#ec6c62"
            }, function () {
                showLoader();
                $.ajax({
                    url: "https://vroom24x7.com/demo/client/Employee/changeEmployeeStatus?employeeId=" + employeeId + "&status=1",
                    type: "DELETE"
                })

                        .done(function (data) {
                            hideLoader();
                            if (data === '1') {
                                swal({
                                    title: "Active Successfully",
                                    text: "This employee is active now",
                                    type: "success",
                                    closeOnConfirm: false,
                                    confirmButtonText: "Ok",
                                    confirmButtonColor: "#A5DC86"
                                }, function () {
                                    window.location.href = "https://vroom24x7.com/demo/client/Employee/employeeList";
                                });
                            } else if (data === '2') {
                                window.location.href = "https://vroom24x7.com/demo/client/Employee/employeeList";
                            }
                        })
                        .error(function (data) {
                            swal("Oops", "We couldn't connect to the server!", "error");
                        });
            });
        }

        var employeeIdArr = new Array();
        function selectAll(source) {
            checkboxes = document.getElementsByName('employeeCheck[]');
            var employeeCheckBoxIdArr = new Array();
            for (var i in checkboxes) {
                checkboxes[i].checked = source.checked;
                if (typeof (checkboxes[i].id) !== 'undefined') {
                    employeeCheckBoxIdArr.push(checkboxes[i].id);
                }
            }
            for (var i = 0; i < employeeCheckBoxIdArr.length; i++) {
                if ($("#" + employeeCheckBoxIdArr[i]).is(':checked')) {
                    var itemtoRemove = $("#" + employeeCheckBoxIdArr[i]).val();
                    employeeIdArr = jQuery.grep(employeeIdArr, function (value) {
                        return value !== itemtoRemove;
                    });
                    employeeIdArr.push($("#" + employeeCheckBoxIdArr[i]).val());
                } else {
                    var itemtoRemove = $("#" + employeeCheckBoxIdArr[i]).val();
                    employeeIdArr = jQuery.grep(employeeIdArr, function (value) {
                        return value !== itemtoRemove;
                    });
                }
            }

        }

        function setCheckBox(employeeId, checkBoxId) {
            if ($("#" + checkBoxId).is(':checked')) {
                var itemtoRemove = employeeId;
                employeeIdArr = jQuery.grep(employeeIdArr, function (value) {
                    return value !== itemtoRemove;
                });
                employeeIdArr.push(employeeId);
            } else {
                var itemtoRemove = employeeId;
                employeeIdArr = jQuery.grep(employeeIdArr, function (value) {
                    return value !== itemtoRemove;
                });
            }
        }
        function submitForm() {
            var employeeIdStr = employeeIdArr.join();
            if (employeeIdStr) {
                $('#employeeIdStr').val(employeeIdStr);
                //console.log(employeeIdStr);
                $("#formId").submit();
            } else {
                sweetAlert('Please select at least one employee...!');
            }
        }
    </script>
@endpush
