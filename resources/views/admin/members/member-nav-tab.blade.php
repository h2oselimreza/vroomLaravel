@php
    $segments = request()->segments();
    $lastSegment = request()->segment(count($segments));
    $secondLastSegment = request()->segment(count($segments) - 1);
    $thirdLastSegment = request()->segment(count($segments) - 2);  
@endphp

<ul class="nav nav-tabs mb-4" id="employeeTab" role="tablist">
    @if(isset($data->exists))
        <li class="nav-item" role="presentation">
            <a class="nav-link {{ isset($data->exists) ? '' : 'nav_item' }}
                        <?= $thirdLastSegment == 'employees' ? 'active' : ''?>" href="{{ isset($data) ? route('admin.member.module.edit', $data->id) : '#' }}" id="personal-tab" role="tab"> Personal </a>
        </li>
    @else
        <li class="nav-item" role="presentation">
            <a class="nav-link {{ isset($data->exists) ? '' : 'nav_item' }}
                        <?= $lastSegment == 'members-create' ? 'active' : ''?>" href="{{ route('admin.member.module.create') }}" id="personal-tab" role="tab"> Personal </a>
        </li>
    @endif
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ isset($data->exists) ? '' : 'nav_item' }}
                    <?= $secondLastSegment == 'employee-office-info' ? 'active' : ''?>" href="{{ isset($data->exists) ? route('admin.member.module.otherFamily.index', $data->id) : '#' }}"> Other Family Member </a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ isset($data->exists) ? '' : 'nav_item' }}
                    <?= $secondLastSegment == 'employee-office-info' ? 'active' : ''?>" href="{{ isset($data->exists) ? route('admin.member.module.office.index', $data->id) : '#' }}"> Office </a>
    </li>
    <li class="nav-item" role="presentation">
    <a class="nav-link {{ isset($data->exists) ? '' : 'nav_item' }}
                    <?= $secondLastSegment == 'employee-education-info' ? 'active' : ''?>" href="{{ isset($data->exists) ? route('admin.employee.education.edit', $data->id) : '#' }}"> Education </a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ isset($data->exists) ? '' : 'nav_item' }}
                    <?= $secondLastSegment == 'working-experience-info' ? 'active' : ''?>" href="{{ isset($data->exists) ? route('admin.working.experience.edit', $data->id) : '#' }}" id="official-tab" role="tab"> Working Experience </a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ isset($data->exists) ? '' : 'nav_item' }}
            <?= $secondLastSegment == 'profile-photo-info' ? 'active' : ''?>" href="{{ isset($data->exists) ? route('admin.profile.photo.edit', $data->id) : '#' }}"> Photograph </a>
    </li>
</ul>