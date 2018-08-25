@extends('layouts.app')

@section('header')
  <h1>Sesje maturalne</h1>
@endsection

@section('main-content')
  <table id="sessions">
    <thead>
      <tr>
        <th>id</th>
        <th><a href="{{ url('/sesja/sortuj/year') }}">rok</a></th>
        <th><a href="{{ url('/sesja/sortuj/type') }}">typ</a></th>
        <th colspan="2">+/-</th>
      </tr>
    </thead>
    <tbody>

    @foreach($sessions as $session)
      <tr>
        <td>{{ $session->id }}</td>
        <td><a href="{{ route('sesja.show', $session->id) }}">{{ $session->year }}</a></td>
        <td>{{ $session->type }}</td>
        <td><a href="{{ route('sesja.edit', $session->id) }}"><img class="edit" src="{{ asset('css/zmiana.png') }}" alt="[]"></a></td>
        <td>
          <form action="{{ route('sesja.destroy', $session->id) }}" method="post" id="delete-form-{{$session->id}}">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button><img class="destroy" src="{{ asset('css/minus.png') }}" /></button>
          </form>
        </td>
      </tr>
    @endforeach

      <tr class="create">
        <td colspan="5"><a href="{{ route('sesja.create') }}"><img class="create" src="{{ asset('css/plus.png') }}" /></a></td>
      </tr>

    </tbody>
  </table>
@endsection