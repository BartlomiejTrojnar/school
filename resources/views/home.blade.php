<!DOCTYPE html>
<html>
    <head>
        <title>II Liceum Ogólnokształcące w Łańcucie</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
                text-align: center;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
      <div class="flex-center position-ref full-height">
        <div class="top-right links">
          @if (Auth::check())
            <a href="http://www.2lolancut.pl">Strona Szkoły</a>
            <a href="{{ url('/home') }}">Home</a>
            <a href="{{ url('szkola') }}">Szkoły</a>
            <a href="{{ url('auth/logout') }}">Wyloguj</a>
          @else
            <a href="{{ url('/login') }}">Logowanie</a>
            <a href="{{ url('/register') }}">Rejestracja</a>
          @endif
        </div>
      <div class="title">
        Zespół Szkół nr 1 im. Janusza Korczaka w Łańcucie
      </div>

    </body>
</html>
