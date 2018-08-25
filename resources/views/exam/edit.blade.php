@extends('layouts.app')
@section('header')
  <h1>Zamiana danych egzaminu</h1>
@endsection

@section('main-content')
  <form action="{{ route('egzamin.update', $exam->id) }}" method="post" role="form">
  {{ csrf_field() }}
  {{ method_field('PATCH') }}
    <table>
      <tr>
        <th><label for="declaration_id">deklaracja</label></th>
        <td><?php  print_r($declarationSelectField);  ?></td>
      </tr>
      <tr>
        <th><label for="exam_description_id">opis egzaminu</label></th>
        <td><?php  print_r($examDescriptionSelectField);  ?></td>
      </tr>
      <tr>
        <th><label for="term_id">termin</label></th>
        <td><?php  print_r($termSelectField);  ?></td>
      </tr>
      <tr>
        <th><label for="exam_type">typ egzaminu</label></th>
        <td><?php  print_r($examTypeSelectField);  ?></td>
      </tr>
      <tr>
        <th><label for="points">punkty</label></th>
        <td><input type="text" name="points" size="5" maxlength="6" value="{{$exam->points}}" /></td>
      </tr>
      <tr>
        <th><label for="comments">uwagi</label></th>
        <td><input type="text" name="comments" size="10" maxlength="15" value="{{$exam->comments}}" /></td>
      </tr>

      <tr class="submit"><td colspan="2">
          <input type="hidden" name="history_view" value="{{ $_SERVER['HTTP_REFERER'] }}" />
          <button type="submit">zapisz zmiany</button>
          <a href="{{ route('egzamin.index') }}">anuluj</a>
      </td></tr>
    </table>
  </form>
@endsection