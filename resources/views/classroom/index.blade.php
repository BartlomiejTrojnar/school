@extends('layouts.app')

@section('header')
  <h1>Sale lekcyjne</h1>
@endsection

@section('main-content')
  {{ $classrooms->links() }}
  <table id="classrooms">
    <thead>
      <tr>
        <th>lp</th>
        <th><a href="{{ url('/sala/sortuj/name') }}">nazwa</a></th>
        <th><a href="{{ url('/sala/sortuj/capacity') }}">pojemność</a></th>
        <th><a href="{{ url('/sala/sortuj/floor') }}">piętro</a></th>
        <th><a href="{{ url('/sala/sortuj/line') }}">rząd</a></th>
        <th><a href="{{ url('/sala/sortuj/column') }}">kolumn</a></th>
        <th colspan="2">+/-</th>
      </tr>
    </thead>
    <tbody>

    @foreach($classrooms as $classroom)
      <tr>
        <td>{{ $classroom->id }}</td>
        <td><a href="{{ route('sala.show', $classroom->id) }}">{{ $classroom->name }}</a></td>
        <td>{{ $classroom->capacity }}</td>
        <td>{{ $classroom->floor }}</td>
        <td>{{ $classroom->line }}</td>
        <td>{{ $classroom->column }}</td>
        <td><a href="{{ route('sala.edit', $classroom->id) }}"><img class="edit" src="{{ asset('css/zmiana.png') }}" alt="[]"></a></td>
        <td>
          <form action="{{ route('sala.destroy', $classroom->id) }}" method="post" id="delete-form-{{$classroom->id}}">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button><img class="destroy" src="{{ asset('css/minus.png') }}" /></button>
          </form>
        </td>
      </tr>
    @endforeach

      <tr class="create">
        <td colspan="8"><a href="{{ route('sala.create') }}"><img class="create" src="{{ asset('css/plus.png') }}" /></a></td>
      </tr>

    </tbody>
  </table>
@endsection