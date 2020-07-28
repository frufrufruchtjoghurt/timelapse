@extends('layouts.app')

@section('content')
<div class="container creator">
    <div class="row justify-content-center">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">SIM-Karte anlegen</div>

                <div class="card-body">

                    <form action="{{ route('sim.index') }}" method="POST">
                      @csrf
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
