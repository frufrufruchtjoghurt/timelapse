@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
      <div class="col-md-12">
          <div class="card">
            <div class="card-header">Systemkomponenten ansehen</div>

                <div class="card-body justify-content-center">

                  <div class="btn-group">
                    <a href="{{ route('fixture.list') }}" class="btn btn-primary">Gehäuse ansehen</a>
                    <a href="{{ route('router.list') }}" class="btn btn-primary">Router ansehen</a>
                    <a href="{{ route('sim.list') }}" class="btn btn-primary">SIM-Karten ansehen</a>
                    <a href="{{ route('ups.list') }}" class="btn btn-primary">USVs ansehen</a>
                    <a href="{{ route('heating.list') }}" class="btn btn-primary">Heizungen ansehen</a>
                    <a href="{{ route('photovoltaic.list') }}" class="btn btn-primary">Photovoltaik ansehen</a>
                  </div>
                </div>
            </div>
            <div class="text-center font-weight-bold text-uppercase">
              - oder -
            </div>
            <div class="card">
              <div class="card-header">Systeme</div>

              <div class="card-body">
                <input class="form-control col-md-3 search-input" type="text" placeholder="Komponentenname suchen..."/>

                <table class="table table-sortable table-responsive">
                  <thead>
                    <tr>
                      <th scope="col">ID</th>
                      <th class="searchable" scope="col">Name</th>
                      <th class="searchable" scope="col">Gehäuse</th>
                      <th class="searchable" scope="col">Router</th>
                      <th class="searchable" scope="col">Sim-Karte</th>
                      <th class="searchable" scope="col">USV</th>
                      <th class="searchable" scope="col">Heizung</th>
                      <th class="searchable" scope="col">Photovoltaik</th>
                      <th scope="col">Reparaturen (gesamt)</th>
                      <th scope="col">Projekt</th>
                      <th class="no-sort" scope="col">Aktion</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($systems as $system)
                      <tr>
                        <form action="{{ route('system.show', ['id_f'=>$system->id_f, 'id_r'=>$system->id_r, 'id_u'=>$system->id_u]) }}"
                          method="GET">
                          @csrf
                          <td>
                            {{ $system->id }}
                          </td>
                          <td>
                            {{ $system->name }}
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
                            @if (!$system->storage)
                              im Lager
                            @else
                              {{ $system->storage }}
                            @endif
                          </td>
                          <td>
                            <button class="btn btn-primary ml-auto float-right">ANZEIGEN</button>
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
