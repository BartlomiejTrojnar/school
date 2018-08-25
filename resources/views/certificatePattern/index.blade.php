@extends('layouts.app')

@section('header')
  <h1>Wzory świadectw</h1>
@endsection

@section('main-content')
  <table id="certificatePatterns">
    <thead>
      <tr>
        <th>id</th>
        <th><a href="{{ url('/wzor_swiadectwa/sortuj/name') }}">nazwa</a></th>
        <th><a href="{{ url('/wzor_swiadectwa/sortuj/destiny') }}">przeznaczenie</a></th>
        <th colspan="2">[]</th>
      </tr>
    </thead>
    <tbody>

    @foreach($patterns as $pattern)
      <tr>
        <td>{{ $pattern->id }}</td>
        <td>{{ $pattern->name }}</td>
        <td>{{ $pattern->destiny }}</td>
        <td><a href="{{ route('wzor_swiadectwa.edit', $pattern->id) }}"><img class="edit" src="{{ asset('css/zmiana.png') }}" alt="[]"></a></td>
      </tr>
    @endforeach

      <tr class="create">
        <td colspan="4"><a href="{{ route('wzor_swiadectwa.create') }}"><img class="create" src="{{ asset('css/plus.png') }}" /></a></td>
      </tr>
    </tbody>
  </table>
@endsection