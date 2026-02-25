
@extends('layouts.app')

@section('content')

<div class="header dashboard_from">
    <h1 class="page-title">Member Search</h1>
    <ul class="breadcrumb">
        <li><a href="#"> Home</a></li>
        <li><a href="#">/ Master Data</a></li>
        <li><a href="#">/ Member</a></li>
    </ul>
</div>



<div class="container">
    <div class="card shadow">
        <div class="card-body">

            <div class="main-content">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel panel-default">
                            <div class="row">
                                <div class="col-sm-12 col-md-12">
                                    <div class="panel-group">
                                        <div class="row">
                                            <div class="col-sm-6 col-md-4">
                                                <div class="form-group mb-2">
                                                    <label class="form-label">Gender</label>
                                                    <select class="form-control" id="genderSearch">
                                                        <option value="">Select Gender</option>
                                                        <option value="male">Male</option>
                                                        <option value="female">Female</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-4">
                                                <div class="form-group mb-2">
                                                    <label class="form-label" for="">Birthday Date</label>
                                                    <input type="text" class="form-control dateInput" id="birthdayDateCalendar" placeholder="Birthday date calendar">
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-4">
                                                <div class="form-group mb-2">
                                                    <label class="form-label" for="">Anniversary Date</label>
                                                    <input type="text" class="form-control dateInput" id="anniversaryDateCalendar" placeholder="Anniversary date calendar">
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-4">
                                                <div class="form-group mb-2">
                                                    <label class="form-label" for="">Date Of Birth</label>
                                                    <input type="text" class="form-control dateInput" id="dobDateCalendar" placeholder="Date of birth">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-default mt-3">
                                            <div class="panel-heading text-center">
                                                <h5 class="panel-title mb-2"><b>Member Type</b></h5>
                                            </div>
                                            <div class="panel-body">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-hover custom-table" id="memberTypeTable" style="table-layout: fixed; width: 100%">
                                                        <thead>
                                                        <tr class="bg-info">
                                                            <th class='td-center' style="width:45px">SL</th>
                                                            <th class='td-center' style="width:70px">Member Type</th>
                                                            <th class='td-center no-sort' style="width:70px"><input type="checkbox" id="selectallMemberType" onClick="selectallMemberType(this, 1)" /></th>
                                                        </tr>
                                                        </thead>

                                                        <tbody>
                                                        <?php
                                                        $serial = 1;
                                                        foreach ($memberTypes as $memberType) {
                                                            echo "<tr>";
                                                            echo "<td>" . $serial . "</td>";
                                                            echo "<td>" . $memberType->element . "</td>";
                                                            echo "<td class='td-center'><input type='checkbox' name='memberTypeArr[]' id='memberTypeCheckBox" . $serial . "' value='" . $memberType->element_code . "'  /></td>";

                                                            echo "</tr>";
                                                            $serial++;
                                                        }
                                                        ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-sm-6 col-md-6">
                                    <div class="panel-group">
                                        <div class="panel panel-default">
                                            <div class="panel-heading text-center">
                                                <h5 class="panel-title mb-2"><b>Blocks</b></h5>
                                            </div>
                                            <div class="panel-body">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-hover custom-table" id="blockTable" style="table-layout: fixed; width: 100%">
                                                        <thead>
                                                        <tr class="bg-info">
                                                            <th class='td-center' style="width:45px">SL</th>
                                                            <th class='td-center' style="width:70px">Block Name</th>
                                                            <th class='td-center no-sort' style="width:70px"><input type="checkbox" id="selectallBlock" onClick="selectAllBlock(this, 1)" /></th>
                                                        </tr>
                                                        </thead>

                                                        <tbody>
                                                        <?php
                                                        $serial = 1;
                                                        foreach ($blocks as $block) {
                                                            echo "<tr>";
                                                            echo "<td>" . $serial . "</td>";
                                                            echo "<td>" . $block->block_name . "</td>";
                                                            echo "<td class='td-center'><input type='checkbox' name='blockArr[]' id='blockCheckBox" . $serial . "' value='" . $block->block_code . "'  /></td>";

                                                            echo "</tr>";
                                                            $serial++;
                                                        }
                                                        ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                    <div class="panel-group">
                                        <div class="panel panel-default">
                                            <div class="panel-heading text-center">
                                                <h5 class="panel-title mb-2"><b>Roads</b></h5>
                                            </div>
                                            <div class="panel-body">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-hover custom-table" id="roadTable" style="table-layout: fixed; width: 100%">
                                                        <thead>
                                                        <tr class="bg-info">
                                                            <th class='td-center' style="width:45px">SL</th>
                                                            <th class='td-center' style="width:70px">Road Name</th>
                                                            <th class='td-center no-sort' style="width:70px"><input type="checkbox" id="selectallRoad" onClick="selectAllRoad(this, 1)" /></th>
                                                        </tr>
                                                        </thead>

                                                        <tbody>
                                                        <?php
                                                        $serial = 1;
                                                        foreach ($roads as $road) {
                                                            echo "<tr>";
                                                            echo "<td>" . $serial . "</td>";
                                                            echo "<td>" . $road['road_name'] . "</td>";
                                                            echo "<td class='td-center'><input type='checkbox' name='roadArr[]' id='roadCheckBox" . $serial . "' value='" . $road->road_code . "'  /></td>";
                                                            echo "</tr>";
                                                            $serial++;
                                                        }
                                                        ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6 col-md-6">
                                    <div class="panel-group">
                                        <div class="panel panel-default">
                                            <div class="panel-heading text-center">
                                                <h5 class="panel-title mb-2"><b>Occupations</b></h5>
                                            </div>
                                            <div class="panel-body">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-hover custom-table" id="occupationTable" style="table-layout: fixed; width: 100%">
                                                        <thead>
                                                        <tr class="bg-info">
                                                            <th class='td-center' style="width:45px">SL</th>
                                                            <th class='td-center' style="width:70px">Occupation</th>
                                                            <th class='td-center no-sort' style="width:70px"><input type="checkbox" id="selectallOccupation" onClick="selectAllOccupation(this, 1)" /></th>
                                                        </tr>
                                                        </thead>

                                                        <tbody>
                                                        <?php
                                                        $serial = 1;
                                                        foreach ($occupations as $occupation) {
                                                            echo "<tr>";
                                                            echo "<td>" . $serial . "</td>";
                                                            echo "<td>" . $occupation->element . "</td>";
                                                            echo "<td class='td-center'><input type='checkbox' name='occupationArr[]' id='occupationCheckBox" . $serial . "' value='" . $occupation->element_code . "'  /></td>";

                                                            echo "</tr>";
                                                            $serial++;
                                                        }
                                                        ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-6">
                                    <div class="panel-group">
                                        <div class="panel panel-default">
                                            <div class="panel-heading text-center">
                                                <h5 class="panel-title mb-2"><b>Blood Groups</b></h5>
                                            </div>
                                            <div class="panel-body">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-hover custom-table" id="occupationTable" style="table-layout: fixed; width: 100%">
                                                        <thead>
                                                        <tr class="bg-info">
                                                            <th class='td-center' style="width:45px">SL</th>
                                                            <th class='td-center' style="width:70px">Blood Group</th>
                                                            <th class='td-center no-sort' style="width:70px"><input type="checkbox" id="selectallBloodGroup" onClick="selectallBloodGroup(this, 1)" /></th>
                                                        </tr>
                                                        </thead>

                                                        <tbody>
                                                        <?php
                                                        $serial = 1;
                                                        foreach ($bloodGroups as $bloodGroup) {
                                                            echo "<tr>";
                                                            echo "<td>" . $serial . "</td>";
                                                            echo "<td>" . $bloodGroup['label'] . "</td>";
                                                            echo "<td class='td-center'><input type='checkbox' name='bloodGroupArr[]' id='bloodGroupCheckBox" . $serial . "' value='" . $bloodGroup['value'] . "'  /></td>";

                                                            echo "</tr>";
                                                            $serial++;
                                                        }
                                                        ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button class="btn btn-primary save_button mt-4" onclick="showBulkSmsPanel()">Show Member List</button>
            <!--                -->
                            <form action="{{ route('admin.member.search') }}" style="display: none"  method="post"id="showMemberBulkSmsForm">
                                @csrf
                                <input type="text" id="memberType" name="memberType" value="">
                                <input type="text" id="block" name="block" value="">
                                <input type="text" id="road" name="road" value="">
                                <input type="text" id="gender" name="gender" value="">
                                <input type="text" id="occupation" name="occupation" value="">
                                <input type="text" id="bloodGroup" name="bloodGroup" value="">
                                <input type="text" id="anniversaryDate" name="anniversaryDate" value="">
                                <input type="text" id="birthdayDate" name="birthdayDate" value="">
                                <input type="text" id="dob" name="dob" value="">
                            </form>
                        </div>
                    </div>
                </div>
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

    var memberTypeCount = '<?php echo count($memberTypes) ?>';
    var blockCount = '<?php echo count($blocks) ?>';
    var roadCount = '<?php echo count($roads) ?>';
    var occupationCount = '<?php echo count($occupations) ?>';
    var bloodGroupCount = '<?php echo count($bloodGroups) ?>';

    function selectAllBlock(source){
        checkboxes = document.getElementsByName('blockArr[]');
        for (var i in checkboxes) {
            checkboxes[i].checked = source.checked;
        }
    }

    function selectAllRoad(source){
        checkboxes = document.getElementsByName('roadArr[]');
        for (var i in checkboxes) {
            checkboxes[i].checked = source.checked;
        }
    }

    function selectAllOccupation(source){
        checkboxes = document.getElementsByName('occupationArr[]');
        for (var i in checkboxes) {
            checkboxes[i].checked = source.checked;
        }
    }

    function selectallBloodGroup(source){
        checkboxes = document.getElementsByName('bloodGroupArr[]');
        for (var i in checkboxes) {
            checkboxes[i].checked = source.checked;
        }
    }

    function selectallMemberType(source){
        checkboxes = document.getElementsByName('memberTypeArr[]');
        for (var i in checkboxes) {
            checkboxes[i].checked = source.checked;
        }
    }

    function showBulkSmsPanel(flag) {
        setCheckedValue('#memberType', '#memberTypeCheckBox', memberTypeCount);
        setCheckedValue('#block', '#blockCheckBox', blockCount);
        setCheckedValue('#road', '#roadCheckBox', roadCount);
        setCheckedValue('#occupation', '#occupationCheckBox', occupationCount);
        setCheckedValue('#bloodGroup', '#bloodGroupCheckBox', bloodGroupCount);
        $('#anniversaryDate').val($('#anniversaryDateCalendar').val());
        $('#birthdayDate').val($('#birthdayDateCalendar').val());
        $('#dob').val($('#dobDateCalendar').val());
        $('#gender').val($('#genderSearch').val());
         $("#showMemberBulkSmsForm").submit();
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
