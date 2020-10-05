@extends('layouts.app')

@section('content')
<div class="container creator">
    <div class="row justify-content-center">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">Systemkomponenten anlegen</div>

                <div class="card-body justify-content-center">

                  <div class="btn-group">
                    <a href="{{ route('fixture.create') }}" class="btn btn-primary">Gehäuse anlegen</a>
                    <a href="{{ route('router.create') }}" class="btn btn-primary">Router anlegen</a>
                    <a href="{{ route('sim.create') }}" class="btn btn-primary">SIM-Karte anlegen</a>
                    <a href="{{ route('ups.create') }}" class="btn btn-primary">USV anlegen</a>
                    <a href="{{ route('heating.create') }}" class="btn btn-primary">Heizung anlegen</a>
                    <a href="{{ route('photovoltaic.create') }}" class="btn btn-primary">Photovoltaik anlegen</a>
                  </div>
                </div>
            </div>
            <div class="text-center font-weight-bold text-uppercase">
              - oder -
            </div>
            <div class="card">
              <div class="card-header">System zusammenfügen</div>

              <div class="card-body">
                <form action="{{ route('system.index') }}" method="POST">
                  @csrf
                  <div class="form-group row">
                    <label for="name" class="col-md-3 col-form-label text-md-right">Bezeichnung<span>*</span></label>

                    <div class="col-md-6">
                      <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="fixture" class="col-md-3 col-form-label text-md-right">Gehäuse<span>*</span></label>

                    <div class="col-md-6">
                      <select class="form-control" id="fixture" name="fixture" required>
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
                        <option value="0">Keine Heizung</option>
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
                        <option value="0">Keine Photovoltaikanlage</option>
                        @foreach ($photovoltaics as $photovoltaic)
                          <option value="{{ $photovoltaic->id }}">Type: {{ $photovoltaic->model }} - SN: {{ $photovoltaic->serial_nr }} - Reparaturen: {{ $photovoltaic->repair_count ?? 0 }}</option>
                        @endforeach
                      </select>
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
@endsection
