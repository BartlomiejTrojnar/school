@extends('layouts.app')

@section('header')
  <h1>Rozszerzenia</h1>
@endsection

@section('main-content')
  <table id="enlargements">
    <thead>
      <tr>
        <th>id</th>
        <th><a href="{{ url('/rozszerzenie/sortuj/student_id') }}">uczeń</a></th>
        <th><a href="{{ url('/rozszerzenie/sortuj/subject_id') }}">przedmiot</a></th>
        <th><a href="{{ url('/rozszerzenie/sortuj/language_level') }}">poziom języka</a></th>
        <th><a href="{{ url('/rozszerzenie/sortuj/date_of_choice') }}">data wyboru</a></th>
        <th><a href="{{ url('/rozszerzenie/sortuj/date_of_resignation') }}">data rezygnacji</a></th>
        <th>dodano</th>
        <th>aktualizacja</th>
        <th colspan="2">+/-</th>
      </tr>
    </thead>
    <tbody>

    @foreach($enlargements as $enlargement)
      <tr>
        <td><a href="{{ route('rozszerzenie.show', $enlargement->id) }}">{{ $enlargement->id }}</a></td>
        <td>{{ $enlargement->student_id }}</td>
        <td>{{ $enlargement->subject_id }}</td>
        <td>{{ $enlargement->language_level }}</td>
        <td>{{ $enlargement->date_of_choice }}</td>
        <td>{{ $enlargement->date_of_resignation }}</td>
        <td>{{ $enlargement->created_at }}</td>
        <td>{{ $enlargement->updated_at }}</td>
        <td><a href="{{ route('rozszerzenie.edit', $enlargement->id) }}"><img class="edit" src="{{ asset('css/zmiana.png') }}" alt="[]"></a></td>
        <td>
          <form action="{{ route('rozszerzenie.destroy', $enlargement->id) }}" method="post" id="delete-form-{{$enlargement->id}}">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button><img class="destroy" src="{{ asset('css/minus.png') }}" /></button>
          </form>
        </td>
      </tr>
    @endforeach

      <tr class="create">
        <td colspan="10"><a href="{{ route('rozszerzenie.create') }}"><img class="create" src="{{ asset('css/plus.png') }}" /></a></td>
      </tr>
    </tbody>
  </table>
@endsection