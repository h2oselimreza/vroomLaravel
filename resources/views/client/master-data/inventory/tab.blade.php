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
        <a href="{{ route('client.master-data.inventory-category.index') }}">
            <button type="button" class="btn btn-<?php echo ($secondLastSegment == 'inventory-category') ? 'info' : 'default' ?> waves-effect btn-lg"> <i class="fa fa-tasks"></i><b>  Category</b></button>
        </a>
    </div>
    <div class="btn-group" role="group">
        <a href="{{ route('client.master-data.expense-head.index') }}">
            <button type="button" class="btn btn-<?php echo ($secondLastSegment == 'expense-head') ? 'info' : 'default' ?> waves-effect btn-lg"> <i class="fa fa-tasks"></i><b>  Product</b></button>
        </a>
    </div>
    <div class="btn-group" role="group">
        <a href="{{ route('client.master-data.expense-head.index') }}">
            <button type="button" class="btn btn-<?php echo ($secondLastSegment == 'expense-head') ? 'info' : 'default' ?> waves-effect btn-lg"> <i class="fa fa-tasks"></i><b>   Variant</b></button>
        </a>
    </div>
</div>