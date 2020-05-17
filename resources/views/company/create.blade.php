@extends('layouts.app')

@section('content')
<div class="container creator">
    <div class="row justify-content-center">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">Firma anlegen</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form action="{{ route('company.index') }}" method="POST">
                      @csrf
                      <div class="form-group row">
                        <label for="name" class="col-md-3 col-form-label text-md-right">Firmenname<span>*</span></label>

                        <div class="col-md-6">
                            <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="street" class="col-md-3 col-form-label text-md-right">Straße<span>*</span></label>

                        <div class="col-md-4">
                            <input id="street" type="text" class="form-control" name="street" value="{{ old('street') }}" required>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="street_nr" class="col-md-3 col-form-label text-md-right">Hausnummer<span>*</span></label>
                        <div class="col-md-1">
                            <input id="street_nr" type="text" class="form-control" name="street_nr" value="{{ old('street_nr') }}" required>
                        </div>
                        <label for="staircase" class="col-form-label text-md-right">Stiege</label>
                        <div class="col-md-1">
                            <input id="staircase" type="number" class="form-control" name="staircase" value="{{ old('staircase') }}">
                        </div>
                        <label for="door_nr" class="col-form-label text-md-right">Tür</label>
                        <div class="col-md-1">
                            <input id="door_nr" type="number" class="form-control" name="door_nr" value="{{ old('door_nr') }}">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="postcode" class="col-md-3 col-form-label text-md-right">PLZ<span>*</span></label>
                        <div class="col-md-2">
                            <input id="postcode" type="number" class="form-control" name="postcode" value="{{ old('postcode') }}" required>
                        </div>
                        <label for="city" class="col-md-1 col-form-label text-md-right">Ort<span>*</span></label>
                        <div class="col-md-3">
                            <input id="city" type="text" class="form-control" name="city" value="{{ old('city') }}" required>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="country" class="col-md-3 col-form-label text-md-right">Land<span>*</span></label>
                        <div class="col-md-3">
                            <input id="country" type="text" class="form-control" name="country" value="{{ old('country') }}" required>
                        </div>
                      </div>
                      <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-5">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Anlegen') }}
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
