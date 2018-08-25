@extends('layouts.app')

@section('header')
  <h1>Zadania</h1>
@endsection

@section('main-content')
  <table id="tasks">
    <thead>
      <tr>
        <th>id</th>
        <th><a href="{{ url('/zadanie/sortuj/name') }}">nazwa</a></th>
        <th><a href="{{ url('/zadanie/sortuj/points') }}">punkty</a></th>
        <th><a href="{{ url('/zadanie/sortuj/importance') }}">waga</a></th>
        <th><a href="{{ url('/zadanie/sortuj/sheet_name') }}">arkusz</a></th>
        <th>wprowadzono</th>
        <th>aktualizacja</th>
        <th colspan="2">+/-</th>
      </tr>
    </thead>
    <tbody>

    @foreach($tasks as $task)
      <tr>
        <td>{{ $task->id }}</td>
        <td><a href="{{ route('zadanie.show', $task->id) }}">{{ $task->name }}</a></td>
        <td>{{ $task->points }}</td>
        <td>{{ $task->importance }}</td>
        <td>{{ $task->sheet_name }}</td>
        <td>{{ $task->created_at }}</td>
        <td>{{ $task->updated_at }}</td>
        <td><a href="{{ route('zadanie.edit', $task->id) }}"><img class="edit" src="{{ asset('css/zmiana.png') }}" alt="[]"></a></td>
        <td>
          <form action="{{ route('zadanie.destroy', $task->id) }}" method="post" id="delete-form-{{$task->id}}">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button><img class="destroy" src="{{ asset('css/minus.png') }}" /></button>
          </form>
        </td>
      </tr>
    @endforeach

      <tr class="create">
        <td colspan="9"><a href="{{ route('zadanie.create') }}"><img class="create" src="{{ asset('css/plus.png') }}" /></a></td>
      </tr>
    </tbody>
  </table>
@endsection