@extends('layouts.app')

@section('content')
<div class="container creator">
    <div class="row justify-content-center">
        <div class="col-sm-12">
            <div class="card">
              <div class="card-header">System {{ $old_fixture->id }}{{ $old_router->id }}{{ $old_ups->id }} bearbeiten</div>

              <div class="card-body">
                <form action="{{ route('system.show', ['id_f' => $old_fixture->id, 'id_r' => $old_router->id, 'id_u' => $old_ups->id]) }}" method="POST">
                  @csrf
                  <div class="form-group row">
                    <label for="fixture" class="col-md-3 col-form-label text-md-right">Gehäuse<span>*</span></label>

                    <div class="col-md-6">
                      <select class="form-control" id="fixture" name="fixture" required>
                        <option value="{{ $old_fixture->id }}" selected>Type: {{ $old_fixture->model }} - SN: {{ $old_fixture->serial_nr }} - Reparaturen: {{ $old_fixture->repair_count ?? 0 }}</option>
                        @foreach ($fixtures as $fixture)
                          <option value="{{ $fixture->id }}">Type: {{ $fixture->model }} - SN: {{ $fixture->serial_nr }} - Reparaturen: {{ $fixture->repair_count ?? 0 }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="router" class="col-md-3 col-form-label text-md-right">Router<span>*</span></label>

                    <div class="col-md-6">
                      <select class="form-control" id="router" name="router" required>
                          <option value="{{ $old_router->id }}" selected>Type: {{ $old_router->model }} - SN: {{ $old_router->serial_nr }} - Reparaturen: {{ $old_router->repair_count ?? 0 }}</option>
                          @foreach ($routers as $router)
                          <option value="{{ $router->id }}">Type: {{ $router->model }} - SN: {{ $router->serial_nr }} - Reparaturen: {{ $router->repair_count ?? 0 }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="sim" class="col-md-3 col-form-label text-md-right">SIM-Karte<span>*</span></label>

                    <div class="col-md-6">
                      <select class="form-control" id="sim" name="sim" required>
                          <option value="{{ $old_sim_card->id }}" selected>Vertrag: {{ $old_sim_card->contract }} - TelNr: {{ $old_sim_card->telephone_nr }} - Reparaturen: {{ $old_sim_card->repair_count ?? 0 }}</option>
                          @foreach ($sims as $sim)
                          <option value="{{ $sim->id }}">Vertrag: {{ $sim->contract }} - TelNr: {{ $sim->telephone_nr }} - Reparaturen: {{ $sim->repair_count ?? 0 }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="ups" class="col-md-3 col-form-label text-md-right">USV<span>*</span></label>

                    <div class="col-md-6">
                      <select class="form-control" id="ups" name="ups" required>
                          <option value="{{ $old_ups->id }}" selected>Type: {{ $old_ups->model }} - SN: {{ $old_ups->serial_nr }} - Reparaturen: {{ $old_ups->repair_count ?? 0 }}</option>
                          @foreach ($ups as $ups)
                          <option value="{{ $ups->id }}">Type: {{ $ups->model }} - SN: {{ $ups->serial_nr }} - Reparaturen: {{ $ups->repair_count ?? 0 }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="heating" class="col-md-3 col-form-label text-md-right">Heizung</label>

                    <div class="col-md-6">
                      <select class="form-control" id="heating" name="heating">
                        <option value="NULL"></option>
                        @if ($old_heating)
                          <option value="{{ $old_heating->id }}" selected>Type: {{ $old_heating->model }} - SN: {{ $old_heating->serial_nr }} - Reparaturen: {{ $old_heating->repair_count ?? 0 }}</option>
                        @endif
                        @foreach ($heatings as $heating)
                          <option value="{{ $heating->id }}">Type: {{ $heating->model }} - SN: {{ $heating->serial_nr }} - Reparaturen: {{ $heating->repair_count ?? 0 }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="photovoltaic" class="col-md-3 col-form-label text-md-right">Photovoltaikanlage</label>

                    <div class="col-md-6">
                      <select class="form-control" id="photovoltaic" name="photovoltaic">
                        <option value="NULL"></option>
                        @if ($old_photovoltaic)
                          <option value="{{ $old_photovoltaic->id }}" selected>Type: {{ $old_photovoltaic->model }} - SN: {{ $old_photovoltaic->serial_nr }} - Reparaturen: {{ $old_photovoltaic->repair_count ?? 0 }}</option>
                        @endif
                        @foreach ($photovoltaics as $photovoltaic)
                          <option value="{{ $photovoltaic->id }}">Type: {{ $photovoltaic->model }} - SN: {{ $photovoltaic->serial_nr }} - Reparaturen: {{ $photovoltaic->repair_count ?? 0 }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>


                  <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-5">
                            <button type="submit" class="btn btn-primary">
                                {{ __('SPEICHERN') }}
                            </button>
                        </div>
                  </div>
                </form>
              </div>
          </div>
        </div>
    </div>
</div>
@endsection
