@extends('layouts.app')

@section('header')
  <h1>Świadectwa</h1>
@endsection

@section('main-content')
  <table id="certificates">
    <thead>
      <tr>
        <th>id</th>
        <th><a href="{{ url('/swiadectwo/sortuj/student_id') }}">uczeń</a></th>
        <th><a href="{{ url('/swiadectwo/sortuj/sheet_pattern_id') }}">wzór arkusza</a></th>
        <th><a href="{{ url('/swiadectwo/sortuj/certificate_pattern_id') }}">wzór świadectwa</a></th>
        <th><a href="{{ url('/swiadectwo/sortuj/date_of_council') }}">data rady</a></th>
        <th><a href="{{ url('/swiadectwo/sortuj/date_of_release') }}">data wydania</a></th>
        <th>wprowadzono</th>
        <th>aktualizacja</th>
        <th colspan="2">+/-</th>
      </tr>
    </thead>
    <tbody>

    @foreach($certificates as $certificate)
      <tr>
        <td><a href="{{ route('swiadectwo.show', $certificate->id) }}">{{ $certificate->id }}</a></td>
        <td>{{ $certificate->student_id }}</td>
        <td>{{ $certificate->sheet_pattern_id }}</td>
        <td>{{ $certificate->certificate_pattern_id }}</td>
        <td>{{ $certificate->date_of_council }}</td>
        <td>{{ $certificate->date_of_release }}</td>
        <td>{{ $certificate->created_at }}</td>
        <td>{{ $certificate->updated_at }}</td>
        <td><a href="{{ route('swiadectwo.edit', $certificate->id) }}"><img class="edit" src="{{ asset('css/zmiana.png') }}" alt="[]"></a></td>
        <td>
          <form action="{{ route('swiadectwo.destroy', $certificate->id) }}" method="post" id="delete-form-{{$certificate->id}}">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button><img class="destroy" src="{{ asset('css/minus.png') }}" /></button>
          </form>
        </td>
      </tr>
    @endforeach

      <tr class="create">
        <td colspan="10"><a href="{{ route('swiadectwo.create') }}"><img class="create" src="{{ asset('css/plus.png') }}" /></a></td>
      </tr>
    </tbody>
  </table>
@endsection