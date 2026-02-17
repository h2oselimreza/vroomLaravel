@extends('layouts.app')

@section('content')

<div class="header dashboard_from">
    <h1 class="page-title">
        {{ isset($data->exists) ? 'Edit Employee' : 'Add Employee' }}
    </h1>
</div>
@php
    $path = request()->path(); // returns 'admin/employee-office-info'
    $lastPart = collect(explode('/', $path))->last();
@endphp
<div class="container">
    <div class="card shadow">
        <div class="card-body">
            <!-- Nav Tabs -->
            <ul class="nav nav-tabs mb-4" id="employeeTab" role="tablist">
                @if(isset($data->id))
                    <li class="nav-item" role="presentation">
                        <a class="nav-link 
                                    <?= $lastPart=='create' ? 'active' : ''?>" href="{{ isset($data) ? route('admin.employee.module.edit', $data->id) : '#' }}" id="personal-tab" role="tab"> Personal </a>
                    </li>
                @else
                    <li class="nav-item" role="presentation">
                        <a class="nav-link 
                                    <?= $lastPart=='create' ? 'active' : ''?>" href="{{ route('admin.employee.module.create') }}" id="personal-tab" role="tab"> Personal </a>
                    </li>
                @endif
                <li class="nav-item" role="presentation">
                    <a class="nav-link 
                                <?= $lastPart=='employee-office-info' ? 'active' : ''?>" href="{{ isset($data) ? route('admin.employee.office.edit', $data->id) : '#' }}" id="official-tab" role="tab"> Official </a>
                </li>
                <li class="nav-item" role="presentation">
                <a class="nav-link 
                                <?= $lastPart=='employee-education-info' ? 'active' : ''?>" href="{{ isset($data) ? route('admin.employee.education.edit', $data->id) : '#' }}" id="official-tab" role="tab"> Education </a>
                </li>
                <li class="nav-item" role="presentation">
                <button class="nav-link" id="experience-tab" data-bs-toggle="tab" data-bs-target="#experience" type="button" role="tab"> Working Experience </button>
                </li>
                <li class="nav-item" role="presentation">
                <button class="nav-link" id="photo-tab" data-bs-toggle="tab" data-bs-target="#photo" type="button" role="tab"> Photograph </button>
                </li>
            </ul>

            {{-- Success Message --}}
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Tab Content -->
            <div class="tab-content" id="employeeTabContent">

                <div class="tab-pane fade show active"
                    id="official"
                    role="tabpanel">
                    <form action="{{ route('admin.employee.office.update',$data->id) }}" method="POST">
                        @csrf
                        @if(isset($data))
                            @method('PUT')
                        @endif

                        <div class="accordion" id="employeeAccordion">

                            {{-- Personal Information --}}
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#personalInfo"
                                            aria-expanded="true">
                                        Official Information
                                    </button>
                                </h2>

                                <div id="personalInfo"
                                    class="accordion-collapse collapse show"
                                    data-bs-parent="#employeeAccordion">

                                    <div class="accordion-body">

                                        <div class="row g-3">

                                            <div class="col-md-12">
                                                <div class="form-check custom-check">
                                                    <input class="form-check-input"
                                                        type="checkbox"
                                                        id="system_user"
                                                        name="system_user"
                                                        value="1"
                                                        {{ old('system_user', $data->system_user ?? 0) ? 'checked' : '' }}>
                                                        

                                                    <label class="form-check-label ms-2 form-label mt-1"
                                                        for="system_user">
                                                        Enable System User
                                                    </label>
                                                </div>

                                                @error('system_user')
                                                    <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- Designation --}}
                                            <div class="col-md-6">
                                                <label class="form-label">Designation</label>
                                                <select name="designation"
                                                    class="form-control @error('designation') is-invalid @enderror">
                                                    <option value="">-- Select Designation--</option>

                                                    @php
                                                        $designations = [
                                                            'computer_operator' => 'Computer Operator',
                                                            'office_assistance' => 'Office Assistance'
                                                        ];
                                                    @endphp

                                                    @foreach($designations as $value => $label)
                                                        <option value="{{ $value }}" 
                                                            {{ old('designation', $data->designation ?? '') == $value ? 'selected' : '' }}>
                                                            {{ $label }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                @error('designation')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            {{-- First Joining date --}}
                                            <div class="col-md-6">
                                                <label class="form-label">First Joining Date</label>
                                                <input type="text"
                                                    name="first_joining_date"
                                                     value="{{ old('first_joining_date', $data->first_joining_date ?? '') }}"
                                                    placeholder="yyyy-mm-dd"
                                                    class="form-control dateInput @error('first_joining_date') is-invalid @enderror">

                                                @error('first_joining_date')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-success">
                            Update Office Info
                            </button>
                        </div>
                    </form>
                </div>

                <div class="tab-pane fade"
                    id="education"
                    role="tabpanel">
                    <h5>Education Information</h5>
                </div>

                <div class="tab-pane fade"
                    id="experience"
                    role="tabpanel">
                    <h5>Working Experience</h5>
                </div>

                <div class="tab-pane fade"
                    id="photo"
                    role="tabpanel">
                    <h5>Photograph Upload</h5>
                </div>

            </div>

        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function(){
        $('.dateInput').datepicker({
            format: 'yyyy-mm-dd',  // format compatible with Laravel date column
            autoclose: true,       // close picker after selecting a date
            todayHighlight: true,  // highlight today
            clearBtn: true,        // optional clear button
            orientation: 'bottom'  // show below the input
        });
    });
</script>
@endpush
