@extends('layouts.app')

@section('header')
  <h1>Opisy egzaminów maturalnych</h1>
@endsection

@section('main-content')
  <table id="examDescriptions">
    <thead>
      <tr>
        <th>id</th>
        <th><a href="{{ url('/opis_egzaminu/sortuj/session_id') }}">sesja</a></th>
        <th><a href="{{ url('/opis_egzaminu/sortuj/subject_id') }}">przedmiot</a></th>
        <th><a href="{{ url('/opis_egzaminu/sortuj/type') }}">typ egzaminu</a></th>
        <th><a href="{{ url('/opis_egzaminu/sortuj/level') }}">poziom</a></th>
        <th><a href="{{ url('/opis_egzaminu/sortuj/max_points') }}">max punktów</a></th>
        <th>wprowadzono</th>
        <th>aktualizacja</th>
        <th colspan="2">+/-</th>
      </tr>

      <tr>
        <td>=</td>
        <td><?php  print_r($sessionSelectField);  ?></td>
        <td><?php  print_r($subjectSelectField);  ?></td>
        <td><?php  print_r($examTypeSelectField);  ?></td>
        <td><?php  print_r($levelSelectField);  ?></td>
        <td colspan="5">=</td>
      </tr>
    </thead>
    <tbody>

    @foreach($examDescriptions as $examDescription)
      <tr>
        <td><a href="{{ route('opis_egzaminu.show', $examDescription->id) }}">{{ $examDescription->id }}</a></td>
        <td>{{ $examDescription->session_id }}</td>
        <td>{{ $examDescription->subject_id }}</td>
        <td>{{ $examDescription->type }}</td>
        <td>{{ $examDescription->level }}</td>
        <td>{{ $examDescription->max_points }}</td>
        <td>{{ $examDescription->created_at }}</td>
        <td>{{ $examDescription->updated_at }}</td>
        <td><a href="{{ route('opis_egzaminu.edit', $examDescription->id) }}"><img class="edit" src="{{ asset('css/zmiana.png') }}" alt="[]"></a></td>
        <td>
          <form action="{{ route('opis_egzaminu.destroy', $examDescription->id) }}" method="post" id="delete-form-{{$examDescription->id}}">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button><img class="destroy" src="{{ asset('css/minus.png') }}" /></button>
          </form>
        </td>
      </tr>
    @endforeach

      <tr class="create">
        <td colspan="10"><a href="{{ route('opis_egzaminu.create') }}"><img class="create" src="{{ asset('css/plus.png') }}" /></a></td>
      </tr>
    </tbody>
  </table>
@endsection