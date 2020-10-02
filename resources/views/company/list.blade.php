@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Firmenübersicht</div>

                <div class="card-body">
                  <input class="form-control col-md-3 search-input" type="text" placeholder="Firmenname, Anschrift oder Land suchen..."/>

                  <table class="table table-sortable table-responsive">
                    <thead>
                      <tr>
                        <th class="searchable" scope="col">Firmenname</th>
                        <th class="searchable" scope="col">Anschrift</th>
                        <th class="searchable" scope="col">Land</th>
                        <th class="no-sort" scope="col">Aktion</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($companies as $company)
                        @foreach ($addresses as $address)
                          @if ($company->aid == $address->id)
                            <tr>
                              <form action="{{ route('company.show', $company->id) }}" method="GET">
                                @csrf
                                <td>
                                  {{ $company->name }}
                                </td>
                                <td>
                                  {{ $address->postcode }} {{ $address->city }}
                                </td>
                                <td>
                                  {{ $address->country}}
                                </td>
                                <td>
                                  <button class="btn btn-primary ml-auto float-right">ANZEIGEN</button>
                                </td>
                              </form>
                            </tr>
                          @endif
                        @endforeach
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
