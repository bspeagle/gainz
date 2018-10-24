@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <p>You've been invited to join contest {{ $iRecord[0]->name }}!</p>
            <p>Click accept to accept this invitation. Select decline to reject this invitation.</p>
            <ul>
                @foreach($errors->all() as $error)
                     <li>{{ $error }}</li>
                @endforeach
            </ul>
            {!! Form::open(array('route' => 'respondInvite', 'class' => 'form', 'id' => 'inviteConfirmForm')) !!}
            {{ Form::hidden('inviteUUID', $iRecord[0]->inviteUUID,
                array('id' => 'inviteUUID')) }}
            {!! Form::submit('Accept',
                array('name'=>'submitBtn',
                    'class'=>'btn btn-success')) !!}
            {!! Form::submit('Decline',
                array('name'=>'submitBtn',
                    'class'=>'btn btn-danger')) !!}
            {!! Form::close() !!}
        </div>
        <div class="col-md-3"></div>
    </div>
@endsection