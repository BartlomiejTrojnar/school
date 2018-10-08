@extends('layouts.app')

@section('header')
  <h1>Nauczyciele</h1>
@endsection

@section('main-content')
{{ $teachers->links() }}
  <table id="teachers">
    <thead>
      <tr>
        <th><a href="{{ url('/nauczyciel/sortuj/id') }}">id</a></th>
        <th><a href="{{ url('/nauczyciel/sortuj/first_name') }}">imię</a></th>
        <th><a href="{{ url('/nauczyciel/sortuj/last_name') }}">nazwisko</a></th>
        <th>rodowe</th>
        <th>skrót</th>
        <th>stopień</th>
        <th><a href="{{ url('/nauczyciel/sortuj/classroom_id') }}">sala</a></th>
        <th><a href="{{ url('/nauczyciel/sortuj/first_year_id') }}">rok pierwszy</a></th>
        <th><a href="{{ url('/nauczyciel/sortuj/last_year_id') }}">rok ostatni</a></th>
        <th><a href="{{ url('/nauczyciel/sortuj/order') }}">kolejność</a></th>
        <th>aktualizacja</th>
        <th colspan="2">+/-</th>
      </tr>
    </thead>
    <tbody>

    @foreach($teachers as $teacher)
      <tr>
        <td>{{ $teacher->id }}</td>
        <td>{{ $teacher->first_name }}</td>
        <td><a href="{{ route('nauczyciel.show', $teacher->id) }}">{{ $teacher->last_name }}</a></td>
        <td>{{ $teacher->family_name }}</td>
        <td>{{ $teacher->short }}</td>
        <td>{{ $teacher->degree }}</td>
        <td>@if($teacher->classroom) {{ $teacher->classroom->name }} @endif</td>
        <td>@if($teacher->first_year) {{ substr($teacher->first_year->date_start, 0, 4) }} @endif</td>
        <td>@if($teacher->last_year) {{ substr($teacher->last_year->date_end, 0, 4) }} @endif</td>
        <td>{{ $teacher->order }}</td>
        <td>{{ $teacher->updated_at }}</td>
        <td><a href="{{ route('nauczyciel.edit', $teacher->id) }}"><img class="edit" src="{{ asset('css/zmiana.png') }}" alt="[]"></a></td>
        <td>
          <form action="{{ route('nauczyciel.destroy', $teacher->id) }}" method="post" id="delete-form-{{$teacher->id}}">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button><img class="destroy" src="{{ asset('css/minus.png') }}" /></button>
          </form>
        </td>
      </tr>
    @endforeach

      <tr class="create">
        <td colspan="13"><a href="{{ route('nauczyciel.create') }}"><img class="create" src="{{ asset('css/plus.png') }}" /></a></td>
      </tr>
    </tbody>
  </table>
@endsection