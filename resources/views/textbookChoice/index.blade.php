@extends('layouts.app')

@section('header')
  <h1>Wybory podręczników</h1>
@endsection

@section('main-content')
  <table id="textbookChoices">
    <thead>
      <tr>
        <th>id</th>
        <th><a href="{{ url('/wybor_podrecznika/sortuj/textbook_id') }}">podręcznik</a></th>
        <th><a href="{{ url('/wybor_podrecznika/sortuj/school_id') }}">szkoła</a></th>
        <th><a href="{{ url('/wybor_podrecznika/sortuj/school_year_id') }}">rok szkolny</a></th>
        <th><a href="{{ url('/wybor_podrecznika/sortuj/learning_year') }}">rok nauki</a></th>
        <th><a href="{{ url('/wybor_podrecznika/sortuj/level') }}">poziom</a></th>
        <th>wprowadzono</th>
        <th>aktualizacja</th>
        <th colspan="2">+/-</th>
      </tr>
    </thead>
    <tbody>

    @foreach($textbookChoices as $textbookChoice)
      <tr>
        <td><a href="{{ route('wybor_podrecznika.show', $textbookChoice->id) }}">{{ $textbookChoice->id }}</a></td>
        <td>{{ $textbookChoice->textbook_id }}</td>
        <td>{{ $textbookChoice->school_id }}</td>
        <td>{{ $textbookChoice->school_year_id }}</td>
        <td>{{ $textbookChoice->learning_year }}</td>
        <td>{{ $textbookChoice->level }}</td>
        <td>{{ $textbookChoice->created_at }}</td>
        <td>{{ $textbookChoice->updated_at }}</td>
        <td><a href="{{ route('wybor_podrecznika.edit', $textbookChoice->id) }}"><img class="edit" src="{{ asset('css/zmiana.png') }}" alt="[]"></a></td>
        <td>
          <form action="{{ route('wybor_podrecznika.destroy', $textbookChoice->id) }}" method="post" id="delete-form-{{$textbookChoice->id}}">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button><img class="destroy" src="{{ asset('css/minus.png') }}" /></button>
          </form>
        </td>
      </tr>
    @endforeach

      <tr class="create">
        <td colspan="10"><a href="{{ route('wybor_podrecznika.create') }}"><img class="create" src="{{ asset('css/plus.png') }}" /></a></td>
      </tr>
    </tbody>
  </table>
@endsection