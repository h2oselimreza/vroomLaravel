@extends('website.layouts.single-page')
@section('main-content')
<div class="col-md-9">
    <div class="heading-custom-panel">
        <div class="panel-heading">
            All Achievements
        </div>
    </div>

    <br>

    @php
        $serial = 1;
        $flag = 0;
    @endphp
    @if(!empty($achievements) && count($achievements) > 0)

        @foreach($achievements as $achievement)

            @if($serial == 6)
                @php
                    $serial = 0;
                    $flag = 1;
                @endphp
            @endif

            <div class="col-md-3">
                <div class="heading-custom-panel" style="margin-bottom:10px">
                    <div class="pricing">

                        <div class="pricing-header">
                            <img src="{{ asset('assets/images/websiteImages/'.$achievement->image) }}" class="img-responsive">
                        </div>

                        <div class="pricing-body">
                            <h5 class="pricing-title">
                                {{ $achievement->heading }}
                            </h5>

                            <p style="word-break: break-all;">
                                <a href="{{ url('achievement-details/'.$achievement->id) }}" style="color:#444444;font-size:14px">
                                    {{ $achievement->short_description }}
                                </a>
                            </p>

                            <div class="card-date">
                                {{ \Carbon\Carbon::parse($achievement->date)->format('Y-M-d') }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            @if($flag == 1)
                @php $flag = 0; @endphp
            @endif

            @php $serial++; @endphp

        @endforeach

    @else
        <div class="col-md-12">
            <div class="no-data" style="padding: 15px; background: #f5f5f5; border: 1px solid #ddd; border-radius: 5px;text-align:center">
                <h4 style="color: #3c763d;">Content is not Available</h4>
                <p style="color: #777;">Content will be updated soon. Please check back later.</p>
            </div>
        </div>
    @endif

</div>
@endsection