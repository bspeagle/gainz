@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
    <ol class="breadcrumb">
        <li><a href="/home">Home</a></li>
        <li><a href="/contest/{{ $contestId }}">Contest</a></li>
        <li class="active">Manage Users</li>
    </ol>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#inviteModal">Invite User</button><br /><br />
    <table class="table table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Owner</th>
                <th>Status</th>
                <th>Options</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cUsers as $user)
                <tr>
                    <th scope="row">{{ $user->id }}</th>
                    @if ($user->name == '0')
                        <td>?????</td>
                    @else
                        <td>{{ $user->name }}</td>
                    @endif
                    <td>{{ $user->email }}</td>
                    @if ($user->owner == 1)
                        <td>True</td>
                    @else
                        <td>False</td>
                    @endif
                    @if ($user->status == 2)
                        <td>Pending Invite</td>
                    @elseif ($user->status == 3)
                        <td>Active</td>
                    @elseif ($user->status == 1)
                        <td>Declined Invite</td>
                    @else
                        <td>{{ $user->status }}</td>
                    @endif
                    <td>
                        <div class="btn-group" role="group" aria-label="Basic example">
                            @if ($user->status == 2 && $user->inviteId != null)
                                <button type="button" class="btn btn-default btn-revoke" data-userInviteId={{$user->inviteId}} data-userId={{$user->id}} data-toggle="modal" data-target="#revokeConfirmModal">Revoke Invite</button>
                            @endif
                            @if ($user->status == 3)
                                <button type="button" class="btn btn-primary">Edit</button>
                            @endif
                            @if ($user->owner != true && $user->status != 2)
                                @if ($user->id != Auth::user()->id)
                                    <button type="button" class="btn btn-danger btn-remove" data-userId={{$user->id}} data-toggle="modal" data-target="#removeConfirmModal">Remove</button>
                                @endif
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
  </div>
  <div class="col-md-2"></div>
</div>

<!-- Modal -->
<div class="modal fade" id="inviteModal" tabindex="-1" role="dialog" aria-labelledby="InviteModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="inviteModal">Invite a user to this shindig!</h5>
            </div>
            <div id="inviteModalBody" class="modal-body">
                <ul>
                    @foreach($errors->all() as $error)
                         <li>{{ $error }}</li>
                    @endforeach
                 </ul>
                {!! Form::open(array('route' => 'sendInvite', 'class' => 'form', 'id' => 'inviteForm')) !!}
                <div class="form-group">
                    {!! Form::label('Email Address: ') !!}
                    {!! Form::text('email', null, 
                        array('required', 
                            'class'=>'form-control', 
                            'placeholder'=>'meWow@aol.com')) !!}
                    {{ Form::hidden('contestId', $contestId,
                        array('id' => 'contestId')) }}
                </div>
                <div class="form-group">
                    {!! Form::submit('Do It!',
                        array('class'=>'btn btn-success')) !!}
                    <button id="inviteCancelBtn" type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
                {!! Form::close() !!}
            </div>
            <div id="spinDiv"></div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="removeConfirmModal" tabindex="-1" role="dialog" aria-labelledby="removeConfirmModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="inviteModal">Remove user from contest</h5>
            </div>
            <div class="modal-body">
                <h3>Are you sure you want to remove this user?</h3>
                <ul>
                    @foreach($errors->all() as $error)
                         <li>{{ $error }}</li>
                    @endforeach
                 </ul>
                {!! Form::open(array('route' => 'removeContestUser', 'class' => 'form', 'id' => 'removeContestUserForm')) !!}
                <div class="form-group">
                    {{ Form::hidden('dUserId', '',
                        array('id' => 'dUserId')) }}
                    {{ Form::hidden('contestId', $contestId,
                        array('id' => 'contestId')) }}
                </div>
                <div class="form-group">
                    {!! Form::submit('Remove User',
                        array('class'=>'btn btn-danger')) !!}
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="revokeConfirmModal" tabindex="-1" role="dialog" aria-labelledby="revokeConfirmModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="revokeModal">Revoke user from contest</h5>
            </div>
            <div class="modal-body">
                <h3>Are you sure you want to revoke this invite?</h3>
                <ul>
                    @foreach($errors->all() as $error)
                         <li>{{ $error }}</li>
                    @endforeach
                 </ul>
                {!! Form::open(array('route' => 'revokeInvite', 'class' => 'form', 'id' => 'revokeContestUserForm')) !!}
                <div class="form-group">
                    {{ Form::hidden('rUserId', '',
                        array('id' => 'rUserId')) }}
                    {{ Form::hidden('contestId', $contestId,
                        array('id' => 'contestId')) }}
                    {{ Form::hidden('rInviteId', '',
                        array('id' => 'rInviteId')) }}
                </div>
                <div class="form-group">
                    {!! Form::submit('Revoke Invite',
                        array('class'=>'btn btn-danger')) !!}
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/spin.js/2.3.2/spin.js"></script>
<script>
    var spinTarget = document.getElementById('spinDiv');
    var spinner = new Spinner();
    $('.btn-success').click(function() {
        if ($('[name="email"]').val() != '') {
            spinner.spin(spinTarget);
        }
    })

    $(document).on('click', '.btn-remove', function () {
        $('#dUserId').val($(this).attr('data-userId'));
    });

    $(document).on('click', '.btn-revoke', function () {
        $('#rUserId').val($(this).attr('data-userId'));
        $('#rInviteId').val($(this).attr('data-userInviteId'));
    });
</script>
@endsection