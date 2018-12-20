@extends('layouts.app')
@section('java-script')
   <script language="javascript" type="text/javascript" src="{{ asset('js/StudentClass.js') }}"></script>
@endsection

@section('header')
  <h1>Modyfikacja przynależności ucznia do klasy</h1>
@endsection

@section('main-content')
  <form action="{{ route('klasy_ucznia.update', $studentClass->id) }}" method="post" role="form">
  {{ csrf_field() }}
  {{ method_field('PATCH') }}
    <table>
      <tr>
        <th><label for="student_id">uczeń</label></th>
        <td colspan="3">
          <?php  echo $studentSelectField;  ?>
        </td>
      </tr>
      <tr>
        <th><label for="grade_id">klasa</label></th>
        <td colspan="3">
          <?php  echo $gradeSelectField;  ?>
        </td>
      </tr>
      <tr id="date_start_row">
        <th><label for="date_start">data początkowa</label></th>
        <td><input id="date_start" type="date" name="date_start" size="8" maxlength="10" value="{{ $studentClass->date_start }}" /></td>
        <td><input type="checkbox" name="confirmation_date_start" @if($studentClass->confirmation_date_start==1) checked="checked" @endif /></td>
        <td class="proposedCell">
            <button class="btn btn-primary studentClassDateStart">{{ session()->get('dateSession') }}</button>
            <button class="btn btn-primary studentClassDateStart">{{ $proposedDates['dateOfStartSchoolYear'] }}</button>
        </td>
      </tr>
      <tr id="date_end_row">
        <th><label for="date_end">data końcowa</label></th>
        <td><input id="date_end" type="date" name="date_end" size="8" maxlength="10" value="{{ $studentClass->date_end }}" /></td>
        <td><input type="checkbox" name="confirmation_date_end" @if($studentClass->confirmation_date_end==1) checked="checked" @endif /></td>
        <td class="proposedCell">
            <button class="btn btn-primary studentClassDateEnd">{{ date('Y-m-d', strtotime('-1 day', strtotime(session()->get('dateSession')))) }}</button>
            <button class="btn btn-primary studentClassDateEnd">{{ $proposedDates['dateOfEndSchoolYear'] }}</button>
            <button class="btn btn-primary studentClassDateEnd">{{ $proposedDates['dateOfGraduationSchoolYear'] }}</button>
            <button class="btn btn-primary studentClassDateEnd">{{ $proposedDates['dateOfGraduationOfTheLastGrade'] }}</button>
        </td>
      </tr>
      <tr>
        <th><label for="numer">numer</label></th>
        <td>
            <input type="text" name="number" size="2" maxlength="2" value="{{ $studentClass->number }}" id="number" />
            <div class="upAndDown">
                <button class="btn btn-primary numerIncrease"><img class="up" src="{{ asset('css/up.png') }}" alt="up" /></button>
                <button class="btn btn-primary numerDecrease"><img class="down" src="{{ asset('css/down.png') }}" alt="down" /></button>
            </div>
        </td>
        <td colspan="2"><input type="checkbox" name="confirmation_numer" @if($studentClass->confirmation_numer==1) checked="checked" @endif /></td>
      </tr>
      <tr>
        <th><label for="comments">uwagi</label></th>
        <td><input type="text" name="comments" size="27" maxlength="32" value="{{ $studentClass->comments }}"  /></td>
        <td colspan="2"><input type="checkbox" name="confirmation_comments" @if($studentClass->confirmation_comments==1) checked="checked" @endif /></td>
      </tr>

      <tr class="submit"><td colspan="4">
          <input type="hidden" name="historyView" value="{{ $_SERVER['HTTP_REFERER'] }}" />
          <button type="submit" class="btn btn-primary">zapisz zmiany</button>
          <a href="{{ $_SERVER['HTTP_REFERER'] }}" class="btn btn-primary">anuluj</a>
      </tr>
    </table>
  </form>
@endsection