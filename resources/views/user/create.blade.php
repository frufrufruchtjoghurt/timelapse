@extends('layouts.app')

@section('content')
<div class="container creator">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Benutzer erstellen</div>

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

                    <form action="{{ route('user.index') }}" method="POST">
                      @csrf
                      <div class="form-group row">
                        <label for="gender" class="col-md-3 col-fom-label text-md-right">{{ __('Anrede') }}<span>*</span></label>
                        <div class="col-md-2">
                          <select class="custom-select" name="gender" id="gender" required autofocus value="{{ old('gender') }}">
                            <option value="Frau">Frau</option>
                            <option value="Herr">Herr</option>
                          </select>
                        </div>
                        <label for="title" class="col-md-1 col-fom-label text-md-right">{{ __('Titel') }}</label>
                        <div class="col-md-3">
                          <input id="title" type="text" class="form-control" name="title" value="{{ old('title') }}">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="first_name" class="col-md-3 col-form-label text-md-right">{{ __('Vorname') }}<span>*</span></label>

                        <div class="col-md-6">
                            <input id="first_name" type="text" class="form-control" name="first_name" value="{{ old('first_name') }}" required>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="last_name" class="col-md-3 col-form-label text-md-right">{{ __('Name') }}<span>*</span></label>

                        <div class="col-md-6">
                            <input id="last_name" type="text" class="form-control" name="last_name" value="{{ old('last_name') }}" required>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="cid" class="col-md-3 col-form-label text-md-right">{{ __('Firma') }}<span>*</span></label>
                        <div class="col-md-5">
                          <select class="custom-select" name="cid" id="cid" value="{{ old('cid') }}" required>
                              <option value="" selected disabled>Keine Auswahl</option>
                            @foreach ($companies as $company)
                              <option class="company-list" value="{{ $company->id }}">{{$company->name}}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="email" class="col-md-3 col-form-label text-md-right">{{ __('E-Mail Address') }}<span>*</span></label>

                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                        </div>
                      </div>
                      @can ('isAdmin')
                        <div class="form-group row">
                          <label for="access" class="col-md-3 col-form-label text-md-right">{{ __('Berechtigung') }}<span>*</span></label>
                          <div class="col-md-6" id="access">
                            <div class="form-check form-check-inline" id="access">
                              <input type="radio" name="role" id="role1" class="form-check-input" value="basic" required>
                              <label for="role1" class="form-check-label">Basic</label>
                            </div>
                            <div class="form-check form-check-inline" id="access">
                              <input type="radio" name="role" id="role2" class="form-check-input" value="manager" required>
                              <label for="role2" class="form-check-label">Manager</label>
                            </div>
                            <div class="form-check form-check-inline" id="access">
                              <input type="radio" name="role" id="role3" class="form-check-input" value="admin" required>
                              <label for="role3" class="form-check-label">Admin</label>
                            </div>
                          </div>
                        </div>
                      @endcan
                      <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-5">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('ANLEGEN') }}
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
