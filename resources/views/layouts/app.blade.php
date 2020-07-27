<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/bootstrap.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-fixed-top navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                  <img class="pr-3" src="/img/timelapse-systems-LOGO_green.jpg" ondragstart="return false;" alt="Timelapse Systems Logo">
                  <span class="border-left border-dark pl-3">
                    {{ config('app.name', 'Laravel') }}
                  </span>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                      @auth
                      <!-- Creator dropdown for users, projects and companies -->
                      @can ('isManagerOrAdmin')
                        <li class="nav-item">
                          <a id="navbarDropdown" class="nav-link" href="#" role="button" aria-haspopup="true" aria-expanded="false" v-pre>
                            Reparaturen
                          </a>
                        </li>
                        <li class="nav-item dropdown">
                          <a id="navbar-dropdown-manager" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            Manager<span class="caret"></span>
                          </a>

                          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a href="{{ route('user.list') }}" class="dropdown-item">Benutzer verwalten</a>
                            <a href="{{ route('company.list') }}" class="dropdown-item">Firmen verwalten</a>
                          </div>
                        </li>
                        <li class="nav-item dropdown">
                          <a id="navbar-dropdown-creator" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            Creator<span class="caret"></span>
                          </a>

                          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a href="{{ route('user.create') }}" class="dropdown-item">Benutzer erstellen</a>
                            <a href="{{ route('company.create') }}" class="dropdown-item">Firma anlegen</a>
                            <a href="{{ route('project.create') }}" class="dropdown-item">Projekt anlegen</a>
                            <a href="{{ route('system.create') }}" class="dropdown-item">System anlegen</a>
                          </div>
                        </li>
                      @endcan
                      <!-- Logout option -->
                      <li class="nav-item dropdown">
                          <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                          {{ Auth::user()->gender }} {{ Auth::user()->title }} {{ Auth::user()->last_name }} <span class="caret"></span>
                          </a>

                          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('user.index') }}">Account</a>
                            <a class="dropdown-item" href="{{ route('company.index') }}">Firmeninformation</a>

                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                              document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                          </div>
                      </li>
                      @endauth
                      <!-- Authentication Links -->
                      @guest
                      @if (!Route::is('login'))
                      <li class="nav-item">
                          <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                      </li>
                        @if (Route::has('register'))
                          <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                          </li>
                        @endif
                      @endif
                      @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
          <div class="container">
            <div class="justify-content-center">
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
              @elseif (session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                  {{ session('warning') }}
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
              @endif
            </div>
          </div>
            @yield('content')
        </main>
    </div>
</body>
</html>
