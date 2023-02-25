<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 22.02.2023 ********************** -->
@extends('layouts.app')

@section('java-script')
   <script language="javascript" type="module" src="{{ asset('public/js/student/index.js') }}"></script>
@endsection

@section('header')
   <h1>Uczniowie</h1>
@endsection

@section('main-content')
<p><a href="{{ route('uczen.import') }}">import uczniów</a></p>
<aside>
   <!-- <p>Dane importowane są z pliku <strong style="border: 1px solid #66f; padding: 5px; background: #555; color: #aaf;">C:/dane/nauczyciele/ksiegauczniow/KsiegaUczniowMSP.xlsx</strong>. Arkusz powinien nosić nazwę <em style="border: 1px solid #66f; padding: 5px; background: #555; color: #aaf;">Uczniowie</em>.</p> -->
   <p>Dane importowane są z pliku <strong style="border: 1px solid #66f; padding: 5px; background: #555; color: #aaf;">C:/dane/nauczyciele/ksiegauczniow/KsiegaUczniow2LO.xlsx</strong>. Arkusz powinien nosić nazwę <em style="border: 1px solid #66f; padding: 5px; background: #555; color: #aaf;">Uczniowie</em>.</p>
   <p>W pliku kolejno powinny być kolumny nazwane: Nazwisko, imie, imie2, PESEL, miejsce_urodzenia, plec, szkola, numer_ksiegi.<br />
      W kolumnach I,J,K,L powinny być: data_przyjecia, poziom_przyjscia, data_opuszczenia, powod_opuszczenia (jeżeli jest data opuszczenia).<br />
      W ostatnich kolumnach M,N,O: oddzial, od, do (daty przynależności do oddziału).</p>
</aside>
<div style="float: right; width: 200px;">
   <p class="btn btn-primary"><a href="{{ route('uczen.search') }}">szukaj</a></p>
</div>

{!! $students->render() !!}
<table id="students">
<thead>
   <tr>
      <?php
         echo view('layouts.thSorting', ["thName"=>"id",          "routeName"=>"uczen.orderBy", "field"=>"id",          "sessionVariable"=>"StudentOrderBy"]);
         echo view('layouts.thSorting', ["thName"=>"imię",        "routeName"=>"uczen.orderBy", "field"=>"first_name",  "sessionVariable"=>"StudentOrderBy"]);
         echo view('layouts.thSorting', ["thName"=>"drugie imię", "routeName"=>"uczen.orderBy", "field"=>"second_name", "sessionVariable"=>"StudentOrderBy"]);
         echo view('layouts.thSorting', ["thName"=>"nazwisko",    "routeName"=>"uczen.orderBy", "field"=>"last_name",   "sessionVariable"=>"StudentOrderBy"]);
      ?>
      <th>rodowe</th>
      <th>płeć</th>
      <?php
         echo view('layouts.thSorting', ["thName"=>"PESEL", "routeName"=>"uczen.orderBy", "field"=>"pesel", "sessionVariable"=>"StudentOrderBy"]);
         echo view('layouts.thSorting', ["thName"=>"miejsce urodzenia", "routeName"=>"uczen.orderBy", "field"=>"place_of_birth", "sessionVariable"=>"StudentOrderBy"]);
      ?>
      <th>wpis</th>
      <th>aktualizacja</th>
      <th>zmień / usuń</th>
   </tr>

   <tr>
      <td>-</td>
      <td><?php  print_r($gradeSF);  ?></td>
      <td><?php  print_r($schoolYearSF);  ?></td>
      <td colspan="8">=</td>
   </tr>
</thead>

<tbody>
   <?php $count = 0; ?>
   @foreach($students as $student)
   <tr data-student_id="{{ $student->id }}">
      @if( !empty($_GET['page']) )
         <td class="small">{{ $_GET['page']*50-50+(++$count) }} ({{ $student->id }})</td>
      @else
         <td class="small">{{ ++$count }} ({{ $student->id }})</td>
      @endif
      <td>{{ $student->first_name }}</td>
      <td>{{ $student->second_name }}</td>
      <td><a href="{{ route('uczen.show', $student->id) }}">{{ $student->last_name }}</a></td>
      <td>{{ $student->family_name }}</td>
      <td class="small">{{ $student->sex }}</td>
      @if( strlen($student->PESEL)==11 )
         <td>{{ $student->PESEL }}</td>
      @else
         <td style="color: red;">{{ $student->PESEL }} ({{ strlen($student->PESEL) }})</td>
      @endif
      <td>{{ $student->place_of_birth }}</td>
      <td class="created">{{ substr($student->created_at, 0, 10) }}</td>
      <td class="updated">{{ substr($student->updated_at, 0, 10) }}</td>
      <td class="edit destroy c">
         <button class="edit btn btn-primary"      data-student_id="{{ $student->id }}"><i class="fa fa-edit"></i></button>
         <button class="destroy btn btn-primary"   data-student_id="{{ $student->id }}"><i class="fa fa-remove"></i></button>
      </td>
   </tr>
   @endforeach

   <tr class="create"><td colspan="11"><button id="showCreateRow" class="btn btn-primary"><i class="fa fa-plus"></i></button>
</tbody>
</table>
<input type="hidden" id="countStudents" value="{{ count($students) }}" />
@endsection