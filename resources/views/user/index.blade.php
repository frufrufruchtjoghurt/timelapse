@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Accountübersicht</div>

                <div class="card-body">

                  <h1>Willkommen, {{ Auth::user()->gender }} {{ Auth::user()->title }} {{ Auth::user()->last_name }}!</h1>
                  <h2>Ihre persönlichen Daten:</h2>
                  <div class="col-md-10" id="personal-data">
                    <span class="font-weight-bold">Anrede: </span>{{ Auth::user()->gender }}<br/>
                    <span class="font-weight-bold">Titel: </span>
                      @if (Auth::user()->title)
                        {{ Auth::user()->title }}
                      @else
                        -
                      @endif
                      <br/>
                    <span class="font-weight-bold">Name: </span>{{ Auth::user()->last_name }}<br/>
                    <span class="font-weight-bold">Vorname: </span>{{ Auth::user()->first_name }}<br/>
                    <span class="font-weight-bold">E-Mail: </span>{{ Auth::user()->email }}<br/>
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
