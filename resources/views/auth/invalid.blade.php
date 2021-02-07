@extends('platform::auth')
@section('title', __('Link ungültig'))

@section('content')
    <h1 class="h4 text-black mb-4">{{ __('Link ist ungültig!') }}</h1>

    <p>
        Ihr Aktivierungslink ist ungültig! Bitte fordern Sie einen neuen Link an!
    </p>
    <p>
        <i>Bitte beachten Sie, dass jeder Aktivierungslink nur 18 Stunden ab Aussendung gültig ist.</i>
    </p>
@endsection
