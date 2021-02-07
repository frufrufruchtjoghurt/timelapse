@extends('platform::auth')
@section('title', __('Benutzer nicht gefunden!'))

@section('content')
    <h1 class="h4 text-black mb-4">{{ __('Benutzer nicht gefunden!') }}</h1>

    <p>
        Ihr Benutzerkonto konnte nicht gefunden werden!<br>
        Bitte prüfen Sie ihre Eingabe erneut oder wenden Sie sich direkt an uns.
    </p>
@endsection
