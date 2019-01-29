@extends('layouts.app')

@section('java-script')
  <script language="javascript" type="text/javascript" src="{{ asset('js/bookOfStudent.js') }}"></script>
@endsection

@section('header')
  <h1>Księga uczniów</h1>
@endsection

@section('main-content')
  <p class="btn btn-primary" style="float: right;"><a href="{{ route('uczen.search') }}">szukaj</a></p>
  {{ $bookOfStudents->links() }}
  <table id="bookOfStudents">
    <thead>
      <tr>
        <th>id</th>
        <th><a href="{{ route('ksiega_uczniow.order', 'school_id') }}">szkoła</a></th>
        <th><a href="{{ route('ksiega_uczniow.order', 'student_id') }}">uczeń</a></th>
        <th><a href="{{ route('ksiega_uczniow.order', 'number') }}">numer</a></th>
        <th>wpis</th>
        <th>aktualizacja</th>
        <th colspan="2">+/-</th>
      </tr>

      <tr>
        <td>-</td>
        <td><?php  print_r($schoolSelectField);  ?></td>
        <td colspan="6">=</td>
      </tr>
    </thead>
    <tbody>

    @foreach($bookOfStudents as $bookOfStudent)
      <tr>
        @if( !empty($_GET['page']) )
          <td style="font-size: small;">{{$_GET['page']*30-30+$loop->iteration}}</td>
        @else
          <td>{{$loop->iteration}}</td>
        @endif
        <td><a href="{{ route('szkola.show', $bookOfStudent->school_id) }}">{{ $bookOfStudent->school->name }}</a></td>
        <td><a href="{{ route('uczen.show', $bookOfStudent->student_id) }}">{{ $bookOfStudent->student->first_name }} {{ $bookOfStudent->student->last_name }}</a></td>
        <td>{{ $bookOfStudent->number }}</td>
        <td>{{ $bookOfStudent->created_at }}</td>
        <td>{{ $bookOfStudent->updated_at }}</td>
        <td class="edit"><a class="btn btn-primary" href="{{ route('ksiega_uczniow.edit', $bookOfStudent->id) }}">
          <img class="edit" src="{{ asset('css/zmiana.png') }}" alt="[]">
        </a></td>
        <td class="destroy">
          <form action="{{ route('ksiega_uczniow.destroy', $bookOfStudent->id) }}" method="post" id="delete-form-{{$bookOfStudent->id}}">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button class="btn btn-primary"><img class="destroy" src="{{ asset('css/minus.png') }}" /></button>
          </form>
        </td>
      </tr>
    @endforeach

      <tr class="create"><td colspan="8">
          <a class="btn btn-primary" href="{{ route('ksiega_uczniow.create') }}"><img class="create" src="{{ asset('css/plus.png') }}" /></a>
      </td></tr>
    </tbody>
  </table>
@endsection