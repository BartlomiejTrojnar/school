@extends('layouts.app')

@section('header')
  <h1>Dodawanie nauczyciela do grupy</h1>
@endsection
@section('java-script')
  <script src="{{ asset('js/groupTeacher.js') }}"></script>
@endsection

@section('main-content')
  <div id="error" class="alert alert-info hide"></div>
  <form action="{{ route('grupa_nauczyciele.store') }}" method="post" role="form">
  {{ csrf_field() }}
    <input type="hidden" name="group_id" value="{{ $group_id }}" />
    <table>
      <tr>
        <th><label for="teacher_id">nauczyciel</label></th>
        <td><?php  print_r($teacherSelectField);  ?></td>
      </tr>
      <tr>
        <th><label for="date_start">od</label></th>
        <td><input type="date" name="date_start" id="date_start" value="{{ $date_start[0] }}" /></td>
        <td><button class="btn btn-primary teacherDateStart" id="groupDateStart">{{ $date_start[1] }}</button></td>
        <td><button class="btn btn-primary teacherDateStart">{{ $date_start[2] }}</button></td>
      </tr>
      <tr>
        <th><label for="date_end">do</label></th>
        <td><input type="date" name="date_end" id="date_end" value="{{ $date_end[0] }}" /></td>
        <td><button class="btn btn-primary teacherDateEnd" id="groupDateEnd">{{ $date_end[1] }}</button></td>
      </tr>

      <tr class="submit"><td colspan="2">
          <input type="hidden" name="history_view" value="
                 @if($history_view) {{ $history_view }}
                 @else {{ $_SERVER['HTTP_REFERER'] }}
                 @endif
                 " />
          <button type="submit">dodaj</button>
          <a href="@if($history_view) {{ $history_view }} @else {{ $_SERVER['HTTP_REFERER'] }} @endif">anuluj</a>
      </td></tr>
    </table>
  </form>
@endsection