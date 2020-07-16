@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Accountübersicht</div>

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
