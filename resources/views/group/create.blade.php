<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 15.03.2022 *********************** -->
@extends('layouts.app')

@section('java-script')
   <script language="javascript" type="text/javascript" src="{{ asset('public/js/group/group.js') }}"></script>
@endsection

@section('header')
  <h1>Dodawanie grupy</h1>
@endsection

@section('main-content')
  <form action="{{ route('grupa.store') }}" method="post" role="form">
  {{ csrf_field() }}
    <table>
      <tr>
        <th><label for="subject_id">przedmiot</label></th>
        <td><?php  print_r($subjectSelectField);  ?></td>
      </tr>
      <tr>
        <th><label for="start">data początkowa</label></th>
        <td><input id="dateStart" type="date" name="start" required /></td>
        <td>
          <button class="btn btn-primary proposedGradeDateStart">{{ date('Y-m-d') }}</button>
          <button class="btn btn-primary proposedGradeDateStart">{{ $proposedDates['dateOfStartSchoolYear'] }}</button>
        </td>
      </tr>
      <tr>
        <th><label for="end">data końcowa</label></th>
        <td><input id="dateEnd" type="date" name="end" required /></td>
        <td>
          <button class="btn btn-primary proposedGradeDateEnd">{{ $proposedDates['dateOfGraduationOfTheLastGrade'] }}</button>
          <button class="btn btn-primary proposedGradeDateEnd">{{ $proposedDates['dateOfGraduationSchoolYear'] }}</button>
        </td>
      </tr>
      <tr>
        <th><label for="comments">uwagi</label></th>
        <td><input type="text" name="comments" size="10" maxlength="30" /></td>
      </tr>
      <tr>
        <th><label for="level">poziom</label></th>
        <td><?php  print_r($levelSelectField);  ?></td>
      </tr>
      <tr>
        <th><label for="hours">godziny</label></th>
        <td><input type="number" name="hours" size="2" maxlength="1" /></td>
      </tr>
      @if(!empty($teacher))
        <tr>
          <th><label for="teacher_id">grupa dla:</label></th>
          <td class="c"><input type="hidden" name="teacher_id" value="{{$teacher->id}}" />{{$teacher->first_name}} {{$teacher->last_name}}</td>
        </tr>
      @else
        <tr class="hidden">
          <td><input type="text" name="teacher_id" value="0" /></td>
        </tr>
      @endif

      <tr class="submit"><td colspan="2">
          <input type="hidden" name="history_view" value="{{ $_SERVER['HTTP_REFERER'] }}" />
          <button class="btn btn-primary" type="submit">dodaj</button>
          <a class="btn btn-primary" href="{{ route('grupa.index') }}">anuluj</a>
      </td></tr>
    </table>
  </form>

  <div id="error" class="alert alert-info hide"></div>
@endsection