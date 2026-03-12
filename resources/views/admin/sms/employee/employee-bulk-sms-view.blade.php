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
                            <div class="panel panel-default">
                                <div class="panel-heading text-center">
                                    <h5 class="panel-title"><b>Designations</b></h5>
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover custom-table" style="table-layout: fixed; width: 100%">
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
                                                @foreach($designations as $designation)
                                                    <tr>
                                                        <td class='td-center'>{{ $serial }}</td>
                                                        <td class='td-center'>{{ $designation->element }}</td>
                                                        <td class='td-center'>
                                                            <input type='checkbox' name='designationArr[]' id='designationCheckBox{{ $serial }}' value='{{ $designation->element_code }}' />
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
                <br>
                <div class="mt-3">
                    <button class="btn btn-primary save_button" onclick="showBulkSmsPanel(1)">Show Custom SMS Panel</button>
                    <button class="btn btn-primary save_button" onclick="showBulkSmsPanel(2)">Show Employee List</button>
                </div>

                <form action="{{ route('admin.employee-showcustom-sms-panel') }}" style="display: none" method="POST" id="showEmployeeBulkSmsForm">
                    @csrf
                    <input type="text" id="designation" name="designation" value="">
                    <input type="text" id="listFlag" name="listFlag" value="">
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script language="JavaScript">
    var designationCount = '<?php echo count($designations) ?>';

    function selectallDesignation(source){
        checkboxes = document.getElementsByName('designationArr[]');
        for (var i in checkboxes) {
            checkboxes[i].checked = source.checked;
        }
    }

    function showBulkSmsPanel(flag) {
        setCheckedValue('#designation', '#designationCheckBox', designationCount);
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
