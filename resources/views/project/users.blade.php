@extends('layouts.app')

@section('content')
<div class="container creator">
    <div class="row justify-content-center">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">Kunden zuweisen</div>

                <div class="card-body">
                  @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                  @elseif (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                      {{ session('error') }}
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                  @endif

                    <form action="{{ route('project.index') }}" method="POST">
                      @csrf
                      <div class="form-group row">
                        <div class="col-md-2">
                            <input id="project_nr" type="number" class="form-control" name="project_nr" value="{{ $project_nr }}" hidden>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="users" class="col-md-3 col-fom-label text-md-right">{{ __('Kunden') }}<span>*</span></label>
                        <div class="col-md-5">
                          <select class="custom-select" name="users[]" id="users" value="" required multiple searchable="Hier eingeben...">
                              <option value="" selected disabled>Keine Auswahl</option>
                            @foreach ($users as $user)
                              @if ($user->cid == $cid)
                                <option class="users-select" value="{{ $user->id }}">{{ $user->title }} {{ $user->first_name }} {{ $user->last_name }}</option>
                              @endif
                            @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-5">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('WEITER') }}
                                </button>
                            </div>
                      </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
