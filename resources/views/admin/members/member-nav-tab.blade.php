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
                        <?= $thirdLastSegment == 'members' ? 'active' : ''?>" href="{{ isset($data) ? route('admin.member.module.edit', $data->id) : '#' }}" id="personal-tab" role="tab"> Personal </a>
        </li>
    @else
        <li class="nav-item" role="presentation">
            <a class="nav-link {{ isset($data->exists) ? '' : 'nav_item' }}
                        <?= $lastSegment == 'member-other-family' ? 'active' : ''?>" href="{{ route('admin.member.module.create') }}" id="personal-tab" role="tab"> Personal </a>
        </li>
    @endif
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ isset($data->exists) ? '' : 'nav_item' }}
                    <?= $secondLastSegment == 'member-other-family' ? 'active' : ''?>" href="{{ isset($data->exists) ? route('admin.member.module.otherFamily.index', $data->id) : '#' }}"> Other Family Member </a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ isset($data->exists) ? '' : 'nav_item' }}
                    <?= $secondLastSegment == 'member-office' ? 'active' : ''?>" href="{{ isset($data->exists) ? route('admin.member.module.office.index', $data->id) : '#' }}"> Office </a>
    </li>
    <li class="nav-item" role="presentation">
    <a class="nav-link {{ isset($data->exists) ? '' : 'nav_item' }}
                    <?= $secondLastSegment == 'member-education' ? 'active' : ''?>" href="{{ isset($data->exists) ? route('admin.member.module.education.index', $data->id) : '#' }}"> Education </a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ isset($data->exists) ? '' : 'nav_item' }}
                    <?= $secondLastSegment == 'member-working-experience' ? 'active' : ''?>" href="{{ isset($data->exists) ? route('admin.member.working.experience.edit', $data->id) : '#' }}" id="official-tab" role="tab"> Working Experience </a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ isset($data->exists) ? '' : 'nav_item' }}
            <?= $secondLastSegment == 'member-profile-photo' ? 'active' : ''?>" href="{{ isset($data->exists) ? route('admin.member.photo.edit', $data->id) : '#' }}"> Photograph </a>
    </li>
</ul>