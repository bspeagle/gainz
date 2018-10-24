@extends('layouts.app')

@section('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.css" rel="stylesheet" />
@endsection

@section('content')
<ul>
@foreach($errors->all() as $error)
    <li>{{ $error }}</li>
@endforeach
</ul>

{!! Form::open(array('route' => 'createContest', 'class' => 'form', 'id' => 'contestCreateForm')) !!}
<br />
<div class="container" style="width: 50%;">
<ol class="breadcrumb">
    <li><a href="/home">Home</a></li>
    <li class="active">New Contest</li>
</ol>
    <div class="jumbotron">
        <div class="form-group">
            {!! Form::label('Contest Name: ') !!}
            {!! Form::text('name', null, 
                array('required', 
                'class'=>'form-control', 
                'placeholder'=>'A fun contest name!')) !!}
        </div>
        <div class="form-group">
            {!! Form::label('Start Date: ') !!}
            <div id="datePicker1" class="input-group date">
                {!! Form::text('startDt', null,
                    array('required',
                    'class'=>'form-control')) !!}
                    <div class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </div>
            </div>
        </div>
        <div clas="form-group">
            <label for="btnGroup">Interval: </label>
            <div id="btnGroup" class="btn-group" role="group" aria-label="...">
                <button type="button" class="btn btn-default" value="28">4 Weeks</button>
                <button type="button" class="btn btn-default" value="56">8 Weeks</button>
            </div>
        </div><br />
        <div class="form-group">
            {!! Form::label('End Date: ') !!}
            <div id="datePicker2" class="input-group date">
                {!! Form::text('endDt', null,
                    array('required',
                    'class'=>'form-control')) !!}
                    <div class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </div>
            </div>
        </div>
        <div class="form-group">
        {!! Form::submit('Create Contest!', 
        array('class'=>'btn btn-primary')) !!}
        </div>
    </div>
</div>
{!! Form::close() !!}
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.js"></script>
<script>
    //enable date picker
    $('.date').datepicker({daysOfWeekDisabled: "0,2,3,4,5,6"});
    
    //calculate interval and set end date
    $(".btn-group > button.btn").on("click", function(){
        if (this.value != "99" && $('#datePicker1 input').val() != "") {
            var dt = new Date($('#datePicker1 input').val());
            dt.setTime(dt.getTime() + this.value * 86400000);
            $('#datePicker2 input').val(dt.toLocaleDateString());
        }
        else if (this.value == "99") {
            //do nothing
        }
    });

    $('[name="startDt"]').change(function() {
        $('[name="endDt"]').val('');
    })
</script>
@endsection