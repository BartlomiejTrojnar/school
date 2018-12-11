@extends('layouts.app')
@section('header')
  <h1>Zamiana danych klasy</h1>
@endsection
@section('main-content')
  <form action="{{ route('klasa.update', $grade->id) }}" method="post" role="form">
  {{ csrf_field() }}
  {{ method_field('PATCH') }}
    <table>
      <tr>
        <th><label for="year_of_beginning">rok rozpoczęcia</label></th>
        <td><input type="text" name="year_of_beginning" value="{{ $grade->year_of_beginning }}" size="3" maxlength="4" /></td>
      </tr>
      <tr>
        <th><label for="year_of_graduation">rok ukonczenia</label></th>
        <td><input type="text" name="year_of_graduation" value="{{ $grade->year_of_graduation }}" size="3" maxlength="4" /></td>
      </tr>
      <tr>
        <th><label for="symbol">symbol</label></th>
        <td><input type="text" name="symbol" value="{{ $grade->symbol }}" size="1" maxlength="1" /></td>
      </tr>
      <tr>
        <th><label for="school_id">szkoła</label></th>
        <td><?php  print_r($schoolSelectField);  ?></td>
      </tr>

      <tr class="submit"><td colspan="2">
          <input type="hidden" name="history_view" value="{{ $_SERVER['HTTP_REFERER'] }}" />
          <button class="btn btn-primary" type="submit">zapisz zmiany</button>
          <a class="btn btn-primary" href="{{ route('klasa.index') }}">anuluj</a>
      </tr>
    </table>
  </form>
@endsection