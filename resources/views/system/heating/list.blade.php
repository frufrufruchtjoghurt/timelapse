@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Heizungen</div>

                <div class="card-body">
                  <input class="form-control col-md-3 search-input" type="text" placeholder="Modell oder Seriennummer suchen..."/>

                  <table class="table table-sortable">
                    <thead>
                      <tr>
                        <th scope="col">ID</th>
                        <th class="searchable" scope="col">Modell</th>
                        <th class="searchable" scope="col">Seriennummer</th>
                        <th scope="col">Kaufdatum</th>
                        <th scope="col">Reparaturen</th>
                        <th class="no-sort" scope="col"></th>
                        <th class="no-sort" scope="col"></th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($heatings as $heating)
                        <tr>
                            @csrf
                            <td>
                              {{ $heating->id }}
                            </td>
                            <td>
                              {{ $heating->model }}
                            </td>
                            <td>
                              {{ $heating->serial_nr }}
                            </td>
                            <td>
                              {{ $heating->purchase_date }}
                            </td>
                            <td>
                              {{ $heating->count }}
                            </td>
                            <td>
                              <form action="{{ route('heating.destroy', ['id' => $heating->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                  <input id="delete" type="text" class="form-control" name="delete" value="" placeholder="LÖSCHEN" required>
                                  <button class="btn btn-primary">LÖSCHEN</button>
                              </form>
                            </td>
                            <td>
                              <form action="{{ route('heating.edit', ['id' => $heating->id]) }}" method="GET">
                                  <button class="btn btn-primary">BEARBEITEN</button>
                              </form>
                            </td>
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
