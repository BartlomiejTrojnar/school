@extends('layouts.app')

@section('header')
  <h1>Grupy</h1>
@endsection

@section('main-content')
  <table id="groups">
    <thead>
      <tr>
        <th>id</th>
        <th><a href="{{ url('/grupa/sortuj/subject_id') }}">przedmiot</a></th>
        <th><a href="{{ url('/grupa/sortuj/date_start') }}">data początkowa</a></th>
        <th><a href="{{ url('/grupa/sortuj/date_end') }}">data końcowa</a></th>
        <th>uwagi</th>
        <th><a href="{{ url('/grupa/sortuj/level') }}">poziom</a></th>
        <th>godziny</th>
        <th>wprowadzono</th>
        <th>aktualizacja</th>
        <th colspan="2">+/-</th>
      </tr>
    </thead>
    <tbody>

    @foreach($groups as $group)
      <tr>
        <td><a href="{{ route('grupa.show', $group->id) }}">{{ $group->id }}</a></td>
        <td>{{ $group->subject_id }} {{ $group->subject_id }}</td>
        <td>{{ $group->date_start }}</td>
        <td>{{ $group->date_end }}</td>
        <td>{{ $group->comments }}</td>
        <td>{{ $group->level }}</td>
        <td>{{ $group->hours }}</td>
        <td>{{ $group->created_at }}</td>
        <td>{{ $group->updated_at }}</td>
        <td><a href="{{ route('grupa.edit', $group->id) }}"><img class="edit" src="{{ asset('css/zmiana.png') }}" alt="[]"></a></td>
        <td>
          <form action="{{ route('grupa.destroy', $group->id) }}" method="post" id="delete-form-{{$group->id}}">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button><img class="destroy" src="{{ asset('css/minus.png') }}" /></button>
          </form>
        </td>
      </tr>
    @endforeach

      <tr class="create">
        <td colspan="11"><a href="{{ route('grupa.create') }}"><img class="create" src="{{ asset('css/plus.png') }}" /></a></td>
      </tr>
    </tbody>
  </table>
@endsection