@extends('layouts.app')

@section('content')

<div class="header dashboard_from">
    <h1 class="page-title">
        {{ isset($data->exists) ? 'Edit Member' : 'Add Member' }}
    </h1>
</div>

<?php
    $day = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31');
    $month = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
?>

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


            <div class="tab-content" id="workingExperianceTabContent">

                <div class="tab-pane fade show active"
                    id="workingExperiance"
                    role="tabpanel">
                    <form action="{{ route('admin.member.module.otherFamily.update',$data->id) }}" method="POST">
                        @csrf

                        <div class="accordion" id="memeberAccordion">

                            {{-- Personal Information --}}
                            <div class="accordion-item active">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#personalInfo"
                                            aria-expanded="true">
                                        Working Experience
                                    </button>
                                </h2>

                                <div id="personalInfo"
                                    class="accordion-collapse collapse show"
                                    data-bs-parent="#memberAccordion">

                                    <div class="accordion-body">

                                        <div class="row g-3">
                                            <div class="panel-body" id="memberFamilyDetailDiv">

                                                @if(empty($memberFamilyDetails) || count($memberFamilyDetails) == 0)
                                                    <div id="noDataDiv" class="alert alert-info text-center">
                                                        <b>No Data Found</b>
                                                    </div>
                                                @endif

                                                @if (!empty($memberFamilyDetails))
                                                    @foreach($memberFamilyDetails as $index => $memberFamilyDetail)
                                                        <div class="card mb-4 bg-light" id="memberDetailDiv{{ $index }}">
                                                            <div class="card-body">

                                                                <div class="text-center">
                                                                    <h5 class="experience_title">
                                                                        <b>Family Member 
                                                                            <span id="familyMemberNo{{ $index }}">
                                                                                {{ $loop->iteration }}
                                                                            </span>
                                                                        </b>
                                                                    </h5>
                                                                    <hr>
                                                                </div>

                                                                {{-- Name + Relation --}}
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <label class="form-label">Family Member Name</label>
                                                                        <input type="text" class="form-control"
                                                                            name="family_members[{{ $index }}][name]"
                                                                            value="{{ $memberFamilyDetail->name }}" required>
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <label class="form-label">Relation</label>
                                                                        <select class="form-control"
                                                                                name="family_members[{{ $index }}][relation]">
                                                                            <option value="">-- Select --</option>
                                                                            @foreach($relations as $relation)
                                                                                <option value="{{ $relation->element_code }}"
                                                                                    {{ $memberFamilyDetail->relation == $relation->element_code ? 'selected' : '' }}>
                                                                                    {{ $relation->element }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                {{-- DOB + Gender --}}
                                                                <div class="row mt-2">
                                                                    <div class="col-md-6">
                                                                        <label class="form-label">Date of Birth</label>
                                                                        <input type="date" class="form-control"
                                                                            name="family_members[{{ $index }}][dob]"
                                                                            value="{{ $memberFamilyDetail->dob }}">
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <label class="form-label">Gender</label>
                                                                        <select class="form-control"
                                                                                name="family_members[{{ $index }}][gender]">
                                                                            <option value="">-- Select Gender --</option>
                                                                            <option value="male" {{ $memberFamilyDetail->gender == 'male' ? 'selected' : '' }}>Male</option>
                                                                            <option value="female" {{ $memberFamilyDetail->gender == 'female' ? 'selected' : '' }}>Female</option>
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                {{-- Mobile + Email --}}
                                                                <div class="row mt-2">
                                                                    <div class="col-md-6">
                                                                        <label class="form-label">Mobile</label>
                                                                        <input type="text" class="form-control"
                                                                            name="family_members[{{ $index }}][mobile]"
                                                                            value="{{ $memberFamilyDetail->mobile }}">
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label class="form-label">Email</label>
                                                                        <input type="email" class="form-control"
                                                                            name="family_members[{{ $index }}][email]"
                                                                            value="{{ $memberFamilyDetail->email }}">
                                                                    </div>
                                                                </div>

                                                                {{-- Occupation --}}
                                                                <div class="row mt-2">
                                                                    <div class="col-md-6">
                                                                        <label class="form-label">Occupation</label>
                                                                        <select class="form-control"
                                                                                name="family_members[{{ $index }}][occupation]">
                                                                            <option value="">-- Select Occupation --</option>
                                                                            @foreach($occupations as $occupation)
                                                                                <option value="{{ $occupation->element_code }}"
                                                                                    {{ $memberFamilyDetail->occupation == $occupation->element_code ? 'selected' : '' }}>
                                                                                    {{ $occupation->element }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                {{-- Hidden ID --}}
                                                                <input type="hidden" name="family_members[{{ $index }}][id]"
                                                                    value="{{ $memberFamilyDetail->id }}">

                                                                {{-- Remove Button --}}
                                                                <div class="mt-3">
                                                                    <button type="button" class="btn btn-sm btn-danger add_button"
                                                                        onclick="removeMemberDetailDiv({{ $index }})">
                                                                        Remove
                                                                    </button>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif

                                            </div>
                                        </div>
                                        <input type='button' value='Add More Family Member' id='addFamilyMemberButton' onclick="addFamilyMemberDiv()" class="btn btn-sm btn-primary add_button">
                                        <input type="hidden" id="deleteMemberRow" name="deleteMemberRow" >
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="checkbox">
                            <input type="hidden"
                                name="memberId"
                                id="memberId"
                                value="{{ $data->id ?? '' }}">

                            <input type="hidden"
                                name="workingExpCount"
                                id="workingExpCount"
                                value="{{ isset($employeeWorkingDetails) ? count($employeeWorkingDetails) : 0 }}">

                            <input type="submit"
                                class="btn btn-success btn-sm"
                                id="updateMemberSubmit"
                                value="Update">

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

let counter = {{ !empty($memberFamilyDetails) ? count($memberFamilyDetails) : 0 }};

const relations = @json($relations);
const occupations = @json($occupations);

function addFamilyMemberDiv() {
    counter++;

    let relationOptions = '<option value="">-- Select --</option>';
    relations.forEach(function(item){
        relationOptions += `<option value="${item.element_code}">${item.element}</option>`;
    });

    let occupationOptions = '<option value="">-- Select Occupation --</option>';
    occupations.forEach(function(item){
        occupationOptions += `<option value="${item.element_code}">${item.element}</option>`;
    });

    let html = `
    <div class="card mb-3" id="memberDetailDiv${counter}">
        <div class="card-body bg-light">
            <div class="text-center">
                <h5 class="experience_title">Family Member ${counter}</h5>
            </div>
            <hr>

            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Name</label>
                    <input type="text" class="form-control"
                        name="family_members[${counter}][name]" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Relation</label>
                    <select class="form-control"
                        name="family_members[${counter}][relation]">
                        ${relationOptions}
                    </select>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-md-6">
                    <label class="form-label">Date of Birth</label>
                    <input type="date" class="form-control"
                        name="family_members[${counter}][dob]">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Gender</label>
                    <select class="form-control"
                        name="family_members[${counter}][gender]">
                        <option value="">-- Select Gender --</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-md-6">
                    <label class="form-label">Mobile</label>
                    <input type="text" class="form-control"
                        name="family_members[${counter}][mobile]">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control"
                        name="family_members[${counter}][email]">
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-md-6">
                    <label class="form-label">Occupation</label>
                    <select class="form-control"
                        name="family_members[${counter}][occupation]">
                        ${occupationOptions}
                    </select>
                </div>
            </div>

            <input type="hidden" name="family_members[${counter}][id]" value="">

            <div class="mt-3">
                <button type="button" class="btn btn-danger btn-sm add_button"
                    onclick="removeMemberDetailDiv(${counter})">
                    Remove
                </button>
            </div>
        </div>
    </div>
    `;

    $('#memberFamilyDetailDiv').append(html);
}

function removeMemberDetailDiv(id) {
    // Push deleted ID if exists
    let hiddenId = $(`input[name='family_members[${id}][id]']`).val();
    if(hiddenId){
        let existing = $('#deleteMemberRow').val();
        $('#deleteMemberRow').val(existing ? existing + ',' + hiddenId : hiddenId);
    }

    $(`#memberDetailDiv${id}`).remove();
}

    function updateMember() {
        var i;
        var msgArray = new Array();
        var organizationNameStr = "<strong><li>Name is required</strong></li>";

        for (i = 1; i < counter; i++) {
            if (typeof ($("#familyMemberName" + i).val()) !== "undefined") {
                if ($.trim($("#familyMemberName" + i).val()) === "") {
                    document.getElementById('familyMemberNameError' + i).innerHTML = ' Name is required';
                    if (jQuery.inArray(organizationNameStr, msgArray) !== -1) {
                    } else {
                        msgArray.push(organizationNameStr);
                    }
                } else {
                    document.getElementById('familyMemberNameError' + i).innerHTML = '';
                }
            }
            
        }

        if (msgArray.length > 0) {
            $("#errorBlockDiv").show();
            document.getElementById('errorBlockDiv').innerHTML = msgArray.join('');
            var etop = $('#contentDiv').offset().top;
            $('html, body').animate({
                scrollTop: etop
            }, 1000);
            return false;
        } else {
            $("#errorBlockDiv").hide();
        }
        document.getElementById('updateMemberSubmit').click();
    }
    function memberInfoRoute(flag) {
        $('#routeText').val(flag);
        $("#memberInfoRouteForm").submit();
    }

    function removeMemberDetailDiv(serial) {
        var idArr = new Array();
        idArr.push($('#hiddenFamilyMemberDiv' + serial).val());
        if ($('#deleteMemberRow').val() !== "") {
            idArr.push($('#deleteMemberRow').val());
        }
        $("#memberDetailDiv" + serial).remove();
        $("#breakDiv" + serial).remove();
        $('#deleteMemberRow').val(idArr.join());

        var i;
        var familyMemberNoDiv = 1;
        for (i = 1; i <= counter; i++) {
            if ($("#familyMemberNo" + i).length !== 0) {
                document.getElementById('familyMemberNo' + i).innerHTML = familyMemberNoDiv;
                familyMemberNoDiv++;
            }
        }
    }
</script>
@endpush
