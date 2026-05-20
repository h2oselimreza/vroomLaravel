@extends('client.layouts.app')
@section('content')
<div class="block-header">
    <h2>REQUESTED LIST FOR QUOTATION</h2><br>
    <div class="breadcrumb breadcrumb-bg-blue-grey">
        <li><a href="/client/Home"> Home</a></li>
        <li><a href="#"> Quotation</a></li>
        <li><a href="/client/Quotation/reqQuotationList"> Requested List For Quotation</a></li>
    </div>
</div>
<div class="row clearfix">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="body">
                <a href="{{ route('client.quotation.quotation-list.create') }}" class="btn bg-blue waves-effect">New Request For Quotation</a>
                <br><br>
                <?php
                // if ($isActiveFlag ?? null == 1) {
                //     echo "<option value='1'>Active</option>";
                //     echo "<option value='2'>Inactive</option>";
                // } else {
                //     echo "<option value='2'>Inactive</option>";
                //     echo "<option value='1'>Active</option>";
                // }
                ?>
                <!-- Success Message -->
                @if(session('success'))
                    <div class="alert alert-success">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                        <strong>{{ session('success') }}</strong>
                    </div>
                @endif

                <!-- Error Message -->
                @if(session('error'))
                    <div class="alert alert-danger" role="alert">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                        <strong>Error!</strong> {{ session('error') }}
                    </div>
                @endif
                <div class="table-custom-responsive">
                    <table class="table table-bordered table-hover custom-table dataTable">
                        <thead>
                            <tr class="bg-info">
                                <th>SL</th>
                                <th>Request No</th>
                                <th>Created Date</th>
                                <th>Sending Date</th>
                                <th>Quotation Submitted Date</th>
                                <th>Status</th>
                                <th>Approved Quotation</th>
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

                            @foreach ($quotationReqLists as $quotationReqList)

                                <tr>

                                    <td>{{ $loop->iteration }}</td>

                                    <td>
                                        {{ $quotationReqList->request_no }}
                                    </td>

                                    <td>
                                        {{ get_date_format1($quotationReqList->created_dt_tm) }}
                                    </td>

                                    <td>
                                        {{ get_date_format1($quotationReqList->req_sending_date) }}
                                    </td>

                                    <td>
                                        {{ get_date_format1($quotationReqList->quotation_submitted_date) }}
                                    </td>

                                    <td>
                                         {!! get_quotation_req_status($quotationReqList->status, 'client') !!}
                                    </td>

                                    <td>
                                        <a
                                            target="_blank"
                                            href=""
                                        >
                                            {{ $quotationReqList->approved_quotation_no }}
                                        </a>
                                    </td>

                                    <td>

                                        <div class="btn-group">

                                            <button
                                                type="button"
                                                class="btn btn-default btn-xs dropdown-toggle"
                                                data-toggle="dropdown"
                                                aria-haspopup="true"
                                                aria-expanded="false"
                                            >
                                                Action <span class="caret"></span>
                                            </button>

                                            <ul class="dropdown-menu pull-right">

                                                <li>
                                                    <a href="{{ route('client.quotation.quotation-list.edit',$quotationReqList->request_no) }}">
                                                        Show
                                                    </a>
                                                </li>

                                                @if (
                                                    $quotationReqList->status == config('constants.REQ_QUOT_SUB_STATUS') ||
                                                    $quotationReqList->status == config('constants.REQ_QUOT_APPV_CUS_STATUS')
                                                )

                                                    <li>
                                                        <a href="{{ url('client/Quotation/showQuotationList/' . $quotationReqList->request_no) }}">
                                                            Show Quotation
                                                        </a>
                                                    </li>

                                                @endif

                                            </ul>

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>
                    </table>
                </div> 
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')

@endpush