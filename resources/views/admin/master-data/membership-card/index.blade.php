@extends('layouts.app')

@section('content')

<div class="header dashboard_from">
    <h1 class="page-title">Membership Card</h1>
    <ul class="breadcrumb">
        <li><a href="{{ url('admin/Home') }}">Home</a></li>
        <li><a href="#">/ Master Data</a></li>
        <li><a href="#">/ Membership Card</a></li>
    </ul>
</div>
<div class="main-content">
    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Error Message -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel panel-default"> 
                <div class="add-button">
                    <a href="{{ route('admin.module.master-data.member-ship-card.create') }}">Add Membership Card</a>
                </div>
                <div class="table-responsive">

                    <table class="table table-bordered table-hover custom-table" id="datatable">
                        <thead>
                            <tr class="bg-primary">
                                <th class="text-center">SL</th>
                                <th class="text-center">Card Number</th>
                                <th class="text-start">Package Code</th>
                                <th class="text-start">Validity Year</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if ($data)
                                @foreach ($data as $value)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $value->card_number }}</td>
                                        <td class="text-center">{{ $value->package_code  }}</td>
                                        <td class="text-center">{{ $value->validity_month }}</td>
                                        <td class="text-center">{{ ($value->status == 2) ? "Not assigned" : 'Assigned' }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="text-center">No Data Found</td>
                                </tr>
                            @endif
                        </tbody>

                        <tfoot>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>

                    </table>

                </div>
            </div>

        </div>
    </div>
    <div class="row mt-5">
        <div class="text-center mb-4"><h4><b>Membership Card</b></h4></div>
        <br>
        <form id="cardUploadForm" action="https://vroom24x7.com/demo/admin/MasterData/uploadMembershipCardList" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <input type="file" class="form-control" name="membershipCsvFile" id="membershipCsvFile" onchange="checkFile(this, this.id);">
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <button type="button" class="btn btn-primary save_button" id="csvUploadBtn" onclick="uploadCsv()">Upload Membership Card List</button>
                        <a href="https://vroom24x7.com/demo/assets/files/demo_csv/membershipcard.csv">Demo CSV Download</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <hr>
    <div class="row">
        <div class="text-center mt-5 mb-4"><h4><b>Generate Membership Card</b></h4></div>
        <br>
        <form action="https://vroom24x7.com/demo/admin/MasterData/generateCardNumber" method="post">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                    <label class="form-label">Two Digit Card Suffix <small><i>(01, 02, 03)</i></small></label>
                    <input type="text" class="form-control" name="cardSuffix" required="">
                </div>
                <div class="form-group">
                    <label class="form-label">Card Quantity</label>
                    <input type="number" min="0" class="form-control" name="quantity" required="">
                </div>
                <input type="submit" class="btn btn-primary save_button mt-3" value="Generate">
            </div>

        </form>
    </div>
</div>

@endsection


@push('scripts')
<script>
$(document).ready(function () {

    $('#datatable').DataTable({
        pageLength: 10,
        ordering: true,
        searching: true
    });

});
</script>
@endpush