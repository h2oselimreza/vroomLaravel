@extends('layouts.app')

@section('content')

<div class="header dashboard_from">
    <h1 class="page-title">
        {{ isset($data->exists) ? 'Edit Employee' : 'Add Employee' }}
    </h1>
</div>

@php
    $path = request()->path();
    $lastPart = collect(explode('/', $path))->last();
    $isEdit = isset($data);
@endphp

<div class="container" x-data="employeeValidation()">
    <div class="card shadow">
        <div class="card-body">
            @include('admin.corporate_customer.employee.tab')

            {{-- Laravel Success Message --}}
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Laravel Validation Errors --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="tab-content" id="employeeTabContent">
                <div class="tab-pane fade show active" id="personal" role="tabpanel">
                    
                    <form action="{{ $isEdit ? route('admin.customer-employee.office.update', $data->employee_id) : route('admin.customer-employee.store') }}"
                          method="POST"
                          @submit.prevent="submitForm">

                        @csrf
                        @if($isEdit)
                            @method('PUT')
                        @endif

                        <div class="accordion" id="employeeAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#personalInfo" aria-expanded="true">
                                        Official Information
                                    </button>
                                </h2>

                                <div id="personalInfo" class="accordion-collapse collapse show" data-bs-parent="#employeeAccordion">
                                    <div class="accordion-body">
                                        <div class="row g-3">

                                            {{-- Employee Type --}}
                                            <div class="col-md-6">
                                                <label class="form-label">Employee Type: <span>*</span></label>
                                                <select name="emp_type"
                                                    x-model="formData.emp_type"
                                                    @change="validateField('emp_type')"
                                                    class="form-select @error('emp_type') is-invalid @enderror"
                                                    :class="errors.emp_type ? 'is-invalid' : ''">
                                                    <option value="">-- Select Employee Type --</option>
                                                    <option value="system_manager">System Manager</option>
                                                    <option value="driver">Driver</option>
                                                </select>
                                                <div class="invalid-feedback" x-show="errors.emp_type" x-text="errors.emp_type"></div>
                                                @error('emp_type')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- Designation --}}
                                            <div class="col-md-6">
                                                <label class="form-label">Designation</label>
                                                <input type="text"
                                                    name="designation"
                                                    x-model="formData.designation"
                                                    placeholder="Designation"
                                                    class="form-control @error('designation') is-invalid @enderror">
                                                @error('designation')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- First Joining Date --}}
                                            <div class="col-md-6">
                                                <label class="form-label">First Joining Date : <span>*</span></label>
                                                <input type="text"
                                                    name="first_joining_date"
                                                    id="first_joining_date"
                                                    x-model="formData.first_joining_date"
                                                    @blur="validateField('first_joining_date')"
                                                    placeholder="yyyy-mm-dd"
                                                    class="form-control dateInput @error('first_joining_date') is-invalid @enderror"
                                                    :class="errors.first_joining_date ? 'is-invalid' : ''">
                                                
                                                <div class="invalid-feedback" x-show="errors.first_joining_date" x-text="errors.first_joining_date"></div>
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
                            <button type="submit" class="btn btn-success save_button">
                                {{ $isEdit ? 'Update Employee' : 'Save Employee' }}
                            </button>
                        </div>
                    </form> 
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')

<script>
    function employeeValidation() {
        return {
            formData: {
                // Initialize with PHP values
                emp_type: '{{ old("emp_type", $data->emp_type ?? "") }}',
                designation: '{{ old("designation", $data->designation ?? "") }}',
                first_joining_date: '{{ old("first_joining_date", $data->first_joining_date ?? "") }}',
            },
            errors: {
                emp_type: '',
                first_joining_date: ''
            },
            validateField(field) {
                // Clear previous error
                this.errors[field] = '';

                if (field === 'emp_type') {
                    if (!this.formData.emp_type || this.formData.emp_type.includes('-- Select')) {
                        this.errors.emp_type = 'Please select an employee type.';
                    }
                }

                if (field === 'first_joining_date') {
                    if (!this.formData.first_joining_date) {
                        this.errors.first_joining_date = 'Joining date is required.';
                    }
                }
            },
            submitForm(e) {
                // Re-validate all fields
                this.validateField('emp_type');
                this.validateField('first_joining_date');

                // Check if any error message exists
                const hasErrors = Object.values(this.errors).some(msg => msg !== '');

                if (!hasErrors) {
                    // Use native form submission
                    e.target.submit();
                } else {
                    // Optional: Scroll to the first error
                    console.log("Validation failed:", this.errors);
                }
            }
        }
    }

    $(document).ready(function(){
        // Initialize Datepicker
        const $dateInput = $('.dateInput');
        
        $dateInput.datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true,
            clearBtn: true,
            orientation: 'bottom'
        }).on('changeDate', function(e) {
            let dateVal = $(this).val();
            
            // Get the container element that has x-data
            let alpineEl = document.querySelector('[x-data]');
            
            if (alpineEl && window.Alpine) {
                // Use Alpine's public API to get and update data
                let data = Alpine.$data(alpineEl);
                data.formData.first_joining_date = dateVal;
                data.errors.first_joining_date = ''; // Clear error immediately
            }
            
            // Trigger a native input event so x-model picks it up as a backup
            this.dispatchEvent(new Event('input', { bubbles: true }));
        });
    });
</script>
@endpush