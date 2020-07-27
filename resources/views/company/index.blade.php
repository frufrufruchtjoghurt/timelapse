@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Firmenübersicht</div>

                <div class="card-body">

                  @if (!$company)
                    <h1>Error 404 | Seite nicht gefunden</h1>
                    <p>
                      Leider stehen uns keine Informationen über Ihre Firma zur Verfügung.
                      Wenn Sie Informationen zu Ihrer Firma hinzufügen möchten, so kontaktieren Sie uns!
                    </p>
                  @else
                    <h1>{{ $company->name }}</h1>
                    <h2>Firmeninformationen</h2>
                    <div class="col-md-10" id="personal-data">
                      <span class="font-weight-bold">Straße: </span>{{ $address->street }}<br/>
                      <span class="font-weight-bold">Anschrift: </span>{{ $address->street_nr }}
                        @if ($address->staircase)
                          / {{ $address->staircase }}
                        @endif
                        @if ($address->door_nr)
                          / {{ $address->door_nr }}
                        @endif
                        <br/>
                      <span class="font-weight-bold">PLZ: </span>{{ $address->postcode }}<br/>
                      <span class="font-weight-bold">Ort: </span>{{ $address->city }}<br/>
                      <span class="font-weight-bold">Land: </span>{{ $address->country }}<br/>
                    </div>
                  @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
