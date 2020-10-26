@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Details zu Projekt {{ $project->pid }} {{ $project->name }}</div>

                <div class="card-body">

                  <h1>Details</h1>
                  <ul>
                      <li>
                        <strong>Name: </strong>
                      </li>
                      <ul>
                        <li>
                          Type: {{ $fixture->model }}
                        </li>
                        <li>
                          SN: {{ $fixture->serial_nr }}
                        </li>
                        <li>
                          Kaufdatum: {{ $fixture->purchase_date }}
                        </li>
                        <li>
                          Reparaturen: {{ $fixture->count }}
                        </li>
                      </ul>
                      <li>
                        <strong>Router: </strong>
                      </li>
                      <ul>
                        <li>
                          Type: {{ $router->model }}
                        </li>
                        <li>
                          SN: {{ $router->serial_nr }}
                        </li>
                        <li>
                          Kaufdatum: {{ $router->purchase_date }}
                        </li>
                        <li>
                          Reparaturen: {{ $router->count }}
                        </li>
                      </ul>
                      <li>
                        <strong>Sim-Karte: </strong>
                      </li>
                      <ul>
                        <li>
                          Vertrag: {{ $sim_card->contract }}
                        </li>
                        <li>
                          Telefonnr.: {{ $sim_card->telephone_nr }}
                        </li>
                        <li>
                          Kaufdatum: {{ $sim_card->purchase_date }}
                        </li>
                        <li>
                          Reparaturen: {{ $sim_card->count }}
                        </li>
                      </ul>
                      <li>
                        <strong>USV: </strong>
                      </li>
                      <ul>
                        <li>
                          Type: {{ $ups->model }}
                        </li>
                        <li>
                          SN: {{ $ups->serial_nr }}
                        </li>
                        <li>
                          Kaufdatum: {{ $ups->purchase_date }}
                        </li>
                        <li>
                          Reparaturen: {{ $ups->count }}
                        </li>
                      </ul>
                      @if ($heating)
                        <li>
                        <strong>Heizung: </strong>
                      </li>
                      <ul>
                        <li>
                          Type: {{ $heating->model }}
                        </li>
                        <li>
                          SN: {{ $heating->serial_nr }}
                        </li>
                        <li>
                          Kaufdatum: {{ $heating->purchase_date }}
                        </li>
                        <li>
                          Reparaturen: {{ $heating->count }}
                        </li>
                      </ul>
                    @endif
                    @if ($photovoltaic)
                      <li>
                        <strong>Photovoltaikanlage: </strong>
                      </li>
                      <ul>
                        <li>
                          Type: {{ $photovoltaic->model }}
                        </li>
                        <li>
                          SN: {{ $photovoltaic->serial_nr }}
                        </li>
                        <li>
                          Kaufdatum: {{ $photovoltaic->purchase_date }}
                        </li>
                        <li>
                          Reparaturen: {{ $photovoltaic->count }}
                        </li>
                      </ul>
                    @endif
                </ul>
                <div class="container">
                  <div class="row">
                    <div class="col">
                      <form action="{{ route('system.destroy', ['id_f'=>$fixture->id, 'id_r'=>$router->id, 'id_u'=>$ups->id]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                          <div class="container">
                            <div class="row">
                              <div class="col-xs-6">
                                <label for="delete" class="col-form-label text-md-left">{{ __('Bitte LÖSCHEN eingeben, um dieses System zu entfernen: ') }}</label>
                              </div>
                              <div class="col-md-4 offset-xs-2">
                                <input id="delete" type="text" class="form-control" name="delete" value="" required>
                              </div>
                              <div class="col-md-2">
                                <button class="btn btn-primary float-right">LÖSCHEN</button>
                              </div>
                            </div>
                          </div>
                      </form>
                    </div>
                    <div class="col-xs-1">
                      <form action="{{ route('system.edit', ['id_f'=>$fixture->id, 'id_r'=>$router->id, 'id_u'=>$ups->id]) }}" method="GET">
                          <button class="btn btn-primary float-right">BEARBEITEN</button>
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
