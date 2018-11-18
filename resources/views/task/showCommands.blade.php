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

  <h2>polecenia</h2>
  <table id="commands">
    <thead>
      <tr>
        <th>id</th>
        <th><a href="{{ url('/polecenie/sortuj/task_id') }}">zadanie</a></th>
        <th><a href="{{ url('/polecenie/sortuj/number') }}">numer</a></th>
        <th><a href="{{ url('/polecenie/sortuj/command') }}">polecenie</a></th>
        <th><a href="{{ url('/polecenie/sortuj/description') }}">opis</a></th>
        <th><a href="{{ url('/polecenie/sortuj/points') }}">punkty</a></th>
        <th>wprowadzono</th>
        <th>aktualizacja</th>
        <th colspan="2">+/-</th>
      </tr>
    </thead>
    <tbody>

    @foreach($commands as $command)
      <tr>
        <td>{{ $command->id }}</td>
        <td>{{ $command->task_id }}</td>
        <td>{{ $command->number }}</td>
        <td><a href="{{ route('polecenie.show', $command->id) }}">{{ $command->command }}</a></td>
        <td>{{ $command->description }}</td>
        <td>{{ $command->points }}</td>
        <td>{{ $command->created_at }}</td>
        <td>{{ $command->updated_at }}</td>
        <td><a href="{{ route('polecenie.edit', $command->id) }}"><img class="edit" src="{{ asset('css/zmiana.png') }}" alt="[]"></a></td>
        <td>
          <form action="{{ route('polecenie.destroy', $command->id) }}" method="post" id="delete-form-{{$command->id}}">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button><img class="destroy" src="{{ asset('css/minus.png') }}" /></button>
          </form>
        </td>
      </tr>
    @endforeach

      <tr class="create">
        <td colspan="10"><a href="{{ route('polecenie.create') }}"><img class="create" src="{{ asset('css/plus.png') }}" /></a></td>
      </tr>
    </tbody>
  </table>
@endsection