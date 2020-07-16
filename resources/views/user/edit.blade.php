@extends('layouts.app')

@section('content')
<div class="container creator">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Benutzer bearbeiten</div>

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

                    <form action="{{ route('user.show', $user->id) }}" method="POST">
                      @csrf
                      <div class="form-group row">
                        <label for="gender" class="col-md-3 col-fom-label text-md-right">{{ __('Anrede') }}<span>*</span></label>
                        <div class="col-md-2">
                          <select class="custom-select" name="gender" id="gender" required autofocus>
                            @if ($user->gender == 'Frau')
                              <option value="Frau" selected>Frau</option>
                              <option value="Herr">Herr</option>
                            @else
                              <option value="Frau">Frau</option>
                              <option value="Herr" selected>Herr</option>
                            @endif
                          </select>
                        </div>
                        <label for="title" class="col-md-1 col-fom-label text-md-right">{{ __('Titel') }}</label>
                        <div class="col-md-3">
                          <input id="title" type="text" class="form-control" name="title" value="{{ $user->title }}">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="first_name" class="col-md-3 col-form-label text-md-right">{{ __('Vorname') }}<span>*</span></label>

                        <div class="col-md-6">
                            <input id="first_name" type="text" class="form-control" name="first_name" value="{{ $user->first_name }}" required>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="last_name" class="col-md-3 col-form-label text-md-right">{{ __('Name') }}<span>*</span></label>

                        <div class="col-md-6">
                            <input id="last_name" type="text" class="form-control" name="last_name" value="{{ $user->last_name }}" required>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="cid" class="col-md-3 col-fom-label text-md-right">{{ __('Firma') }}</label>
                        <div class="col-md-5">
                          <select class="custom-select" name="cid" id="cid">
                              <option value="" selected>Keine Auswahl</option>
                            @foreach ($companies as $company)
                              @if ($company->id == $user->cid)
                                <option class="company-list" value="{{ $company->id }}" selected>{{$company->name}}</option>
                              @else
                                <option class="company-list" value="{{ $company->id }}">{{$company->name}}</option>
                              @endif
                            @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="email" class="col-md-3 col-form-label text-md-right">{{ __('E-Mail Address') }}<span>*</span></label>

                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control" name="email" value="{{ $user->email }}" required>
                        </div>
                      </div>
                        <div class="form-group row">
                          <label for="access" class="col-md-3 col-form-label text-md-right">{{ __('Berechtigung') }}<span>*</span></label>
                          <div class="col-md-6" id="access">
                            <div class="form-check form-check-inline" id="access">
                              @if ($roles->where('id', $user->rid)->pluck('name')->first() == 'basic')
                                <input type="radio" name="role" id="role1" class="form-check-input" value="basic" required checked>
                              @else
                                <input type="radio" name="role" id="role1" class="form-check-input" value="basic" required>
                              @endif
                              <label for="role1" class="form-check-label">Basic</label>
                            </div>
                            @can ('isAdmin')
                            <div class="form-check form-check-inline" id="access">
                              @if ($roles->where('id', $user->rid)->pluck('name')->first() == 'manager')
                                <input type="radio" name="role" id="role2" class="form-check-input" value="manager" required checked>
                              @else
                                <input type="radio" name="role" id="role2" class="form-check-input" value="manager" required>
                              @endif
                              <label for="role2" class="form-check-label">Manager</label>
                            </div>
                            <div class="form-check form-check-inline" id="access">
                              @if ($roles->where('id', $user->rid)->pluck('name')->first() == 'admin')
                                <input type="radio" name="role" id="role3" class="form-check-input" value="admin" required checked>
                              @else
                                <input type="radio" name="role" id="role3" class="form-check-input" value="admin" required>
                              @endif
                              <label for="role3" class="form-check-label">Admin</label>
                            </div>
                            @endcan
                          </div>
                        </div>
                      <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-5">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('ÄNDERUNGEN SPEICHERN') }}
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
