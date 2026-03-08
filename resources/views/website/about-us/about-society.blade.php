@extends('website.layouts.single-page')
@section('main-content')
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


@endsection