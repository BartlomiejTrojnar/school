@extends('layouts.app')

@section('header')
  <h1>Oceny poleceń</h1>
@endsection

@section('main-content')
  <table id="commandRatings">
    <thead>
      <tr>
        <th>id</th>
        <th><a href="{{ url('/ocena_polecenia/sortuj/command_id') }}">polecenie</a></th>
        <th><a href="{{ url('/ocena_polecenia/sortuj/task_rating_id') }}">ocena zadania</a></th>
        <th><a href="{{ url('/ocena_polecenia/sortuj/points') }}">punkty</a></th>
        <th>wprowadzono</th>
        <th>aktualizacja</th>
        <th colspan="2">+/-</th>
      </tr>
    </thead>
    <tbody>

    @foreach($commandRatings as $commandRating)
      <tr>
        <td>{{ $commandRating->id }}</td>
        <td>{{ $commandRating->command_id }}</td>
        <td>{{ $commandRating->task_rating_id }}</td>
        <td>{{ $commandRating->points }}</td>
        <td>{{ $commandRating->created_at }}</td>
        <td>{{ $commandRating->updated_at }}</td>
        <td><a href="{{ route('ocena_polecenia.edit', $commandRating->id) }}"><img class="edit" src="{{ asset('css/zmiana.png') }}" alt="[]"></a></td>
        <td>
          <form action="{{ route('ocena_polecenia.destroy', $commandRating->id) }}" method="post" id="delete-form-{{$commandRating->id}}">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button><img class="destroy" src="{{ asset('css/minus.png') }}" /></button>
          </form>
        </td>
      </tr>
    @endforeach

      <tr class="create">
        <td colspan="8"><a href="{{ route('ocena_polecenia.create') }}"><img class="create" src="{{ asset('css/plus.png') }}" /></a></td>
      </tr>
    </tbody>
  </table>
@endsection