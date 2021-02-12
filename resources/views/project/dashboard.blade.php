<div class="d-block bg-white rounded shadow-sm mb-3">
    <div class="row no-gutters">

        @empty(!$projects)
            @foreach($projects as $project)
                <div class="col-md-4">
                    <div class="h-100" style="display: contents">
                        <img src="{{ url($picturePaths[$project->id][0]) }}" class="img-fluid img-card">
                    </div>
                </div>

                <div class="col">
                    <div class="card-body h-full p-4">
                        <div class="row d-flex align-items-center">
                            <div class="col-auto">
                                <h5 class="card-title">
                                    {{ $project->name }}
                                </h5>
                            </div>

                            <div class="col-auto ml-auto text-right">
                                <div class="btn-group command-bar">
                                    <a href="{{ url('/view/' . $project->id) }}" class="btn btn-xs btn-info pull-right">Details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endempty
    </div>
</div>
