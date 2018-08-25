@extends('layouts.app')
@section('header')
  <h1>Dodawanie egzaminu</h1>
@endsection

@section('main-content')
  <form action="{{ route('egzamin.store') }}" method="post" role="form">
  {{ csrf_field() }}
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
        <td><input type="text" name="points" size="5" maxlength="6" /></td>
      </tr>
      <tr>
        <th><label for="comments">uwagi</label></th>
        <td><input type="text" name="comments" size="10" maxlength="15" /></td>
      </tr>

      <tr class="submit"><td colspan="2">
          <input type="hidden" name="history_view" value="{{ $_SERVER['HTTP_REFERER'] }}" />
          <button type="submit">dodaj</button>
          <a href="{{ route('egzamin.index') }}">anuluj</a>
      </td></tr>
    </table>
  </form>
@endsection