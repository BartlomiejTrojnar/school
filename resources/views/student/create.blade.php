@extends('layouts.app')
@section('header')
  <h1>Dodawanie ucznia</h1>
@endsection

@section('main-content')
  <form action="{{ route('uczen.store') }}" method="post" role="form">
  {{ csrf_field() }}
    <table>
      <tr>
        <th><label for="first_name">imię</label></th>
        <td><input type="text" name="first_name" size="10" maxlength="12" required autofocus /></td>
      </tr>
      <tr>
        <th><label for="second_name">drugie imię</label></th>
        <td><input type="text" name="second_name" size="10" maxlength="12" /></td>
      </tr>
      <tr>
        <th><label for="last_name">nazwisko</label></th>
        <td><input type="text" name="last_name" size="12" maxlength="15" required /></td>
      </tr>
      <tr>
        <th><label for="family_name">rodowe</label></th>
        <td><input type="text" name="family_name" size="10" maxlength="12" /></td>
      </tr>
      <tr>
        <th><label for="sex">płeć</label></th>
        <td><?php  print_r($sexSelectField);  ?></td>
      </tr>
      <tr>
        <th><label for="pesel">PESEL</label></th>
        <td><input type="text" name="PESEL" size="11" maxlength="11" /></td>
      </tr>
      <tr>
        <th><label for="place_of_birth">miejsce urodzenia</label></th>
        <td><input type="text" name="place_of_birth" size="10" maxlength="12" /></td>
      </tr>

      <tr class="submit"><td colspan="2">
          <input type="hidden" name="history_view" value="{{ $_SERVER['HTTP_REFERER'] }}" />
          <button class="btn btn-primary" type="submit">dodaj</button>
          <a class="btn btn-primary" href="{{ $_SERVER['HTTP_REFERER'] }}">anuluj</a>
      </td></tr>
    </table>
  </form>
@endsection