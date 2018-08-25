@extends('layouts.app')

@section('header')
  <h1>Przedmioty</h1>
@endsection

@section('main-content')
  {{ $subjects->links() }}
  <table id="subjects">
    <thead>
      <tr>
        <th>lp</th>
        <th><a href="{{ url('przedmiot/sortuj/name') }}">nazwa</a></th>
        <th>skrót</th>
        <th><a href="{{ url('przedmiot/sortuj/actual') }}">aktywny?</a></th>
        <th><a href="{{ url('przedmiot/sortuj/order_in_the_sheet') }}">kolejność w arkuszu</a></th>
        <th><a href="{{ url('przedmiot/sortuj/expanded') }}">rozszerzany?</a></th>
        <th colspan="2">+/-</th>
      </tr>
    </thead>
    <tbody>

    @foreach($subjects as $subject)
      <tr>
        <td>{{ $subject->id }}</td>
        <td><a href="{{ route('przedmiot.show', $subject->id) }}">{{ $subject->name }}</a></td>
        <td>{{ $subject->short_name }}</td>
        <td>{{ $subject->actual }}</td>
        <td>{{ $subject->order_in_the_sheet }}</td>
        <td>{{ $subject->expanded }}</td>
        <td><a href="{{ route('przedmiot.edit', $subject->id) }}"><img class="edit" src="{{ asset('css/zmiana.png') }}" alt="[]"></a></td>
        <td>
          <form action="{{ route('przedmiot.destroy', $subject->id) }}" method="post" id="delete-form-{{$subject->id}}">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button><img class="destroy" src="{{ asset('css/minus.png') }}" /></button>
          </form>
        </td>
      </tr>
    @endforeach

      <tr class="create">
        <td colspan="8"><a href="{{ route('przedmiot.create') }}"><img class="create" src="{{ asset('css/plus.png') }}" /></a></td>
      </tr>

    </tbody>
  </table>
@endsection