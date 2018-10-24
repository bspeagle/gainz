 <div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Invites <span class="glyphicon glyphicon-envelope"></span></h3>
    </div>
    <div class="panel-body">
        @if (count($invites) == 0)
            No Invites...
        @else
            @foreach ($invites as $invite)
                <p><a href="http://localhost:8000/invite/{{ $invite->inviteUUID }}">Contest: {{ $invite->name }} - you're invited!</a></p>
            @endforeach
        @endif
     </div>
</div>