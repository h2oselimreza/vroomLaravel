@extends('client.layouts.app')

@section('content')
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

    <div class="block-header">
        <h2>EMPLOYEE LIST</h2><br>
        <div class="breadcrumb breadcrumb-bg-blue-grey">
            <li><a href="https://vroom24x7.com/demo/client/Home"> Home</a></li>
            <li><a href="#"> Employee</a></li>
            <li><a href="https://vroom24x7.com/demo/client/Employee/employeeList"> Employee List</a></li>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="body">
                    <a href="https://vroom24x7.com/demo/client/Employee/addEmpPersonalShow" class="btn bg-blue waves-effect">Add Employee</a>
                    <!--<button class="btn bg-blue waves-effect">Add Employee</button>-->
                    <br><br>
                    <div class="table-custom-responsive">
                        <form target="_blank" action="https://vroom24x7.com/demo/client/Employee/showMultiEmployeeInfo" method="POST" id="formId">
                            <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper form-inline dt-bootstrap"><div class="row"><div class="col-sm-9"><div class="dataTables_length" id="DataTables_Table_0_length"><label>Show <select name="DataTables_Table_0_length" aria-controls="DataTables_Table_0" class="form-control input-sm"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option><option value="-1">All</option></select> entries</label></div></div><div class="col-sm-3"><div id="DataTables_Table_0_filter" class="dataTables_filter"><label>Search:<input type="search" class="form-control input-sm" placeholder="" aria-controls="DataTables_Table_0"></label></div></div></div><div class="row"><div class="col-sm-12"><table class="table table-bordered table-hover jq-no-sort-datatable custom-table dataTable" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                                <thead>
                                    <tr class="bg-info" role="row"><th class="sorting_asc" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="ascending" aria-label="SL: activate to sort column descending" style="width: 30.2px;">SL</th><th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Employee ID: activate to sort column ascending" style="width: 103.2px;">Employee ID</th><th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Employee Name: activate to sort column ascending" style="width: 131.2px;">Employee Name</th><th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Designation: activate to sort column ascending" style="width: 99.2px;">Designation</th><th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Contact No: activate to sort column ascending" style="width: 94.2px;">Contact No</th><th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Employee Type: activate to sort column ascending" style="width: 123.2px;">Employee Type</th><th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending" style="width: 59.2px;">Status</th><th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Action: activate to sort column ascending" style="width: 59.2px;">Action</th><th class="no-sort sorting_disabled" rowspan="1" colspan="1" aria-label="
                                            
                                            
                                        " style="width: 69px;">
                                            <input type="checkbox" id="selectall" class="filled-in chk-col-blue" onclick="selectAll(this)">
                                            <label for="selectall" class="form-label m-l-20 m-b--10"></label>
                                        </th></tr>
                                </thead>
                                <tfoot>
                                    <tr><th rowspan="1" colspan="1"><select><option value=""></option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option></select></th><th rowspan="1" colspan="1"><select><option value=""></option><option value="FE00002">FE00002</option><option value="FE00009">FE00009</option><option value="FE00011">FE00011</option><option value="FE00012">FE00012</option><option value="FE00013">FE00013</option><option value="FE00087">FE00087</option><option value="FE00088">FE00088</option></select></th><th rowspan="1" colspan="1"><select><option value=""></option><option value="Abdul Karim">Abdul Karim</option><option value="Ashik Sarkar">Ashik Sarkar</option><option value="Joshim">Joshim</option><option value="Mr.Rokan">Mr.Rokan</option><option value="Nur Alam">Nur Alam</option><option value="Rashik Raj">Rashik Raj</option><option value="Sojib Hasan">Sojib Hasan</option></select></th><th rowspan="1" colspan="1"><select><option value=""></option><option value=""></option><option value="Driver">Driver</option><option value="Support Engineer">Support Engineer</option></select></th><th rowspan="1" colspan="1"><select><option value=""></option><option value="8801333978665">8801333978665</option><option value="8801640578722">8801640578722</option><option value="8801713364956">8801713364956</option><option value="8801727018860">8801727018860</option><option value="8801829331461">8801829331461</option><option value="8801945882352">8801945882352</option><option value="8801987654321">8801987654321</option></select></th><th rowspan="1" colspan="1"><select><option value=""></option><option value="Driver">Driver</option><option value="System Manager">System Manager</option></select></th><th rowspan="1" colspan="1"><select><option value=""></option><option value="&lt;span class=" text-success"="">Active"&gt;<span class="text-success">Active</span></option></select></th></tr>
                                </tfoot>
                                <tbody>
                                                                <tr role="row" class="odd"><td class="sorting_1">1</td><td>FE00002</td><td class="td-left">Rashik Raj</td><td class="td-left"></td><td>8801640578722</td><td>Driver</td><td><span class="text-success">Active</span></td>                                    <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Action <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu pull-right">
                                                    <li><a href="https://vroom24x7.com/demo/client/Employee/updateEmpPersonalShow/FE00002" class=" waves-effect waves-block">Update</a></li>
                                                    <li role="separator" class="divider"></li>

                                                                                                        <li><a href="#" onclick="inactiveEmployee('33')" class=" waves-effect waves-block">Inactive</a></li>
                                                        

                                                </ul>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="checkbox" id="employeeCheck1" value="33" name="employeeCheck[]" onclick="setCheckBox(this.value, this.id)" class="filled-in chk-col-blue">
                                            <label for="employeeCheck1" class="form-label" style="margin-bottom: -12px"></label>
                                        </td>
                                        </tr><tr role="row" class="even"><td class="sorting_1">2</td><td>FE00087</td><td class="td-left">Mr.Rokan</td><td class="td-left">Support Engineer</td><td>8801829331461</td><td>System Manager</td><td><span class="text-success">Active</span></td>                                    <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Action <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu pull-right">
                                                    <li><a href="https://vroom24x7.com/demo/client/Employee/updateEmpPersonalShow/FE00087" class=" waves-effect waves-block">Update</a></li>
                                                    <li role="separator" class="divider"></li>

                                                                                                        <li><a href="#" onclick="inactiveEmployee('139')" class=" waves-effect waves-block">Inactive</a></li>
                                                        

                                                </ul>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="checkbox" id="employeeCheck2" value="139" name="employeeCheck[]" onclick="setCheckBox(this.value, this.id)" class="filled-in chk-col-blue">
                                            <label for="employeeCheck2" class="form-label" style="margin-bottom: -12px"></label>
                                        </td>
                                        </tr><tr role="row" class="odd"><td class="sorting_1">3</td><td>FE00088</td><td class="td-left">Ashik Sarkar</td><td class="td-left">Driver</td><td>8801333978665</td><td>Driver</td><td><span class="text-success">Active</span></td>                                    <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Action <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu pull-right">
                                                    <li><a href="https://vroom24x7.com/demo/client/Employee/updateEmpPersonalShow/FE00088" class=" waves-effect waves-block">Update</a></li>
                                                    <li role="separator" class="divider"></li>

                                                                                                        <li><a href="#" onclick="inactiveEmployee('142')" class=" waves-effect waves-block">Inactive</a></li>
                                                        

                                                </ul>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="checkbox" id="employeeCheck3" value="142" name="employeeCheck[]" onclick="setCheckBox(this.value, this.id)" class="filled-in chk-col-blue">
                                            <label for="employeeCheck3" class="form-label" style="margin-bottom: -12px"></label>
                                        </td>
                                        </tr><tr role="row" class="even"><td class="sorting_1">4</td><td>FE00009</td><td class="td-left">Sojib Hasan</td><td class="td-left"></td><td>8801945882352</td><td>Driver</td><td><span class="text-success">Active</span></td>                                    <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Action <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu pull-right">
                                                    <li><a href="https://vroom24x7.com/demo/client/Employee/updateEmpPersonalShow/FE00009" class=" waves-effect waves-block">Update</a></li>
                                                    <li role="separator" class="divider"></li>

                                                                                                        <li><a href="#" onclick="inactiveEmployee('41')" class=" waves-effect waves-block">Inactive</a></li>
                                                        

                                                </ul>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="checkbox" id="employeeCheck4" value="41" name="employeeCheck[]" onclick="setCheckBox(this.value, this.id)" class="filled-in chk-col-blue">
                                            <label for="employeeCheck4" class="form-label" style="margin-bottom: -12px"></label>
                                        </td>
                                        </tr><tr role="row" class="odd"><td class="sorting_1">5</td><td>FE00011</td><td class="td-left">Abdul Karim</td><td class="td-left"></td><td>8801987654321</td><td>Driver</td><td><span class="text-success">Active</span></td>                                    <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Action <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu pull-right">
                                                    <li><a href="https://vroom24x7.com/demo/client/Employee/updateEmpPersonalShow/FE00011" class=" waves-effect waves-block">Update</a></li>
                                                    <li role="separator" class="divider"></li>

                                                                                                        <li><a href="#" onclick="inactiveEmployee('46')" class=" waves-effect waves-block">Inactive</a></li>
                                                        

                                                </ul>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="checkbox" id="employeeCheck5" value="46" name="employeeCheck[]" onclick="setCheckBox(this.value, this.id)" class="filled-in chk-col-blue">
                                            <label for="employeeCheck5" class="form-label" style="margin-bottom: -12px"></label>
                                        </td>
                                        </tr><tr role="row" class="even"><td class="sorting_1">6</td><td>FE00012</td><td class="td-left">Nur Alam</td><td class="td-left"></td><td>8801727018860</td><td>Driver</td><td><span class="text-success">Active</span></td>                                    <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Action <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu pull-right">
                                                    <li><a href="https://vroom24x7.com/demo/client/Employee/updateEmpPersonalShow/FE00012" class=" waves-effect waves-block">Update</a></li>
                                                    <li role="separator" class="divider"></li>

                                                                                                        <li><a href="#" onclick="inactiveEmployee('54')" class=" waves-effect waves-block">Inactive</a></li>
                                                        

                                                </ul>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="checkbox" id="employeeCheck6" value="54" name="employeeCheck[]" onclick="setCheckBox(this.value, this.id)" class="filled-in chk-col-blue">
                                            <label for="employeeCheck6" class="form-label" style="margin-bottom: -12px"></label>
                                        </td>
                                        </tr><tr role="row" class="odd"><td class="sorting_1">7</td><td>FE00013</td><td class="td-left">Joshim</td><td class="td-left"></td><td>8801713364956</td><td>Driver</td><td><span class="text-success">Active</span></td>                                    <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Action <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu pull-right">
                                                    <li><a href="https://vroom24x7.com/demo/client/Employee/updateEmpPersonalShow/FE00013" class=" waves-effect waves-block">Update</a></li>
                                                    <li role="separator" class="divider"></li>

                                                                                                        <li><a href="#" onclick="inactiveEmployee('55')" class=" waves-effect waves-block">Inactive</a></li>
                                                        

                                                </ul>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="checkbox" id="employeeCheck7" value="55" name="employeeCheck[]" onclick="setCheckBox(this.value, this.id)" class="filled-in chk-col-blue">
                                            <label for="employeeCheck7" class="form-label" style="margin-bottom: -12px"></label>
                                        </td>
                                        </tr></tbody>
                            </table></div></div><div class="row"><div class="col-sm-5"><div class="dataTables_info" id="DataTables_Table_0_info" role="status" aria-live="polite">Showing 1 to 7 of 7 entries</div></div><div class="col-sm-7"><div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate"><ul class="pagination"><li class="paginate_button previous disabled" id="DataTables_Table_0_previous"><a href="#" aria-controls="DataTables_Table_0" data-dt-idx="0" tabindex="0">Previous</a></li><li class="paginate_button active"><a href="#" aria-controls="DataTables_Table_0" data-dt-idx="1" tabindex="0">1</a></li><li class="paginate_button next disabled" id="DataTables_Table_0_next"><a href="#" aria-controls="DataTables_Table_0" data-dt-idx="2" tabindex="0">Next</a></li></ul></div></div></div></div>
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
    $('#datatable').DataTable();
</script>
@endpush
