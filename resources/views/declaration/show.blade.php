<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 28.09.2022 *********************** -->
@extends('layouts.app')

@section('css')
   <link href="{{ asset('public/css/'.$css) }}" rel="stylesheet">
@endsection

@section('java-script')
   <script src="{{ asset('public/js/'.$js) }}"></script>
@endsection

@section('header')
   <aside id="arrow_left"><a href="{{ route('deklaracja.show', $previous) }}"><i class='fa fa-chevron-left'></i></a></aside>
   <aside id="arrow_right"><a href="{{ route('deklaracja.show', $next) }}"><i class='fa fa-chevron-right'></i></a></aside>
   <h1>Deklaracja maturalna: {{ $declaration->id }} ({{ $declaration->student->first_name }} {{ $declaration->student->second_name }} {{ $declaration->student->last_name }})</h1>
   <input type="hidden" id="declaration_id" value="{{ $declaration->id }}" />
@endsection

@section('main-content')
   <ul class="nav nav-tabs nav-justified">
      <li class="nav-item"><a class="nav-link" href="{{ url('deklaracja/'.$declaration->id) }}">informacje <i class='fas fa-info-circle'></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ route('deklaracja.index') }}">powrót <i class='fa fa-undo'></i></a></li>
   </ul>

   <h2>Informacje o deklaracji</h2>
   <table>
      <tr>
         <th>Imię i nazwisko</th>
         <td>{{ $declaration->student->first_name }} {{ $declaration->student->last_name }}</td>
      </tr>
      <tr>
         <th>sesja</th>
         <td class="c">{{ $declaration->session->year }} {{ $declaration->session->type }}</td>
      </tr>
      <tr>
         <th>numer zgłoszenia</th>
         <td class="c">{{ $declaration->application_number }}</td>
      </tr>
      <tr>
         <th>kod ucznia</th>
         <td class="c">{{ $declaration->student_code }}</td>
      </tr>
      <tr>
         <th>liczba egzaminów</th>
         <td class="c">{{ count($declaration->exams) }}</td>
      </tr>
   </table>

   <?php echo $examsTable; ?>
@endsection