@extends('layouts.app')

@section('content')

<div class="header dashboard_from">
    <h1 class="page-title">Package</h1>
    <ul class="breadcrumb">
        <li><a href="{{ url('admin/Home') }}">Home</a></li>
        <li><a href="#">/ Master Data</a></li>
        <li><a href="{{ url('admin/MasterData/area') }}">/ Package</a></li>
    </ul>
</div>

<div class="main-content">
    <div class="card from_card">
        <div class="card-header">
            {{ isset($data->exists) ? 'Update Package' : 'Create Package' }}
        </div>

        <div class="card-body" x-data="packageForm()">
            <form 
                action="{{ isset($data->exists) ? route('admin.module.master-data.package.update', $data->id) : route('admin.module.master-data.package.store') }}"
                method="POST"
                @submit.prevent="submitForm($event)"
            >
                @csrf
                @if(isset($data->exists)) @method('PUT') @endif

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Package Name : <span>*</span></label>
                        <input type="text" name="package_name" class="form-control"
                            x-model="formData.package_name"
                            @blur="validateField('package_name')"
                            :class="errors.package_name ? 'is-invalid' : ''"
                            placeholder="Package name">
                        
                        <template x-if="errors.package_name">
                            <div class="text-danger small" x-text="errors.package_name"></div>
                        </template>

                        @error('package_name')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Max User : <span>*</span></label>
                        <input type="number" name="maxUser" class="form-control"
                            x-model="formData.maxUser"
                            @blur="validateField('maxUser')"
                            :class="errors.maxUser ? 'is-invalid' : ''"
                            placeholder="Max user">

                        <template x-if="errors.maxUser">
                            <div class="text-danger small" x-text="errors.maxUser"></div>
                        </template>

                        @error('maxUser')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Max Vehicle : <span>*</span></label>
                        <input type="number" name="maxVehicle" class="form-control"
                            x-model="formData.maxVehicle"
                            @blur="validateField('maxVehicle')"
                            :class="errors.maxVehicle ? 'is-invalid' : ''"
                            placeholder="Max vehicle">

                        <template x-if="errors.maxVehicle">
                            <div class="text-danger small" x-text="errors.maxVehicle"></div>
                        </template>

                        @error('maxVehicle')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="ps-0 card-footer bg-white d-flex gap-2">
                    <button type="submit" class="btn btn-primary" :disabled="isSubmitting">
                        <span x-show="!isSubmitting">{{ isset($data->exists) ? 'Update' : 'Save' }}</span>
                        <span x-show="isSubmitting">Processing...</span>
                    </button> 

                    <a href="{{ route('admin.module.master-data.package.index') }}" class="btn btn-outline-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function packageForm() {
    return {
        formData: {
            package_name: "{{ old('package_name', $data->package_name ?? '') }}",
            maxUser: "{{ old('maxUser', $data->max_user ?? '') }}", // Note: match your select alias
            maxVehicle: "{{ old('maxVehicle', $data->max_vehicle ?? '') }}",
        },
        errors: {},
        isSubmitting: false,

        validateField(field) {
            if (field === 'package_name') {
                this.errors.package_name = this.formData.package_name.trim() === '' ? 'Package name is required.' : '';
            }
            if (field === 'maxUser') {
                this.errors.maxUser = (this.formData.maxUser === '' || this.formData.maxUser < 1) ? 'Enter a valid number of users.' : '';
            }
            if (field === 'maxVehicle') {
                this.errors.maxVehicle = (this.formData.maxVehicle === '' || this.formData.maxVehicle < 1) ? 'Enter a valid number of vehicles.' : '';
            }
        },

        submitForm(event) {
            // Validate all fields
            this.validateField('package_name');
            this.validateField('maxUser');
            this.validateField('maxVehicle');

            // Check if errors object has any messages
            const hasErrors = Object.values(this.errors).some(error => error !== '');

            if (!hasErrors) {
                this.isSubmitting = true;
                event.target.submit();
            }
        }
    }
}
</script>
@endsection