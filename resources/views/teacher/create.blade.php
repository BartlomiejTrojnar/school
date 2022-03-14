@extends('layouts.app')
@section('header')
  <h1>Dodawanie nauczyciela</h1>
@endsection

@section('main-content')
  <form action="{{ route('nauczyciel.store') }}" method="post" role="form">
  {{ csrf_field() }}
    <table>
      <tr>
        <th><label for="first_name">imię</label></th>
        <td><input type="text" name="first_name" size="12" maxlength="16" /></td>
      </tr>
      <tr>
        <th><label for="last_name">nazwisko</label></th>
        <td><input type="text" name="last_name" size="12" maxlength="18" required /></td>
      </tr>
      <tr>
        <th><label for="family_name">rodowe</label></th>
        <td><input type="text" name="family_name" size="12" maxlength="15" /></td>
      </tr>
      <tr>
        <th><label for="short">skrót</label></th>
        <td><input type="text" name="short" size="2" maxlength="2" /></td>
      </tr>

      <tr>
        <th><label for="degree">stopień</label></th>
        <td><input type="text" name="degree" size="8" maxlength="10" /></td>
      </tr>
      <tr>
        <th><label for="classroom_id">sala</label></th>
        <td><?php  print_r($classroomSelectField);  ?></td>
      </tr>
      <tr>
        <th><label for="first_year_id">rok pierwszy</label></th>
        <td><?php  print_r($firstYearSelectField);  ?></td>
      </tr>
      <tr>
        <th><label for="last_year_id">rok ostatni</label></th>
        <td><?php  print_r($lastYearSelectField);  ?></td>
      </tr>
      <tr>
        <th><label for="order">kolejność</label></th>
        <td><input type="number" name="order" min="1" max="20" /></td>
      </tr>

      <tr class="submit"><td colspan="2">
          <input type="hidden" name="history_view" value="{{ $_SERVER['HTTP_REFERER'] }}" />
          <button class="btn btn-primary" type="submit">dodaj</button>
          <a class="btn btn-primary" href="{{ route('nauczyciel.index') }}">anuluj</a>
      </td></tr>
    </table>
  </form>
@endsection