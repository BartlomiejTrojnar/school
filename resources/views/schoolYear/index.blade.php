@extends('layouts.app')

@section('header')
  <h1>Lata szkolne</h1>
@endsection

@section('main-content')
  {{ $schoolYears->links() }}
  <table id="schoolYears">
    <thead>
      <tr>
        <th>id</th>
        <th>data rozpoczęcia</th>
        <th>data zakończenia</th>
        <th>data klasyfikacji<br />ostatnich klas</th>
        <th>data zakończenia nauki<br />ostatnich klas</th>
        <th>data<br />klasyfikacji</th>
        <th>data<br />zakończenia nauki</th>
        <th colspan="2">+/-</th>
      </tr>
    </thead>
    <tbody>
    @foreach($schoolYears as $sy)
      <tr>
        <td>{{ $sy->id }}</td>
        <td>{{ $sy->date_start }}</td>
        <td><a href="{{ route('rok_szkolny.show', $sy->id) }}">{{ $sy->date_end }}</a></td>
        <td>{{ $sy->date_of_classification_of_the_last_grade }}</td>
        <td>{{ $sy->date_of_graduation_of_the_last_grade }}</td>
        <td>{{ $sy->date_of_classification }}</td>
        <td>{{ $sy->date_of_graduation }}</td>
        <td class="edit"><a class="btn btn-primary" href="{{ route('rok_szkolny.edit', $sy->id) }}">
          <img class="edit" src="{{ asset('css/zmiana.png') }}" alt="[]">
        </a></td>
        <td class="destroy">
          <form action="{{ route('rok_szkolny.destroy', $sy->id) }}" method="post" id="delete-form-{{$sy->id}}">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button class="btn btn-primary"><img class="destroy" src="{{ asset('css/minus.png') }}" /></button>
          </form>
        </td>
      </tr>
    @endforeach

      <tr class="create"><td colspan="9">
          <a class="btn btn-primary" href="{{ route('rok_szkolny.create') }}"><img class="create" src="{{ asset('css/plus.png') }}" /></a>
      </td></tr>
    </tbody>
  </table>
@endsection