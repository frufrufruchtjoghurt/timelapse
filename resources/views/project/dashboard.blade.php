<div class="d-block bg-white rounded shadow-sm mb-3">
    <div class="row no-gutters">

        @empty(!$projects)
            @foreach($projects as $project)
                <div class="col-md-4">
                    <div class="h-100" style="display: contents">
                        <img src="{{ url($picturePaths[$project->id][0]) }}" class="img-fluid img-card">
                    </div>
                </div>
            @endforeach
        @endempty
    </div>
</div>
