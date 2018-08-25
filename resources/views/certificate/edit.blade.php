@extends('layouts.app')
@section('header')
  <h1>Modyfikacja świadectwa</h1>
@endsection

@section('main-content')
  <form action="{{ route('swiadectwo.update', $certificate->id) }}" method="post" role="form">
  {{ csrf_field() }}
  {{ method_field('PATCH') }}
    <table>
      <tr>
        <th><label for="student_id">uczeń</label></th>
        <td><?php  print_r($studentSelectField);  ?></td>
      </tr>
      <tr>
        <th><label for="sheet_pattern_id">wzór arkusza</label></th>
        <td><?php  print_r($sheetPatternSelectField);  ?></td>
      </tr>
      <tr>
        <th><label for="certificate_pattern_id">wzór świadectwa</label></th>
        <td><?php  print_r($certificatePatternSelectField);  ?></td>
      </tr>
      <tr>
        <th><label for="date_of_council">data rady</label></th>
        <td><input type="date" name="date_of_council" required value="{{$certificate->date_of_council}}" /></td>
      </tr>
      <tr>
        <th><label for="date_of_release">data wydania świadectwa</label></th>
        <td><input type="date" name="date_of_release" required value="{{$certificate->date_of_release}}" /></td>
      </tr>

      <tr class="submit"><td colspan="2">
          <input type="hidden" name="history_view" value="{{ $_SERVER['HTTP_REFERER'] }}" />
          <button type="submit">zapisz zmiany</button>
          <a href="{{ route('swiadectwo.index') }}">anuluj</a>
      </td></tr>
    </table>
  </form>
@endsection