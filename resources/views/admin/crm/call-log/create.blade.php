@extends('layouts.app')
@section('content')
<style>
    .panel {
        padding: 0px; 
    }

</style>

<div class="header dashboard_from">
    <h1 class="page-title">Add Call Log</h1>
    <ul class="breadcrumb">
        <li><a href="#">Home</a> / </li>
        <li><a href="#">CRM</a> / </li>
        <li><a href="/admin/crm/call-log">  Call Log</a> / </li>
        <li><a href="#">Add Call Log</a></li>
    </ul>
</div>
<div class="main-content">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @php
    $disableFlag = "";
    $customerId = "";
    $customerName = "";
    $mobile = "";
    $address = "";

    if (!empty($customerInfo)) {
        $disableFlag = 'readonly';
        $customerName = $customerInfo[0]['name'];
        $mobile = $customerInfo[0]['mobile'];
        $address = $customerInfo[0]['address'];

        if ($callerType == 'customer') {
            $customerId = $customerInfo[0]['customer_id'];
        }
    } elseif (!empty($logDetails)) {
        $disableFlag = 'readonly';
        $customerId = $logDetails[0]['company'];
        $customerName = $logDetails[0]['customer_name'];
        $mobile = $logDetails[0]['customer_mobile_no'];
        $address = $logDetails[0]['customer_address'];
    }
