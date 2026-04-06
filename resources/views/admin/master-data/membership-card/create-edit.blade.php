@extends('layouts.app')

@section('content')

<div class="header dashboard_from">
    <h1 class="page-title">Add Membership Card</h1>
    <ul class="breadcrumb">
        <li><a href="{{ url('admin/Home') }}">Home</a></li>
        <li><a href="#">/ Master Data</a></li>
        <li><a href="#">/ Add Membership Card</a></li>
    </ul>
</div>

<div class="main-content">
    <!-- Error Message -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    <div class="card from_card">
        <div class="card-header">
            {{ isset($data->exists) ? 'Update Membership Card' : 'Create Membership Card' }}
        </div>

        <div class="card-body" x-data="packageForm()">
            <form 
                action="{{ isset($data->exists) ? route('admin.module.master-data.member-ship-card.update', $data->id) : route('admin.module.master-data.member-ship-card.store') }}"
                method="POST"
                @submit.prevent="submitForm($event)"
            >
                @csrf
                @if(isset($data->exists)) @method('PUT') @endif

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Card Number : <span>*</span></label>
                        <input type="text" name="card_number" class="form-control"
                            x-model="formData.card_number"
                            @blur="validateField('card_number')"
                            :class="errors.card_number ? 'is-invalid' : ''"
                            placeholder="Card Number">
                        
                        <template x-if="errors.card_number">
                            <div class="text-danger small" x-text="errors.card_number"></div>
                        </template>

                        @error('card_number')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Validity month : <span>*</span></label>
                        <input type="number" name="validity_month" class="form-control"
                            x-model="formData.validity_month"
                            @blur="validateField('validity_month')"
                            :class="errors.validity_month ? 'is-invalid' : ''"
                            placeholder="Enter validity month">

                        <template x-if="errors.validity_month">
                            <div class="text-danger small" x-text="errors.validity_month"></div>
                        </template>

                        @error('validity_month')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="ps-0 card-footer bg-white d-flex gap-2">
                    <button type="submit" class="btn btn-primary" :disabled="isSubmitting">
                        <span x-show="!isSubmitting">{{ isset($data->exists) ? 'Update' : 'Save' }}</span>
                        <span x-show="isSubmitting">Processing...</span>
                    </button> 

                    <a href="{{ route('admin.module.master-data.member-ship-card.index') }}" class="btn btn-outline-secondary">
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
            card_number: "{{ old('card_number', $data->card_number ?? '') }}",
            validity_month: "{{ old('validity_month', $data->validity_month ?? '') }}", // Note: match your select alias
        },
        errors: {},
        isSubmitting: false,

        validateField(field) {
            if (field === 'validity_month') {
                this.errors.validity_month = this.formData.validity_month.trim() === '' ? 'Validity month is required.' : '';
            }
            if (field === 'card_number') {
                this.errors.card_number = (this.formData.card_number === '' || this.formData.card_number < 1) ? 'Card number is required.' : '';
            }
        },

        submitForm(event) {
            // Validate all fields
            this.validateField('card_number');
            this.validateField('validity_month');

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