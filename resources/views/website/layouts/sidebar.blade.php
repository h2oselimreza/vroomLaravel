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
<br>
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