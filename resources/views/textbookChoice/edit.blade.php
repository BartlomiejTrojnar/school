@extends('layouts.app')
@section('header')
  <h1>Modyfikacja wyboru podręcznika</h1>
@endsection

@section('main-content')
  <form action="{{ route('wybor_podrecznika.update', $textbookChoice->id) }}" method="post" role="form">
  {{ csrf_field() }}
  {{ method_field('PATCH') }}
    <table>
      <tr>
        <th><label for="textbook_id">podręcznik</label></th>
        <td><?php  print_r($textbookSelectField);  ?></td>
      </tr>
      <tr>
        <th><label for="school_id">szkoła</label></th>
        <td><?php  print_r($schoolSelectField);  ?></td>
      </tr>
      <tr>
        <th><label for="school_year_id">rok szkolny</label></th>
        <td><?php  print_r($schoolYearSelectField);  ?></td>
      </tr>
      <tr>
        <th><label for="learning_year">rok nauki</label></th>
        <td><input type="number" name="learning_year" value="{{$textbookChoice->learning_year}}" min="1" max="4" required /></td>
      </tr>
      <tr>
        <th><label for="level">poziom</label></th>
        <td><?php  print_r($levelSelectField);  ?></td>
      </tr>

      <tr class="submit"><td colspan="2">
          <input type="hidden" name="history_view" value="{{ $_SERVER['HTTP_REFERER'] }}" />
          <button type="submit">zapisz zmiany</button>
          <a href="{{ route('wybor_podrecznika.index') }}">anuluj</a>
      </td></tr>
    </table>
  </form>
@endsection