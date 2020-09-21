@extends('layouts.app')

@section('content')
<div class="container creator">
    <div class="row justify-content-center">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">Kunden zuweisen</div>

                <div class="card-body">

                    <form action="{{ route('project.index') }}" method="POST">
                      @csrf
                      <input class="form-control col-md-3 search-input" type="text" placeholder="Name suchen..."/>
                      <div class="form-group row">
                        <label for="users" class="col-md-3 col-fom-label text-md-right">{{ __('Kunden') }}:<span>*</span></label>
                        <div class="col-md-5">
                          <table class="table table-sort-asc" name="users">
                            <thead>
                              <tr>
                                <th class="text-center" scope="col">Auswahl</th>
                                <th scope="col">Titel</th>
                                <th class="sort-by searchable" scope="col">Name</th>
                                <th class="searchable" scope="col">Vorname</th>
                              </tr>
                            </thead>
                            <tbody>
                            @foreach ($users as $user)
                              @foreach ($cids as $cid)
                                @if ($user->cid == $cid)
                                  <tr>
                                    <td class="text-center">
                                      <input type="checkbox" class="users-select" name="users[]" value="{{ $user->id }}"/>
                                    </td>
                                    <td>
                                      {{ $user->title }}
                                    </td>
                                    <td>
                                      {{ $user->last_name }}
                                    </td>
                                    <td>
                                      {{ $user->first_name }}
                                    </td>
                                  </tr>
                                @endif
                              @endforeach
                            @endforeach
                            </tbody>
                          </table>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="date" class="col-md-3 col-fom-label text-md-right">{{ __('Startdatum') }}:<span>*</span></label>
                        <input name="date" class="form-control col-md-3" type="date"/>
                      </div>
                      <input class="form-control col-md-3 search-input" type="text" placeholder="System suchen..."/>
                      <div class="form-group row">
                        <label for="system" class="col-md-3 col-fom-label text-md-right">{{ __('Systeme') }}:<span>*</span></label>
                        <div class="col-md-5">
                          <table class="table table-sort-asc" name="system">
                            <thead>
                              <tr>
                                <th class="text-center" scope="col">Auswahl</th>
                                <th class="searchable" scope="col">Gehäuse</th>
                                <th class="searchable" scope="col">Router</th>
                                <th class="searchable" scope="col">Sim-Karte</th>
                                <th class="searchable" scope="col">USV</th>
                                <th class="searchable" scope="col">Heizung</th>
                                <th class="searchable" scope="col">Photovoltaik</th>
                                <th scope="col">Reparaturen (gesamt)</th>
                                <th scope="col">letzte Verwendung</th>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach ($systems as $system)
                                  <tr>
                                    <td class="text-center">
                                      <input type="radio" class="users-select" name="system" value="{{ $system->id_f }};{{ $system->id_r }};{{ $system->id_u }}"/>
                                    </td>
                                    <td>
                                      {{ $system->model_f }}
                                    </td>
                                    <td>
                                      {{ $system->model_r }}
                                    </td>
                                    <td>
                                      {{ $system->model_s }}
                                    </td>
                                    <td>
                                      {{ $system->model_u }}
                                    </td>
                                    <td>
                                      @if (!$system->id_h)
                                        -
                                      @else
                                        {{ $system->model_h }}
                                      @endif
                                    </td>
                                    <td>
                                      @if (!$system->id_p)
                                        -
                                      @else
                                        {{ $system->model_p }}
                                      @endif
                                    </td>
                                    <td>
                                      {{ $system->count_f + $system->count_r + $system->count_s + $system->count_u
                                        + $system->count_h + $system->count_p }}
                                    </td>
                                    <td>
                                      @if (!$system->inactivity_date)
                                        nie
                                      @else
                                        {{ $system->inactivity_date }}
                                      @endif
                                    </td>
                                  </tr>
                              @endforeach
                            </tbody>
                          </table>
                        </div>
                      </div>

                      <input class="form-control col-md-3 search-input" type="text" placeholder="Kamera suchen..."/>
                      <div class="form-group row">
                        <label for="camera" class="col-md-3 col-fom-label text-md-right">{{ __('Kameras') }}:<span>*</span></label>
                        <div class="col-md-5">
                          <table class="table table-sort-asc" name="camera">
                            <thead>
                              <tr>
                                <th class="text-center" scope="col">Auswahl</th>
                                <th class="searchable" scope="col">Modell</th>
                                <th scope="col">Reparaturen</th>
                                <th scope="col">letzte Verwendung</th>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach ($cameras as $camera)
                                  <tr>
                                    <td class="text-center">
                                      <input type="radio" class="users-select" name="camera" value="{{ $camera->id }}"/>
                                    </td>
                                    <td>
                                      {{ $camera->model }}
                                    </td>
                                    <td>
                                      {{ $camera->count }}
                                    </td>
                                    <td>
                                      @if (!$system->inactivity_date)
                                        nie
                                      @else
                                        {{ $system->inactivity_date }}
                                      @endif
                                    </td>
                                  </tr>
                              @endforeach
                            </tbody>
                          </table>
                        </div>
                      </div>

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
<script src="{{ asset('js/table.js') }}"></script>
@endsection
