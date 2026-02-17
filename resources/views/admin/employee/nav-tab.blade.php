@php
    $segments = request()->segments();
    $lastSegment = request()->segment(count($segments));
    $secondLastSegment = request()->segment(count($segments) - 1);
    $thirdLastSegment = request()->segment(count($segments) - 2);  
@endphp

<ul class="nav nav-tabs mb-4" id="employeeTab" role="tablist">
    @if(isset($data->exists))
        <li class="nav-item" role="presentation">
            <a class="nav-link 
                        <?= $thirdLastSegment == 'employees' ? 'active' : ''?>" href="{{ isset($data) ? route('admin.employee.module.edit', $data->id) : '#' }}" id="personal-tab" role="tab"> Personal </a>
        </li>
    @else
        <li class="nav-item" role="presentation">
            <a class="nav-link 
                        <?= $lastSegment == 'create' ? 'active' : ''?>" href="{{ route('admin.employee.module.create') }}" id="personal-tab" role="tab"> Personal </a>
        </li>
    @endif
    <li class="nav-item" role="presentation">
        <a class="nav-link 
                    <?= $secondLastSegment == 'employee-office-info' ? 'active' : ''?>" href="{{ isset($data->exists) ? route('admin.employee.office.edit', $data->id) : '#' }}"> Official </a>
    </li>
    <li class="nav-item" role="presentation">
    <a class="nav-link 
                    <?= $secondLastSegment == 'employee-education-info' ? 'active' : ''?>" href="{{ isset($data->exists) ? route('admin.employee.education.edit', $data->id) : '#' }}"> Education </a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link 
                    <?= $secondLastSegment == 'working-experience-info' ? 'active' : ''?>" href="{{ isset($data->exists) ? route('admin.working.experience.edit', $data->id) : '#' }}" id="official-tab" role="tab"> Working Experience </a>
    </li>
    <li class="nav-item" role="presentation">
    <button class="nav-link" id="photo-tab" data-bs-toggle="tab" data-bs-target="#photo" type="button" role="tab"> Photograph </button>
    </li>
</ul>