@section('java-script')
@extends('layouts.app')
   <script language="javascript" type="text/javascript" src="{{ asset('js/StudentClass.js') }}"></script>
@endsection

@section('header')
  <h1>Dodawanie klasy dla ucznia</h1>
@endsection

@section('main-content')
  <form action="{{ route('klasy_uczniow.store') }}" method="post" role="form">
  {{ csrf_field() }}
    <table>
      <tr>
        <th><label for="student_id">uczeń</label></th>
        <td colspan="2">
          <?php  echo $studentSelectField;  ?>
        </td>
      </tr>
      <tr>
        <th><label for="grade_id">klasa</label></th>
        <td colspan="2">
          <?php  echo $gradeSelectField;  ?>
        </td>
      </tr>
      <tr>
        <th><label for="date_start">data początkowa</label></th>
        <td><input id="date_start" type="date" name="date_start" size="8" maxlength="10" /></td>
        <td><input type="checkbox" name="confirmation_date_start" /></td>
        <td><a id="date_start1" href="">{{ $proposedDates['dateOfSession'] }}</a></td>
        <td><a id="date_start2" href="">{{ $proposedDates['dateOfStartSchoolYear'] }}</a></td>
      </tr>
      <tr>
        <th><label for="date_end">data końcowa</label></th>
        <td><input type="date" name="date_end" size="8" maxlength="10" id="date_end" /></td>
        <td><input type="checkbox" name="confirmation_date_end" /></td>
        <td><a id="date_end1" href="">{{ $proposedDates['dateOfSession'] }}</a></td>
        <td><a id="date_end2" href="">{{ $proposedDates['dateOfGraduationOfTheLastGrade'] }}</a></td>
        <td><a id="date_end3" href="">{{ $proposedDates['dateOfGraduationSchoolYear'] }}</a></td>
      </tr>
      <tr>
        <th><label for="numer">numer</label></th>
        <td><input type="text" name="numer" size="2" maxlength="2" id="numer" /></td>
        <td><input type="checkbox" name="confirmation_numer" /></td>
      <td><a id="proposedNumber" href="">{{ $proposedNumber }}</a></td>
      </tr>
      <tr>
        <th><label for="comments">uwagi</label></th>
        <td><input type="text" name="comments" size="20" maxlength="32" /></td>
        <td><input type="checkbox" name="confirmation_comments" /></td>
      </tr>
      <tr class="submit"><td colspan="3">
          <input type="hidden" name="history_view" value="{{ $_SERVER['HTTP_REFERER'] }}" />
          <button type="submit">dodaj</button>
          <a href="{{ route('klasa.index') }}">anuluj</a>
      </tr>
    </table>
  </form>
@endsection