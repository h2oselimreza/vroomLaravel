@extends('layouts.app')
@section('content')

<div class="header dashboard_from">
    <h1 class="page-title">Member Bulk SMS</h1>
    <ul class="breadcrumb">
        <li><a href="#"> Home</a></li>
        <li><a href="#">/ SMS</a></li>
        <li><a href="#">/ Member Bulk SMS</a></li>
    </ul>
</div>
<div class="main-content">
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel panel-default member_bulk_sms">
                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <table class="headingTable">
                            <tr class="bg-info">
                                <th style="width:100px">Member Type :</th>
                                <td>{{ $memberType ? get_common_table_name_str($memberType, 'member_type') : 'N/A' }}</td>
                            </tr>
                            <tr class="bg-warning">
                                <th>Block :</th>
                                <td>{{ $block ? get_block_name_str($block) : 'N/A' }}</td>
                            </tr>
                            <tr class="bg-info">
                                <th>Road :</th>
                                <td>{{ $road ? get_road_name_str($road) : 'N/A' }}</td>
                            </tr>
                            <tr class="bg-warning">
                                <th>Occupation :</th>
                                <td>{{ $occupation ? get_common_table_name_str($occupation, 'occupation') : 'N/A' }}</td>
                            </tr>
                            <tr class="bg-info">
                                <th>Blood Group :</th>
                                <td>{{ $bloodGroup ?? 'N/A' }}</td>
                            </tr>
                        </table>
                        <br>
                        <form action="{{ route('admin.send-member-custom-bulk-msg') }}" method="POST" id="customMsgForm">
                            @csrf
                            <div class="form-group" id="writeMsgId">
                                <label class="form-label" for="">Write Message*</label><br>
                                <textarea class="form-control" id="textAreaMsg" name="customMsg"
                                          style="width:100%;resize: none;height:150px;"></textarea>
                            </div>
                            <div style="display:none">
                                <input type="text" name="memberIdStr" value="{{ $memberIdStr }}">
                                <input type="text" id="memberType" name="memberType" value="{{ $memberType }}">
                                <input type="text" id="block" name="block" value="{{ $block }}">
                                <input type="text" id="road" name="road" value="{{ $road }}">
                                <input type="text" id="occupation" name="occupation" value="{{ $occupation }}">
                                <input type="text" id="bloodGroup" name="bloodGroup" value="{{ $bloodGroup }}">
                                <input type="text" name="checkBulkMemberFlag" value="{{ $checkBulkMemberFlag }}">
                            </div>
                        </form>
                        <br>
                        <button class="btn btn-primary save_button" onclick="sendMessage()">Send Message</button>
                    </div>

                    <div class="col-sm-6 col-md-6">
                        @if($checkBulkMemberFlag == 2)
                            <div class="text-center">
                                <h4><b>Total Member:</b> {{ count($members) }}</h4>
                                (Except Selected Duplicate Member)
                                <br> <br>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover custom-table" id="dataTable">
                                    <thead>
                                        <tr class="bg-primary">
                                            <th>SL</th>
                                            <th>Member ID</th>
                                            <th>Member Name</th>
                                            <th>Block</th>
                                            <th>Road</th>
                                            <th>Member Type</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>SL</th>
                                            <th>Member ID</th>
                                            <th>Member Name</th>
                                            <th>Block</th>
                                            <th>Road</th>
                                            <th>Member Type</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        @php $count = 1; @endphp
                                        @foreach($members as $member)
                                            <tr>
                                                <td class="td-center">{{ $count }}</td>
                                                <td>{{ $member->member_id }}</td>
                                                <td>{{ $member->member_name }}</td>
                                                <td>{{ $member->block_name }}</td>
                                                <td>{{ $member->road_name }}</td>
                                                <td>{{ $member->member_type_name }}</td>
                                            </tr>
                                            @php $count++; @endphp
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script language="JavaScript">
   $(document).ready(function () {
        var table = $('#dataTable').DataTable();
    });

    function sendMessage() {
        if ($.trim($('#textAreaMsg').val()) === "") {
            alert('Message body is required...!');
            return false;
        }
        if (confirm('Are you sure ?')) {
            $("#customMsgForm").submit();
        }
    }
</script>
@endpush
