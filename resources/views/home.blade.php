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
            div#menu {
               position: absolute;
               top: 350px;
               width: 100%;
            }
            div#menu ul {
               padding: 0;
               list-style: none;
               width: 400px;
               margin: auto;
            }
            div#menu a {
               margin: 15px;
               text-decoration: none;
               color: #ddd;
               font-size: 1.2em;
               display: block;
               padding: 15px;
               border: 3px solid #99f;
               border-radius: 12px;
               background: #339;
               box-shadow: 7px 7px red;
            }
            div#menu a:hover {
               background: #66b;
               color: #fff;
            }
        </style>
    </head>
  <body>
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

      <div class="title" style="position: absolute; top: 150px;">
        Zespół Szkół nr 1 im. Janusza Korczaka w Łańcucie
      </div>

      <div id="menu"><ul>
         <li><a class="btn btn-warning" href="{{ route('klasy_ucznia.przepiszKlasy') }}">sprawdź i przepisz klasy</a></li>
         <li><a href="">przepisz oceny</a></li>
         <li><a href="{{ route('grupa_uczniowie.sprawdzGrupyUczniow') }}">sprawdź grupy uczniów</a></li>
         <li><a href="">sprawdź historie uczniów</a></li>
         <li><a href="">sprawdź osiągnięcia</a></li>
         <li><a href="">sprawdź rozszerzenia</a></li>
         <li><a href="">sprawdź plan lekcji</a></li>
      </ul></div>
  </body>
</html>
