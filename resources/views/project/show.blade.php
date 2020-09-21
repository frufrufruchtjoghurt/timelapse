@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Details zu Projekt {{ $project->project_nr }} {{ $project->name }}</div>

                <div class="card-body">
                  <ul>
                    {{ $project->start_date }}
                  </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
