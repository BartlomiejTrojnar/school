@extends('layouts.app')
@section('header')
  <h1>Zmiana danych terminu</h1>
@endsection

@section('main-content')
  <form action="{{ route('termin.update', $term->id) }}" method="post" role="form">
  {{ csrf_field() }}
  {{ method_field('PATCH') }}
    <table>
      <tr>
        <th><label for="exam_description_id">opis egzaminu</label></th>
        <td><?php  print_r($examDescriptionSelectField);  ?></td>
      </tr>
      <tr>
        <th><label for="classroom_id">sala</label></th>
        <td><?php  print_r($classroomSelectField);  ?></td>
      </tr>
      <tr>
        <th><label for="date_start">data początkowa</label></th>
        <td><input type="datetime-local" name="date_start" value="{{$term->date_start}}" size="16" maxlength="18" required /></td>
      </tr>
      <tr>
        <th><label for="date_end">data końcowa</label></th>
        <td><input type="datetime-local" name="date_end" value="{{$term->date_end}}" size="16" maxlength="18" required /></td>
      </tr>

      <tr class="submit"><td colspan="2">
          <input type="hidden" name="history_view" value="{{ $_SERVER['HTTP_REFERER'] }}" />
          <button type="submit">zapisz zmiany</button>
          <a href="{{ route('termin.index') }}">anuluj</a>
      </td></tr>
    </table>
  </form>
@endsection