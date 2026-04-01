@extends('layouts.app')

@section('content')

<div class="header dashboard_from">
    <h1 class="page-title">Fuel</h1>
    <ul class="breadcrumb">
        <li><a href="{{ url('admin/Home') }}">Home</a></li>
        <li><a href="#">/ Master Data</a></li>
        <li><a href="{{ url('admin/MasterData/area') }}">/ Fuel</a></li>
    </ul>
</div>

<div class="main-content">
    <div class="card from_card">
        <div class="card-header">
            {{ isset($data->exists) ? 'Update Fuel' : 'Create Fuel' }}
        </div>

        <div class="card-body" x-data="FuelForm()">
            <form 
                action="{{ isset($data->exists) ? route('admin.module.master-data.fuel.update', $data->id) : route('admin.module.master-data.fuel.store') }}"
                method="POST"
                @submit.prevent="submitForm($event)"
            >
                @csrf
                @if(isset($data->exists)) @method('PUT') @endif

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Fuel Name : <span>*</span></label>
                        <input type="text" name="fuel_name" class="form-control"
                            x-model="formData.fuel_name"
                            @blur="validateField('fuel_name')"
                            :class="errors.fuel_name ? 'is-invalid' : ''"
                            placeholder="Fuel name">
                        
                        <template x-if="errors.fuel_name">
                            <div class="text-danger small" x-text="errors.fuel_name"></div>
                        </template>

                        @error('fuel_name')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Fuel Rate : <span>*</span></label>
                        <input type="text" name="fuel_rate" class="form-control"
                            x-model="formData.fuel_rate"
                            @blur="validateField('fuel_rate')"
                            :class="errors.fuel_rate ? 'is-invalid' : ''"
                            placeholder="Fuel rate">

                        <template x-if="errors.fuel_rate">
                            <div class="text-danger small" x-text="errors.fuel_rate"></div>
                        </template>

                        @error('fuel_rate')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="ps-0 card-footer bg-white d-flex gap-2">
                    <button type="submit" class="btn btn-primary" :disabled="isSubmitting">
                        <span x-show="!isSubmitting">{{ isset($data->exists) ? 'Update' : 'Save' }}</span>
                        <span x-show="isSubmitting">Processing...</span>
                    </button> 

                    <a href="{{ route('admin.module.master-data.fuel.index') }}" class="btn btn-outline-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function FuelForm() {
    return {
        formData: {
            fuel_rate: "{{ old('fuel_rate', $data->fuel_rate ?? '') }}",
            fuel_name: "{{ old('fuel_name', $data->fuel_name ?? '') }}",
        },
        errors: {},
        isSubmitting: false,

        validateField(field) {
            if (field === 'fuel_rate') {
                this.errors.fuel_rate = this.formData.fuel_rate.trim() === '' ? 'Fuel rate is required.' : '';
            }
            if (field === 'fuel_name') {
                this.errors.fuel_name = this.formData.fuel_name.trim() === '' ? 'Fuel Name is required.' : '';
            }
        },

        submitForm(event) {
            // Validate all fields
            this.validateField('fuel_name');
            this.validateField('fuel_rate');

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