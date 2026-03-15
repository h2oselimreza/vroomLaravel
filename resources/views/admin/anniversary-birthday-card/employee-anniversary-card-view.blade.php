@extends('layouts.app')
@section('content')

<div class="header dashboard_from">
    <h1 class="page-title">Employee Anniversary & Birthday Card</h1>
    <ul class="breadcrumb">
        <li><a href="#"> Home</a></li>
        <li><a href="#">/ Anniversary & Birthday Card</a></li>
        <li><a href="#">/ Employee Anniversary & Birthday Card</a></li>
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
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel panel-default">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel-group">
                            <div class="row mb-3">
                                <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label class="form-label" for="">Type</label>
                                        <select class="form-control" name="type" id="type">
                                            <option value="anniversary">Anniversary</option>
                                            <option value="birthday">Birthday</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label class="form-label" for="">Date</label>
                                        <input type="text" class="form-control dateInput" name="anniversaryDateCalendar" id="anniversaryDateCalendar" placeholder="Select date">
                                    </div>
                                </div>
                            </div>

                            <div class="panel panel-default">
                                <div class="panel-heading text-center">
                                    <h5 class="panel-title mb-2"><b>Designation</b></h5>
                                </div>

                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover custom-table" id="designationTable" style="table-layout: fixed; width: 100%">
                                            <thead>
                                            <tr class="bg-info">
                                                <th class='td-center' style="width:45px">SL</th>
                                                <th class='td-center' style="width:70px">Designation</th>
                                                <th class='td-center no-sort' style="width:70px">
                                                    <input type="checkbox" id="selectallDesignation" onClick="selectallDesignation(this, 1)" />
                                                </th>
                                            </tr>
                                            </thead>

                                            <tbody>
                                            @php $serial = 1; @endphp
                                            @foreach ($designations as $designation)
                                                <tr>
                                                    <td>{{ $serial }}</td>
                                                    <td>{{ $designation->element }}</td>
                                                    <td class='td-center'>
                                                        <input type='checkbox'
                                                               name='designationArr[]'
                                                               id='designationCheckBox{{ $serial }}'
                                                               value='{{ $designation->element_code }}' />
                                                    </td>
                                                </tr>
                                                @php $serial++; @endphp
                                            @endforeach
                                            </tbody>

                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <button class="btn btn-primary save_button mt-4" onclick="showBulkSmsPanel(2)">Show Employee List</button>

                <form action="{{ route('admin.show-employee-anniversary-card-panel') }}"
                      style="display: none"
                      method="post"
                      id="showEmployeeBulkSmsForm">

                    @csrf

                    <input type="text" id="designation" name="designation" value="">
                    <input type="text" id="cardType" name="cardType" value="">
                    <input type="text" id="listFlag" name="listFlag" value="">
                    <input type="text" id="anniversaryDate" name="anniversaryDate" value="">
                </form>

            </div>
        </div>
    </div>
</div>

@endsection
@push('scripts')
<script language="JavaScript">
    $('.dateInput').datepicker({
        format: 'yyyy-mm-dd',  // format compatible with Laravel date column
        autoclose: true,       // close picker after selecting a date
        todayHighlight: true,  // highlight today
        clearBtn: true,        // optional clear button
        orientation: 'bottom'  // show below the input
    });
    var designationCount = '<?php echo count($designations) ?>';

    function selectallDesignation(source){
        checkboxes = document.getElementsByName('designationArr[]');
        for (var i in checkboxes) {
            checkboxes[i].checked = source.checked;
        }
    }

    function showBulkSmsPanel(flag) {
        setCheckedValue('#designation', '#designationCheckBox', designationCount);
        if ($.trim($('#anniversaryDateCalendar').val()) === '') {
            alert('Date is required...!');
            return false;
        }
        $('#anniversaryDate').val($('#anniversaryDateCalendar').val());
        $('#cardType').val($('#type').val());
        $("#listFlag").val(flag);
        $("#showEmployeeBulkSmsForm").submit();
    }

    function setCheckedValue(textFieldId, checkBoxId, loopCount) {
        var arr = new Array();
        for (var i = 1; i <= loopCount; i++) {
            if ($(checkBoxId + i).is(':checked')) {
                arr.push($(checkBoxId + i).val());
            }
        }
        $(textFieldId).val(arr.join());
        return true;
    }

</script>
@endpush
