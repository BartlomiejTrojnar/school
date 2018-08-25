@extends('layouts.app')

@section('header')
  <h1>Terminy</h1>
@endsection

@section('main-content')
  <table id="terms">
    <thead>
      <tr>
        <th>id</th>
        <th><a href="{{ url('/termin/sortuj/exam_description_id') }}">opis egzaminu</a></th>
        <th><a href="{{ url('/termin/sortuj/classroom_id') }}">sala</a></th>
        <th><a href="{{ url('/termin/sortuj/date_start') }}">data rozpoczęcia</a></th>
        <th><a href="{{ url('/termin/sortuj/date_end') }}">data zakończenia</a></th>
        <th>wprowadzono</th>
        <th>aktualizacja</th>
        <th colspan="2">+/-</th>
      </tr>

      <tr>
        <td>=</td>
        <td><?php  print_r($examDescriptionSelectField);  ?></td>
        <td><?php  print_r($classroomSelectField);  ?></td>
        <td colspan="6">=</td>
      </tr>
    </thead>
    <tbody>

    @foreach($terms as $term)
      <tr>
        <td><a href="{{ route('termin.show', $term->id) }}">{{ $term->id }}</a></td>
        <td>{{ $term->exam_description_id }}</td>
        <td>{{ $term->classroom_id }}</td>
        <td>{{ $term->date_start }}</td>
        <td>{{ $term->date_end }}</td>
        <td>{{ $term->created_at }}</td>
        <td>{{ $term->updated_at }}</td>
        <td><a href="{{ route('termin.edit', $term->id) }}"><img class="edit" src="{{ asset('css/zmiana.png') }}" alt="[]"></a></td>
        <td>
          <form action="{{ route('termin.destroy', $term->id) }}" method="post" id="delete-form-{{$term->id}}">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button><img class="destroy" src="{{ asset('css/minus.png') }}" /></button>
          </form>
        </td>
      </tr>
    @endforeach
      <tr class="create">
        <td colspan="9"><a href="{{ route('termin.create') }}"><img class="create" src="{{ asset('css/plus.png') }}" /></a></td>
      </tr>
    </tbody>
  </table>
@endsection