@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
           <h4>Well... Thanks for nothing :) We do appreciate you clicking our link at least. Gotta find the good in the bad. That's what I always say.</h4>
           <h4>We'll make sure to tell the host you can't make it. I'm sure they'll understand...</h4><br />
            <div style="text-align: center;">{{ Html::image('images/sadwave.gif') }}</div>
        </div>
        <div class="col-md-3"></div>
    </div>
@endsection