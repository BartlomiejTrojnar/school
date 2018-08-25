@extends('layouts.app')
@section('header')
  <h1>Dodawanie deklaracji</h1>
@endsection

@section('main-content')
  <form action="{{ route('deklaracja.store') }}" method="post" role="form">
  {{ csrf_field() }}
    <table>
      <tr>
        <th><label for="student_id">uczeń</label></th>
        <td><?php  print_r($studentSelectField);  ?></td>
      </tr>
      <tr>
        <th><label for="session_id">sesja</label></th>
        <td><?php  print_r($sessionSelectField);  ?></td>
      </tr>
      <tr>
        <th><label for="application_number">numer zgłoszenia</label></th>
        <td><input type="number" name="application_number" min="1" max="10" required /></td>
      </tr>
      <tr>
        <th><label for="student_code">kod ucznia</label></th>
        <td><input type="text" name="student_code" size="2" maxlength="3" /></td>
      </tr>

      <tr class="submit"><td colspan="2">
          <input type="hidden" name="history_view" value="{{ $_SERVER['HTTP_REFERER'] }}" />
          <button type="submit">dodaj</button>
          <a href="{{ route('deklaracja.index') }}">anuluj</a>
      </td></tr>
    </table>
  </form>
@endsection