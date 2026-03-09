@extends('website.layouts.single-page')
@section('main-content')
    @if (isset($data))
    <div class="col-md-9">
        <div class="heading-custom-panel" style="">
            <div class="panel-heading">
                {{ $data->heading }}
            </div>
            <div class="custom-panel-body">
                <div class="text-justify" style="">
                    {!! $data->description !!}
                </div>
            </div>
        </div>
    </div>
    @else
        <div class="col-md-9">
            <div class="no-data" style="padding: 15px; background: #f5f5f5; border: 1px solid #ddd; border-radius: 5px;text-align:center">
                <h4 style="color: #3c763d;">Content is not Available</h4>
                <p style="color: #777;">Content will be updated soon. Please check back later.</p>
            </div>
        </div>
    @endif
@endsection

