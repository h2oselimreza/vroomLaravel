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
                        <?= $thirdLastSegment == 'customer-employee-edit' ? 'active' : ''?>" href="{{ isset($data) ? route('admin.customer-employee.edit', $data->employee_id) : '#' }}" id="personal-tab" role="tab"> Personal </a>
        </li>
    @else
        <li class="nav-item" role="presentation">
            <a class="nav-link {{ isset($data->exists) ? '' : 'nav_item' }}
                        <?= $lastSegment == 'create' ? 'active' : ''?>" href="{{ route('admin.customer-employee.create') }}" id="personal-tab" role="tab"> Personal </a>
        </li>
    @endif
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ isset($data->exists) ? '' : 'nav_item' }}
                    <?= $secondLastSegment == 'customer-employee-office' ? 'active' : ''?>" href="{{ isset($data->exists) ? route('admin.customer-employee.office.edit', $data->employee_id) : '#' }}"> Official </a>
    </li>
    <li class="nav-item" role="presentation">
    <a class="nav-link {{ isset($data->exists) ? '' : 'nav_item' }}
                    <?= $secondLastSegment == 'customer-employee-photo' ? 'active' : ''?>" href="{{ isset($data->exists) ? route('admin.customer-employee.photo.edit', $data->employee_id) : '#' }}"> Photograph </a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ isset($data->exists) ? '' : 'nav_item' }}
                    <?= $secondLastSegment == 'customer-employee-attachment' ? 'active' : ''?>" href="{{ isset($data->exists) ? route('admin.customer-employee.attachment.edit', $data->employee_id) : '#' }}" id="official-tab" role="tab"> Attachment </a>
    </li>
</ul>