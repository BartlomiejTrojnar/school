@extends('layouts.app')

@section('header')
  <h1>Księga uczniów</h1>
@endsection

@section('main-content')
  <table id="bookOfStudents">
    <thead>
      <tr>
        <th>id</th>
        <th><a href="{{ url('/ksiega_uczniow/sortuj/school_id') }}">szkoła</a></th>
        <th><a href="{{ url('/ksiega_uczniow/sortuj/student_id') }}">uczeń</a></th>
        <th><a href="{{ url('/ksiega_uczniow/sortuj/number') }}">numer</a></th>
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
        <td>{{ $bookOfStudent->id }}</td>
        <td>{{ $bookOfStudent->school->name }}</td>
        <td>{{ $bookOfStudent->student->first_name }} {{ $bookOfStudent->student->last_name }}</td>
        <td><a href="{{ route('ksiega_uczniow.show', $bookOfStudent->id) }}">{{ $bookOfStudent->number }}</a></td>
        <td>{{ $bookOfStudent->created_at }}</td>
        <td>{{ $bookOfStudent->updated_at }}</td>
        <td><a href="{{ route('ksiega_uczniow.edit', $bookOfStudent->id) }}"><img class="edit" src="{{ asset('css/zmiana.png') }}" alt="[]"></a></td>
        <td>
          <form action="{{ route('ksiega_uczniow.destroy', $bookOfStudent->id) }}" method="post" id="delete-form-{{$bookOfStudent->id}}">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button><img class="destroy" src="{{ asset('css/minus.png') }}" /></button>
          </form>
        </td>
      </tr>
    @endforeach

      <tr class="create">
        <td colspan="8"><a href="{{ route('ksiega_uczniow.create') }}"><img class="create" src="{{ asset('css/plus.png') }}" /></a></td>
      </tr>
    </tbody>
  </table>
@endsection