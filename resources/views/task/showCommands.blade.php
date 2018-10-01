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
    <p>{{ $task->name }}</p>
    <p>{{ $task->points }}</p>
    <p>{{ $task->importance }}</p>
    <p>{{ $task->sheet_name }}</p>
    <p>{{ $task->created_at }}</p>
    <p>{{ $task->updated_at }}</p>

  <h2>polecenia</h2>
  <table>
    <tr>
      <th>numer</th>
      <th>polecenie</th>
      <th>opis</th>
      <th>punkty</th>
      <th colspan="2">+/-</th>
    </tr>

    @foreach($commands as $command)
    <tr>
      <td><a href="{{ route('polecenie.show', $command->id) }}">
        {{ $command->name }}
      </a></td>
      <td><a href="{{ route('polecenie.edit', $command->id) }}"><img class="edit" src="{{ asset('css/zmiana.png') }}" alt="--"></a></td>
      <td>
        <form action="{{ route('polecenie.destroy', $command->id) }}" method="post" id="delete-form-{{$command->id}}">
          {{ csrf_field() }}
          {{ method_field('DELETE') }}
          <button><img class="destroy" src="{{ asset('css/minus.png') }}" /></button>
        </form>
      </td>
    </tr>
    @endforeach

    <tr class="create"><td colspan="7">
      <a href="{{ route('polecenie.create') }}">
        <img class="create" src="{{ asset('css/plus.png') }}" /> dodaj polecenie
      </a>
    </td></tr>
  </table>

    <p><a href="{{ route('zadanie.index') }}">powrót</a></p>
@endsection