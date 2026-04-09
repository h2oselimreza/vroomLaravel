<div class="navbar-header">
    <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
    <a href="javascript:void(0);" class="bars" style="display: none;"></a>
    
    <a class="navbar-brand" href="{{ url('client/Home') }}">
        <img src="{{ asset('assets/client/images/vroom_white_logo.png') }}" 
            style="height: 31px; margin-top: -6px;" 
            alt="Logo">
    </a>
</div>

<div class="collapse navbar-collapse" id="navbar-collapse">
    <ul class="nav navbar-nav navbar-right">
        {{-- <li><a href="javascript:void(0);" class="js-search" data-close="true"><i class="material-icons">search</i></a></li> --}}
    </ul>

    <ul class="nav navbar-nav navbar-right">
        <li>
            <a href="javascript:void(0);" class="font-20" style="color:#fff">
                {{ $companyName ?? 'BRACK BANK PLC.' }}
            </a>
        </li>
    </ul>
</div>