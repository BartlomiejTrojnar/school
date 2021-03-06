<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <!-- Styles -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  @section('css')
    @show
</head>

<body>
  <div id="app">
    <nav class="navbar navbar-default navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <!-- Collapsed Hamburger -->
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
            <span class="sr-only">Toggle Navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>

          <!-- Branding Image -->
          <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('app.name', 'Laravel') }}
          </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
          <!-- Left Side Of Navbar -->
          <ul class="nav navbar-nav">
            &nbsp;
          </ul>

          <!-- Right Side Of Navbar -->
          <ul class="nav navbar-nav navbar-right">
            <!-- Authentication Links -->
            @if (Auth::guest())
              <li><a href="{{ route('login') }}">Logowanie</a></li>
              <li><a href="{{ route('register') }}">Rejestracja</a></li>
            @else
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                  {{ Auth::user()->name }} <span class="caret"></span>
                </a>

                <ul class="dropdown-menu" role="menu">
                  <li>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                      Wyloguj
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                      {{ csrf_field() }}
                    </form>
                  </li>
                </ul>
              </li>
            @endif
          </ul>
        </div>
      </div>
    </nav>

    @if(Auth::guest())
      @yield('content')
    @endif
  </div>

  @if (Auth::check())
    <header>
    @section('header')
      @show
    </header>

    <section id="main-content">
    @section('main-content')
      @show
    </section>

    <nav id="main-menu">
      <ul class="nav flex-column">
        <li class="nav-item"><a class="nav-link" href="{{ route('szkola.index') }}">szko??y</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('rok_szkolny.index') }}">lata szkolne</a></li>
<?php /*
        <li><a href="{{ route('godzina.index') }}">godziny</a></li>
        <li><a href="{{ route('sala.index') }}">sale lekcyjne</a></li>
        <li><a href="{{ route('przedmiot.index') }}">przedmioty</a></li>
*/ ?>
        <li class="nav-item"><a class="nav-link" href="{{ route('uczen.index') }}">uczniowie</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('ksiega_uczniow.index') }}">ksi??ga uczni??w</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('klasa.index') }}">klasy</a></li>
<?php /*
        <li><a href="{{ route('nauczyciel.index') }}">nauczyciele</a></li>
        <li><a href="{{ route('rozszerzenie.index') }}">rozszerzenia</a></li>
        <li><a href="{{ route('grupa.index') }}">grupy</a></li>
        <li><a href="{{ route('grupa_uczniowie.index') }}">uczniowie grupy</a></li>
        <li><a href="{{ route('plan_lekcji.index') }}">plany lekcji</a></li>
        <li><a href="{{ route('lekcja.index') }}">lekcje</a></li>

*/ ?>
        <li class="nav-item"><a class="nav-link" href="{{ route('zadanie.index') }}">zadania</a></li>
<?php /*
        <li><a href="{{ route('polecenie.index') }}">polecenia</a></li>
        <li><a href="{{ route('ocena_zadania.index') }}">oceny zada??</a></li>
        <li><a href="{{ route('ocena_polecenia.index') }}">oceny polece??</a></li>

        <li><a href="{{ route('wzor_swiadectwa.index') }}">wzory ??wiadectw</a></li>
        <li><a href="{{ route('swiadectwo.index') }}">swiadectwa</a></li>
        <li><a href="{{ route('osiagniecie.index') }}">osi??gni??cia</a></li>

        <li><a href="{{ route('podrecznik.index') }}">podr??czniki</a></li>
        <li><a href="{{ route('wybor_podrecznika.index') }}">wybory podr??cznika</a></li>

        <li><a href="{{ route('sesja.index') }}">sesje</a></li>
        <li><a href="{{ route('deklaracja.index') }}">deklaracje</a></li>
        <li><a href="{{ route('opis_egzaminu.index') }}">opisy egzamin??w</a></li>
        <li><a href="{{ route('termin.index') }}">terminy</a></li>
        <li><a href="{{ route('egzamin.index') }}">egzaminy</a></li>
*/ ?>
      </ul>
    </nav>
  @endif

  <!-- Scripts -->
  <script src="{{ asset('js/app.js') }}"></script>
  <script src="http://code.jquery.com/jquery-latest.min.js"></script>

  @section('java-script')
    @show
</body>
</html>
