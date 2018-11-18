@extends('layouts.app')

@section('header')
  <h1>Klasy w grupie</h1>
@endsection

@section('main-content')
  <table id="groupClasses">
    <thead>
      <tr>
        <th>id</th>
        <th>grupa</th>
        <th>klasa</th>
        <th>wprowadzono</th>
        <th>aktualizacja</th>
        <th colspan="2">+/-</th>
      </tr>
    </thead>
    <tbody>

    @foreach($groupClasses as $groupClass)
      <tr>
        <td>{{ $groupClass->id }}</td>
        <td>
          <a href="{{ route('grupa.show', $groupClass->group_id) }}">
            {{ $groupClass->group->date_start }} - {{ $groupClass->group->date_end }} {{ $groupClass->group->subject->name }} {{ $groupClass->group->level }}
          </a>
        </td>
        <td>
          <a href="{{ route('klasa.show', $groupClass->grade_id) }}">
            {{ $groupClass->grade->year_of_beginning }}-{{ $groupClass->grade->year_of_graduation }}{{ $groupClass->grade->symbol }}
          </a>
        </td>
        <td>{{ $groupClass->created_at }}</td>
        <td>{{ $groupClass->updated_at }}</td>
        <td><a href="{{ route('grupa_klasy.edit', $groupClass->id) }}"><img class="edit" src="{{ asset('css/zmiana.png') }}" alt="[]"></a></td>
        <td>
          <form action="{{ route('grupa_klasy.destroy', $groupClass->id) }}" method="post" id="delete-form-{{$groupClass->id}}">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button><img class="destroy" src="{{ asset('css/minus.png') }}" /></button>
          </form>
        </td>
      </tr>
    @endforeach

      <tr class="create">
        <td colspan="7"><a href="{{ route('grupa_klasy.create') }}"><img class="create" src="{{ asset('css/plus.png') }}" /></a></td>
      </tr>
    </tbody>
  </table>
@endsection