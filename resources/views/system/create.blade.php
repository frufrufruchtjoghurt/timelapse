@extends('layouts.app')

@section('content')
<div class="container creator">
    <div class="row justify-content-center">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">System anlegen</div>

                <div class="card-body">

                    <form action="{{ route('system.index') }}" method="POST">
                      @csrf
                      <h2>Kamera</h2>
                      <div class="form-group row">
                        <label for="serial_nr_c" class="col-md-3 col-form-label text-md-right">Seriennummer<span>*</span></label>

                        <div class="col-md-6">
                            <input id="serial_nr_c" type="text" class="form-control" name="serial_nr_c" value="{{ old('serial_nr_c') }}" required>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="type_c" class="col-md-3 col-form-label text-md-right">Type<span>*</span></label>

                        <div class="col-md-4">
                            <input id="type_c" type="text" class="form-control" name="type_c" value="{{ old('type_c') }}" required>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="build_year_c" class="col-md-3 col-form-label text-md-right">Baujahr<span>*</span></label>
                        <div class="col-md-3">
                            <input id="build_year_c" type="text" class="form-control" name="build_year_c" value="{{ old('build_year_c') }}" required>
                        </div>
                      </div>

                      <h2>Router</h2>
                      <div class="form-group row">
                        <label for="serial_nr_r" class="col-md-3 col-form-label text-md-right">Seriennummer<span>*</span></label>

                        <div class="col-md-6">
                            <input id="serial_nr_r" type="text" class="form-control" name="serial_nr_r" value="{{ old('serial_nr_r') }}" required>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="type_r" class="col-md-3 col-form-label text-md-right">Type<span>*</span></label>

                        <div class="col-md-4">
                            <input id="type_r" type="text" class="form-control" name="type_r" value="{{ old('type_r') }}" required>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="build_year_r" class="col-md-3 col-form-label text-md-right">Baujahr<span>*</span></label>
                        <div class="col-md-3">
                            <input id="build_year_r" type="text" class="form-control" name="build_year_r" value="{{ old('build_year_r') }}" required>
                        </div>
                      </div>

                      <h2>SIM-Karte</h2>
                      <div class="form-group row">
                        <label for="serial_nr_s" class="col-md-3 col-form-label text-md-right">Telefonnummer<span>*</span></label>

                        <div class="col-md-4">
                            <input id="serial_nr_s" type="text" class="form-control" name="serial_nr_s" value="{{ old('serial_nr_s') }}" required>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="type_s" class="col-md-3 col-form-label text-md-right">Vertrag<span>*</span></label>

                        <div class="col-md-5">
                            <input id="type_s" type="text" class="form-control" name="type_s" value="{{ old('type_s') }}" required>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="build_year_s" class="col-md-3 col-form-label text-md-right">Ausstellungsdatum<span>*</span></label>
                        <div class="col-md-3">
                            <input id="build_year_s" type="text" class="form-control" name="build_year_s" value="{{ old('build_year_s') }}" required>
                        </div>
                      </div>

                      <h2>USV</h2>
                      <div class="form-group row">
                        <label for="serial_nr_u" class="col-md-3 col-form-label text-md-right">Seriennummer<span>*</span></label>

                        <div class="col-md-6">
                            <input id="serial_nr_u" type="text" class="form-control" name="serial_nr_u" value="{{ old('serial_nr_u') }}" required>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="type_u" class="col-md-3 col-form-label text-md-right">Type<span>*</span></label>

                        <div class="col-md-4">
                            <input id="type_u" type="text" class="form-control" name="type_u" value="{{ old('type_u') }}" required>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="build_year_u" class="col-md-3 col-form-label text-md-right">Baujahr<span>*</span></label>
                        <div class="col-md-3">
                            <input id="build_year_u" type="text" class="form-control" name="build_year_u" value="{{ old('build_year_u') }}" required>
                        </div>
                      </div>

                      <h2>Photovoltaik</h2>
                      <div class="form-group row">
                        <label for="serial_nr_p" class="col-md-3 col-form-label text-md-right">Seriennummer<span>*</span></label>

                        <div class="col-md-6">
                            <input id="serial_nr_p" type="text" class="form-control" name="serial_nr_p" value="{{ old('serial_nr_p') }}" required>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="type_p" class="col-md-3 col-form-label text-md-right">Type<span>*</span></label>

                        <div class="col-md-4">
                            <input id="type_p" type="text" class="form-control" name="type_p" value="{{ old('type_p') }}" required>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="build_year_p" class="col-md-3 col-form-label text-md-right">Baujahr<span>*</span></label>
                        <div class="col-md-3">
                            <input id="build_year_p" type="text" class="form-control" name="build_year_p" value="{{ old('build_year_p') }}" required>
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
