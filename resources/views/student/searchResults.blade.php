@extends('layouts.app')

@section('java-script')
   <script language="javascript" type="module" src="{{ asset('public/js/student/index.js') }}"></script>
@endsection

@section('header')
   <h1>Znalezieni uczniowie</h1>
@endsection

@section('main-content')
<p class="btn btn-primary" style="float: right;"><a href="{{ route('uczen.search') }}">szukaj</a></p>
<table id="students">
<thead>
   <tr>
      <th>id</th>
      <th>imię</th>
      <th>drugie imię</th>
      <th>nazwisko</th>
      <th>rodowe</th>
      <th>płeć</th>
      <th>PESEL</th>
      <th>miejsce urodzenia</th>
      <th>wpis</th>
      <th>aktualizacja</th>
      <th>zmień / usuń</th>
   </tr>
   <tr style="background: orange;">
      <td></td>
      <td>{{ $request->first_name }}</td>
      <td>{{ $request->second_name }}</td>
      <td>{{ $request->last_name }}</td>
      <td></td>
      <td></td>
      <td>{{ $request->pesel }}</td>
      <td>{{ $request->place_of_birth }}</td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
   </tr>
</thead>
<tbody>
   <?php $count=0; ?>
   @foreach($students as $student)
   <tr data-student_id="{{ $student->id }}">
      <td style="font-size: x-small;">{{ ++$count }} ({{ $student->id }})</td>
      <td>{{ $student->first_name }}</td>
      <td>{{ $student->second_name }}</td>
      <td><a href="{{ route('uczen.show', $student->id) }}">{{ $student->last_name }}</a></td>
      <td>{{ $student->family_name }}</td>
      <td style="font-size: x-small">{{ $student->sex }}</td>
      <td>{{ $student->PESEL }}</td>
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
@endsection