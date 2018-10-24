@if (count($contests))
    <h3>Your active contests:</h3><br />
@endif
@foreach ($contests as $contest)
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <a href="/contest/{{ $contest->id }}">
                    <h3 class="panel-title">{{ $contest->name }}</h3>
                </a>
            </div>            
            <div class="panel-body">
                <p>Start: {{ date('m/d/Y', strtotime($contest->startDt)) }}</p>
                <p>End: {{ date('m/d/Y', strtotime($contest->endDt)) }}</p>
            </div>
        </div>
    </div>
@endforeach