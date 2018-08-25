@extends('layouts.app')

@section('header')
  <h1>Nauczane przedmioty</h1>
@endsection

@section('main-content')
  <table id="taughtSubjects">
    <thead>
      <tr>
        <th>id</th>
        <th><a href="{{ url('/nauczany_przedmiot/sortuj/teacher_id') }}">nauczyciel</a></th>
        <th><a href="{{ url('/nauczany_przedmiot/sortuj/subject_id') }}">przedmiot</a></th>
        <th colspan="2">+/-</th>
      </tr>
    </thead>
    <tbody>

    @foreach($taughtSubjects as $taughtSubject)
      <tr>
        <td><a href="{{ route('nauczany_przedmiot.show', $taughtSubject->id) }}">{{ $taughtSubject->id }}</a></td>
        <td>{{ $taughtSubject->teacher->last_name }}</td>
        <td>{{ $taughtSubject->subject->name }}</td>
        <td><a href="{{ route('nauczany_przedmiot.edit', $taughtSubject->id) }}"><img class="edit" src="{{ asset('css/zmiana.png') }}" alt="[]"></a></td>
        <td>
          <form action="{{ route('nauczany_przedmiot.destroy', $taughtSubject->id) }}" method="post" id="delete-form-{{$taughtSubject->id}}">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button><img class="destroy" src="{{ asset('css/minus.png') }}" /></button>
          </form>
        </td>
      </tr>
    @endforeach

      <tr class="create">
        <td colspan="5"><a href="{{ route('nauczany_przedmiot.create') }}"><img class="create" src="{{ asset('css/plus.png') }}" /></a></td>
      </tr>
    </tbody>
  </table>
@endsection