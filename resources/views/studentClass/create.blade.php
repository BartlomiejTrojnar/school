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
        <td><button class="btn btn-primary studentClassDateStart">{{ session()->get('dateSession') }}</button></td>
        <td><button class="btn btn-primary studentClassDateStart">{{ $proposedDates['dateOfStartSchoolYear'] }}</button></td>
      </tr>
      <tr>
        <th><label for="date_end">data końcowa</label></th>
        <td><input type="date" name="date_end" size="8" maxlength="10" id="date_end" /></td>
        <td><input type="checkbox" name="confirmation_date_end" /></td>
        <td><button class="btn btn-primary studentClassDateEnd">{{ date('Y-m-d', strtotime('-1 day', strtotime(session()->get('dateSession')))) }}</button></td>
        <td><button class="btn btn-primary studentClassDateEnd">{{ $proposedDates['dateOfEndSchoolYear'] }}</button></td>
        <td><button class="btn btn-primary studentClassDateEnd">{{ $proposedDates['dateOfGraduationSchoolYear'] }}</button></td>
        <td><button class="btn btn-primary studentClassDateEnd">{{ $proposedDates['dateOfGraduationOfTheLastGrade'] }}</button></td>
      </tr>
      <tr>
        <th><label for="number">numer</label></th>
        <td>
            <input type="text" name="number" size="2" maxlength="2" id="number" />
            <div class="upAndDown">
                <button class="btn btn-primary numerIncrease"><img class="up" src="{{ asset('css/up.png') }}" alt="up" /></button>
                <button class="btn btn-primary numerDecrease"><img class="down" src="{{ asset('css/down.png') }}" alt="down" /></button>
            </div>
        </td>
        <td><input type="checkbox" name="confirmation_number" /></td>
        <td><button class="btn btn-primary studentClassProposedNumber">{{ $proposedNumber }}</button></td>
      </tr>
      <tr>
        <th><label for="comments">uwagi</label></th>
        <td><input type="text" name="comments" size="20" maxlength="32" /></td>
        <td><input type="checkbox" name="confirmation_comments" /></td>
      </tr>
      <tr class="submit"><td colspan="3">
          <input type="hidden" name="history_view" value="{{ $_SERVER['HTTP_REFERER'] }}" />
          <button type="submit" class="btn btn-success">dodaj</button>
          <a href="{{ $_SERVER['HTTP_REFERER'] }}" class="btn btn-success">anuluj</a>
      </tr>
    </table>
  </form>
@endsection