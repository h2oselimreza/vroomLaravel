@php
    $btnFlag = 0;
    $disableFlag = isset($vendor->exists) ? 0 : 1;

    $segments = request()->segments();
    $lastSegment = request()->segment(count($segments));
    $secondLastSegment = request()->segment(count($segments) - 2);
    $thirdLastSegment = $segments[2];
@endphp

<div class="btn-group btn-group-lg hidden-xs btn-group-justified" role="group" aria-label="...">
    @if ($thirdLastSegment == 'info')
        <div class="btn-group" role="group">
            <a href="{{ route('client.vendor.venor-list.create') }}">
                <button type="submit"  class="btn btn-<?php echo ($thirdLastSegment == 'info') ? 'info' : 'default' ?> btn-lg waves-effect"> <i class="fa fa-user"></i><b> General Info</b></button>
            </a>
        </div>
    @else
        <div class="btn-group" role="group">
            <a href="{{ isset($vendor) ? route('client.vendor.venor-list.edit', $vendor->id) : '#' }}">
                <button type="submit"  class="btn btn-<?php echo ($secondLastSegment == 'info') ? 'info' : 'default' ?> btn-lg waves-effect"> <i class="fa fa-user"></i><b> General Info</b></button>
            </a>
        </div>
    @endif
    
    <div class="btn-group" role="group">
        <a href="{{ isset($vendor) ? route('client.vendor.profile-image.edit', $vendor->id) : '#' }}">
            <button type="button" class="btn btn-<?php echo ($secondLastSegment == 'profile-image') ? 'info' : 'default' ?> waves-effect btn-lg" <?php echo ($disableFlag == '1') ? 'disabled' : '' ?>> <i class="fa fa-home"></i><b> Images</b></button>
        </a>
    </div>
    <div class="btn-group" role="group">
        <a href="{{ isset($vendor) ? route('client.vendor.attachment.edit', $vendor->id) : '#' }}">
            <button type="button" class="btn btn-<?php echo ($secondLastSegment == 'attachment') ? 'info' : 'default' ?> waves-effect btn-lg" <?php echo ($disableFlag == '1') ? 'disabled' : '' ?>> <i class="fa fa-image"></i><b> Attachment</b></button>
        </a>
    </div>
</div>