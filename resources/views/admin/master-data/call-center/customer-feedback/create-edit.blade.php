@extends('layouts.app')

@section('content')

<div class="header dashboard_from">
    <h1 class="page-title">Customer Feedback</h1>
    <ul class="breadcrumb">
        <li><a href="{{ url('admin/Home') }}">Home</a></li>
        <li><a href="#">/ Master Data</a></li>
        <li><a href="{{ url('admin/master-data/call-reason') }}">/ Customer Feedback</a></li>
    </ul>
</div>

<div class="main-content">
    <div class="card from_card">
        <div class="card-header">   
            {{ isset($data->exists) ? 'Update' : 'Create' }}
        </div>

        <div class="card-body" x-data="vehicleForm()">
            <form 
                action="{{ isset($data->exists) ? route('admin.module.master-data.customer-feedback.update', $data->feedback_code) : route('admin.module.master-data.customer-feedback.store') }}"
                method="POST"
                @submit.prevent="submitForm($event)" 
            >
                @csrf
                @if(isset($data->exists)) @method('PUT') @endif

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Call Type : <span>*</span></label>
                        <select class="form-select" name="call_type" 
                            x-model="formData.call_type"
                            @change="validateField('call_type')"
                            :class="errors.call_type ? 'is-invalid' : ''">
                            <option value="">Select Type</option>
                            <option value="inbound" {{ (isset($data->call_type) && $data->call_type == 'inbound') ? 'selected' : '' }}>Inbound</option>
                            <option value="outbound" {{ (isset($data->call_type) && $data->call_type == 'outbound') ? 'selected' : '' }}>Outbound</option>
                        </select>

                        <template x-if="errors.call_type">
                            <div class="text-danger small" x-text="errors.call_type"></div>
                        </template>

                        @error('call_type')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Reason Title : <span>*</span></label>
                        <input type="text" name="title" class="form-control"
                            x-model="formData.title"
                            @blur="validateField('title')"
                            placeholder="Reason title"
                            :class="errors.title ? 'is-invalid' : ''">
                        
                        <template x-if="errors.title">
                            <div class="text-danger small" x-text="errors.title"></div>
                        </template>
                        
                        @error('title')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Feedback Order :</label>
                        <input type="number" name="feedback_order" class="form-control"
                            x-model="formData.feedback_order"
                            placeholder="Enter order"
                            value="{{ old('feedback_order', $data->feedback_order ?? '') }}">
                        @error('feedback_order')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Description :</label>
                        <textarea 
                            class="form-control" 
                            name="description" 
                            id="description" 
                            x-model="formData.description"
                        >{{ old('description', $data->description ?? '') }}</textarea>
                        @error('description')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="ps-0 card-footer bg-white d-flex gap-2">
                    <button type="submit" class="btn btn-primary" :disabled="isSubmitting">
                        <span x-show="!isSubmitting">{{ isset($data->exists) ? 'Update' : 'Save' }}</span>
                        <span x-show="isSubmitting">Processing...</span>
                    </button> 

                    <a href="{{ route('admin.module.master-data.call-reason.index') }}" class="btn btn-outline-secondary">
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
            call_type: "{{ old('call_type', $data->call_type ?? '') }}",
            title: "{{ old('title', $data->title ?? '') }}",
            feedback_order: "{{ old('feedback_order', $data->feedback_order ?? '') }}",
            description: "{{ old('description', $data->description ?? '') }}",
        },
        errors: {},
        isSubmitting: false,

        validateField(field) {
            if (field === 'call_type') {
                this.errors.call_type = this.formData.call_type.trim() === '' ? 'Call type is required.' : '';
            }
            if (field === 'title') {
                this.errors.title = this.formData.title === '' ? 'Title is required.' : '';
            }
        },

        submitForm(event) {
            // Validate all fields before submission
            this.validateField('call_type');
            this.validateField('title');

            // Check if there are any errors
            if (!this.errors.call_type && !this.errors.title) {
                this.isSubmitting = true;
                event.target.submit(); // Standard Laravel form submit
            }
        }
    }
}
</script>
@endsection