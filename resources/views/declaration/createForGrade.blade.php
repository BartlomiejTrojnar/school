<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 06.11.2021 *********************** -->
@extends('layouts.app')

@section('java-script')
   <script src="{{asset('public/js/declaration/createForGrade.js')}}"></script>
@endsection

@section('header')
   <h1>Dodawanie deklaracji dla uczniów klasy</h1>
@endsection

@section('main-content')
   <form action="{{ route('deklaracja.storeForGrade') }}" method="post" role="form">
   {{ csrf_field() }}
      <table>
         <tr>
            <th><label for="session_id">sesja</label></th>
            <td><?php  print_r($sessionSelectField);  ?></td>
         </tr>
         <tr>
            <th><label for="application_number">numer zgłoszenia</label></th>
            <td><input type="number" name="application_number" min="1" max="10" size="2" required /></td>
         </tr>
         <tr>
            <th><label for="session_id">klasa</label></th>
            <td><?php  print_r($gradeSelectField);  ?></td>
         </tr>
         <tr>
            <td colspan="2" id="studentsList">wczytuję listę uczniów</td>
         </tr>   
         <tr class="submit"><td colspan="2">
            <input type="hidden" name="history_view" value="{{ $_SERVER['HTTP_REFERER'] }}" />
            <button class="btn btn-primary" type="submit">dodaj</button>
            <a class="btn btn-primary" href="{{ route('deklaracja.index') }}">anuluj</a>
         </td></tr>
      </table>
   </form>
@endsection