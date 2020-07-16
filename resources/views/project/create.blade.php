@extends('layouts.app')

@section('content')
<div class="container creator">
    <div class="row justify-content-center">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">Projekt anlegen</div>

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

                    <form action="{{ route('project.users') }}" method="GET">
                      @csrf
                      <div class="form-group row">
                        <label for="project_nr" class="col-md-3 col-form-label text-md-right">Projektnummer<span>*</span></label>

                        <div class="col-md-2">
                            <input id="project_nr" type="number" class="form-control" name="project_nr" value="{{ old('project_nr') }}" required>
                        </div>
                        <label for="name" class="col-md-2 col-form-label text-md-right">Projektname<span>*</span></label>

                        <div class="col-md-3">
                            <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="cid" class="col-md-3 col-fom-label text-md-right">{{ __('Kundenfirma') }}<span>*</span></label>
                        <div class="col-md-5">
                          <select class="custom-select" name="cid" id="cid" value="{{ old('cid') }}" required>
                              <option value="" selected disabled>Keine Auswahl</option>
                            @foreach ($companies as $company)
                              <option class="company-list" value="{{ $company->id }}">{{$company->name}}</option>
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
