@extends('layouts.app')

@section('header')
  <h1>Osiągnięcia</h1>
@endsection

@section('main-content')
  <table id="achievements">
    <thead>
      <tr>
        <th>id</th>
        <th><a href="{{ url('/osiagniecie/sortuj/certificate_id') }}">świadectwo</a></th>
        <th><a href="{{ url('/osiagniecie/sortuj/inscription') }}">wpis</a></th>
        <th>wprowadzono</th>
        <th>aktualizacja</th>
        <th colspan="2">+/-</th>
      </tr>
    </thead>
    <tbody>

    @foreach($achievements as $achievement)
      <tr>
        <td>{{ $achievement->id }}</td>
        <td>{{ $achievement->certificate_id }}</td>
        <td>{{ $achievement->inscription }}</td>
        <td>{{ $achievement->created_at }}</td>
        <td>{{ $achievement->updated_at }}</td>
        <td><a href="{{ route('osiagniecie.edit', $achievement->id) }}"><img class="edit" src="{{ asset('css/zmiana.png') }}" alt="[]"></a></td>
        <td>
          <form action="{{ route('osiagniecie.destroy', $achievement->id) }}" method="post" id="delete-form-{{$achievement->id}}">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button><img class="destroy" src="{{ asset('css/minus.png') }}" /></button>
          </form>
        </td>
      </tr>
    @endforeach

      <tr class="create">
        <td colspan="7"><a href="{{ route('osiagniecie.create') }}"><img class="create" src="{{ asset('css/plus.png') }}" /></a></td>
      </tr>
    </tbody>
  </table>
@endsection