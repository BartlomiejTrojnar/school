@extends('layouts.app')
@section('header')
  <h1>Wyszukiwanie uczniów</h1>
@endsection

@section('main-content')
  <form action="{{ route('uczen.search_results') }}" method="post" role="form">
  {{ csrf_field() }}
    <table>
      <tr>
        <th><label for="first_name">imię</label></th>
        <td><input type="text" name="first_name" size="10" maxlength="12" /></td>
      </tr>
      <tr>
        <th><label for="last_name">nazwisko</label></th>
        <td><input type="text" name="last_name" size="10" maxlength="12" /></td>
      </tr>
      <tr>
        <th><label for="pesel">PESEL</label></th>
        <td><input type="text" name="PESEL" size="10" maxlength="11" /></td>
      </tr>
      <tr>
        <th><label for="place_of_birth">miejsce urodzenia</label></th>
        <td><input type="text" name="place_of_birth" size="10" maxlength="12" /></td>
      </tr>

      <tr class="submit"><td colspan="2">
          <input type="hidden" name="history_view" value="{{ $_SERVER['HTTP_REFERER'] }}" />
          <input type="hidden" name="search_field" value="1" />
          <button class="btn btn-primary" type="submit">szukaj</button>
          <a class="btn btn-primary" href="{{ $_SERVER['HTTP_REFERER'] }}">anuluj</a>
      </td></tr>
    </table>
  </form>
@endsection