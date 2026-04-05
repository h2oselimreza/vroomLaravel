@extends('layouts.app')

@section('content')

<div class="header dashboard_from">
    <h1 class="page-title">Appointment Service</h1>
    <ul class="breadcrumb">
        <li><a href="{{ url('admin/Home') }}">Home</a></li>
        <li><a href="#">/ Master Data</a></li>
        <li><a href="#">/ Appointment Service</a></li>
    </ul>
</div>

<div class="main-content">
    <div class="card from_card">
        <div class="card-header">   
            {{ isset($data->exists) ? 'Update' : 'Create' }}
        </div>

        <div class="card-body" x-data="vehicleForm()">
            <form 
                action="{{ isset($data->exists) ? route('admin.modules.master-data.service-list.update', $data->service_code) : route('admin.modules.master-data.service-list.store') }}"
                method="POST"
                @submit.prevent="submitForm($event)" 
            >
                @csrf
                @if(isset($data->exists)) @method('PUT') @endif

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Service Category : <span>*</span></label>
                        <select class="form-select" name="service_category" 
                            x-model="formData.service_category"
                            @change="validateField('service_category')"
                            :class="errors.service_category ? 'is-invalid' : ''">
                            <option>--- Select Category ---</option>
                            @if ($categories)
                                @foreach ($categories as $value)
                                    <option value="{{ $value->category_code }}">{{ $value->category_name }}</option>
                                @endforeach
                            @endif
                        </select>

                        <template x-if="errors.service_category">
                            <div class="text-danger small" x-text="errors.service_category"></div>
                        </template>

                        @error('service_category')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Service Name : <span>*</span></label>
                        <input type="text" name="service_name" class="form-control"
                            x-model="formData.service_name"
                            @blur="validateField('service_name')"
                            placeholder="Service name"
                            :class="errors.service_name ? 'is-invalid' : ''">
                        
                        <template x-if="errors.service_name">
                            <div class="text-danger small" x-text="errors.service_name"></div>
                        </template>
                        
                        @error('service_name')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="ps-0 card-footer bg-white d-flex gap-2">
                    <button type="submit" class="btn btn-primary" :disabled="isSubmitting">
                        <span x-show="!isSubmitting">{{ isset($data->exists) ? 'Update' : 'Save' }}</span>
                        <span x-show="isSubmitting">Processing...</span>
                    </button> 

                    <a href="{{ route('admin.modules.master-data.service-list.index') }}" class="btn btn-outline-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function vehicleForm() {
    return {
        formData: {
            service_name: "{{ old('service_name', $data->service_name ?? '') }}",
            service_category: "{{ old('service_category', $data->service_category ?? '') }}",
        },
        errors: {},
        isSubmitting: false,

        validateField(field) {
            if (field === 'service_name') {
                this.errors.service_name = this.formData.service_name.trim() === '' ? 'Service name is required.' : '';
            }
            if (field === 'service_category') {
                this.errors.service_category = this.formData.service_category.trim() === '' ? 'Service category is required.' : '';
            }
        },

        submitForm(event) {
            // Validate all fields before submission
            this.validateField('service_name');
            this.validateField('service_category');

            // Check if there are any errors
            if (!this.errors.service_name && !this.errors.service_category) {
                this.isSubmitting = true;
                event.target.submit(); // Standard Laravel form submit
            }
        }
    }
}
</script>
@endsection