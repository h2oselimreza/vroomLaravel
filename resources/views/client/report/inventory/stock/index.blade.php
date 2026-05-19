@extends('client.layouts.app')
@section('content')
<div class="block-header">
    <h2>STOCK REPORT</h2><br>
    <div class="breadcrumb breadcrumb-bg-blue-grey">
        <li><a href="/client/Home"> Home</a></li>
        <li><a href="#"> Report</a></li>
        <li><a href="/client/Report/inventoryReport"> Inventory</a></li>
        <li><a href="/client/Report/currentStock"> Stock Report</a></li>
    </div>
</div>
<div class="row clearfix">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="body">
                @include('client.report.inventory.tab')
                <br>
                <div class="panel panel-default"> 
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12 col-sm-6 col-xs-12">
                                <div class="table-custom-responsive">
                                    <table class="table table-bordered table-hover custom-table dataTable">
                                        <thead>
                                            <tr class="bg-info">
                                                <th width="10%">SL</th>
                                                <th width="25%">Category</th>
                                                <th width="15%">Product</th>
                                                <th width="15%">Variant</th>
                                                <th width="15%">Quantity</th>
                                                <th width="15%">Unit Name</th>
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
                                            </tr>
                                        </tfoot>
                                        <tbody>

                                        @php
                                            $variantSerial = 1;
                                            $productCode = '';
                                            $bgColor = '#fcf8e3';
                                        @endphp

                                        @foreach ($variants as $variant)

                                            @php

                                                if ($productCode == '') {

                                                    $bgColor = '#fcf8e3';

                                                } elseif ($productCode != $variant->product) {

                                                    if ($bgColor == '#e5eaec') {
                                                        $bgColor = '#fcf8e3';
                                                    } else {
                                                        $bgColor = '#e5eaec';
                                                    }
                                                }

                                                $productCode = $variant->product;

                                            @endphp

                                            <tr style="background-color: {{ $bgColor }}">

                                                <td class="td-center">
                                                    {{ $variantSerial }}
                                                </td>

                                                <td>
                                                    {{ $variant->category_name }}
                                                </td>

                                                <td class="td-left">
                                                    {{ $variant->product_name }}
                                                </td>

                                                @if ($variant->variant_name == 'Default')

                                                    <td class="text-muted td-left">
                                                        <i>{{ $variant->variant_name }}</i>
                                                    </td>

                                                @else

                                                    <td class="td-left">
                                                        {{ $variant->variant_name }}
                                                    </td>

                                                @endif

                                                <td class="td-right">
                                                    {{ $variant->quantity }}
                                                </td>

                                                <td class="td-left">
                                                    {{ $variant->unit_name }}
                                                </td>

                                            </tr>

                                            @php
                                                $variantSerial++;
                                            @endphp

                                        @endforeach

                                    </tbody>
                                    </table>     
                                    <div class="text-left">
                                        <a target="_blank" href="{{ route('client.report.inventory-stock-report') }}" class="btn bg-blue waves-effect">Print Report</a>
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