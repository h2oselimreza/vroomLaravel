@extends('website.layouts.single-page')
@section('main-content')
<div class="col-md-9">
    <div class="heading-custom-panel" style="">
        <div class="panel-heading">
            All Events
        </div>
    </div>
    <br>

    @php
        $serial = 1;
        $flag = 0;
    @endphp

    @if($events)
        @foreach($events as $event)

            @php
                if($serial == 6){
                    $serial = 0;
                    $flag = 1;
            @endphp
                <!--<div class="row">-->
            @php
                }
            @endphp

            <div class="col-md-3">
                <div class="heading-custom-panel" style="margin-bottom:10px">
                    <div class="pricing">
                        <div class="pricing-header">
                            <img src="{{ asset('assets/images/websiteImages/'.$event['image']) }}">
                        </div>
                        <div class="pricing-body">
                            <h5 class="pricing-title">{{ $event['heading'] }}</h5>
                            <p style="word-break: break-all;">
                                <a href="{{ url('event/event-details/'.$event['id']) }}" style="color:#444444;font-size: 14px">
                                    {{ $event['short_description'] }}
                                </a>
                            </p>
                            <div class="card-date">
                                {{ \Carbon\Carbon::parse($event['date'])->format('Y-M-d') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @php
                if($flag == 1){
                    $flag = 0;
            @endphp
                <!--</div>-->
                <!--<br>-->
            @php
                }
            @endphp

            @php $serial++; @endphp

        @endforeach
    @else
        no data found
    @endif

</div>
@endsection