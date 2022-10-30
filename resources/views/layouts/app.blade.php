<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  @section('title')
    @show

  <title>{{ config('app.name', 'Group Manager') }}</title>

  <!-- Styles -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
  <link href="{{ url('public/css/app.css') }}" rel="stylesheet">
  @section('css')
    @show

  <!-- Scripts -->
  <script src="http://code.jquery.com/jquery-latest.min.js"></script>
  <?php /*
  <script language="javascript" type="text/javascript" src="{{ url('public/js/jquery.min.js') }}"></script>
  */ ?>
  <script src="{{ asset('js/app.js') }}"></script>

  @section('java-script')
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
            {{ config('app.name', 'Zespół Szkół nr 1 im. Janusza Korczaka') }}
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

                <ul class="menu" role="menu">
                  <li>
                    <a href="{{ url('auth/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                      Wyloguj
                    </a>

                    <form id="logout-form" action="{{ url('auth/logout') }}" method="POST" style="display: none;">
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
        <li class="nav-item"><a class="nav-link" href="{{ route('szkola.index') }}">szkoły</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('rok_szkolny.index') }}">lata szkolne</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('sala.index') }}">sale lekcyjne</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('przedmiot.index') }}">przedmioty</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('nauczyciel.index') }}@if(!empty( session()->get('TeacherPage') ))?page={{session()->get('TeacherPage')}} @endif">nauczyciele</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('uczen.index') }}">uczniowie</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('ksiega_uczniow.index') }}">księga uczniów</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('klasa.index') }}">klasy</a></li>

        <li class="nav-item"><a class="nav-link" href="{{ route('grupa.index') }}">grupy</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('grupa.compare') }}">porównaj grupy</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('godzina.index') }}">godziny</a></li>
<?php /*
        <li><a href="{{ route('rozszerzenie.index') }}">rozszerzenia</a></li>
        <li><a href="{{ route('grupa_uczniowie.index') }}">uczniowie grupy</a></li>
        <li><a href="{{ route('plan_lekcji.index') }}">plany lekcji</a></li>
        <li><a href="{{ route('lekcja.index') }}">lekcje</a></li>

*/ ?>
        <li class="nav-item"><a class="nav-link" href="{{ route('zadanie.index') }}">zadania</a></li>
<?php /*
        <li><a href="{{ route('polecenie.index') }}">polecenia</a></li>
        <li><a href="{{ route('ocena_zadania.index') }}">oceny zadań</a></li>
        <li><a href="{{ route('ocena_polecenia.index') }}">oceny poleceń</a></li>

        <li><a href="{{ route('wzor_swiadectwa.index') }}">wzory świadectw</a></li>
        <li><a href="{{ route('swiadectwo.index') }}">swiadectwa</a></li>
        <li><a href="{{ route('osiagniecie.index') }}">osiągnięcia</a></li>

*/ ?>
        <li class="nav-item"><a class="nav-link" href="{{ route('podrecznik.index') }}">podręczniki</a></li>

        <li class="nav-item"><a class="nav-link" href="{{ route('sesja.index') }}">sesje</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('opis_egzaminu.index') }}">opisy egzaminów</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('deklaracja.index') }}">deklaracje</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('termin.index') }}">terminy egzaminów</a></li>
<?php /*
        <li><a href="{{ route('wybor_podrecznika.index') }}">wybory podręcznika</a></li>

        <li><a href="{{ route('egzamin.index') }}">egzaminy</a></li>
*/ ?>
      </ul>
    </nav>
  @endif

</body>
</html>
