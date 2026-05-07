@extends('client.layouts.app')

@section('content')

<div class="block-header">
    <h2>VENDOR LIST</h2><br>
    <div class="breadcrumb breadcrumb-bg-blue-grey">
        <li><a href="/client/Home"> Home</a></li>
        <li><a href="#"> Master Data</a></li>
        <li><a href="/client/MasterData/vendor"> Vendor List</a></li>
    </div>
</div>
<div class="row clearfix">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="body">
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
                <a href="{{ route('client.vendor.venor-list.create') }}" class="btn bg-blue waves-effect">Add Vendor</a>
                <br><br>
                <div class="table-custom-responsive">
                    <table class="table table-bordered table-hover custom-table dataTable">
                        <thead>
                            <tr class="bg-info">
                                <th>SL</th>
                                <th>Vendor Title/Name</th>
                                <th>Vendor Code</th>
                                <th>Address</th>
                                <th>Mobile</th>
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
                        @php
                            $count = 1;
                        @endphp

                        @foreach ($vendors as $vendor)
                            <tr>

                                {{-- Serial --}}
                                <td class="td-center">{{ $count }}</td>

                                {{-- Vendor Info --}}
                                <td>{{ $vendor->title }}</td>
                                <td class="td-center">{{ $vendor->vendor_code }}</td>
                                <td>{{ $vendor->address }}</td>
                                <td>{{ $vendor->vendor_mobile }}</td>

                                {{-- Status --}}
                                <td class="td-center">
                                    @if ($vendor->is_active == 1)
                                        <span class="text-success">Active</span>
                                    @else
                                        <span class="text-danger">Inactive</span>
                                    @endif
                                </td>

                                {{-- Action --}}
                                <td class="td-center">
                                    <div class="btn-group">
                                        <button type="button"
                                                class="btn btn-default btn-xs dropdown-toggle"
                                                data-toggle="dropdown"
                                                aria-haspopup="true"
                                                aria-expanded="false">
                                            Action <span class="caret"></span>
                                        </button>

                                        <ul class="dropdown-menu pull-right">

                                            {{-- Edit --}}
                                            <li>
                                                <a href="{{ route('client.vendor.venor-list.edit',$vendor->vendor_code) }}">
                                                    Edit
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>

                            </tr>

                            @php
                                $count++;
                            @endphp
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