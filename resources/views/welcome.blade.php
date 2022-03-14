<!DOCTYPE html>
<html>
    <head>
        <title>Laravel</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                display: table;
                font-weight: 100;
                font-family: 'Lato';
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 96px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">
                    Zespół Szkół nr 1 im. Janusza Korczaka w Łańcucie
                </div>
                <div class="links">
                    <a href="{{ url('/login') }}">wejście</a>
                    <a href="{{ url('/register') }}">Nowy użytkownik</a>
                    <a href="http://www.2lolancut.pl">Strona Szkoły</a>
                </div>
            </div>
        </div>
    </body>
</html>
