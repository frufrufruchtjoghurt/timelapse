@extends('layouts.app')

@section('content')
<div class="container creator">
    <div class="row justify-content-center">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">SIM-Karte bearbeiten</div>

                <div class="card-body">

                    <form action="{{ route('sim.edit', ['id' => $sim_card->id]) }}" method="POST">
                      @csrf
                      <div class="form-group row">
                        <label for="telephone_nr_s" class="col-md-3 col-form-label text-md-right">Telefonnummer<span>*</span></label>

                        <div class="col-md-4">
                            <input id="telephone_nr_s" type="text" class="form-control" name="telephone_nr_s" value="{{ $sim_card->telephone_nr }}" required>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="contract_s" class="col-md-3 col-form-label text-md-right">Vertrag<span>*</span></label>

                        <div class="col-md-5">
                            <input id="contract_s" contract="text" class="form-control" name="contract_s" value="{{ $sim_card->contract }}" required>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="purchase_date_s" class="col-md-3 col-form-label text-md-right">Kaufdatum<span>*</span></label>
                        <div class="col-md-3">
                            <input id="purchase_date_s" type="date" class="form-control" name="purchase_date_s" value="{{ $sim_card->purchase_date }}" required>
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
