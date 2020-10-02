@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Benutzerübersicht</div>

                <div class="card-body">
                  <div class="row">
                    <input class="form-control col-md-4 search-input" type="text" placeholder="Name, E-Mailadresse oder Benutzerrolle suchen..."/>
                  </div>

                    <table class="table table-sortable table-responsive">
                      <thead>
                        <tr>
                          <th class="searchable" scope="col">Name</th>
                          <th class="searchable" scope="col">Vorname</th>
                          <th class="searchable" scope="col">E-Mailadresse</th>
                          <th class="searchable no-sort" scope="col">Benutzerrolle</th>
                          <th class="no-sort" scope="col">Aktion</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($users as $user)
                          <tr>
                            <form action="{{ route('user.show', $user->id) }}" method="GET">
                              @csrf
                              <td>
                                {{ $user->last_name }}
                              </td>
                              <td>
                                {{ $user->first_name }}
                              </td>
                              <td>
                                {{ $user->email }}
                              </td>
                              <td>
                                <span class="text-capitalize">{{ $roles->where('id', $user->rid)->pluck('name')->first() }}</span>
                              </td>
                              <td>
                                <button class="btn btn-primary">ANZEIGEN</button>
                              </td>
                            </form>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('js/table.js') }}"></script>
@endsection
