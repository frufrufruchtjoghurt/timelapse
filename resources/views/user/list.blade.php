@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Benutzerübersicht</div>

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

                    <input class="form-control col-md-3 search-user" type="text" placeholder="Name oder E-Mailadresse suchen..."/>

                    <table class="table table-sortable">
                      <thead>
                        <tr>
                          <th class="searchable" scope="col">Name</th>
                          <th class="searchable" scope="col">Vorname</th>
                          <th class="searchable" scope="col">E-Mailadresse</th>
                          <th class="no-sort" scope="col">Benutzerrolle</th>
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
