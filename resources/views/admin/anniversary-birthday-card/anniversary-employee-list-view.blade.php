@extends('layouts.app')
@section('content')

<div class="header dashboard_from">
    <h1 class="page-title">Employee Anniversary & Birthday Card</h1>
    <ul class="breadcrumb">
        <li><a href="#"> Home</a></li>
        <li><a href="#">/ Anniversary & Birthday</a></li>
        <li><a href="#">/ Employee Anniversary & Birthday Card</a></li>
    </ul>
</div>
<div class="main-content">
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel panel-default">
                <div class="text-right"><h4><b>Selected Employee: <span id="selectedEmployee">0</span></b></h4></div>
                <div class="table-responsive">
                    <form action="{{ route('admin.show-employee-anniversary-card') }}" method="POST">
                        
                        @csrf

                        <table class="table table-bordered table-hover custom-table" id="datatable">
                            <thead>
                                <tr class="bg-primary">
                                    <th>SL</th>
                                    <th>Employee ID</th>
                                    <th>Employee Name</th>
                                    <th>Designation</th>
                                    <th>Contact No</th>
                                    <th>Anniversary</th>
                                    <th>DOB</th>
                                    <th class="no-sort" style="width:50px">
                                        <input type="checkbox" id="selectall" onClick="selectAll(this)" />
                                    </th>
                                </tr>
                            </thead>

                            <tfoot>
                                <tr>
                                    <th>SL</th>
                                    <th>Employee ID</th>
                                    <th>Employee Name</th>
                                    <th>Designation</th>
                                    <th>Contact No</th>
                                    <th>Anniversary</th>
                                    <th>DOB</th>
                                </tr>
                            </tfoot>

                            <tbody>
                                @php $count = 1; @endphp

                                @foreach ($data['employees'] as $employee)
                                    <tr>
                                        <td class='td-center'>{{ $count }}</td>
                                        <td>{{ $employee['employee_id'] }}</td>
                                        <td>{{ $employee['employee_name'] }}</td>
                                        <td>{{ $employee['designation'] }}</td>
                                        <td>{{ $employee['primary_mobile'] }}</td>
                                        <td>{{ $employee['anniversary'] }}</td>
                                        <td>{{ $employee['dob'] }}</td>

                                        <td class='td-center'>
                                            <input type='checkbox'
                                                   name='employeeIdArr[]'
                                                   id="employeeIdCheckbox{{ $count }}"
                                                   onclick="showCheckedEmployeeCount()"
                                                   value="{{ $employee['id'] }}" />
                                        </td>
                                    </tr>

                                    @php $count++; @endphp
                                @endforeach

                            </tbody>
                        </table>

                        <div style="text-align:right;">
                            <input type="hidden" name="cardType" value="{{ $data['cardType'] }}">
                            <input type="submit" class="btn btn-success save_button" value="Show Card">
                        </div>

                    </form>
                </div> 
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    table = $('#datatable').DataTable({
        // processing: true,
        // serverSide: true,
        initComplete: function () {
            this.api().columns().every(function () {
                var column = this;

                // ❌ Skip Action column (last column index = 7)
                if (column.index() === 10) return;

                var select = $('<select class="form-control" style="width:100%"><option value="">All</option></select>')
                    .appendTo($(column.footer()).empty())
                    .on('change', function () {
                        var val = $.fn.dataTable.util.escapeRegex($(this).val());

                        column
                            .search(val ? '^' + val + '$' : '', true, false)
                            .draw();
                    });

                column.data().unique().sort().each(function (d) {

                    // ✅ Convert HTML → plain text
                    var text = $('<div>').html(d).text().trim();

                    if (text) {
                        select.append('<option value="' + text + '">' + text + '</option>');
                    }
                });
            });
        }
    });

    function selectAll(source) {
        checkboxes = document.getElementsByName('employeeIdArr[]');
        for(var i in checkboxes)
          checkboxes[i].checked = source.checked;

        showCheckedEmployeeCount();
    }


    function showCheckedEmployeeCount() {
        checkboxes = document.getElementsByName('employeeIdArr[]');
        var employeeCheckBoxIdArr = new Array();
        var j  =  1;
        for (var i in checkboxes){
            if($("#employeeIdCheckbox"+j).is(':checked')) {
                employeeCheckBoxIdArr.push(checkboxes[i].id);
            }
            j = j+ 1;
        }
        $("#selectedEmployee").html(employeeCheckBoxIdArr.length);
    }
</script>

@endpush
