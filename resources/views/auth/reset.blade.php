@extends('platform::auth')
@section('title', __('Passwort vergessen?'))

@section('content')
    <h1 class="h4 text-black mb-4">{{ __('Neues Passwort festlegen') }}</h1>

    <form class="m-t-md"
          role="form"
          method="POST"
          data-controller="layouts--form"
          data-action="layouts--form#submit"
          data-layouts--form-button-animate="#button-login"
          data-layouts--form-button-text="{{ __('Loading...') }}"
          action="{{ route('password.store') }}">
        @csrf

        <input type="hidden" name="resetToken" value="{{ $resetToken }}">

        <input type="hidden" name="email" value="{{ $email }}">

        <div class="form-group">
            {!!  \Orchid\Screen\Fields\Input::make('email_confirmation')
                ->type('email')
                ->required()
                ->tabindex(1)
                ->autofocus()
                ->title('E-Mail-Adresse')
                ->placeholder(__('E-Mail-Adresse eingeben'))
            !!}
        </div>

        <div class="form-group">
            {!!  \Orchid\Screen\Fields\Password::make('password')
                ->title('Passwort')
                ->required()
                ->tabindex(2)
                ->help(__('Sollte mindestens einen Großbuchstaben, eine Zahl und ein Sonderzeichen enthalten!'))
                ->placeholder(__('Neues Passwort eingeben'))
            !!}
        </div>

        <div class="form-group">
            {!!  \Orchid\Screen\Fields\Password::make('password_confirmation')
                ->title('Passwort bestätigen')
                ->required()
                ->tabindex(3)
                ->placeholder(__('Passwort erneut eingeben'))
            !!}
        </div>

        <div class="row align-items-center">
            <div class="ml-auto col-md-6 col-xs-12">
                <button id="button-login" type="submit" class="btn btn-default btn-block" tabindex="4">
                    {{ __('Passwort speichern') }}
                </button>
            </div>
        </div>
    </form>

    <p>
        <i>Information: Wenn Sie nach Bestätigung Ihrer Eingabe nicht zum Login weitergeleitet werden, prüfen Sie Ihre Eingabe erneut!</i>
    </p>
@endsection
