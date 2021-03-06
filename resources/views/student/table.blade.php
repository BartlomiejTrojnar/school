@if( !empty( $links ) )
  {{ $students->links() }}
@endif

<p>Stan na {{ session()->get('dateSession') }}</p>
<h2>{{ $subTitle }}</h2>
<table id="students">
  <thead>
    <tr>
      <th>id</th>
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

    @if( !empty( $links ) )
    <tr>
      <td>-</td>
      <td><?php  print_r($gradeSelectField);  ?></td>
      <td><?php  print_r($schoolYearSelectField);  ?></td>
      <?php /*<td>  print_r($groupSelectField);  </td> */ ?>
      <td colspan="10">=</td>
    </tr>
    @endif
  </thead>

  <tbody>
    @if( !empty($students) )
    @foreach($students as $student)
      @if($student->sex == 'mężczyzna')
        <tr class="man">
      @else
        <tr class="woman">
      @endif
        <td>{{ $loop->iteration }}</td>
        <td>{{ $student->first_name }}</td>
        <td>{{ $student->second_name }}</td>
        <td><a href="{{ route('uczen.show', $student->id) }}">{{ $student->last_name }}</a></td>
        <td>{{ $student->family_name }}</td>
        <td>{{ $student->sex }}</td>
        <td>{{ $student->PESEL }}</td>
        <td>{{ $student->place_of_birth }}</td>
        <td>{{ $student->created_at }}</td>
        <td>{{ $student->updated_at }}</td>
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
    @endif

    <tr class="create"><td colspan="12">
        <a class="btn btn-primary" href="{{ route('uczen.create') }}"><img class="create" src="{{ asset('css/plus.png') }}" /></a>
    </td></tr>
  </tbody>
</table>