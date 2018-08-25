@extends('layouts.app')

@section('header')
  <h1>Uczniowie grupy</h1>
@endsection

@section('main-content')
  <table id="groupStudents">
    <thead>
      <tr>
        <th>id</th>
        <th><a href="{{ url('/grupa_uczniowie/sortuj/group_id') }}">grupa</a></th>
        <th><a href="{{ url('/grupa_uczniowie/sortuj/student_id') }}">uczeń</a></th>
        <th><a href="{{ url('/grupa_uczniowie/sortuj/date_start') }}">data początkowa</a></th>
        <th><a href="{{ url('/grupa_uczniowie/sortuj/date_end') }}">data końcowa</a></th>
        <th>ocena śródroczna</th>
        <th>ocena końcowa</th>
        <th>wprowadzono</th>
        <th>aktualizacja</th>
        <th colspan="2">+/-</th>
      </tr>
    </thead>
    <tbody>

    @foreach($groupStudents as $groupStudent)
      <tr>
        <td>{{ $groupStudent->id }}</td>
        <td>{{ $groupStudent->group_id }}</td>
        <td>{{ $groupStudent->student_id }}</td>
        <td>{{ $groupStudent->date_start }}</td>
        <td>{{ $groupStudent->date_end }}</td>
        <td>{{ $groupStudent->midyear_rating }}</td>
        <td>{{ $groupStudent->final_rating }}</td>
        <td>{{ $groupStudent->created_at }}</td>
        <td>{{ $groupStudent->updated_at }}</td>
        <td><a href="{{ route('grupa_uczniowie.edit', $groupStudent->id) }}"><img class="edit" src="{{ asset('css/zmiana.png') }}" alt="[]"></a></td>
        <td>
          <form action="{{ route('grupa_uczniowie.destroy', $groupStudent->id) }}" method="post" id="delete-form-{{$groupStudent->id}}">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button><img class="destroy" src="{{ asset('css/minus.png') }}" /></button>
          </form>
        </td>
      </tr>
    @endforeach

      <tr class="create">
        <td colspan="11"><a href="{{ route('grupa_uczniowie.create') }}"><img class="create" src="{{ asset('css/plus.png') }}" /></a></td>
      </tr>
    </tbody>
  </table>
@endsection