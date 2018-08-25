@extends('layouts.app')
@section('java-script')
   <script language="javascript" type="text/javascript" src="{{ asset('js/StudentClass.js') }}"></script>
@endsection

@section('header')
  <h1>Modyfikacja przynależności ucznia do klasy</h1>
@endsection

@section('main-content')
  <form action="{{ route('klasy_uczniow.update', $sc->id) }}" method="post" role="form">
  {{ csrf_field() }}
  {{ method_field('PATCH') }}
    <table>
      <tr>
        <th><label for="student_id">uczeń</label></th>
        <td><?php  print_r($studentSelectField);  ?></td>
      </tr>
      <tr>
        <th><label for="grade_id">klasa</label></th>
        <td><?php  print_r($gradeSelectField);  ?></td>
        <td colspan="2">
          <?php /*
            use App\Grade;
            $grade = new Grade;
            $grade->printSelectField($sc->grade_id);
          */ ?>
        </td>
      </tr>
      <tr>
        <th><label for="date_start">data początkowa</label></th>
        <td><input id="date_start" type="date" name="date_start" size="8" maxlength="10" value="{{ $sc->date_start }}" /></td>
        <td><input type="checkbox" name="confirmation_date_start" @if($sc->confirmation_date_start==1) checked="checked" @endif /></td>
        <td><a id="date_start1" href="">{{ $dates['dateOfSession'] }}</a></td>
        <td><a id="date_start2" href="">{{ $dates['dateOfStartSchoolYear'] }}</a></td>
      </tr>
      <tr>
        <th><label for="date_end">data końcowa</label></th>
        <td><input id="date_end" type="date" name="date_end" size="8" maxlength="10" value="{{ $sc->date_end }}" /></td>
        <td><input type="checkbox" name="confirmation_date_end" @if($sc->confirmation_date_end==1) checked="checked" @endif /></td>
        <td><a id="date_end1" href="">{{ $dates['dateOfSession'] }}</a></td>
        <td><a id="date_end2" href="">{{ $dates['dateOfGraduationOfTheLastGrade'] }}</a></td>
        <td><a id="date_end3" href="">{{ $dates['dateOfGraduationSchoolYear'] }}</a></td>
      </tr>
      <tr>
        <th><label for="numer">numer</label></th>
        <td>
          <input type="text" name="numer" size="2" maxlength="2" value="{{ $sc->numer }}" id="numer" />
          <a id="numerDecrease" href=""><img class="destroy" src="{{ asset('css/minus.png') }}" /></a>
          <a id="numerIncrease" href=""><img class="create" src="{{ asset('css/plus.png') }}" /></a>
        </td>
        <td><input type="checkbox" name="confirmation_numer" @if($sc->confirmation_numer==1) checked="checked" @endif /></td>
      </tr>
      <tr>
        <th><label for="comments">uwagi</label></th>
        <td><input type="text" name="comments" size="20" maxlength="32" value="{{ $sc->comments }}"  /></td>
        <td><input type="checkbox" name="confirmation_comments" @if($sc->confirmation_comments==1) checked="checked" @endif /></td>
      </tr>
      <tr class="submit"><td colspan="3">
          <input type="hidden" name="view" value="{{ $_SERVER['HTTP_REFERER'] }}" />
          <button type="submit">zapisz zmiany</button>
          <a href="{{ route('klasa.index') }}">anuluj</a>
      </tr>
    </table>
  </form>
@endsection