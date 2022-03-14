@extends('layouts.app')

@section('header')
  <h1>Godziny lekcyjne</h1>
@endsection

@section('main-content')
  <table id="lessonHours0">
    <thead>
      <tr>
        <th>numer</th>
        <th>godziny</th>
        <th>poniedziałek</th>
        <th>wtorek</th>
        <th>środa</th>
        <th>czwartek</th>
        <th>piątek</th>
      </tr>
    </thead>
    <tbody>
      @for ($i = 0; $i < 9; $i++)
        <tr>
          <td>{{ $lessonHours[$i]->lesson_number }}</td>
          <td>{{ substr($lessonHours[$i]->start_time, 0, 5) }} - {{ substr($lessonHours[$i]->end_time, 0, 5) }}</td>
          <td class="c"><a href="{{ route('godzina.show', $lessonHours[$i]->id) }}">{{ $lessonHours[$i]->id }}</a></td>
          <td class="c"><a href="{{ route('godzina.show', $lessonHours[$i+9]->id) }}">{{ $lessonHours[$i+9]->id }}</a></td>
          <td class="c"><a href="{{ route('godzina.show', $lessonHours[$i+18]->id) }}">{{ $lessonHours[$i+18]->id }}</a></td>
          <td class="c"><a href="{{ route('godzina.show', $lessonHours[$i+27]->id) }}">{{ $lessonHours[$i+27]->id }}</a></td>
          <td class="c"><a href="{{ route('godzina.show', $lessonHours[$i+36]->id) }}">{{ $lessonHours[$i+36]->id }}</a></td>
        </tr>
      @endfor

    </tbody>
  </table><hr />


  <table id="lessonHours">
    <tr class="create"><td colspan="7">
        <a class="btn btn-primary" href="{{ route('godzina.create') }}"><i class="fa fa-plus"></i></a>
    </td></tr>

    <thead>
      <tr>
        <th>id</th>
        <th>dzień</th>
        <th>numer lekcji</th>
        <th>godzina<br />rozpoczęcia</th>
        <th>godzina<br />zakończenia</th>
        <th colspan="2">+/-</th>
      </tr>
    </thead>
    <tbody>

    @foreach($lessonHours as $lessonHour)
      <tr>
        <td><a href="{{ route('godzina.show', $lessonHour->id) }}">{{ $lessonHour->id }}</a></td>
        <td>{{ $lessonHour->day }}</td>
        <td>{{ $lessonHour->lesson_number }}</td>
        <td>{{ substr($lessonHour->start_time, 0, 5) }}</td>
        <td>{{ substr($lessonHour->end_time, 0, 5) }}</td>
        <td class="edit"><a class="btn btn-primary" href="{{ route('godzina.edit', $lessonHour->id) }}"><i class="fa fa-edit"></i></a></td>

        <td class="destroy">
          <form action="{{ route('godzina.destroy', $lessonHour->id) }}" method="post" id="delete-form-{{$lessonHour->id}}">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button class="btn btn-primary"><i class="fa fa-remove"></i></button>
          </form>
        </td>
      </tr>
    @endforeach

    </tbody>
  </table>
@endsection