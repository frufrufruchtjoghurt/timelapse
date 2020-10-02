@extends('layouts.app')

@section('alert')
  @error('name')
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    Projekt{{ $message }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  @enderror
@endsection

@section('content')
<div class="container creator">
    <div class="row justify-content-center">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">Projekt anlegen</div>

                <div class="card-body">
                    <form action="{{ route('project.users') }}" method="GET">
                      @csrf
                      <div class="form-group row">
                        <label for="project_nr" class="col-md-3 col-form-label text-md-right">Projektnummer<span>*</span></label>

                        <div class="col-md-2">
                            <input id="project_nr" type="number" class="form-control" name="project_nr" value="{{ old('project_nr') }}" required>
                        </div>
                        <label for="name" class="col-md-2 col-form-label text-md-right">Projektname<span>*</span></label>

                        <div class="col-md-3">
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="cid" class="col-md-3 col-form-label text-md-right">{{ __('Kundenfirma') }}<span>*</span></label>
                        <input class="form-control col-md-3 search-input" type="text" placeholder="Firma suchen..."/>
                      </div>
                      <div class="col-md-5 table-responsive scroll-wrapper">
                        <table class="table table-sort-asc align-center scroll-wrapper" name="cid">
                          <thead>
                            <tr>
                              <th class="text-center" scope="col">Auswahl</th>
                              <th class="sort-by searchable">Firmenname</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($companies as $company)
                              <tr>
                                <td class="text-center">
                                  <input type="checkbox" class="company-list" name="cid[]" value="{{ $company->id }}"/>
                                </td>
                                <td>
                                  {{$company->name}}
                                </td>
                              </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                      <div class="col-md-6 offset-md-5">
                        <button type="submit" class="btn btn-primary float-right">
                            {{ __('WEITER') }}
                        </button>
                      </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('js/table.js') }}"></script>
@endsection
