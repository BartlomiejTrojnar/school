@extends('layouts.app')

@section('header')
  <h1>Egzaminy</h1>
@endsection

@section('main-content')
  <table id="exams">
    <thead>
      <tr>
        <th>id</th>
        <th><a href="{{ url('/egzamin/sortuj/declaration_id') }}">deklaracja</a></th>
        <th><a href="{{ url('/egzamin/sortuj/exam_description_id') }}">opis egzaminu</a></th>
        <th><a href="{{ url('/egzamin/sortuj/term_id') }}">termin</a></th>
        <th><a href="{{ url('/egzamin/sortuj/exam_type') }}">typ egzaminu</a></th>
        <th><a href="{{ url('/egzamin/sortuj/points') }}">punkty</a></th>
        <th><a href="{{ url('/egzamin/sortuj/comments') }}">uwagi</a></th>
        <th>wprowadzono</th>
        <th>aktualizacja</th>
        <th colspan="2">+/-</th>
      </tr>

      <tr>
        <td>-</td>
        <td><?php  print_r($declarationSelectField);  ?></td>
        <td><?php  print_r($examDescriptionSelectField);  ?></td>
        <td><?php  print_r($termSelectField);  ?></td>
        <td><?php  print_r($examTypeSelectField);  ?></td>
        <td colspan="6">=</td>
      </tr>
    </thead>
    <tbody>

    @foreach($exams as $exam)
      <tr>
        <td><a href="{{ route('egzamin.show', $exam->id) }}">{{ $exam->id }}</a></td>
        <td>{{ $exam->declaration_id }}</td>
        <td>{{ $exam->exam_description_id }}</td>
        <td>{{ $exam->term_id }}</td>
        <td>{{ $exam->exam_type }}</td>
        <td>{{ $exam->points }}</td>
        <td>{{ $exam->comments }}</td>
        <td>{{ $exam->created_at }}</td>
        <td>{{ $exam->updated_at }}</td>
        <td><a href="{{ route('egzamin.edit', $exam->id) }}"><img class="edit" src="{{ asset('css/zmiana.png') }}" alt="[]"></a></td>
        <td>
          <form action="{{ route('egzamin.destroy', $exam->id) }}" method="post" id="delete-form-{{$exam->id}}">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button><img class="destroy" src="{{ asset('css/minus.png') }}" /></button>
          </form>
        </td>
      </tr>
    @endforeach

      <tr class="create">
        <td colspan="11"><a href="{{ route('egzamin.create') }}"><img class="create" src="{{ asset('css/plus.png') }}" /></a></td>
      </tr>
    </tbody>
  </table>
@endsection