<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 15.10.2021 ********************** -->
<?php $dataBiezaca = date('Y-m-d'); $dataBiezaca = date('Y-m-d', strtotime($dataBiezaca . ' +1 day')); ?>

@extends('layouts.app')

@section('header')
  <h1>Dodawanie nauczyciela do grupy</h1>
@endsection
@section('java-script')
  <script src="{{ asset('public/js/group/groupTeacher.js') }}"></script>
@endsection

@section('main-content')
  <div id="error" class="alert alert-info hide"></div>
  <form action="{{ route('grupa_nauczyciele.store') }}" method="post" role="form" id="addTeacherForm">
  {{ csrf_field() }}
    <input type="hidden" name="group_id" value="{{ $group_id }}" />
    <table>
      <tr>
        <th><label for="teacher_id">nauczyciel</label></th>
        <td><?php  print_r($teacherSelectField);  ?></td>
      </tr>
      <tr>
        <th><label for="start">od</label></th>
        <td><input type="date" name="start" id="start" value="{{ $start[0] }}" /></td>
        <td><button class="btn btn-primary teacherStart" id="groupStart">{{ $start[1] }}</button></td>
        <td><button class="btn btn-primary teacherStart">{{ $start[2] }}</button></td>
        <td><button class="btn btn-primary teacherStart">{{ $dataBiezaca }}</button></td>
      </tr>
      <tr>
        <th><label for="end">do</label></th>
        <td><input type="date" name="end" id="end" value="{{ $end[0] }}" /></td>
        <td><button class="btn btn-primary teacherEnd" id="groupEnd">{{ $end[1] }}</button></td>
      </tr>

      <tr class="submit"><td colspan="2">
        @if($history_view)
          <input type="hidden" name="history_view" value="{{$history_view}}" />
        @else
          <input type="hidden" name="history_view" value="{{$_SERVER['HTTP_REFERER']}}" />
        @endif
          <button class="btn btn-primary" type="submit">dodaj</button>
          <button class="btn btn-primary" type="submit" id="changeTeacher">zamień</button>
        @if($history_view)
          <a class="btn btn-primary" href="{{ $history_view }}">anuluj</a>
        @else
          <a class="btn btn-primary" href="{{ $_SERVER['HTTP_REFERER'] }}">anuluj</a>
        @endif
      </td></tr>
    </table>
  </form>
@endsection