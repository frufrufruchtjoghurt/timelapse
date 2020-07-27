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

                      <input class="form-control col-md-3" type="text" placeholder="Startdatum"/>
                      <input class="form-control col-md-3 search-input" type="text" placeholder="System suchen..."/>
                      <div class="form-group row">
                        <label for="system" class="col-md-3 col-fom-label text-md-right">{{ __('System') }}:<span>*</span></label>
                        <div class="col-md-5">
                          <table class="table table-sort-asc" name="system">
                            <thead>
                              <tr>
                                <th class="text-center" scope="col">Auswahl</th>
                                <th class="sort-by searchable" scope="col">Name</th>
                                <th class="searchable" scope="col">Kamera</th>
                                <th class="no-sort" scope="col">Reparaturen</th>
                                <th scope="col">letzte Verwendung</th>
                              </tr>
                            </thead>
                            <tbody>
                                  <tr>
                                    <td class="text-center">
                                    </td>
                                    <td>
                                    </td>
                                    <td>
                                    </td>
                                    <td>
                                    </td>
                                    <td>
                                    </td>
                                  </tr>
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
