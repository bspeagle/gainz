@extends('layouts.app')

@section('styles')
<link href="{{ asset('css/flapper.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container">
  <ol class="breadcrumb">
      <li><a href="/home">Home</a></li>
      <li class="active">Contest</li>
  </ol>
  <div class="row">
    <div class="col-md-4">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">Details:</h3>
        </div>            
        <div class="panel-body">
          <p>Owner: <span class="glyphicon glyphicon-user" aria-hidden="true"></span>{{ $ownerName }}</p>
          <p>Start: {{ $contest[0]->startDt }}</p>
          <p>End: {{ $contest[0]->endDt }}</p>
          @if ($cWeek[0]->week > 0)
            <br />
            <p>Current Week: {{ $cWeek[0]->week }}</p>
            <p>Start: {{ $cWeek[0]->startDt }}</p>
            <p>Weigh In: {{ $cWeek[0]->weighDt }}</p>
            <p>End: {{ $cWeek[0]->endDt }}</p>
          @endif
        </div>
      </div>
    </div>
    <div class="col-md-4">
    <div class="jumbotron" style="background-image: url(../images/diamondplate.jpeg); text-align: center">
          <h3 style="color: white;">{{ $contest[0]->name }}</h3>
          @if ($ownerId == $userId)
            <a class="btn btn-primary btn-lg" href="/contest/{{ $contest[0]->id }}/users" role="button">Manage Users</a>
          @endif
        </div>
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">Users:</h3>
        </div>            
        <div class="panel-body">
          <div class="row">
            @foreach ($cUsers as $cUser)
              <div class="col-sm-6">
                <span class="glyphicon glyphicon-user" aria-hidden="true"></span>{{ $cUser->name }}
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">Your Stats:</h3>
        </div>            
        <div class="panel-body">
          <h4>Starting Weight:</h4><input id="lbsDisplay" />
          <div class="row">
            <div class="col-lg-12">
              <br />
              @if ($cWeek[0]->week == '0')
                <ul>
                  @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
                <button id="lbsEditBtn" type="button" class="btn btn-primary btn-sm">Edit</button>
                <div id="lbsSaveBtnGroup" class="btn-group" role="group" aria-label="..." style="display: none;">
                  <button id="editSaveBtn" type="button" class="btn btn-success btn-sm" value="30">Save</button>
                  <button id="editCancelBtn" type="button" class="btn btn-default btn-sm" value="60">Cancel</button>
                </div>
              @endif
            </div>
          </div>
          @if ($cWeek[0]->week == '0')
            <div id="lbsEditGroup" class="row" style="display: none;">
              <br />
              <div class="col-lg-10">
                <input id="lbsEdit" class="input-border" style="width: 25%; font-size: 2em;" maxlength="3" />
                <p>You can only edit this field until the contest starts. Then it's "too bad so sad" for you.</p>
              </div>
              <div class="col-lg-2">
                <div id="lbsSpinner" style="padding: 10px;"></div>
              </div>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/spin.js/2.3.2/spin.js"></script>
  <script src="{{ asset('js/jquery.flapper.js') }}"></script>
  <script>
    window.Laravel = { csrfToken: '{{ csrf_token() }}' };
  </script>
  <script>
    var options = {width: 3};
    @if ($userLBS != '')
      $('#lbsDisplay').flapper(options).val({{ $userLBS }}).change();
    @else
      $('#lbsDisplay').flapper(options).val(0).change();
    @endif

    $('#lbsEditBtn').click(function() {
      $('#lbsEditGroup').show();
      $('#lbsSaveBtnGroup').show();
      $(this).hide();
    })

    $('#editCancelBtn').click(function() {
      $('#lbsSaveBtnGroup').hide();
      $('#lbsEditGroup').hide();
      $('#lbsEditBtn').show();
      $('#lbsEdit').val('');
      $('#lbsEdit').addClass('input-border');
      $('#lbsEdit').removeClass('input-glowing-border');
    })

    var spinner = new Spinner();

    $('#editSaveBtn').click(function() {
      if ($.isNumeric($('#lbsEdit').val())) {
        $('#lbsEdit').addClass('input-border');
        $('#lbsEdit').removeClass('input-glowing-border');

        $('#lbsEditGroup').hide();
        
        var target = document.getElementById('lbsSpinner');
        var spinner = new Spinner();
        spinner.spin(target);

        $.ajax({
          url: '/api/contest/user/stats',
          type: 'post',
          data: {
            contestId: {{ $contest[0]->id }},
            userId: {{ $userId }},
            weight: $('#lbsEdit').val()
          },
          headers: {
            'X-CSRF-TOKEN'    : window.Laravel.csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
          },
          dataType: 'json',
          success: function (data) {
            spinner.stop();
            $('#lbsDisplay').val($('#lbsEdit').val());
            $('#lbsDisplay').change();
            $('#lbsSaveBtnGroup').hide();
            $('#lbsEditBtn').show();
            $('#lbsEdit').val('');
          }
        });
      }
      else {
        $('#lbsEdit').addClass('input-glowing-border');
      }
    })
  </script>
@endsection