@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Photovoltaikanlagen</div>

                <div class="card-body">
                  <input class="form-control col-md-3 search-input" type="text" placeholder="Modell oder Seriennummer suchen..."/>

                  <table class="table table-sortable table-responsive">
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
                      @foreach ($photovoltaics as $photovoltaic)
                        <tr>
                            @csrf
                            <td>
                              {{ $photovoltaic->id }}
                            </td>
                            <td>
                              {{ $photovoltaic->model }}
                            </td>
                            <td>
                              {{ $photovoltaic->serial_nr }}
                            </td>
                            <td>
                              {{ $photovoltaic->purchase_date }}
                            </td>
                            <td>
                              {{ $photovoltaic->count }}
                            </td>
                            <td>
                              <form action="{{ route('photovoltaic.destroy', ['id' => $photovoltaic->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                  <input id="delete" type="text" class="form-control" name="delete" value="" placeholder="LÖSCHEN" required>
                                  <button class="btn btn-primary">LÖSCHEN</button>
                              </form>
                            </td>
                            <td>
                              <form action="{{ route('photovoltaic.edit', ['id' => $photovoltaic->id]) }}" method="GET">
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
