@extends('layouts.app')

@section('content')

<div class="header dashboard_from">
    <h1 class="page-title">
        {{ isset($data->exists) ? 'Edit Service' : 'Add Workshop' }}
    </h1>
</div>

@php
    $path = request()->path();
    $lastPart = collect(explode('/', $path))->last();
@endphp
<div class="container">
    <div class="card shadow">
        <div class="card-body">
            <!-- Nav Tabs -->
            @include('admin.work-shop.tab')
            {{-- Success Message --}}
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Validation Errors --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Tab Content -->
            <div class="tab-content" id="employeeTabContent">

                <div class="tab-pane fade show active"
                    id="personal"
                    role="tabpanel">
                    @php
                        $isEdit = isset($data);
                    @endphp
                    <div class="accordion" id="employeeAccordion">

                        {{-- Service --}}
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#service"
                                        aria-expanded="true">
                                    Service
                                </button>
                            </h2>

                            <div id="service"
                                class="accordion-collapse collapse show"
                                data-bs-parent="#employeeAccordion">

                                <div class="accordion-body">
                                    <div class="row">
                                        <div class="col-md-10 col-sm-12 col-xs-12">
                                            <h6 class="panel-title custom-panel-title">
                                                <?php echo get_workshop_name($workshopCode) ?>
                                            </h6>
                                        </div>
                                        <div class="col-md-2 col-sm-12 col-xs-12 text-right">
                                            <h6 class="panel-title custom-panel-title">
                                                <?php echo $workshopCode ?>
                                            </h6>
                                        </div>
                                    </div>
                                    <div class="row mt-2 mb-2">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="float-right p-r-20">
                                                <input type="checkbox" name="checkedAll" id="checkedAll" /><i> Check all</i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <form action="{{ route('admin.workshop-service.store') }}" method="post" id="serviceForm">
                                                @csrf
                                            
                                                @php $count = 1; @endphp
                                                @foreach ($distinctServices as $distinctService)
                                                    <div class="accordion-item mb-2">
                                            
                                                        <h2 class="accordion-header" id="heading{{ $distinctService->service }}">
                                                            <button class="accordion-button collapsed"
                                                                    type="button"
                                                                    data-bs-toggle="collapse"
                                                                    data-bs-target="#collapse{{ $distinctService->service }}"
                                                                    aria-expanded="false" 
                                                                    style="background: #d9d9d9;padding: 9px 10px;">
                                            
                                                                <i class="fa fa-tags me-2"></i>
                                                                {{ $distinctService->service_name }}
                                                            </button>
                                                        </h2>
                                            
                                                        <div id="collapse{{ $distinctService->service }}"
                                                             class="accordion-collapse show"
                                                             data-bs-parent="#serviceAccordion">
                                            
                                                            <div class="accordion-body">
                                            
                                                                <table class="table table-striped custom-table">
                                                                    @php $serial = 1; @endphp
                                            
                                                                    @foreach ($serviceVariants as $serviceVariant)
                                            
                                                                        @if ($serviceVariant->service == $distinctService->service)
                                            
                                                                            @php
                                                                                $checked = !empty($serviceVariant->workshop_ser_var) ? 'checked' : '';
                                                                            @endphp
                                            
                                                                            <tr>
                                                                                <td class="text-center">{{ $serial }}</td>
                                            
                                                                                <td style="width:80%">
                                                                                    {{ $serviceVariant->service_variant_name }}
                                                                                </td>
                                            
                                                                                <td>
                                                                                    <input type="checkbox"
                                                                                           class="checkSingle"
                                                                                           name="serviceVarCheckBox{{ $count }}"
                                                                                           id="serviceVarCheckBox{{ $count }}"
                                                                                           onclick="serviceVariantCheck({{ $count }})"
                                                                                           {{ $checked }}>
                                                                                </td>
                                            
                                                                                <input type="hidden"
                                                                                       name="serviceVariantId{{ $count }}"
                                                                                       id="serviceVariantId{{ $count }}"
                                                                                       value="{{ $serviceVariant->workshop_service_id ?? '' }}">
                                            
                                                                                <input type="hidden"
                                                                                       name="serviceVariantCode{{ $count }}"
                                                                                       id="serviceVariantCode{{ $count }}"
                                                                                       value="{{ $serviceVariant->variant_code }}">
                                            
                                                                            </tr>
                                            
                                                                            @php
                                                                                $serial++;
                                                                                $count++;
                                                                            @endphp
                                            
                                                                        @endif
                                            
                                                                    @endforeach
                                                                </table>
                                            
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            
                                                <input type="hidden"
                                                       name="workshopCode"
                                                       value="{{ $workshopCode }}">
                                            
                                                <input type="hidden"
                                                       name="serviceVariantCount"
                                                       id="serviceVariantCount"
                                                       value="{{ $count }}">
                                            
                                                <input type="hidden"
                                                       name="removeServiceVarIdStr"
                                                       id="removeServiceVarIdStr">
                                            
                                            </form>
                                            
                                            <button class="btn btn-primary save_button" onclick="saveService()">
                                                Save Service
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>                  
                </div>

            </div>

        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    var removeArr = new Array();
    function serviceVariantCheck(serial) {
        if (!$('#serviceVarCheckBox' + serial).is(':checked')) {
            var serviceVariantId = $('#serviceVariantId' + serial).val();
            if (serviceVariantId !== "") {
                removeArr.push(serviceVariantId);
                removeArr = jQuery.unique(removeArr);
            }
        } else {
            var serviceVariantId = $('#serviceVariantId' + serial).val();
            if (serviceVariantId !== "") {
                removeArr.splice($.inArray(serviceVariantId, removeArr), 1);
            }
        }
        // console.log(serial);
    }

    function saveService() {
        //showLoader();
        var flag = 0;
        var serial = $('#serviceVariantCount').val();
        //console.log(serial);
        for (var i = 1; i < serial; i++) {
            if ($('#serviceVarCheckBox' + i).is(':checked')) {
                //console.log(1);
                flag = 1;
            }
        }

        if (flag === 1) {
            $('#removeServiceVarIdStr').val(removeArr.join());
            $('#serviceForm').submit();
        } else {
            //hideLoader();
            sweetAlert('Please select at least one service...!');
        }
    }
    $(document).ready(function () {
        $("#checkedAll").change(function () {
            if (this.checked) {
                $(".checkSingle").each(function () {
                    this.checked = true;
                })
            } else {
                $(".checkSingle").each(function () {
                    this.checked = false;
                })
            }
        });

        $(".checkSingle").click(function () {
            if ($(this).is(":checked")) {
                var isAllChecked = 0;
                $(".checkSingle").each(function () {
                    if (!this.checked)
                        isAllChecked = 1;
                })
                if (isAllChecked == 0) {
                    $("#checkedAll").prop("checked", true);
                }
            } else {
                $("#checkedAll").prop("checked", false);
            }
        });
    });


</script>
@endpush