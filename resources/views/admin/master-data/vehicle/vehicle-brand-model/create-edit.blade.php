@extends('layouts.app')

@section('content')

<div class="header dashboard_from">
    <h1 class="page-title">Vehicle Brand Model</h1>
    <ul class="breadcrumb">
        <li><a href="{{ url('admin/Home') }}">Home</a></li>
        <li><a href="#">/ Master Data</a></li>
        <li><a href="{{ url('admin/MasterData/area') }}">/ Vehicle Brand Model</a></li>
    </ul>
</div>

<div class="main-content">
    <div class="card from_card">
        <div class="card-header">   
            {{ isset($data->exists) ? 'Update' : 'Create' }}
        </div>

        <div class="card-body" x-data="vehicleForm()">
            <form 
                action="{{ isset($data->exists) ? route('admin.modules.master-data.vehicle-brand-model.update', $data->id) : route('admin.modules.master-data.vehicle-brand-model.store') }}"
                method="POST"
                @submit.prevent="submitForm($event)" 
            >
                @csrf
                @if(isset($data->exists)) @method('PUT') @endif

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Brand Name : <span>*</span></label>
                        <select class="form-select" name="depend_on_element" 
                            x-model="formData.depend_on_element"
                            @change="validateField('depend_on_element')"
                            :class="errors.depend_on_element ? 'is-invalid' : ''">
                            <option value="">Select Brand Name</option>
                            @if ($vehicleBrand)
                                @foreach ($vehicleBrand as $value)
                                    <option value="{{ $value->element_code }}">{{ $value->element }}</option>
                                @endforeach
                            @endif
                        </select>

                        <template x-if="errors.depend_on_element">
                            <div class="text-danger small" x-text="errors.depend_on_element"></div>
                        </template>

                        @error('depend_on_element')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Vehicle Brand Model : <span>*</span></label>
                        <input type="text" name="element" class="form-control"
                            x-model="formData.element"
                            @blur="validateField('element')"
                            placeholder="Vehicle Brand Model"
                            :class="errors.element ? 'is-invalid' : ''">
                        
                        <template x-if="errors.element">
                            <div class="text-danger small" x-text="errors.element"></div>
                        </template>
                        
                        @error('element')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Vehicle Brand Model Order :</label>
                        <input type="number" name="element_order" class="form-control"
                            x-model="formData.element_order"
                            placeholder="Enter order"
                            value="{{ old('element_order', $data->element_order ?? '') }}">
                        @error('element_order')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status : <span>*</span></label>
                        <select class="form-select" name="is_active" 
                            x-model="formData.is_active"
                            @change="validateField('is_active')"
                            :class="errors.is_active ? 'is-invalid' : ''">
                            <option value="">Select Status</option>
                            <option value="1" {{ (isset($data->is_active) && $data->is_active == 1) ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ (isset($data->is_active) && $data->is_active == 0) ? 'selected' : '' }}>Inactive</option>
                        </select>

                        <template x-if="errors.is_active">
                            <div class="text-danger small" x-text="errors.is_active"></div>
                        </template>

                        @error('is_active')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="ps-0 card-footer bg-white d-flex gap-2">
                    <button type="submit" class="btn btn-primary" :disabled="isSubmitting">
                        <span x-show="!isSubmitting">{{ isset($data->exists) ? 'Update' : 'Save' }}</span>
                        <span x-show="isSubmitting">Processing...</span>
                    </button> 

                    <a href="{{ route('admin.modules.master-data.vehicle-brand-model.index') }}" class="btn btn-outline-secondary">
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
            element: "{{ old('element', $data->element ?? '') }}",
            element_order: "{{ old('element_order', $data->element_order ?? '') }}",
            is_active: "{{ old('is_active', $data->is_active ?? '') }}",
            depend_on_element: "{{ old('depend_on_element', $data->depend_on_element ?? '') }}",
        },
        errors: {},
        isSubmitting: false,

        validateField(field) {
            if (field === 'element') {
                this.errors.element = this.formData.element.trim() === '' ? 'Vehicle Type is required.' : '';
            }
            if (field === 'depend_on_element') {
                this.errors.depend_on_element = this.formData.element.trim() === '' ? 'Vehicle Brand is required.' : '';
            }
            if (field === 'is_active') {
                this.errors.is_active = this.formData.is_active === '' ? 'Please select a status.' : '';
            }
        },

        submitForm(event) {
            // Validate all fields before submission
            this.validateField('depend_on_element');
            this.validateField('element');
            this.validateField('is_active');

            // Check if there are any errors
            if (!this.errors.depend_on_element && !this.errors.element && !this.errors.is_active) {
                this.isSubmitting = true;
                event.target.submit(); // Standard Laravel form submit
            }
        }
    }
}
</script>
@endsection