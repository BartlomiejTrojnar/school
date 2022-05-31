@extends('layouts.app')

@section('java-script')
  <script language="javascript" type="text/javascript" src="{{ asset('public/js/student/student.js') }}"></script>
@endsection

@section('header')
  <h1>Uczniowie</h1>
@endsection

@section('main-content')
<p><a href="{{ route('uczen.import') }}">import uczniów</a></p>
<aside>
  <p>Dane importowane są z pliku <strong style="border: 1px solid #66f; padding: 5px; background: #555; color: #aaf;">C:/dane/nauczyciele/ksiegauczniow/KsiegaUczniowMSP.xlsx</strong>. Arkusz powinien nosić nazwę <em style="border: 1px solid #66f; padding: 5px; background: #555; color: #aaf;">Uczniowie</em>.</p>
  <p>W pliku kolejno powinny być kolumny nazwane: Nazwisko, imie, imie2, PESEL, miejsce_urodzenia, plec, szkola, numer_ksiegi.<br />
  W kolumnach I,J,K,L powinny być: data_przyjecia, poziom_przyjscia, data_opuszczenia, powod_opuszczenia (jeżeli jest data opuszczenia).<br />
  W ostatnich kolumnach M,N,O: oddzial, od, do (daty przynależności do oddziału).</p>
</aside>
<div style="float: right; width: 200px;">
  <p class="btn btn-primary"><a href="{{ route('uczen.search') }}">szukaj</a></p>
</div>

  {!! $students->render() !!}
  <table id="students">
    <thead>
      <tr>
        <th><a href="{{ route('uczen.orderBy', 'id') }}">id
          @if( session()->get('StudentOrderBy[0]') == 'id' )
            @if( session()->get('StudentOrderBy[1]') == 'asc' )
              <i class="fa fa-sort-alpha-asc"></i>
            @else
              <i class="fa fa-sort-alpha-desc"></i>
            @endif
          @else
              <i class="fa fa-sort"></i>
          @endif
        </a></th>

        <th><a href="{{ route('uczen.orderBy', 'first_name') }}">imię
          @if( session()->get('StudentOrderBy[0]') == 'first_name' )
            @if( session()->get('StudentOrderBy[1]') == 'asc' )
              <i class="fa fa-sort-alpha-asc"></i>
            @else
              <i class="fa fa-sort-alpha-desc"></i>
            @endif
          @else
              <i class="fa fa-sort"></i>
          @endif
        </a></th>

        <th><a href="{{ route('uczen.orderBy', 'second_name') }}">drugie imię
          @if( session()->get('StudentOrderBy[0]') == 'second_name' )
            @if( session()->get('StudentOrderBy[1]') == 'asc' )
              <i class="fa fa-sort-alpha-asc"></i>
            @else
              <i class="fa fa-sort-alpha-desc"></i>
            @endif
          @else
              <i class="fa fa-sort"></i>
          @endif
        </a></th>

        <th><a href="{{ route('uczen.orderBy', 'last_name') }}">nazwisko
          @if( session()->get('StudentOrderBy[0]') == 'last_name' )
            @if( session()->get('StudentOrderBy[1]') == 'asc' )
              <i class="fa fa-sort-alpha-asc"></i>
            @else
              <i class="fa fa-sort-alpha-desc"></i>
            @endif
          @else
              <i class="fa fa-sort"></i>
          @endif
        </a></th>

        <th>rodowe</th>
        <th>płeć</th>

        <th><a href="{{ route('uczen.orderBy', 'pesel') }}">PESEL
          @if( session()->get('StudentOrderBy[0]') == 'pesel' )
            @if( session()->get('StudentOrderBy[1]') == 'asc' )
              <i class="fa fa-sort-alpha-asc"></i>
            @else
              <i class="fa fa-sort-alpha-desc"></i>
            @endif
          @else
              <i class="fa fa-sort"></i>
          @endif
        </a></th>

        <th><a href="{{ route('uczen.orderBy', 'place_of_birth') }}">miejsce urodzenia
          @if( session()->get('StudentOrderBy[0]') == 'place_of_birth' )
            @if( session()->get('StudentOrderBy[1]') == 'asc' )
              <i class="fa fa-sort-alpha-asc"></i>
            @else
              <i class="fa fa-sort-alpha-desc"></i>
            @endif
          @else
              <i class="fa fa-sort"></i>
          @endif
        </a></th>

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

    <?php $count = 0; ?>
    @foreach($students as $student)
      <?php $count++; ?>
      <tr>
        @if( !empty($_GET['page']) )
        <td style="font-size: x-small;">{{$_GET['page']*50-50+$count}} ({{$student->id}})</td>
        @else
        <td style="font-size: x-small;">{{$count}} ({{$student->id}})</td>
        @endif
        <td>{{ $student->first_name }}</td>
        <td>{{ $student->second_name }}</td>
        <td><a href="{{ route('uczen.show', $student->id) }}">{{ $student->last_name }}</a></td>
        <td>{{ $student->family_name }}</td>
        <td style="font-size: x-small">{{ $student->sex }}</td>
        @if( strlen($student->PESEL)==11 )
          <td>{{ $student->PESEL }}</td>
        @else
          <td style="color: red;">{{ $student->PESEL }} ({{ strlen($student->PESEL) }})</td>
        @endif
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
            <button class="btn btn-primary"><i class="fa fa-remove"></i></button>
          </form>
        </td>
      </tr>
    @endforeach

      <tr class="create"><td colspan="12">
          <a class="btn btn-primary" href="{{ route('uczen.create') }}"><i class="fa fa-plus"></i></a>
      </td></tr>
    </tbody>
  </table>
@endsection