@extends('layouts.app')

@section('content')
<div class="container creator">
    <div class="row justify-content-center">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">USV anlegen</div>

                <div class="card-body">

                    <form action="{{ route('ups.index') }}" method="POST">
                      @csrf
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
                        <label for="purchase_date_u" class="col-md-3 col-form-label text-md-right">Baujahr<span>*</span></label>
                        <div class="col-md-3">
                            <input id="purchase_date_u" type="date" class="form-control" name="purchase_date_u" value="{{ old('purchase_date_u') }}" required>
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
