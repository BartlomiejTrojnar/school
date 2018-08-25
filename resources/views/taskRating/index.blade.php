@extends('layouts.app')

@section('header')
  <h1>Oceny zadań</h1>
@endsection

@section('main-content')
  <table id="taskRatings">
    <thead>
      <tr>
        <th>id</th>
        <th><a href="{{ url('/ocena_zadania/sortuj/student_id') }}">uczeń</a></th>
        <th><a href="{{ url('/ocena_zadania/sortuj/task_id') }}">zadanie</a></th>
        <th><a href="{{ url('/ocena_zadania/sortuj/deadline') }}">termin</a></th>
        <th><a href="{{ url('/ocena_zadania/sortuj/implementation_date') }}">data wykonania</a></th>
        <th><a href="{{ url('/ocena_zadania/sortuj/version') }}">wersja</a></th>
        <th><a href="{{ url('/ocena_zadania/sortuj/importance') }}">waga</a></th>
        <th><a href="{{ url('/ocena_zadania/sortuj/rating_date') }}">data oceny</a></th>
        <th><a href="{{ url('/ocena_zadania/sortuj/points') }}">punkty</a></th>
        <th><a href="{{ url('/ocena_zadania/sortuj/rating') }}">ocena</a></th>
        <th><a href="{{ url('/ocena_zadania/sortuj/comments') }}">uwagi</a></th>
        <th><a href="{{ url('/ocena_zadania/sortuj/diary') }}">dziennik</a></th>
        <th><a href="{{ url('/ocena_zadania/sortuj/entry_date') }}">data dziennika</a></th>
        <th>wprowadzono</th>
        <th>aktualizacja</th>
        <th colspan="2">+/-</th>
      </tr>
    </thead>
    <tbody>

    @foreach($taskRatings as $taskRating)
      <tr>
        <td><a href="{{ route('ocena_zadania.show', $taskRating->id) }}">{{ $taskRating->id }}</a></td>
        <td>{{ $taskRating->student_id }}</td>
        <td>{{ $taskRating->task_id }}</td>
        <td>{{ $taskRating->deadline }}</td>
        <td>{{ $taskRating->implementation_date }}</td>
        <td>{{ $taskRating->version }}</td>
        <td>{{ $taskRating->importance }}</td>
        <td>{{ $taskRating->rating_date }}</td>
        <td>{{ $taskRating->points }}</td>
        <td>{{ $taskRating->rating }}</td>
        <td>{{ $taskRating->comments }}</td>
        <td>{{ $taskRating->diary }}</td>
        <td>{{ $taskRating->entry_date }}</td>
        <td>{{ $taskRating->created_at }}</td>
        <td>{{ $taskRating->updated_at }}</td>
        <td><a href="{{ route('ocena_zadania.edit', $taskRating->id) }}"><img class="edit" src="{{ asset('css/zmiana.png') }}" alt="[]"></a></td>
        <td>
          <form action="{{ route('ocena_zadania.destroy', $taskRating->id) }}" method="post" id="delete-form-{{$taskRating->id}}">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button><img class="destroy" src="{{ asset('css/minus.png') }}" /></button>
          </form>
        </td>
      </tr>
    @endforeach

      <tr class="create">
        <td colspan="17"><a href="{{ route('ocena_zadania.create') }}"><img class="create" src="{{ asset('css/plus.png') }}" /></a></td>
      </tr>
    </tbody>
  </table>
@endsection