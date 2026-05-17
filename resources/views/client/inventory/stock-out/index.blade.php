@extends('client.layouts.app')

@section('content')
    <div class="block-header">
    <h2>STOCK OUT</h2><br>
    <div class="breadcrumb breadcrumb-bg-blue-grey">
        <li><a href="#"> Home</a></li>
        <li><a href="#"> Inventory</a></li>
        <li><a href="{{ route('client.master-data.stock') }}"> Stock</a></li>
        <li><a href="{{ route('client.inventory.stock-out.index') }}"> Stock Out</a></li>
    </div>
</div>
<div class="row clearfix">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="body">
                @include('client.inventory.tab')
                <br>
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


                <div class="panel panel-default"> 
                    <div class="panel-body">
                        <div class="row" >
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <a href="{{ route('client.inventory.stock-out.create') }}" class="btn btn-primary bg-blue btn-sm waves-effect">New Stock Out</a>
                            </div>
                        </div>

                        <div class="row" >
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="table-custom-responsive">
                                    <table class="table table-bordered table-hover custom-table dataTable">
                                        <thead>
                                            <tr class="bg-info">
                                                <th>SL</th>
                                                <th>Stock Id</th>
                                                <th>Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>SL</th>
                                                <th>Stock Id</th>
                                                <th>Date</th>
                                                
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            @php $serial = 1; @endphp

                                            @foreach ($stocks as $stock)
                                                <tr>
                                                    <td>{{ $serial }}</td>

                                                    <td>{{ $stock->stock_summary_id }}</td>

                                                    <td class="td-left">
                                                        {{ get_date_format1($stock->stock_date) }}
                                                    </td>

                                                    <td class="td-center">
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
                                                                    <a href="{{ route('client.inventory.stock-out.edit', $stock->stock_summary_id) }}">
                                                                        Edit
                                                                    </a>
                                                                </li>

                                                                <li>
                                                                    <a href="#"
                                                                    onclick="removeStockSummary('{{ $stock->stock_summary_id }}')">
                                                                        Remove
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>

                                                @php $serial++; @endphp
                                            @endforeach
                                        </tbody>
                                    </table>
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

//    function changeStatus() {
//        showLoader();
//        $('#statusForm').submit();
//    }
     function removeStockSummary(stockSummaryId)
    {
        swal({
            title: "Are you sure?",
            text: "If you remove this main stock will increase...!",
            type: "warning",
            showCancelButton: true,
            closeOnConfirm: false,
            confirmButtonText: "Yes, remove it...!",
            confirmButtonColor: "#ec6c62"
        }, function () {

            showLoader();

            $.ajax({

                url: "{{ route('client.master-data.removeStockOutSummary') }}",

                type: "POST",

                data: {
                    stockSummaryId: stockSummaryId,
                    _token: "{{ csrf_token() }}"
                },

            })
            .done(function (data) {

                hideLoader();

                if (data === '1' || data == 1) {

                    swal({
                        title: "Remove Successfully",
                        text: "This stock Out is removed now",
                        type: "success",
                        closeOnConfirm: false,
                        confirmButtonText: "Ok",
                        confirmButtonColor: "#A5DC86"

                    }, function () {

                        window.location.href =
                            "{{ route('client.inventory.stock-out.index') }}";
                    });

                } else if (data == '2' || data == 2) {

                    sweetAlert('Remove cannot possible...!');
                }

            })
            .fail(function () {

                hideLoader();

                swal(
                    "Oops",
                    "We couldn't connect to the server!",
                    "error"
                );
            });
        });
    }

</script>
@endpush