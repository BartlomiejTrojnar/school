@extends('layouts.app')

@section('header')
  <h1>Szkoły</h1>
@endsection

@section('main-content')
  <table id="schools">
    <thead>
      <tr>
        <th>id</th>
        <th><a href="{{ url('/szkola/sortuj/name') }}">nazwa</a></th>
        <th><a href="{{ url('/szkola/sortuj/id_OKE') }}">identyfikator OKE</a></th>
        <th colspan="2">+/-</th>
      </tr>
    </thead>
    <tbody>

    @foreach($schools as $school)
      <tr>
        <td>{{ $school->id }}</td>
        <td><a href="{{ route('szkola.show', $school->id) }}">{{ $school->name }}</a></td>
        <td>{{ $school->id_OKE }}</td>
        <td><a href="{{ route('szkola.edit', $school->id) }}"><img class="edit" src="{{ asset('css/zmiana.png') }}" alt="[]"></a></td>
        <td>
          <form action="{{ route('szkola.destroy', $school->id) }}" method="post" id="delete-form-{{$school->id}}">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button><img class="destroy" src="{{ asset('css/minus.png') }}" /></button>
          </form>
        </td>
      </tr>
    @endforeach

      <tr class="create">
        <td colspan="5"><a href="{{ route('szkola.create') }}"><img class="create" src="{{ asset('css/plus.png') }}" /></a></td>
      </tr>

    </tbody>
  </table>
@endsection