@extends('layouts.app')
@section('header')
  <h1>Dodawanie ucznia do grupy</h1>
@endsection

@section('main-content')
  <form action="{{ route('grupa_uczniowie.store') }}" method="post" role="form">
  {{ csrf_field() }}
    <table>
      <tr>
        <th><label for="group_id">grupa</label></th>
        <td><?php  print_r($groupSelectField);  ?></td>
      </tr>
      <tr>
        <th><label for="student_id">uczeń</label></th>
        <td><?php  print_r($studentSelectField);  ?></td>
      </tr>
      <tr>
        <th><label for="date_start">data początkowa</label></th>
        <td><input type="date" name="date_start" required /></td>
      </tr>
      <tr>
        <th><label for="date_end">data końcowa</label></th>
        <td><input type="date" name="date_end" required /></td>
      </tr>
      <tr>
        <th><label for="year_rating">ocena śródroczna</label></th>
        <td><?php  print_r($midyearRatingSelectField);  ?></td>
      </tr>
      <tr>
        <th><label for="final_rating">ocena końcowa</label></th>
        <td><?php  print_r($finalRatingSelectField);  ?></td>
      </tr>

      <tr class="submit"><td colspan="2">
          <input type="hidden" name="history_view" value="{{ $_SERVER['HTTP_REFERER'] }}" />
          <button type="submit">dodaj</button>
          <a href="{{ route('grupa_uczniowie.index') }}">anuluj</a>
      </td></tr>
    </table>
  </form>
@endsection