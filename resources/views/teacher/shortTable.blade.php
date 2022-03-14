@if( !empty( $links ) )
  {!! $teachers->render() !!}
@endif

<h2>{{ $subTitle }}</h2>
<table id="teachers">
  <thead>
    <tr>
      <th><a href="{{ route('nauczyciel.orderBy', 'id') }}">id
        @if( session()->get('TeacherOrderBy[0]') == 'id' )
          @if( session()->get('TeacherOrderBy[1]') == 'asc' )
            <i class="fa fa-sort-alpha-asc"></i>
          @else
            <i class="fa fa-sort-alpha-desc"></i>
          @endif
        @else
            <i class="fa fa-sort"></i>
        @endif
      </a></th>
      <th>stopień</th>

      <th><a href="{{ route('nauczyciel.orderBy', 'first_name') }}">imię
        @if( session()->get('TeacherOrderBy[0]') == 'first_name' )
          @if( session()->get('TeacherOrderBy[1]') == 'asc' )
            <i class="fa fa-sort-alpha-asc"></i>
          @else
            <i class="fa fa-sort-alpha-desc"></i>
          @endif
        @else
            <i class="fa fa-sort"></i>
        @endif
      </a></th>

      <th><a href="{{ route('nauczyciel.orderBy', 'last_name') }}">nazwisko
        @if( session()->get('TeacherOrderBy[0]') == 'last_name' )
          @if( session()->get('TeacherOrderBy[1]') == 'asc' )
            <i class="fa fa-sort-alpha-asc"></i>
          @else
            <i class="fa fa-sort-alpha-desc"></i>
          @endif
        @else
            <i class="fa fa-sort"></i>
        @endif
      </a></th>

      <th>rodowe</th>
    </tr>
  </thead>

  <tbody>
    <?php $count = 0; ?>
    @foreach($teachers as $teacher)
      <?php $count++; ?>
      <tr>
        <td>{{ $count }}</td>
        <td>{{ $teacher->degree }}</td>
        <td>{{ $teacher->first_name }}</td>
        <td><a href="{{ route('nauczyciel.show', $teacher->id.'/showSubjects') }}">{{ $teacher->last_name }}</a></td>
        <td>{{ $teacher->family_name }}</td>
      </tr>
    @endforeach
  </tbody>
</table>