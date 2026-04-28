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
                        <?= $secondLastSegment == 'place-info' ? 'active' : ''?>" href="{{ isset($data) ? route('admin.place.place-info.edit', $data->place_code) : '#' }}" id="personal-tab" role="tab"> General Info </a>
        </li>
    @else
        <li class="nav-item" role="presentation">
            <a class="nav-link {{ isset($data->exists) ? '' : 'nav_item' }}
                        <?= $lastSegment == 'create' ? 'active' : ''?>" href="{{ route('admin.place.place-info.create') }}" id="personal-tab" role="tab"> General Info </a>
        </li>
    @endif
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ isset($data->exists) ? '' : 'nav_item' }}
                    <?= $secondLastSegment == 'place-time-schedule' ? 'active' : ''?>" href="{{ isset($data->exists) ? route('admin.place.place-time-schedule.edit', $data->place_code) : '#' }}"> Time Schedule </a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ isset($data->exists) ? '' : 'nav_item' }}
                    <?= $secondLastSegment == 'workshop-image' ? 'active' : ''?>" href="{{ isset($data->exists) ? route('admin.workshop-image.edit', $data->place_code) : '#' }}" id="official-tab" role="tab"> Image </a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ isset($data->exists) ? '' : 'nav_item' }}
                    <?= $secondLastSegment == 'workshop-attachment' ? 'active' : ''?>" href="{{ isset($data->exists) ? route('admin.workshop-attachment.edit', $data->place_code) : '#' }}" id="official-tab" role="tab"> Attachment </a>
    </li>
</ul>