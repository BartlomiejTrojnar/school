@extends('layouts.app')
@section('header')
  <h1>Dodawanie klasy</h1>
@endsection

@section('main-content')
  <form action="{{ route('klasa.store') }}" method="post" role="form">
  {{ csrf_field() }}
    <table>
      <tr>
        <th><label for="year_of_beginning">rok rozpoczecia</label></th>
        <td><input type="number" name="year_of_beginning" min="1900" required autofocus /></td>
      </tr>
      <tr>
        <th><label for="year_of_graduation">rok ukonczenia</label></th>
        <td><input type="number" name="year_of_graduation" min="1900" required /></td>
      </tr>
      <tr>
        <th><label for="symbol">symbol</label></th>
        <td><input type="text" name="symbol" size="1" maxlength="1" /></td>
      </tr>
      <tr>
        <th><label for="school_id">szkoła</label></th>
        <td><?php  print_r($schoolSelectField);  ?></td>
      </tr>

      <tr class="submit"><td colspan="2">
          <input type="hidden" name="history_view" value="{{ $_SERVER['HTTP_REFERER'] }}" />
          <button class="btn btn-primary" type="submit">dodaj</button>
          <a class="btn btn-primary" href="{{ route('klasa.index') }}">anuluj</a>
      </tr>
    </table>
  </form>
@endsection