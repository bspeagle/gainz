@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-9">
            <div class="jumbotron">
                <h1 class="display-3">Hello, {{Auth::user()->name}}!</h1>
                <p class="lead">Welcome back to Gainz!</p>
                <p class="lead">
                    <a class="btn btn-primary btn-lg" href="/contest" role="button">Create a new contest!</a>
                </p>
                <p>Member since: {{ date('m/d/Y', strtotime(Auth::user()->created_at)) }}</p>
            </div>
        </div>
        <div class="col-sm-3">
           @include('messages', ['invites' => $invites])
        </div>
    </div>
</div>

<div class="container">
    <div id="contestsDiv" class="row">
        @include('contestMini', ['contests' => $contests])
    </div>
</div>
@endsection
