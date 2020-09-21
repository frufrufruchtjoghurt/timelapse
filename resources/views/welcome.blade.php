@extends('layouts.app')

@section('alert')
<div class="alert alert-success alert-dismissible fade show" role="alert">
  Sie sind eingeloggt als
  @can ('isAdmin')
      Administrator.
  @endcan
  @can ('isManager')
      Manager.
  @endcan
  @can ('isBasic')
      Standardnutzer.
  @endcan
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                @if ($projects)
                  @foreach ($projects as $project)
                      <button class="btn btn-primary">{{ $project[1] }} (ID: {{ $project[0] }})</button>
                  @endforeach
                @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
