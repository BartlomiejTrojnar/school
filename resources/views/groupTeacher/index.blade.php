@extends('layouts.app')

@section('header')
  <h1>Nauczyciele grupy</h1>
@endsection

@section('main-content')
  <table id="groupTeachers">
    <thead>
      <tr>
        <th>id</th>
        <th><a href="{{ url('/grupa_nauczyciele/sortuj/group_id') }}">grupa</a></th>
        <th><a href="{{ url('/grupa_nauczyciele/sortuj/teacher_id') }}">nauczyciel</a></th>
        <th><a href="{{ url('/grupa_nauczyciele/sortuj/date_start') }}">data początkowa</a></th>
        <th><a href="{{ url('/grupa_nauczyciele/sortuj/date_end') }}">data końcowa</a></th>
        <th>wprowadzono</th>
        <th>aktualizacja</th>
        <th colspan="2">+/-</th>
      </tr>
    </thead>
    <tbody>

    @foreach($groupTeachers as $groupTeacher)
      <tr>
        <td>{{ $groupTeacher->id }}</td>
        <td>{{ $groupTeacher->group_id }}</td>
        <td>{{ $groupTeacher->teacher_id }}</td>
        <td>{{ $groupTeacher->date_start }}</td>
        <td>{{ $groupTeacher->date_end }}</td>
        <td>{{ $groupTeacher->created_at }}</td>
        <td>{{ $groupTeacher->updated_at }}</td>
        <td><a href="{{ route('grupa_nauczyciele.edit', $groupTeacher->id) }}"><img class="edit" src="{{ asset('css/zmiana.png') }}" alt="[]"></a></td>
        <td>
          <form action="{{ route('grupa_nauczyciele.destroy', $groupTeacher->id) }}" method="post" id="delete-form-{{$groupTeacher->id}}">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button><img class="destroy" src="{{ asset('css/minus.png') }}" /></button>
          </form>
        </td>
      </tr>
    @endforeach

      <tr class="create">
        <td colspan="9"><a href="{{ route('grupa_nauczyciele.create') }}"><img class="create" src="{{ asset('css/plus.png') }}" /></a></td>
      </tr>
    </tbody>
  </table>
@endsection