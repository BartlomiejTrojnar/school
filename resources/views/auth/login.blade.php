<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>{{ config('app.name', 'Group Manager') }}</title>

  <!-- Styles -->
  <link href="{{ url('public/css/app.css') }}" rel="stylesheet">
</head>

<body class="c">
  <h1>Group Manager</h1>
  <h2>Zespół Szkół nr 1 im. Janusza Korczaka w Łańcucie</h2>
  <main>Zaloguj się aby wejść dalej</main>

  <form method="POST" action="{{ url('auth/login') }}">
    {!! csrf_field() !!}

    <div style="width: 48%; height: 125px; border: 2px solid gray; border-radius: 20px; float: left; margin: auto 1%;">
      <div>
        Email
        <input type="email" name="email" value="{{ old('email')  }}" />
      </div>
      <div>
        Hasło
        <input type="password" name="password" id="password" />
      </div>
      <div>
        <input type="checkbox" name="remember" /> Zapamiętaj mnie
      </div>
      <div>
        <button class="btn btn-primary" type="submit">Zaloguj</button>
      </div>
    </div>

    <div style="width: 48%; height: 45px; padding: 40px 0; border: 2px solid gray; border-radius: 20px; float: left;">
      <div>
        <a class="btn btn-primary" href="{{ url('auth/register') }}">Zarejestruj się</a>
      </div>
    </div>
  </form>
</body>