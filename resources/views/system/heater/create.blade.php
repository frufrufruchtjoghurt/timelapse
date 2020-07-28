@extends('layouts.app')

@section('content')
<div class="container creator">
    <div class="row justify-content-center">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">Heizung anlegen</div>

                <div class="card-body">

                    <form action="{{ route('heater.index') }}" method="POST">
                      @csrf
                      <div class="form-group row">
                        <label for="serial_nr_h" class="col-md-3 col-form-label text-md-right">Seriennummer<span>*</span></label>

                        <div class="col-md-6">
                            <input id="serial_nr_h" type="text" class="form-control" name="serial_nr_h" value="{{ old('serial_nr_h') }}" required>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="type_h" class="col-md-3 col-form-label text-md-right">Type<span>*</span></label>

                        <div class="col-md-4">
                            <input id="type_h" type="text" class="form-control" name="type_h" value="{{ old('type_h') }}" required>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="build_year_h" class="col-md-3 col-form-label text-md-right">Baujahr<span>*</span></label>
                        <div class="col-md-3">
                            <input id="build_year_h" type="text" class="form-control" name="build_year_h" value="{{ old('build_year_h') }}" required>
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
