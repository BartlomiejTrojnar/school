@extends('layouts.app')

@section('java-script')
  <script language="javascript" type="text/javascript" src="{{ asset('js/student.js') }}"></script>
@endsection

@section('header')
  <h1>Znalezieni uczniowie</h1>
@endsection

@section('main-content')
<p class="btn btn-primary" style="float: right;"><a href="{{ route('uczen.search') }}">szukaj</a></p>
  <table id="students">
    <thead>
      <tr>
        <th>id</th>
        <th>imię</th>
        <th>drugie imię</th>
        <th>nazwisko</th>
        <th>rodowe</th>
        <th>płeć</th>
        <th>PESEL</th>
        <th>miejsce urodzenia</th>
        <th>wpis</th>
        <th>aktualizacja</th>
        <th colspan="2">+/-</th>
      </tr>

      <tr style="background: orange;">
        <td></td>
        <td>{{ $request->first_name }}</td>
        <td>{{ $request->second_name }}</td>
        <td>{{ $request->last_name }}</td>
        <td></td>
        <td></td>
        <td>{{ $request->pesel }}</td>
        <td>{{ $request->place_of_birth }}</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
    </thead>
    <tbody>

    <?php $count=0; ?>
    @foreach($students as $student)
      <?php $count++; ?>
      <tr>
        <td style="font-size: x-small;">{{$count}} ({{$student->id}})</td>
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
          <i class="fa fa-edit"></i>
        </a></td>
        <td class="destroy">
          <form action="{{ route('uczen.destroy', $student->id) }}" method="post" id="delete-form-{{$student->id}}">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button class="btn btn-primary"><i class="fas fa-remove"></i></button>
          </form>
        </td>
      </tr>
    @endforeach

      <tr class="create"><td colspan="12">
          <a class="btn btn-primary" href="{{ route('uczen.create') }}"><i class="fas fa-plus"></i></a>
      </td></tr>
    </tbody>
  </table>
@endsection