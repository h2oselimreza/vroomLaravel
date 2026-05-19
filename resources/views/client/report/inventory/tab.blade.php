@php
    $btnFlag = 0;
    $disableFlag = isset($vendor->exists) ? 0 : 1;

    $segments = request()->segments();
    $lastSegment = request()->segment(count($segments));
    $secondLastSegment = request()->segment(count($segments) - 0);
    $thirdLastSegment = $segments[2];
@endphp

<div class="btn-group btn-group-lg hidden-xs btn-group-justified" role="group" aria-label="...">
    <div class="btn-group" role="group">
        <a href="{{ route('client.report.inventory-stock-in-list.index') }}">
            <button type="button" class="btn btn-{{ ($secondLastSegment == 'inventory-stock-in-list') ? 'info' : 'default' }} waves-effect btn-lg"> <i class="fa fa-plus-square-o"></i><b>  Stock In</b></button>
        </a>
    </div>
    <div class="btn-group" role="group">
        <a href="{{ route('client.inventory.stock-out.index') }}">
            <button type="button" class="btn btn-{{ (($thirdLastSegment == 'stock-out')) ? 'info' : 'default' }} waves-effect btn-lg"> <i class="fa fa-minus-square-o"></i><b>  Stock Out</b></button>
        </a>
    </div>
    <div class="btn-group" role="group">
        <a href="{{ route('client.inventory.stock-out.index') }}">
            <button type="button" class="btn btn-{{ (($thirdLastSegment == 'stock-out')) ? 'info' : 'default' }} waves-effect btn-lg"> <i class="fa a fa-bars"></i><b>  Stock</b></button>
        </a>
    </div>
</div>