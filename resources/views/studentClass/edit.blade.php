@extends('layouts.app')
@section('java-script')
   <script language="javascript" type="text/javascript" src="{{ asset('js/StudentClass.js') }}"></script>
@endsection

@section('header')
  <h1>Modyfikacja przynależności ucznia do klasy</h1>
@endsection

@section('main-content')
  <form action="{{ route('klasy_uczniow.update', $studentClass->id) }}" method="post" role="form">
  {{ csrf_field() }}
  {{ method_field('PATCH') }}
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
        <td><input id="date_start" type="date" name="date_start" size="8" maxlength="10" value="{{ $studentClass->date_start }}" /></td>
        <td><input type="checkbox" name="confirmation_date_start" @if($studentClass->confirmation_date_start==1) checked="checked" @endif /></td>
        <td><a id="date_start1" href="">{{ session()->get('dateSession') }}</a></td>
        <td><a id="date_start2" href="">{{ $proposedDates['dateOfStartSchoolYear'] }}</a></td>
      </tr>
      <tr>
        <th><label for="date_end">data końcowa</label></th>
        <td><input id="date_end" type="date" name="date_end" size="8" maxlength="10" value="{{ $studentClass->date_end }}" /></td>
        <td><input type="checkbox" name="confirmation_date_end" @if($studentClass->confirmation_date_end==1) checked="checked" @endif /></td>
        <td><a id="date_end1" href="">{{ date('Y-m-d', strtotime('-1 day', strtotime(session()->get('dateSession')))) }}</a></td>
        <td><a id="date_end2" href="">{{ $proposedDates['dateOfEndSchoolYear'] }}</a></td>
        <td><a id="date_end3" href="">{{ $proposedDates['dateOfGraduationSchoolYear'] }}</a></td>
        <td><a id="date_end4" href="">{{ $proposedDates['dateOfGraduationOfTheLastGrade'] }}</a></td>
      </tr>
      <tr>
        <th><label for="numer">numer</label></th>
        <td>
          <input type="text" name="number" size="2" maxlength="2" value="{{ $studentClass->number }}" id="number" />
          <a id="numerDecrease" href=""><img class="destroy" src="{{ asset('css/minus.png') }}" /></a>
          <a id="numerIncrease" href=""><img class="create" src="{{ asset('css/plus.png') }}" /></a>
        </td>
        <td><input type="checkbox" name="confirmation_numer" @if($studentClass->confirmation_numer==1) checked="checked" @endif /></td>
      </tr>
      <tr>
        <th><label for="comments">uwagi</label></th>
        <td><input type="text" name="comments" size="20" maxlength="32" value="{{ $studentClass->comments }}"  /></td>
        <td><input type="checkbox" name="confirmation_comments" @if($studentClass->confirmation_comments==1) checked="checked" @endif /></td>
      </tr>
      <tr class="submit"><td colspan="3">
          <input type="hidden" name="historyView" value="{{ $_SERVER['HTTP_REFERER'] }}" />
          <button type="submit">zapisz zmiany</button>
          <a href="{{ route('klasa.index') }}">anuluj</a>
      </tr>
    </table>
  </form>
@endsection