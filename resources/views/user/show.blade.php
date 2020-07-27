@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Details zu Benutzer-ID {{ $user->id }}</div>

                <div class="card-body">
                  <ul>
                    <li>
                      <strong>Honorativ: </strong> {{ $user->gender }}
                    </li>
                    <li>
                      <strong>Titel: </strong> {{ $user->title }}
                    </li>
                    <li>
                      <strong>Vorname: </strong> {{ $user->first_name }}
                    </li>
                    <li>
                      <strong>Nachname: </strong> {{ $user->last_name }}
                    </li>
                    <li>
                      <strong>E-Mail Adresse: </strong> {{ $user->email }}
                    </li>
                    <li>
                      <strong>Firma: </strong> {{ $company->name }}
                    </li>
                    <li>
                      <strong>Benutzerrechte: </strong> <span class='text-capitalize'>{{ $role->name }}</span>
                    </li>
                    <li>
                      <strong>Projekte: </strong> (In Bearbeitung...)
                    </li>
                  </ul>
                  <div class="container">
                    <div class="row">
                      <div class="col">
                        <form action="{{ route('user.destroy', $user->id) }}" method="POST">
                          @csrf
                          @method('DELETE')
                          @if (!(($role->name == 'admin' && Auth::user()->rid != $role->rid)
                            || Auth::user()->id == $user->id))
                            <div class="container">
                              <div class="row">
                                <div class="col-xs-6">
                                  <label for="delete" class="col-form-label text-md-left">{{ __('Bitte LÖSCHEN eingeben, um diesen Benutzer zu entfernen: ') }}</label>
                                </div>
                                <div class="col-md-4 offset-xs-2">
                                  <input id="delete" type="text" class="form-control" name="delete" value="" required>
                                </div>
                                <div class="col-md-2">
                                  <button class="btn btn-primary float-right">LÖSCHEN</button>
                                </div>
                              </div>
                            </div>
                          @endif
                        </form>
                      </div>
                      <div class="col-xs-1">
                        <form action="{{ route('user.edit', $user->id) }}" method="GET">
                          @if (!(($role->name == 'admin' && Auth::user()->rid != $role->rid)
                            || Auth::user()->id == $user->id))
                            <button class="btn btn-primary float-right">BEARBEITEN</button>
                          @endif
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
