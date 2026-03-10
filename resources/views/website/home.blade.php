@extends('website.layouts.web')
@section('content')
<style>
    .messageNameMargin {
        margin-bottom: 20px !important;
    }
    .message-divider{
        line-height: 1px;
    }
</style>
<div class="banner mt-3">
    <div class="container">

        <div class="row">
            <div class="col-md-9">
                <div class="custom-panel">
                    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">

                        <ol class="carousel-indicators">
                            @foreach($sliderImages as $key => $image)
                                <li data-target="#carousel-example-generic"
                                    data-slide-to="{{ $key }}"
                                    class="{{ $key == 0 ? 'active' : '' }}">
                                </li>
                            @endforeach
                        </ol>

                        <div class="carousel-inner" role="listbox">

                            @foreach($sliderImages as $image)

                                <div class="item {{ $loop->first ? 'active' : '' }}">
                                    <img src="{{ asset('assets/images/websiteImages/'.$image->image) }}">
                                </div>

                            @endforeach

                        </div>

                        <a class="left carousel-control"
                           href="#carousel-example-generic"
                           role="button"
                           data-slide="prev">

                            <span class="glyphicon glyphicon-chevron-left"></span>
                            <span class="sr-only">Previous</span>

                        </a>

                        <a class="right carousel-control"
                           href="#carousel-example-generic"
                           role="button"
                           data-slide="next">

                            <span class="glyphicon glyphicon-chevron-right"></span>
                            <span class="sr-only">Next</span>

                        </a>

                    </div>
                </div>
            </div>


            <div class="col-md-3">

                <div class="notices-custom-panel">

                    <div class="panel-heading">
                        Notices
                    </div>

                    <div class="custom-panel-body">

                        <marquee direction="up"
                                 onmouseover="this.stop();"
                                 onmouseout="this.start();"
                                 scrolldelay="100">

                            <table>

                                @foreach($noticeLists as $notice)

                                    @php
                                        $date = \Carbon\Carbon::parse($notice->publish_date);
                                    @endphp

                                    <tr style="border-bottom:1px solid #ddd;border-top:1px solid #ddd">

                                        <td style="color:white;background-color:#3c763d;padding:0px 15px;vertical-align:middle">

                                            {{ $date->format('d') }}
                                            <br>
                                            {{ $date->format('M') }}

                                        </td>

                                        <td class="custom-vertical-text">
                                            {{ $date->format('Y') }}
                                        </td>

                                        <td>

                                            <a href="{{ url('Home/showNotice/1/'.$notice->id) }}"
                                               style="color:#3C3C3C">

                                                {{ $notice->heading }}

                                            </a>

                                        </td>

                                    </tr>

                                @endforeach

                            </table>

                        </marquee>


                        <div class="text-center">

                            <a href="{{ url('Home/specialEvent') }}">

                                <img src="{{ asset('assets/website/images/company/event1.jpg') }}"
                                     class="notice-event-image">

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
</div>
<br>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="news-custom-panel">
                <div class="row">
                    <div class="col-md-2">
                        <b style="color:#b32323 ">Announcement:</b>
                    </div>
                    <div class="col-md-10">
                        <marquee onmouseover="this.stop();" onmouseout="this.start();">
                            @foreach ($newsLists as $newsList)
                                <a style='color:#b32323' href='{{ url("Home/showNews/1/".$newsList->id) }}'>{{ $newsList->heading }}</a>
                                |
                            @endforeach
                        </marquee>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<br>
