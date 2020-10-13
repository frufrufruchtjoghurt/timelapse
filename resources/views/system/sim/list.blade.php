@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Sim-Karten</div>

                <div class="card-body">
                  <input class="form-control col-md-3 search-input" type="text" placeholder="Modell oder Seriennummer suchen..."/>

                  <table class="table table-sortable table-responsive">
                    <thead>
                      <tr>
                        <th scope="col">ID</th>
                        <th class="searchable" scope="col">Vertrag</th>
                        <th class="searchable" scope="col">Telefonnummer</th>
                        <th scope="col">Kaufdatum</th>
                        <th scope="col">Reparaturen</th>
                        <th scope="col">Projekt</th>
                        <th class="no-sort" scope="col"></th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($sim_cards as $sim_card)
                        <tr>
                            @csrf
                            <td>
                              {{ $sim_card->id }}
                            </td>
                            <td>
                              {{ $sim_card->contract }}
                            </td>
                            <td>
                              {{ $sim_card->telephone_nr }}
                            </td>
                            <td>
                              {{ $sim_card->purchase_date }}
                            </td>
                            <td>
                              {{ $sim_card->count }}
                            </td>
                            <td>
                              @if (!$sim_card->storage)
                                im Lager
                              @else
                                {{ $sim_card->storage }}
                              @endif
                            </td>
                            <td>
                              <form action="{{ route('sim.destroy', ['id' => $sim_card->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                  <input id="delete" type="text" class="form-control" name="delete" value="" placeholder="LÖSCHEN" required>
                                  <button class="btn btn-primary">LÖSCHEN</button>
                              </form>
                            </td>
                            <td>
                              <form action="{{ route('sim.edit', ['id' => $sim_card->id]) }}" method="GET">
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