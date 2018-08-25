@extends('layouts.app')
@section('header')
  <h1>Dodawanie nauczania przedmiotu</h1>
@endsection

@section('main-content')
  <form action="{{ route('nauczany_przedmiot.store') }}" method="post" role="form">
  {{ csrf_field() }}
    <table>
      <tr>
        <th><label for="teacher_id">nauczyciel</label></th>
        <td><?php  print_r($teacherSelectField);  ?></td>
      </tr>
      <tr>
        <th><label for="subject_id">przedmiot</label></th>
        <td><?php  print_r($subjectSelectField);  ?></td>
      </tr>

      <tr class="submit"><td colspan="2">
          <input type="hidden" name="history_view" value="{{ $_SERVER['HTTP_REFERER'] }}" />
          <button type="submit">dodaj</button>
          <a href="{{ route('nauczany_przedmiot.index') }}">anuluj</a>
      </td></tr>
    </table>
  </form>
@endsection