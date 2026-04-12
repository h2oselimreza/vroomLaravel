<script>
    function employeeRoute(flag) {
        var routeFunction;
        var employeeId = '1';
        if (flag === 'personal') {
            routeFunction = 'updateEmpPersonalShow';
        } else if (flag === 'official') {
            routeFunction = 'updateEmpOfficialShow';
        } else if (flag === 'education') {
            routeFunction = 'updateEmpEducationShow';
        } else if (flag === 'workingExp') {
            routeFunction = 'updateEmpWorkingShow';
        } else if (flag === 'photograph') {
            routeFunction = 'updateEmpPhotographShow';
        }

        if (employeeId === "") {
            window.location.href = "client/Employee/addEmpPersonalShow";
        } else {
            window.location.href = "client/Employee/" + routeFunction + "/" + employeeId;
        }


    }
</script>

@php
    $btnFlag = 0;
    $disableFlag = 0;
@endphp

<div class="btn-group btn-group-lg hidden-xs btn-group-justified" role="group" aria-label="...">
    <div class="btn-group" role="group">
        <button type="submit" onclick="employeeRoute('personal')" class="btn btn-<?php echo ($btnFlag == 'personal') ? 'info' : 'default' ?> btn-lg waves-effect"> <i class="fa fa-user"></i><b> Personal</b></button>
    </div>
    <div class="btn-group" role="group">
        <button type="button" onclick="employeeRoute('official')" class="btn btn-<?php echo ($btnFlag == 'official') ? 'info' : 'default' ?> waves-effect btn-lg" <?php echo ($disableFlag == '1') ? 'disabled' : '' ?>> <i class="fa fa-home"></i><b> Official</b></button>
    </div>
    <!--<div class="btn-group" role="group">-->
    <!--    <button type="button" onclick="employeeRoute('education')" class="btn btn-<?php echo ($btnFlag == 'education') ? 'info' : 'default' ?> waves-effect btn-lg" <?php echo ($disableFlag == '1') ? 'disabled' : '' ?>> <i class="fa fa-graduation-cap"></i><b> Education</b></button>-->
    <!--</div>-->
    <!--<div class="btn-group" role="group">-->
    <!--    <button type="button" onclick="employeeRoute('workingExp')" class="btn btn-<?php echo ($btnFlag == 'workingExp') ? 'info' : 'default' ?> waves-effect btn-lg" <?php echo ($disableFlag == '1') ? 'disabled' : '' ?>> <i class="fa fa-bank"></i><b> Working Experience</b></button>-->
    <!--</div>-->
    <div class="btn-group" role="group">
        <button type="button" onclick="employeeRoute('photograph')" class="btn btn-<?php echo ($btnFlag == 'photograph') ? 'info' : 'default' ?> waves-effect btn-lg" <?php echo ($disableFlag == '1') ? 'disabled' : '' ?>> <i class="fa fa-image"></i><b> Photograph</b></button>
    </div>
</div>
<!-- end for web device -->

<!-- for xs devices -->
<div class="btn-group btn-group-vertical visible-xs" role="group" aria-label="...">
    <div class="btn-group" role="group">
        <button type="submit" onclick="employeeRoute('personal')" class="btn btn-<?php echo ($btnFlag == 'personal') ? 'info' : 'default' ?> btn-lg waves-effect"> <i class="fa fa-user"></i><b> Personal</b></button>
    </div>
    <div class="btn-group" role="group">
        <button type="button" onclick="employeeRoute('official')" class="btn btn-<?php echo ($btnFlag == 'official') ? 'info' : 'default' ?> waves-effect btn-lg" <?php echo ($disableFlag == '1') ? 'disabled' : '' ?>> <i class="fa fa-home"></i><b> Official</b></button>
    </div>
    <!--<div class="btn-group" role="group">-->
    <!--    <button type="button" onclick="employeeRoute('education')" class="btn btn-<?php echo ($btnFlag == 'education') ? 'info' : 'default' ?> waves-effect btn-lg" <?php echo ($disableFlag == '1') ? 'disabled' : '' ?>> <i class="fa fa-graduation-cap"></i><b> Education</b></button>-->
    <!--</div>-->
    <!--<div class="btn-group" role="group">-->
    <!--    <button type="button" onclick="employeeRoute('workingExp')" class="btn btn-<?php echo ($btnFlag == 'workingExp') ? 'info' : 'default' ?> waves-effect btn-lg" <?php echo ($disableFlag == '1') ? 'disabled' : '' ?>> <i class="fa fa-bank"></i><b> Working Experience</b></button>-->
    <!--</div>-->
    <div class="btn-group" role="group">
        <button type="button" onclick="employeeRoute('photograph')" class="btn btn-<?php echo ($btnFlag == 'photograph') ? 'info' : 'default' ?> waves-effect btn-lg" <?php echo ($disableFlag == '1') ? 'disabled' : '' ?>> <i class="fa fa-image"></i><b> Photograph</b></button>
    </div>
</div>