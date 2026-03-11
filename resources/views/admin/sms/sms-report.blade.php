@extends('layouts.app')
@section('content')

<div class="header dashboard_from">
    <h1 class="page-title">SMS Balance</h1>
    <ul class="breadcrumb">
        <li><a href="#"> Home</a></li>
        <li><a href="#">/ SMS</a></li>
        <li><a href="#">/ SMS Balance</a></li>
    </ul>
</div>
<div class="main-content">
    <div class="row">
        <div class="main-content">
            <div class="row">
                <div class="col-sm-12 col-md-12">
                    <div class="panel panel-default">
                        <h3>
                            SMS Balance : <b>{{ $smsBalance }}</b>
                        </h3>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>
@endsection