@endphp


    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel panel-default p-3">

                <form id="addCallLogForm" action="{{ route('admin.crm.call-log.store') }}" method="POST">
                    @csrf

                    <div class="row">

                        {{-- Customer Name --}}
                        <div class="col-md-4 col-sm-4 col-xs-12 mb-2">
                            <div class="form-group">
                                <label class="form-label">Customer Name</label>

                                @if($disableFlag)
                                    <input type="text" class="form-control" name="customerName"
                                        id="customerName" value="{{ $customerName }}" {{ $disableFlag }}>
                                @else
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="customerName" id="customerName">

                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-primary save_button" onclick="getCustomerList()">
                                                <i class="fa fa-md fa-search"></i>
                                            </button>
                                        </span>
                                    </div>
                                @endif

                                <input type="hidden" name="companyCode" id="companyCode" value="{{ $customerId }}">
                                <input type="hidden" name="leadCode" value="{{ $leadCode ?? '' }}">
                            </div>
                        </div>

                        {{-- Mobile --}}
                        <div class="col-md-4 col-sm-4 col-xs-12 mb-2">
                            <div class="form-group">
                                <label class="form-label">Customer Mobile</label>
                                <span class="text-danger">*</span>
                                <small class="hidden custom-text-danger" id="customerMobileReq-error">
                                    Customer Mobile is Required
                                </small>

                                <input type="text" class="form-control" name="customerMobile"
                                    id="customerMobile"
                                    onchange="checkMobileNumber(this.value, 'customerMobile')"
                                    value="{{ $mobile }}" {{ $disableFlag }}>

                                @error('customerMobile')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        {{-- Address --}}
                        <div class="col-md-4 col-sm-4 col-xs-12 mb-2">
                            <div class="form-group">
                                <label class="form-label">Customer Address</label>

                                <input type="text" class="form-control" name="customerAddress"
                                    id="customerAddress"
                                    value="{{ $address }}" {{ $disableFlag }}>

                                @error('customerAddress')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        {{-- Call Type --}}
                        <div class="col-md-6 col-sm-6 col-xs-12 mb-2">
                            <div class="form-group">
                                <label class="form-label">Call Type</label>
                                <span class="text-danger">*</span>
                                <small class="hidden custom-text-danger" id="callTypeReq-error">
                                    Please select a call type
                                </small>

                                <select class="form-control" id="callType" name="callType"
                                        onchange="setCallType(this.value)">
                                    <option value="">-- Select Type --</option>

                                    @foreach($data['callTypes'] as $callType)
                                        <option value="{{ $callType->element_code }}">
                                            {{ $callType->element }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('callType')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        {{-- Reason --}}
                        <div class="col-md-6 col-sm-6 col-xs-12 mb-2">
                            <div class="form-group">
                                <label class="form-label">Reason</label>
                                <span class="text-danger">*</span>
                                <small class="hidden custom-text-danger" id="reasonCodeReq-error">
                                    Please select a reason
                                </small>

                                <div id="reasonDiv">
                                    <select class="form-control" name="reason" id="reasonCode">
                                        <option value="">-- Select Reason --</option>
                                    </select>
                                </div>
                                @error('reasonCode')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        {{-- Start Time --}}
                        <div class="col-sm-6 col-md-6 col-xs-12 mb-2">
                            <div class="form-group">
                                <label class="form-label">Start Time</label>
                                <span class="text-danger">*</span>
                                <small class="hidden custom-text-danger" id="hiddenStartReq-error">
                                    Start Time is Required
                                </small>

                                <div class="input-group">
                                    <input type="text" class="form-control" id="dispStart" readonly>
                                    <input type="hidden" name="startDtTm" id="hiddenStart">

                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-primary save_button"
                                                onclick="setCurrentTime('Start')">
                                            Set
                                        </button>
                                    </span>
                                </div>
                                @error('dispStart')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        {{-- End Time --}}
                        <div class="col-sm-6 col-md-6 col-xs-12 mb-2">
                            <div class="form-group">
                                <label class="form-label">End Time</label>
                                <span class="text-danger">*</span>
                                <small class="hidden custom-text-danger" id="hiddenEndReq-error">
                                    End Time is Required
                                </small>

                                <div class="input-group">
                                    <input type="text" class="form-control" id="dispEnd" readonly>
                                    <input type="hidden" name="endDtTm" id="hiddenEnd">

                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-primary save_button"
                                                onclick="setCurrentTime('End')">
                                            Set
                                        </button>
                                    </span>
                                </div>
                                @error('endDtTm')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        {{-- Feedback --}}
                        <div class="col-md-6 col-sm-6 col-xs-12 mb-2">
                            <div class="form-group">
                                <label class="form-label">Feedback</label>
                                <span class="text-danger">*</span>
                                <small class="hidden custom-text-danger" id="feedbackCodeReq-error">
                                    Please select a feedback
                                </small>

                                <div id="feedbackDiv">
                                    <select class="form-control" name="feedback" id="feedbackCode">
                                        <option value="">-- Select Feedback --</option>
                                    </select>
                                </div>
                                @error('feedback')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        {{-- Next Call Date --}}
                        <div class="col-md-3 col-sm-3 col-xs-12 mb-2">
                            <div class="form-group">
                                <label class="form-label">Next Call Date</label>
                                <input type="text" class="form-control dateInput" name="nextCallDate" id="nextCallDate">
                            </div>
                        </div>

                        {{-- Next Call Time --}}
                        <div class="col-md-3 col-sm-3 col-xs-12 mb-2">
                            <div class="form-group">
                                <label class="form-label">Next Call Time</label>
                                <input type="text" class="form-control timepicker" name="nextCallTime" id="nextCallTime">
                            </div>
                        </div>

                        {{-- Remarks --}}
                        <div class="col-md-12 col-sm-12 col-xs-12 mb-2">
                            <div class="form-group">
                                <label class="form-label">Remarks</label>
                                <textarea class="form-control" rows="10" name="remarks" id="remarks"
                                style="min-height:100px;"></textarea>
                            </div>
                        </div>

                    </div>

                    {{-- Submit --}}
                    <div class="row mt-2">
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <input type="hidden" name="logId" value="{{ $logId ?? '' }}">

                            <button type="button" class="btn btn-primary save_button" onclick="addCallLog()">
                                Submit
                            </button>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
    <button type="button" class="hidden" data-toggle="modal" data-target="#myModal" id="showCustomerModalBtn"></button>

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                {{-- Header --}}
                <div class="modal-header">
                    <h5 class="modal-title">Customer List</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                {{-- Body --}}
                <div class="modal-body">
                    <div class="table-responsive">

                        <table class="table table-bordered table-hover custom-table dataTable">
                            <thead>
                                <tr class="bg-primary">
                                    <th>SL</th>
                                    <th>Account Name</th>
                                    <th>Individual Code</th>
                                    <th>Mobile</th>
                                    <th>Address</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </tfoot>

                            <tbody>
                                @php $count = 1; @endphp

                                @foreach ($data['companies'] as $company)
                                    <tr>
                                        <td class="td-center">{{ $count }}</td>
                                        <td>{{ $company->title }}</td>
                                        <td class="td-center">{{ $company->company_code }}</td>
                                        <td class="td-center">{{ $company->company_mobile }}</td>
                                        <td>{{ $company->address }}</td>

                                        <td class="td-center">
                                            @if ($company->is_active == 1)
                                                <span class="text-success">Active</span>
                                            @else
                                                <span class="text-danger">Inactive</span>
                                            @endif
                                        </td>

                                        <td class="td-center">
                                            <button type="button"
                                                    class="btn btn-primary btn-xs btn-circle-puchase"
                                                    onclick="setCustomer('{{ $count }}')">
                                                <i class="fa fa-arrow-down"></i>
                                            </button>

                                            <input type="hidden"
                                                id="customerNameModalHidden{{ $count }}"
                                                value="{{ $company->title }}">

                                            <input type="hidden"
                                                id="customerCodeModalHidden{{ $count }}"
                                                value="{{ $company->company_code }}">

                                            <input type="hidden"
                                                id="customerMobileModalHidden{{ $count }}"
                                                value="{{ $company->company_mobile }}">

                                            <input type="hidden"
                                                id="customerAddressModalHidden{{ $count }}"
                                                value="{{ $company->address }}">
                                        </td>
                                    </tr>

                                    @php $count++; @endphp
                                @endforeach
                            </tbody>

                        </table>

                    </div>
                </div>

                {{-- Footer --}}
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger save_button" onclick="clearFields()">Clear</button>
                    <button type="button" class="btn btn-primary save_button" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    var reasonObj = @json($data['reasons']);
    var feedbackObj = @json($data['feedbacks']);

    function setCallType(callTypeDropDown) {

        var reasonList = (reasonObj && reasonObj.reasonData) ? reasonObj.reasonData : [];
        var feedbackList = (feedbackObj && feedbackObj.feedbackData) ? feedbackObj.feedbackData : [];

        var optionStr = "<option value=''>Nothing Selected</option>";

        for (var i = 0; i < reasonList.length; i++) {
            if (reasonList[i].call_type === callTypeDropDown) {
                optionStr += "<option value='" +
                    reasonList[i].reason_code + "|" +
                    reasonList[i].title + "'>" +
                    reasonList[i].title +
                "</option>";
            }
        }

        $('#reasonDiv').html(
            '<select class="form-control" name="reason" id="reasonCode">' +
            optionStr +
            '</select>'
        );

        var optionFbStr = "<option value=''>Nothing Selected</option>";

        for (var i = 0; i < feedbackList.length; i++) {
            if (feedbackList[i].call_type === callTypeDropDown) {
                optionFbStr += "<option value='" +
                    feedbackList[i].feedback_code + "|" +
                    feedbackList[i].title + "'>" +
                    feedbackList[i].title +
                "</option>";
            }
        }

        $('#feedbackDiv').html(
            '<select class="form-control" name="feedback" id="feedbackCode">' +
            optionFbStr +
            '</select>'
        );
    }


    function setCurrentTime(fieldId) {

        var logId = "{{ $logId ?? '' }}";

        $.ajax({
            type: 'POST',
            url: "{{ route('admin.crm.setCurrentTime') }}",
            data: {
                logId: logId,
                fieldId: fieldId,
                _token: "{{ csrf_token() }}"
            }
        })
        .done(function (data) {

            var result = typeof data === "string" ? JSON.parse(data) : data;

            if (result.response === 2) {
                window.location.href = "{{ url('/admin/crm/call-log') }}";

            } else if (result.response === 3) {

                Swal.fire({
                    title: "One user has engaged with this call...!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Ok",
                    confirmButtonColor: "#ec6c62",
                    allowOutsideClick: false
                }).then((resultSwal) => {
                    if (resultSwal.isConfirmed) {
                        window.location.href = "{{ url('/admin/crm/call-log') }}";
                    }
                });

            } else {
                $('#disp' + fieldId).val(result.time);
                $('#hidden' + fieldId).val(result.dateTime);
            }
        })
        .fail(function () {
            Swal.fire({
                title: "Call already in use!",
                icon: "warning",
                confirmButtonText: "OK"
            });
        });

    }

    function getCustomerList() {
        var myModal = new bootstrap.Modal(document.getElementById('myModal'));
        myModal.show();
    }

    function setCustomer(count) {
        var companyCode = $('#customerCodeModalHidden' + count).val();
        var name = $('#customerNameModalHidden' + count).val();
        var mobile = $('#customerMobileModalHidden' + count).val();
        var address = $('#customerAddressModalHidden' + count).val();

        $('#customerName').val(name);
        $('#customerMobile').val(mobile);
        $('#customerAddress').val(address);
        $('#companyCode').val(companyCode);

        $('#customerName').prop('readonly', true);
        $('#customerMobile').prop('readonly', true);
        $('#customerAddress').prop('readonly', true);

        // 🔥 REMOVE focus from all fields
        $('#customerName, #customerMobile, #customerAddress').blur();

        // optional: remove focus from any active element
        document.activeElement.blur();

        closeCustomerModal();
    }

    function clearFields() {
        $('#customerName').val('');
        $('#customerMobile').val('');
        $('#customerAddress').val('');
        $('#companyCode').val('');

        $('#customerName').prop('readonly', false);
        $('#customerMobile').prop('readonly', false);
        $('#customerAddress').prop('readonly', false);

        closeCustomerModal();
    }

    function addCallLog() {
        var errorMsg = "";
        // filed id, error div id
        var fieldsArr = new Array("callType|callTypeReq-error", "reasonCode|reasonCodeReq-error",
                "customerMobile|customerMobileReq-error", "hiddenStart|hiddenStartReq-error", "hiddenEnd|hiddenEndReq-error",
                "feedbackCode|feedbackCodeReq-error");  // filed id, error div id
        var inputFiledJsonData = getInputData(fieldsArr);
        if (!inputFiledJsonData) {
            errorMsg += getReuiredFiledErrorMsg();
            showErrorMsg(errorMsg);
            return false;  // required filed check
        } else {
            hideErrorDiv();
        }

        var startTime = new Date($('#hiddenStart').val());
        var endTime = new Date($('#hiddenEnd').val());

        if (startTime > endTime) {
            $('#hiddenStart').val('');
            $('#hiddenEnd').val('');
            sweetAlert('Start time can not be greater than end time...!');
            return false;
        }

        var nextCallDate = $('#nextCallDate').val();
        var nextCallTime = $('#nextCallTime').val();

        if (nextCallDate !== '' && nextCallTime === '') {
            sweetAlert('Please insert next call time...!');
            return false;
        }

        $('#addCallLogForm').submit();
    }

    function closeCustomerModal() {
        $('#myModal').modal('hide');
    }

</script>
@endpush
