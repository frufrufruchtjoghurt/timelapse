@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Details zu Firmen-ID {{ $company->id }}</div>

                <div class="card-body">
                  @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                  @elseif (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                      {{ session('error') }}
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                  @endif

                  <h1><strong>{{ $company->name }}</strong></h1>
                  <h2>Addressdaten:</h2>
                  <ul>
                      <li>
                        <strong>Straße: </strong> {{ $address->street }}
                      </li>
                      <li>
                        <strong>Hausnummer: </strong> {{ $address->street_nr }}
                      </li>
                      @if ($address->staircase != NULL)
                      <li>
                        <strong>Stiege: </strong> {{ $address->staircase }}
                      </li>
                      @endif
                      @if ($address->door_nr != NULL)
                      <li>
                        <strong>Türnummer: </strong> {{ $address->door_nr }}
                      </li>
                      @endif
                      <li>
                        <strong>Postleitzahl: </strong> {{ $address->postcode }}
                      </li>
                      <li>
                        <strong>Ort: </strong> {{ $address->city }}
                      </li>
                      <li>
                        <strong>Bundesland: </strong> {{ $address->region }}
                      </li>
                      <li>
                        <strong>Land: </strong> {{ $address->country }}
                      </li>
                  </ul>
                  <div class="container">
                    <div class="row">
                      <div class="col">
                        <form action="{{ route('company.destroy', $company->id) }}" method="POST">
                          @csrf
                          @method('DELETE')
                            <div class="container">
                              <div class="row">
                                <div class="col-xs-6">
                                  <label for="delete" class="col-form-label text-md-left">{{ __('Bitte LÖSCHEN eingeben, um diesen Benutzer zu entfernen: ') }}</label>
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
                        <form action="{{ route('company.edit', $company->id) }}" method="GET">
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
