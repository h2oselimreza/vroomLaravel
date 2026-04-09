@php
    $segments = request()->segments();
    $lastSegment = request()->segment(count($segments));
    $secondLastSegment = request()->segment(count($segments) - 2);
    $thirdLastSegment = $segments[1];
@endphp

<ul class="nav nav-tabs mb-4" id="employeeTab" role="tablist">
    @if(isset($data->exists))
        <li class="nav-item" role="presentation">
            <a class="nav-link {{ isset($data->exists) ? '' : 'nav_item' }}
                        <?= $thirdLastSegment == 'workshop-general-info' ? 'active' : ''?>" href="{{ isset($data) ? route('admin.workshop-general-info.edit', $data->workshop_code) : '#' }}" id="personal-tab" role="tab"> General Info </a>
        </li>
    @else
        <li class="nav-item" role="presentation">
            <a class="nav-link {{ isset($data->exists) ? '' : 'nav_item' }}
                        <?= $lastSegment == 'create' ? 'active' : ''?>" href="{{ route('admin.workshop-general-info.create') }}" id="personal-tab" role="tab"> General Info </a>
        </li>
    @endif
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ isset($data->exists) ? '' : 'nav_item' }}
                    <?= $secondLastSegment == 'workshop-time-schedule' ? 'active' : ''?>" href="{{ isset($data->exists) ? route('admin.workshop-time-schedule.edit', $data->workshop_code) : '#' }}"> Time Schedule </a>
    </li>
    <li class="nav-item" role="presentation">
    <a class="nav-link {{ isset($data->exists) ? '' : 'nav_item' }}
                    <?= $secondLastSegment == 'workshop-vehicle-type' ? 'active' : ''?>" href="{{ isset($data->exists) ? route('admin.workshop-vehicle-type.edit', $data->workshop_code) : '#' }}"> Vehicle Type </a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ isset($data->exists) ? '' : 'nav_item' }}
                    <?= $secondLastSegment == 'workshop-image' ? 'active' : ''?>" href="{{ isset($data->exists) ? route('admin.workshop-image.edit', $data->workshop_code) : '#' }}" id="official-tab" role="tab"> Image </a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ isset($data->exists) ? '' : 'nav_item' }}
                    <?= $secondLastSegment == 'workshop-attachment' ? 'active' : ''?>" href="{{ isset($data->exists) ? route('admin.workshop-attachment.edit', $data->workshop_code) : '#' }}" id="official-tab" role="tab"> Attachment </a>
    </li>
</ul>