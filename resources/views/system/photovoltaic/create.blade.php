@extends('layouts.app')

@section('content')
<div class="container creator">
    <div class="row justify-content-center">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">Photovoltaik anlegen</div>

                <div class="card-body">

                    <form action="{{ route('photovoltaic.index') }}" method="POST">
                      @csrf
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
