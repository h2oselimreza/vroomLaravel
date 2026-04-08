@php
    $segments = request()->segments();
    $lastSegment = request()->segment(count($segments));
    $secondLastSegment = request()->segment(count($segments) - 1);
    $thirdLastSegment = $segments[1];
@endphp

<ul class="nav nav-tabs mb-4" id="employeeTab" role="tablist">
    @if(isset($data->exists))
        <li class="nav-item" role="presentation">
            <a class="nav-link {{ isset($data->exists) ? '' : 'nav_item' }}
                        <?= $thirdLastSegment == 'admin.workshop-general-info' ? 'active' : ''?>" href="{{ isset($data) ? route('admin.workshop-general-info.edit', $data->workshop_code) : '#' }}" id="personal-tab" role="tab"> General Info </a>
        </li>
    @else
        <li class="nav-item" role="presentation">
            <a class="nav-link {{ isset($data->exists) ? '' : 'nav_item' }}
                        <?= $lastSegment == 'create' ? 'active' : ''?>" href="{{ route('admin.workshop-general-info.create') }}" id="personal-tab" role="tab"> General Info </a>
        </li>
    @endif
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ isset($data->exists) ? '' : 'nav_item' }}
                    <?= $secondLastSegment == 'customer-employee-office' ? 'active' : ''?>" href="{{ isset($data->exists) ? route('admin.customer-employee.office.edit', $data->workshop_code) : '#' }}"> Official </a>
    </li>
    <li class="nav-item" role="presentation">
    <a class="nav-link {{ isset($data->exists) ? '' : 'nav_item' }}
                    <?= $secondLastSegment == 'customer-employee-photo' ? 'active' : ''?>" href="{{ isset($data->exists) ? route('admin.customer-employee.photo.edit', $data->workshop_code) : '#' }}"> Photograph </a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ isset($data->exists) ? '' : 'nav_item' }}
                    <?= $secondLastSegment == 'customer-employee-attachment' ? 'active' : ''?>" href="{{ isset($data->exists) ? route('admin.customer-employee.attachment.edit', $data->workshop_code) : '#' }}" id="official-tab" role="tab"> Attachment </a>
    </li>
</ul>