@extends('layouts.app')

@section('java-script')
  <script language="javascript" type="text/javascript" src="{{ asset('js/student.js') }}"></script>
@endsection

@section('header')
  <h1>Uczniowie</h1>
@endsection

@section('main-content')
<p class="btn btn-primary" style="float: right;"><a href="{{ route('uczen.search') }}">szukaj</a></p>
{{ $students->links() }}
  <table id="students">
    <thead>
      <tr>
        <th><a href="{{ route('uczen.order', 'id') }}">id</a></th>
        <th><a href="{{ route('uczen.order', 'first_name') }}">imię</a></th>
        <th><a href="{{ route('uczen.order', 'second_name') }}">drugie imię</a></th>
        <th><a href="{{ route('uczen.order', 'last_name') }}">nazwisko</a></th>
        <th>rodowe</th>
        <th>płeć</th>
        <th><a href="{{ route('uczen.order', 'pesel') }}">PESEL</a></th>
        <th><a href="{{ route('uczen.order', 'place_of_birth') }}">miejsce urodzenia</a></th>
        <th>wpis</th>
        <th>aktualizacja</th>
        <th colspan="2">+/-</th>
      </tr>

      <tr>
        <td>-</td>
        <td><?php  print_r($gradeSelectField);  ?></td>
        <td><?php  print_r($schoolYearSelectField);  ?></td>
        <?php /*<td>  print_r($groupSelectField);  </td> */ ?>
        <td colspan="10">=</td>
      </tr>

    </thead>
    <tbody>

    @foreach($students as $student)
      <tr>
        @if( !empty($_GET['page']) )
        <td style="font-size: x-small;">{{$_GET['page']*50-50+$loop->iteration}} ({{$student->id}})</td>
        @else
        <td style="font-size: x-small;">{{$loop->iteration}} ({{$student->id}})</td>
        @endif
        <td>{{ $student->first_name }}</td>
        <td>{{ $student->second_name }}</td>
        <td><a href="{{ route('uczen.show', $student->id) }}">{{ $student->last_name }}</a></td>
        <td>{{ $student->family_name }}</td>
        <td style="font-size: x-small">{{ $student->sex }}</td>
        <td>{{ $student->PESEL }}</td>
        <td>{{ $student->place_of_birth }}</td>
        <td style="font-size: x-small">{{ $student->created_at }}</td>
        <td style="font-size: x-small">{{ $student->updated_at }}</td>
        <td class="edit"><a class="btn btn-primary" href="{{ route('uczen.edit', $student->id) }}">
          <img class="edit" src="{{ asset('css/zmiana.png') }}" alt="[]">
        </a></td>
        <td class="destroy">
          <form action="{{ route('uczen.destroy', $student->id) }}" method="post" id="delete-form-{{$student->id}}">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button class="btn btn-primary"><img class="destroy" src="{{ asset('css/minus.png') }}" /></button>
          </form>
        </td>
      </tr>
    @endforeach

      <tr class="create"><td colspan="12">
          <a class="btn btn-primary" href="{{ route('uczen.create') }}"><img class="create" src="{{ asset('css/plus.png') }}" /></a>
      </td></tr>
    </tbody>
  </table>
@endsection