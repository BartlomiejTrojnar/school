@extends('layouts.app')
@section('header')
  <h1>dodawanie przedmiotu</h1>
@endsection

@section('main-content')
  <form action="{{ route('przedmiot.store') }}" method="post" role="form">
  {{ csrf_field() }}
    <table>
      <tr>
        <th><label for="name">nazwa</label></th>
        <td><input type="text" name="name" size="30" maxlength="60" required autofocus /></td>
      </tr>
      <tr>
        <th><label for="short_name">skrót nazwy</label></th>
        <td><input type="text" name="short_name" size="10" maxlength="15" /></td>
      </tr>
      <tr>
        <th><label for="actual">aktualny?</label></th>
        <td><input type="checkbox" name="actual" /></td>
      </tr>
      <tr>
        <th><label for="order_in_the_sheet">kolejność a arkuszu</label></th>
        <td><input type="number" name="order_in_the_sheet" min="1" max="25" /></td>
      </tr>
      <tr>
        <th><label for="expanded">rozszerzany</label></th>
        <td><input type="checkbox" name="expanded" /></td>
      </tr>

      <tr class="submit"><td colspan="2">
          <input type="hidden" name="history_view" value="{{ $_SERVER['HTTP_REFERER'] }}" />
          <button class="btn btn-primary" type="submit">dodaj</button>
          <a class="btn btn-primary" href="{{ $_SERVER['HTTP_REFERER'] }}">anuluj</a>
      </td></tr>
    </table>
  </form>
@endsection