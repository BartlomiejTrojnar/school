@extends('layouts.app')

@section('header')
  <h1>{{ $task->name }}</h1>
  <aside id="strzalka_l">
    <a href="{{ route('zadanie.show', $previous) }}">
      <img src="{{ asset('css/strzalka_l1.png') }}" alt="poprzedni">
    </a>
  </aside>
  <aside id="strzalka_p">
    <a href="{{ route('zadanie.show', $next) }}">
      <img src="{{ asset('css/strzalka_p1.png') }}" alt="nastepny">
    </a>
  </aside>
@endsection

@section('main-content')
  <ul class="nav nav-tabs nav-justified">
    <li class="nav-item"><a class="nav-link" href="{{ url('zadanie/'.$task->id.'/showInfo') }}">informacje</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('zadanie/'.$task->id.'/showRatings') }}">oceny</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('zadanie/'.$task->id.'/showCommands') }}">polecenia</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ route('zadanie.index') }}">powrót</a></li>
  </ul>

    <p>{{ $task->name }}</p>
    <p>{{ $task->points }}</p>
    <p>{{ $task->importance }}</p>
    <p>{{ $task->sheet_name }}</p>
    <p>{{ $task->created_at }}</p>
    <p>{{ $task->updated_at }}</p>

  <h2>oceny zadania</h2>
  <table>
    <tr>
      <th>uczeń</th>
      <th>termin</th>
      <th>data realizacji</th>
      <th>wersja</th>
      <th>waga</th>
      <th>data oceny</th>
      <th>punkty</th>
      <th>ocena</th>
      <th>uwagi</th>
      <th>dziennik?</th>
      <th>data dziennika</th>
      <th colspan="2">+/-</th>
    </tr>

    @foreach($taskRatings as $taskRating)
    <tr>
      <td><a href="{{ route('ocena_zadania.show', $taskRating->id) }}">
        {{ $command->student_id }}
      </a></td>
      <td><a href="{{ route('ocena_zadania.edit', $taskRating->id) }}"><img class="edit" src="{{ asset('css/zmiana.png') }}" alt="--"></a></td>
      <td>
        <form action="{{ route('ocena_zadania.destroy', $taskRating->id) }}" method="post" id="delete-form-{{$taskRating->id}}">
          {{ csrf_field() }}
          {{ method_field('DELETE') }}
          <button><img class="destroy" src="{{ asset('css/minus.png') }}" /></button>
        </form>
      </td>
    </tr>
    @endforeach

    <tr class="create"><td colspan="12">
      <a href="{{ route('ocena_zadania.create') }}">
        <img class="create" src="{{ asset('css/plus.png') }}" /> dodaj ocenę zadania
      </a>
    </td></tr>
  </table>

    <p><a href="{{ route('zadanie.index') }}">powrót</a></p>
@endsection
