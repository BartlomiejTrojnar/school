@extends('layouts.app')
@section('header')
  <h1>Zamiana danych ucznia</h1>
@endsection

@section('main-content')
  <form action="{{ route('uczen.update', $student->id) }}" method="post" role="form">
  {{ csrf_field() }}
  {{ method_field('PATCH') }}
    <table>
      <tr>
        <th><label for="first_name">imię</label></th>
        <td><input type="text" name="first_name" value="{{ $student->first_name }}" size="10" maxlength="12" required /></td>
      </tr>
      <tr>
        <th><label for="second_name">drugie imię</label></th>
        <td><input type="text" name="second_name" value="{{ $student->second_name }}" size="10" maxlength="12" /></td>
      </tr>
      <tr>
        <th><label for="last_name">nazwisko</label></th>
        <td><input type="text" name="last_name" value="{{ $student->last_name }}" size="12" maxlength="15" required /></td>
      </tr>
      <tr>
        <th><label for="family_name">rodowe</label></th>
        <td><input type="text" name="family_name" value="{{ $student->family_name }}" size="10" maxlength="12" /></td>
      </tr>
      <tr>
        <th><label for="sex">płeć</label></th>
        <td><?php  print_r($sexSelectField);  ?></td>
      </tr>
      <tr>
        <th><label for="pesel">PESEL</label></th>
        <td><input type="text" name="PESEL" value="{{ $student->PESEL }}" size="10" maxlength="11" /></td>
      </tr>
      <tr>
        <th><label for="place_of_birth">miejsce urodzenia</label></th>
        <td><input type="text" name="place_of_birth" value="{{ $student->place_of_birth }}" size="10" maxlength="12" /></td>
      </tr>
      <tr class="submit"><td colspan="2">
        <input type="hidden" name="history_view" value="{{ $_SERVER['HTTP_REFERER'] }}" />
        <button class="btn btn-primary" type="submit">zapisz zmiany</button>
        <a class="btn btn-primary" href="{{ route('uczen.index') }}">anuluj</a>
      </td></tr>
    </table>
  </form>
@endsection