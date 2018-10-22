@extends('layouts.app')

@section('header')
  <h1>Lekcje</h1>
@endsection

@section('main-content')
  <table id="lessons">
    <thead>
      <tr>
        <th>id</th>
        <th><a href="{{ url('/lekcja/sortuj/group_id') }}">grupa</a></th>
        <th><a href="{{ url('/lekcja/sortuj/teacher_id') }}">nauczyciel</a></th>
        <th><a href="{{ url('/lekcja/sortuj/lesson_date') }}">data lekcji</a></th>
        <th><a href="{{ url('/lekcja/sortuj/lesson_length') }}">czas lekcji</a></th>
        <th><a href="{{ url('/lekcja/sortuj/number') }}">numer</a></th>
        <th><a href="{{ url('/lekcja/sortuj/topic_entered') }}">temat wpisany</a></th>
        <th><a href="{{ url('/lekcja/sortuj/topic_realized') }}">temat zrealizowany</a></th>
        <th><a href="{{ url('/lekcja/sortuj/comments') }}">uwagi</a></th>
        <th>wprowadzono</th>
        <th>aktualizacja</th>
        <th colspan="2">+/-</th>
      </tr>
    </thead>
    <tbody>

    @foreach($lessons as $lesson)
      <tr>
        <td>{{ $lesson->id }}</td>
        <td>{{ $lesson->group_id }}</td>
        <td>{{ $lesson->teacher_id }}</td>
        <td>{{ $lesson->lesson_date }}</td>
        <td>{{ $lesson->lesson_length }}</td>
        <td>{{ $lesson->number }}</td>
        <td>{{ $lesson->topic_entered }}</td>
        <td><a href="{{ route('lekcja.show', $lesson->id) }}">{{ $lesson->topic_realized }}</a></td>
        <td>{{ $lesson->comments }}</td>
        <td>{{ $lesson->created_at }}</td>
        <td>{{ $lesson->updated_at }}</td>
        <td><a href="{{ route('lekcja.edit', $lesson->id) }}"><img class="edit" src="{{ asset('css/zmiana.png') }}" alt="[]"></a></td>
        <td>
          <form action="{{ route('lekcja.destroy', $lesson->id) }}" method="post" id="delete-form-{{$lesson->id}}">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button><img class="destroy" src="{{ asset('css/minus.png') }}" /></button>
          </form>
        </td>
      </tr>
    @endforeach

      <tr class="create">
        <td colspan="13"><a href="{{ route('lekcja.create') }}"><img class="create" src="{{ asset('css/plus.png') }}" /></a></td>
      </tr>
    </tbody>
  </table>
@endsection