<div class="container">
    <div class="row">

        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <a href="{{ url('about-society') }}">
                        <div class="heading-custom-panel" style="">
                            <div class="panel-heading">
                                {{ isset($aboutSociety->heading) ? $aboutSociety->heading : NULL }}
                            </div>
                            <div class="custom-panel-body">
                                <div class="row">
                                    <div class="col-md-12 home-about-society-body">
                                        {!! isset($aboutSociety->short_description) ? $aboutSociety->short_description : NULL !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-3">
                    <a href="{{ url('apply-member-ship') }}" class="no-hover-color">
                        <div class="heading-custom-panel">
                            <div class="about-two">
                                <div class="about-two-header">
                                    <img src="{{ url('assets/website/images/company/apply-for-mambership.png') }}"
                                         style="width: 60px">
                                </div>
                                <div class="about-two-body">
                                    Apply For Membership
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="{{ url('apply-for-car-sticker') }}" class="no-hover-color">
                        <div class="heading-custom-panel">
                            <div class="about-two">
                                <div class="about-two-header">
                                    <img src="{{ url('assets/website/images/company/car-sticker.png') }}"
                                         style="width: 60px">
                                </div>
                                <div class="about-two-body">
                                    Apply For Car Sticker
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="{{ url('life-members') }}" class="no-hover-color">
                        <div class="heading-custom-panel">
                            <div class="about-two">
                                <div class="about-two-header">
                                    <img src="{{ url('assets/website/images/company/complaint-form.png') }}"
                                         style="width: 60px">
                                </div>
                                <div class="about-two-body">
                                    Complaint Form
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="{{ url('donar-members') }}" class="no-hover-color">
                        <div class="heading-custom-panel">
                            <div class="about-two">
                                <div class="about-two-header">
                                    <img src="{{ url('assets/website/images/emergencyWebsiteLogo/national_help_desk.png') }}"
                                         style="width: 60px">
                                </div>
                                <div class="about-two-body">
                                    Contact Us
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="heading-custom-panel">
                <div class="panel-heading">
                    Prayer Time
                </div>
                <div class="custom-panel-body">
                    <table class="table table-striped prayerTable">
                        <tr>
                            <td><b style="color:#ed7b2b">Today</b></td>
                            <td>
                                <b style="color:#ed7b2b">{{ date('d M Y', strtotime($prayerTime['prayer_date'])) }}</b>
                            </td>
                        </tr>
                        <tr>
                            <td>Fajr</td>
                            <td>{{ $prayerTime['fajr'] }}</td>
                        </tr>
                        <tr>
                            <td>Zuhr</td>
                            <td>{{ $prayerTime['zuhor'] }}</td>
                        </tr>
                        <tr>
                            <td>Asor</td>
                            <td>{{ $prayerTime['asor'] }}</td>
                        </tr>
                        <tr>
                            <td>Maghrib</td>
                            <td>{{ $prayerTime['maghrib'] }}</td>
                        </tr>
                        <tr>
                            <td>Isha</td>
                            <td>{{ $prayerTime['isha'] }}</td>
                        </tr>
                        <tr>
                            <td>Jumma</td>
                            <td>{{ $prayerTime['jumma'] }}</td>
                        </tr>
                        <tr>
                            <td>Sunrise</td>
                            <td>{{ $prayerTime['sunrise'] }}</td>
                        </tr>
                        <tr>
                            <td>Sunset</td>
                            <td>{{ $prayerTime['sunset'] }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<br>

<div class="container">
    <div class="row">
        <div class="col-md-12">

            @foreach($presidentSecretary as $index => $person)
                @php
                    $links = [
                        0 => 'message-from-president',
                        1 => 'message-from-general-secretary',
                        2 => 'message-from-office-secretary',
                        3 => 'message-from-pnp-secretary',
                    ];
                    $defaultImages = asset('assets/images/user.png');
                    $imageUrl = $person->image ? asset('assets/images/websiteImages/' . $person->image) : $defaultImages;

                    $names = [
                        0 => 'Prof Dr Md Abul Bashar',
                        1 => 'Md Rezaul Karim Chowdhury Arif',
                        2 => 'J C Md Abdur Rouf',
                        3 => 'Engr Md Moazzem Hossain Bhuiyan',
                    ];

                    $designations = [
                        0 => 'President',
                        1 => 'General Secretary',
                        2 => 'Office Secretary',
                        3 => 'Prog. & Pub. Secretary',
                    ];
                @endphp

                <div class="col-md-3">
                    <div class="row {{ $index > 0 ? 'messageRowLeft' : '' }}" style="{{ $index > 1 ? '//margin-left:1px;' : '' }}">
                        <a href="{{ url($links[$index]) }}">
                            <div class="heading-custom-panel" style="">
                                <div class="panel-heading">
                                    {{ $person->heading ?? null }}
                                </div>
                                <div class="custom-panel-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="messageImage">
                                                <img src="{{ $imageUrl }}" style="height:100px">
                                            </div>
                                            <br>
                                            <div class="messageNameDesignation">
                                                <div class="messageName <?= ($names[$index] == 'J C Md Abdur Rouf') ? 'messageNameMargin':''?>">{{ $names[$index] }}</div>
                                                <div class="message-divider"><span></span></div>
                                                <div>{{ $designations[$index] }}</div>
                                                <div class="message-divider"><span></span></div>
                                                <div>Niketan Society</div>
                                                <div class="message-divider"><span></span></div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-12 home-message-body">
                                            {{ $person->short_description ?? null }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

            @endforeach

        </div>
    </div>
</div>
<br>

<div class="container">

    <div class="row">
        <div class="col-md-12">
            <div class="heading-custom-panel" style="">
                <div class="panel-heading">
                    Emergency Contact
                </div>
            </div>
        </div>
    </div>
</div>
<br>


<div class="container">
    <div class="row">
        <div class="col-md-2">
            <div class="heading-custom-panel">
                <div class="pricing">
                    <div class="pricing-header">
                        <img src="{{ asset('assets/website/images/emergencyWebsiteLogo/national_help_desk.png') }}"
                             style="width: 40px">
                    </div>
                    <div class="pricing-body">
                        <h5 class="pricing-title">Niketan Emergency</h5>
                        <p style="word-break: break-all;"></p>
                        <div class="card-date">+88 01959906940<br>
                            +88 01959906941
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="heading-custom-panel">
                <div class="pricing">
                    <div class="pricing-header">
                        <img src="{{ asset('assets/website/images/emergencyWebsiteLogo/secuirity.png') }}"
                             style="width: 40px">
                    </div>
                    <div class="pricing-body">
                        <h5 class="pricing-title">Niketan Sequrity</h5>
                        <p style="word-break: break-all;"></p>
                        <div class="card-date"> +88 01739042901<br>
                            +88 01959906911
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="heading-custom-panel">
                <div class="pricing">
                    <div class="pricing-header">
                        <img src="{{ asset('assets/website/images/emergencyWebsiteLogo/ambulance.png') }}"
                             style="width: 40px">
                    </div>
                    <div class="pricing-body">
                        <h5 class="pricing-title">Niketan Ambulance</h5>
                        <p style="word-break: break-all;"></p>
                        <div class="card-date"> +88 01301728270<br><br></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="heading-custom-panel">
                <div class="pricing">
                    <div class="pricing-header">
                        <img src="{{ asset('assets/website/images/emergencyWebsiteLogo/heart.png') }}"
                             style="width: 40px">
                    </div>
                    <div class="pricing-body">
                        <h5 class="pricing-title">Niketan Health Care</h5>
                        <p style="word-break: break-all;"></p>
                        <div class="card-date"> +88 01959906920<br><br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="heading-custom-panel">
                <div class="pricing">
                    <div class="pricing-header">
                        <img src="{{ asset('assets/website/images/emergencyWebsiteLogo/hospital.png') }}"
                             style="width: 40px">
                    </div>
                    <div class="pricing-body">
                        <h5 class="pricing-title">United Hospital</h5>
                        <p style="word-break: break-all;"></p>
                        <div class="card-date">10666, +88 02 9852466<br>
                            +88 01914001234
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="heading-custom-panel">
                <div class="pricing">
                    <div class="pricing-header">
                        <img src="{{ asset('assets/website/images/emergencyWebsiteLogo/police.png') }}"
                             style="width: 40px">
                    </div>
                    <div class="pricing-body">
                        <h5 class="pricing-title">Police</h5>
                        <p style="word-break: break-all;"></p>
                        <div class="card-date"> +88 02 9619999, 9895826<br>
                            +88 01713373171
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <br>

    <div class="row">
        <div class="col-md-2">
            <div class="heading-custom-panel">
                <div class="pricing">
                    <div class="pricing-header">
                        <img src="{{ asset('assets/website/images/emergencyWebsiteLogo/rab.png') }}"
                             style="width: 40px">
                    </div>
                    <div class="pricing-body">
                        <h5 class="pricing-title">Rab</h5>
                        <p style="word-break: break-all;"></p>
                        <div class="card-date"> +88 02 7913158, 7913154<br>
                            +88 017777101999
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="heading-custom-panel">
                <div class="pricing">
                    <div class="pricing-header">
                        <img src="{{ asset('assets/website/images/emergencyWebsiteLogo/desco.png') }}"
                             style="width: 40px">
                    </div>
                    <div class="pricing-body">
                        <h5 class="pricing-title">Desco</h5>
                        <p style="word-break: break-all;"></p>
                        <div class="card-date">+88 02 9895120, 9895045<br>
                            +88 01713443013
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="heading-custom-panel">
                <div class="pricing">
                    <div class="pricing-header">
                        <img src="{{ asset('assets/website/images/emergencyWebsiteLogo/wasa.png') }}"
                             style="width: 40px">
                    </div>
                    <div class="pricing-body">
                        <h5 class="pricing-title">Wasa</h5>
                        <p style="word-break: break-all;"></p>
                        <div class="card-date"> 16162, +88 02 9899338-9<br>
                            +88 01819229416
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="heading-custom-panel">
                <div class="pricing">
                    <div class="pricing-header">
                        <img src="{{ asset('assets/website/images/emergencyWebsiteLogo/fire-service.png') }}"
                             style="width: 40px">
                    </div>
                    <div class="pricing-body">
                        <h5 class="pricing-title">Fire Service</h5>
                        <p style="word-break: break-all;"></p>
                        <div class="card-date">+88 02 9555555, 9556666-7<br>
                            +88 01730336699
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="heading-custom-panel">
                <div class="pricing">
                    <div class="pricing-header">
                        <img src="{{ asset('assets/website/images/emergencyWebsiteLogo/titas.png') }}"
                             style="width: 40px">
                    </div>
                    <div class="pricing-body">
                        <h5 class="pricing-title">Titas</h5>
                        <p style="word-break: break-all;"></p>
                        <div class="card-date">16496, +88 02 9891054<br>
                            +88 01955500497
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="heading-custom-panel">
                <div class="pricing">
                    <div class="pricing-header">
                        <img src="{{ asset('assets/website/images/emergencyWebsiteLogo/btcl.png') }}"
                             style="width: 40px">
                    </div>
                    <div class="pricing-body">
                        <h5 class="pricing-title">BTCL</h5>
                        <p style="word-break: break-all;"></p>
                        <div class="card-date"> 16402, +88 02 9320000<br>
                            +88 02 9320111, 9320222
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<br>

<!-- ------ Events ---------------- -->

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="heading-custom-panel" style="">
                <div class="panel-heading">
                    Events
                </div>
            </div>
        </div>
    </div>
</div>
<br>
<div class="container">
    <div class="row">
        @php $serial = 1; @endphp
        @if($events && count($events) > 0)
            @foreach($events as $event)
                <div class="col-md-2">
                    <div class="heading-custom-panel">
                        <div class="pricing">
                            <div class="pricing-header">
                                <img src="{{ asset('assets/images/websiteImages/' . $event->image) }}">
                            </div>
                            <div class="pricing-body">
                                <h5 class="pricing-title">{{ $event->heading }}</h5>
                                <p style="word-break: break-all;">
                                    <a href="{{ url('event/event-details/' . $event->id) }}"
                                       style="color:#444444;font-size: 14px">{{ $event->short_description }}</a>
                                </p>
                                <div class="card-date">{{ date('Y-M-d', strtotime($event->date)) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                @php
                    if($serial == 6) break;
                    $serial++;
                @endphp
            @endforeach
        @else
            no data found
        @endif
    </div>
</div>
<br>
<!-- ------ Facilities ---------------- -->
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="heading-custom-panel" style="">
                <div class="panel-heading">
                    Facilities
                </div>
            </div>
        </div>
    </div>
</div>
<br>
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="banner-outer-div">
                <div class="banner-inner-div">
                    <a href="{{ url('gym') }}"><img
                                src="{{ asset('assets/images/adBanner/gym.jpg') }}" alt="Clients Logo"
                                class="banner-image"></a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="banner-outer-div second-banner">
                <div class="banner-inner-div">
                    <a href="{{ url('convention') }}"><img
                                src="{{ asset('assets/images/adBanner/hall.jpg') }}" alt="Clients Logo"
                                class="banner-image"></a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="banner-outer-div second-banner">
                <div class="banner-inner-div">
                    <a href="{{ url('memberLounge') }}"><img
                                src="{{ asset('assets/images/adBanner/member-lounge.jpg') }}" alt="Clients Logo"
                                class="banner-image"></a>
                </div>
            </div>
        </div>
    </div>
</div>
<br>

<!-- ------ Achievements ---------------- -->

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="heading-custom-panel" style="">
                <div class="panel-heading">
                    Achievements
                </div>
            </div>
        </div>
    </div>
</div>
<br>
<div class="container">
    <div class="row">
        @php $serial = 1; @endphp
        @if($achievements)
            @foreach($achievements as $achievement)
                <div class="col-md-2">
                    <div class="heading-custom-panel">
                        <div class="pricing">
                            <div class="pricing-header">
                                <img src="{{ asset('assets/images/websiteImages/' . $achievement->image) }}">
                            </div>
                            <div class="pricing-body">
                                <h5 class="pricing-title">{{ $achievement->heading }}</h5>
                                <p style="word-break: break-all;">
                                    <a href="{{ url('achievement-details/' . $achievement->id) }}"
                                       style="color:#444444;font-size: 14px">{{ $achievement->short_description }}</a>
                                </p>
                                <div class="card-date">{{ date('Y-M-d', strtotime($achievement->date)) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                @php
                    if($serial == 6) break;
                    $serial++;
                @endphp
            @endforeach
        @else
            no data found
        @endif
    </div>
</div>
<br>

<!-- ------ Birth day and anniversary ----------- -->

<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="heading-custom-panel" style="">
                <div class="panel-heading">
                    Happy Birthday
                </div>
            </div>
            
            <div class="row">
                @if($birthDayMembers)
                    <div class="col-md-12">
                        <div class="birthday-anniversary-body">
                            <ul id="birthday-links">
                                <marquee onmouseover="this.stop();" onmouseout="this.start();">
                                    @foreach($birthDayMembers as $birthDayMember)
                                        @php
                                            $imageUrl = asset('assets/images/user.png');
                                            if(isset($birthDayMember->member_image) && file_exists(public_path('assets/images/member/' . $birthDayMember->member_image))) {
                                                $imageUrl = asset('assets/images/member/' . $birthDayMember->member_image);
                                            }
                                        @endphp
                                        <li class="text-center">
                                            <div class="heading-custom-panel">
                                                <div class="pricing-header">
                                                    <img src="{{ $imageUrl }}" style="width:120px">
                                                </div>
                                                <div style="padding: 5px">
                                                    {{ $birthDayMember->member_name }}
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </marquee>
                            </ul>
                        </div>
                    </div>
                @else
                    <div class="col-md-12">
                        <div class="birthday-anniversary-body">
                            <p><b><h4>No Birthday Today</h4></b></p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-md-6">
            <div class="heading-custom-panel" style="">
                <div class="panel-heading">
                    Happy Anniversary
                </div>
            </div>
            
            <div class="row">
                @if($anniversaryMembers)
                    <div class="col-md-12">
                        <div class="birthday-anniversary-body">
                            <ul id="birthday-links">
                                <marquee onmouseover="this.stop();" onmouseout="this.start();">
                                    @foreach($anniversaryMembers as $anniversaryMember)
                                        @php
                                            $imageUrl = asset('assets/images/user.png');
                                            if(isset($anniversaryMember->member_image) && file_exists(public_path('assets/images/member/' . $anniversaryMember->member_image))) {
                                                $imageUrl = asset('assets/images/member/' . $anniversaryMember->member_image);
                                            }
                                        @endphp
                                        <li class="text-center">
                                            <div class="heading-custom-panel">
                                                <div class="pricing-header">
                                                    <img src="{{ $imageUrl }}" style="width:120px">
                                                </div>
                                                <div class="pricing-body" style="padding: 5px">
                                                    {{ $anniversaryMember->member_name }}
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </marquee>
                            </ul>
                        </div>
                    </div>
                @else
                    <div class="col-md-12">
                        <div class="birthday-anniversary-body">
                            <p><b><h4>No Anniversary Today</h4></b></p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<br>

<!-- ------ Niketan in short ---------------- -->

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="heading-custom-panel" style="">
                <div class="panel-heading">
                    Niketan In Short
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="in-short">
        <div class="row">

            <div class="col-md-3">
                <div class="inshort-panel">
                    <div class="inshort-header counter" data-count="{{ $dashboardCount['lifeMemberCount'] ?? 0 }}">0
                    </div>
                    <div class="inshort-body">Life Member</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="inshort-panel">
                    <div class="inshort-header counter" data-count="{{ $dashboardCount['donarMemberCount'] ?? 0 }}">0
                    </div>
                    <div class="inshort-body">Donor Member</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="inshort-panel">
                    <div class="inshort-header counter" data-count="{{ $dashboardCount['blockCount'] ?? 0 }}">0</div>
                    <div class="inshort-body">Block</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="inshort-panel">
                    <div class="inshort-header counter" data-count="{{ $dashboardCount['roadCount'] ?? 0 }}">0</div>
                    <div class="inshort-body">Road</div>
                </div>
            </div>

        </div>
    </div>
</div>
<br>
<!-- ------ Important Website Links ---------------- -->

<div class="container">
    <div class="row">
        <div class="col-md-12" >
            <div class="heading-custom-panel" style="">
                <div class="panel-heading">
                    Important Website Links
                </div>
                <div class="website-links-panel" >
                    <ul id="website-links">
                        <marquee  onmouseover="this.stop();" onmouseout="this.start();">
                            <li class="text-center">
                                <a href="http://www.rab.gov.bd/" target="_blank"><img src="{{ asset('assets/website/images/importantWebsiteLogo/1.png') }}" style="width:100px"></a>
                            </li>
                            <li class="text-center">
                                <a href="https://dmp.gov.bd/" target="_blank"><img src="{{ asset('assets/website/images/importantWebsiteLogo/2.png') }}" style="width:100px"></a>
                            </li>
                            <li class="text-center">
                                <a href="http://www.police.gov.bd/" target="_blank"><img src="{{ asset('assets/website/images/importantWebsiteLogo/3.png') }}" style="width:100px"></a>
                            </li>
                            <li class="text-center">
                                <a href="https://www.army.mil.bd/" target="_blank"><img src="{{ asset('assets/website/images/importantWebsiteLogo/4.png') }}" style="width:100px"></a>
                            </li>
                            <li class="text-center">
                                <a href="http://www.navy.mil.bd/" target="_blank"><img src="{{ asset('assets/website/images/importantWebsiteLogo/5.png') }}" style="width:100px"></a>
                            </li>
                            <li class="text-center">
                                <a href="https://www.baf.mil.bd/" target="_blank"><img src="{{ asset('assets/website/images/importantWebsiteLogo/6.png') }}" style="width:100px"></a>
                            </li>
                            <li class="text-center">
                                <a href="http://www.fireservice.gov.bd/" target="_blank"><img src="{{ asset('assets/website/images/importantWebsiteLogo/7.png') }}" style="width:100px"></a>
                            </li>
                            <li class="text-center">
                                <a href="http://www.btcl.com.bd/" target="_blank"><img src="{{ asset('assets/website/images/importantWebsiteLogo/8.png') }}" style="width:100px"></a>
                            </li>
                            <li class="text-center">
                                <a href="https://www.titasgas.org.bd/" target="_blank"><img src="{{ asset('assets/website/images/importantWebsiteLogo/9.png') }}" style="width:100px"></a>
                            </li>
                            <li class="text-center">
                                <a href="https://dwasa.org.bd/" target="_blank"><img src="{{ asset('assets/website/images/importantWebsiteLogo/10.png') }}" style="width:100px"></a>
                            </li>
                            <li class="text-center">
                                <a href="http://www.bangladesh.gov.bd/" target="_blank"><img src="{{ asset('assets/website/images/importantWebsiteLogo/11.png') }}" style="width:100px"></a>
                            </li>
                            <li class="text-center">
                                <a href="https://www.desco.org.bd/" target="_blank"><img src="{{ asset('assets/website/images/importantWebsiteLogo/12.png') }}" style="width:100px"></a>
                            </li>
                            <li class="text-center">
                                <a href="http://a2i.pmo.gov.bd/" target="_blank"><img src="{{ asset('assets/website/images/importantWebsiteLogo/13.png') }}" style="width:100px"></a>
                            </li>
                            <li class="text-center">
                                <a href="http://www.du.ac.bd/" target="_blank"><img src="{{ asset('assets/website/images/importantWebsiteLogo/14.png') }}" style="width:100px"></a>
                            </li>
                            <li class="text-center">
                                <a href="http://www.nu.edu.bd/home/" target="_blank"><img src="{{ asset('assets/website/images/importantWebsiteLogo/15.png') }}" style="width:100px"></a>
                            </li>
                            <li class="text-center">
                                <a href="http://www.dncc.gov.bd/" target="_blank"><img src="{{ asset('assets/website/images/importantWebsiteLogo/16.png') }}" style="width:100px"></a>
                            </li>
                        </marquee>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection