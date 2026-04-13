@php
    $btnFlag = 0;
    $disableFlag = isset($data->exists) ? 0 : 1;

    $segments = request()->segments();
    $lastSegment = request()->segment(count($segments));
    $secondLastSegment = request()->segment(count($segments) - 2);
    $thirdLastSegment = $segments[2];
@endphp

<div class="btn-group btn-group-lg hidden-xs btn-group-justified" role="group" aria-label="...">
    @if ($thirdLastSegment == 'create')
        <div class="btn-group" role="group">
            <a href="{{ route('client.employee.create') }}">
                <button type="submit"  class="btn btn-<?php echo ($secondLastSegment == 'create') ? 'info' : 'default' ?> btn-lg waves-effect"> <i class="fa fa-user"></i><b> Personal</b></button>
            </a>
        </div>
    @else
        <div class="btn-group" role="group">
            <a href="{{ route('client.employee.edit', $data->id) }}">
                <button type="submit"  class="btn btn-<?php echo ($secondLastSegment == 'info') ? 'info' : 'default' ?> btn-lg waves-effect"> <i class="fa fa-user"></i><b> Personal</b></button>
            </a>
        </div>
    @endif
    
    <div class="btn-group" role="group">
        <a href="{{ route('client.employee.office.edit', $data->id) }}">
            <button type="button" onclick="employeeRoute('official')" class="btn btn-<?php echo ($secondLastSegment == 'office') ? 'info' : 'default' ?> waves-effect btn-lg" <?php echo ($disableFlag == '1') ? 'disabled' : '' ?>> <i class="fa fa-home"></i><b> Official</b></button>
        </a>
    </div>
    <div class="btn-group" role="group">
        <a href="{{ route('client.employee.photograph.edit', $data->id) }}">
            <button type="button" onclick="employeeRoute('photograph')" class="btn btn-<?php echo ($secondLastSegment == 'photograph') ? 'info' : 'default' ?> waves-effect btn-lg" <?php echo ($disableFlag == '1') ? 'disabled' : '' ?>> <i class="fa fa-image"></i><b> Photograph</b></button>
        </a>
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
    <div class="btn-group" role="group">
        <button type="button" onclick="employeeRoute('photograph')" class="btn btn-<?php echo ($btnFlag == 'photograph') ? 'info' : 'default' ?> waves-effect btn-lg" <?php echo ($disableFlag == '1') ? 'disabled' : '' ?>> <i class="fa fa-image"></i><b> Photograph</b></button>
    </div>
</div>