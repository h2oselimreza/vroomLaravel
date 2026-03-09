@extends('website.layouts.single-page')
@section('main-content')
<div class="col-md-9">
    <div class="heading-custom-panel">
        <div class="panel-heading">
            {{ $pageHeading }}
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            <div style=" height: 650px;  overflow-y: auto;">
                <table class="table member-table">
                    <tr style="background-color: #3c763d;color:white">
                        <th class="text-center">SL</th>
                        <th class="text-center" style="width: 200px">Image</th>
                        <th>Name & Address</th>
                        <th class="text-center">Member Id</th>
                    </tr>

                    @php $sl = 1; @endphp
                    @foreach($members as $member)
                        @php
                            $imageUrl = asset('assets/images/user.png');
                            if(!empty($member['member_image']) && file_exists(public_path('assets/images/member/' . $member['member_image']))) {
                                $imageUrl = asset('assets/images/member/' . $member['member_image']);
                            }
                        @endphp

                        <tr style="background-color: white">
                            <td class="text-center vertical-middle">{{ $sl }}</td>
                            <td class="text-center">
                                <img src="{{ $imageUrl }}" style="width:80px">
                            </td>
                            <td class="vertical-middle" style="word-break: break-all">
                                <b>{{ $member['member_name'] }}</b>
                                <p>
                                    {{ "Flat: " . ($member['society_flat'] ?? "N/A") .
                                       ", Plot/House: " . ($member['society_plot'] ?? "N/A") .
                                       ", Road: " . ($member['road_name'] ?? "N/A") .
                                       ", Block: " . ($member['block_name'] ?? "N/A") .
                                       ", Niketan, Gulshan, Dhaka-1212" }}
                                </p>
                            </td>
                            <td class="text-center vertical-middle" style="width: 150px">
                                {{ $donarMember == 1 ? $member['donar_member_id'] : $member['member_id'] }}
                            </td>
                        </tr>

                        @php $sl++; @endphp
                    @endforeach

                </table>
            </div>
        </div>
    </div>
</div>
@endsection