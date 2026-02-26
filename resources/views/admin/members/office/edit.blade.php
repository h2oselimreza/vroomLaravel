@extends('layouts.app')

@section('content')

<div class="header dashboard_from">
    <h1 class="page-title">
        Update Office information
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
                    <form action="{{ route('admin.member.module.office.update',$data->id) }}" method="POST">
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
                                            {{-- ddd --}}
                                            <div class="col-md-6">
                                                <label class="form-label">Member Type</label>
                                                <select name="member_type" id="memberType"
                                                    onchange="showDonarMember()"
                                                    class="form-control @error('member_type') is-invalid @enderror">

                                                    <option value="">--Select Member Type--</option>

                                                    @php
                                                        $designations = [
                                                            'life' => 'Life',
                                                            'donate' => 'Donate'
                                                        ];
                                                    @endphp

                                                    @foreach($designations as $value => $label)
                                                        <option value="{{ $value }}"
                                                            {{ old('member_type', $data->member_type ?? '') == $value ? 'selected' : '' }}>
                                                            {{ $label }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                @error('designation')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <?php 
                                                $donarMemberDiv = 'none';
                                                if($data->member_type == \App\Enums\MemberType::DONATE->value){
                                                    $donarMemberDiv = "block";
                                                } ?>

                                                <div class="col-md-6 col-sm-6 col-xs-12" id="donarMemberDiv" style="display: <?php echo $donarMemberDiv?>;">
                                                    <div class="form-group" >
                                                        <label class="form-label"> Donar Member ID </label>
                                                        <input type="text" class="form-control" id="donarMemberId" name="donarMemberId" value="{{ $data->donar_member_id }}" placeholder="Donar Member ID">
                                                    </div>
                                                </div>

                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <div class="form-group">
                                                    <label class="form-label"> Membership Proposed By </label>
                                                    <input 
                                                        type="text" 
                                                        class="form-control ui-autocomplete-input" 
                                                        id="firstIntroducedBy" 
                                                        name="first_introduced_by"
                                                        value="{{ old('first_introduced_by', $data->first_introduced_by ?? '') }}"
                                                        placeholder="First introduced by" 
                                                        >
                                                    @error('first_introduced_by')
                                                        <div class="text-danger mt-1">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <div class="form-group">
                                                    <label class="form-label"> Membership Seconded By </label>
                                                    <input 
                                                        type="text" 
                                                        class="form-control ui-autocomplete-input" 
                                                        id="secondIntroducedBy" 
                                                        name="second_introduced_by"
                                                        value="{{ old('second_introduced_by', $data->second_introduced_by ?? '') }}"
                                                        placeholder="Second Introduced By"  
                                                        >
                                                    @error('second_introduced_by')
                                                        <div class="text-danger mt-1">{{ $message }}</div>
                                                    @enderror
                                                </div>
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
    function showDonarMember(){
        var memberType = $('#memberType').val();
        console.log(memberType);
        $('#donarMemberDiv').hide();
        if(memberType == 'donate') {
            $('#donarMemberDiv').show();
        }
    }
</script>
@endpush
