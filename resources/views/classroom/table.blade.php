@if( !empty( $links ) )
  {{ $grades->links() }}
@endif

<h2>{{ $subTitle }}</h2>
<table id="classrooms">
  <thead>
    <tr>
      <th>lp</th>
      <?php
        echo view('layouts.thSorting', ["thName"=>"nazwa", "routeName"=>"sala.orderBy", "field"=>"name", "sessionVariable"=>"ClassroomOrderBy"]);
        echo view('layouts.thSorting', ["thName"=>"pojemność", "routeName"=>"sala.orderBy", "field"=>"capacity", "sessionVariable"=>"ClassroomOrderBy"]);
        echo view('layouts.thSorting', ["thName"=>"piętro", "routeName"=>"sala.orderBy", "field"=>"floor", "sessionVariable"=>"ClassroomOrderBy"]);
        echo view('layouts.thSorting', ["thName"=>"rząd", "routeName"=>"sala.orderBy", "field"=>"line", "sessionVariable"=>"ClassroomOrderBy"]);
        echo view('layouts.thSorting', ["thName"=>"kolumna", "routeName"=>"sala.orderBy", "field"=>"column", "sessionVariable"=>"ClassroomOrderBy"]);
      ?>
      <th colspan="2">+/-</th>
    </tr>
  </thead>

  <tbody>
    <?php $count = 0; ?>
    @foreach($classrooms as $classroom)
      <?php $count++; ?>
      <tr>
        <td>{{ $count }}</td>
        <td><a href="{{ route('sala.show', $classroom->id) }}">{{ $classroom->name }}</a></td>
        <td>{{ $classroom->capacity }}</td>
        <td>{{ $classroom->floor }}</td>
        <td>{{ $classroom->line }}</td>
        <td>{{ $classroom->column }}</td>
        <td class="edit"><a class="btn btn-primary" href="{{ route('sala.edit', $classroom->id) }}">
          <i class="fa fa-edit"></i>
        </a></td>
        <td class="destroy">
          <form action="{{ route('sala.destroy', $classroom->id) }}" method="post" id="delete-form-{{$classroom->id}}">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button class="btn btn-primary"><i class="fa fa-remove"></i></button>
          </form>
        </td>
      </tr>
    @endforeach

    <tr class="create"><td colspan="8">
        <a class="btn btn-primary" href="{{ route('sala.create') }}"><i class="fa fa-plus"></i></a>
    </td></tr>
  </tbody>
</table>