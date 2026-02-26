@extends('layouts.app')

@section('content')

<div class="header dashboard_from">
    <h1 class="page-title">
        Update Photo
    </h1>
    <ul class="breadcrumb">
        <li><a href="#"> Home</a></li>
        <li><a href="#">/ Master Data</a></li>
        <li><a href="#">/ Member</a></li>
    </ul>
</div>
<div class="container">
    <div class="card shadow">
        <div class="card-body">
            <!-- Nav Tabs -->
            @include('admin.members.member-nav-tab')
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
                    <form action="{{ route('admin.member.photo.update',$data->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="accordion" id="employeeAccordion">
                            {{-- Personal Information --}}
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#personalInfo"
                                            aria-expanded="true">
                                        Photograph
                                    </button>
                                </h2>

                                <div id="personalInfo"
                                    class="accordion-collapse collapse show"
                                    data-bs-parent="#employeeAccordion">
                                    <div class="accordion-body">
                                        <div class="row g-3">
                                            <div class="col-md-4"></div>
                                            <div class="col-md-4">
                                                <div class="d-flex flex-column align-items-center text-center">

                                                    <label class="mb-2">Image (300px X 300px)</label>

                                                    {{-- Show Current Image --}}
                                                    @if($data->member_image)
                                                        <img border="1"
                                                            id="blah"
                                                            src="{{ asset('storage/member/' . $data->member_image) }}"
                                                            class="profile-image mb-3">
                                                    @else
                                                        <img border="1"
                                                            id="blah"
                                                            src="{{ asset('assets/images/company/no_image.jpg') }}"
                                                            class="profile-image mb-3">
                                                    @endif

                                                    <input type="file"
                                                        class="form-control w-auto"
                                                        name="member_image"
                                                        id="aboutImage"
                                                        onchange="imageShow(this, this.id);"
                                                        required>

                                                    <input type="hidden" name="old_image" value="{{ $data->member_image }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-success profile_photo save_button">
                                Update Photo
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
