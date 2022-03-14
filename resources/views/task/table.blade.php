<h2>{{ $subTitle }}</h2>

<table id="tasks">
  <thead>
    <tr>
      <th>id</th>
      <?php
        echo view('layouts.thSorting', ["thName"=>"nazwa", "routeName"=>"zadanie.orderBy", "field"=>"name", "sessionVariable"=>"TaskOrderBy"]);
        echo view('layouts.thSorting', ["thName"=>"punkty", "routeName"=>"zadanie.orderBy", "field"=>"points", "sessionVariable"=>"TaskOrderBy"]);
        echo view('layouts.thSorting', ["thName"=>"waga", "routeName"=>"zadanie.orderBy", "field"=>"importance", "sessionVariable"=>"TaskOrderBy"]);
        echo view('layouts.thSorting', ["thName"=>"arkusz", "routeName"=>"zadanie.orderBy", "field"=>"sheet_name", "sessionVariable"=>"TaskOrderBy"]);
      ?>
      <th>liczba poleceń</th>
      <th>liczba ocen</th>
      <th>wprowadzono</th>
      <th>aktualizacja</th>
      <th colspan="2">+/-</th>
    </tr>
  </thead>

  <tbody>
    @foreach($tasks as $task)
      <tr>
        <td>{{ $task->id }}</td>
        <td><a href="{{ route('zadanie.show', $task->id) }}">{{ $task->name }}</a></td>
        <td>{{ $task->points }}</td>
        <td>{{ $task->importance }}</td>
        <td>{{ $task->sheet_name }}</td>
        <td>{{ count($task->commands) }}</td>
        <td>{{ count($task->taskRatings) }}</td>
        <td>{{ $task->created_at }}</td>
        <td>{{ $task->updated_at }}</td>
        <td class="edit"><a class="btn btn-primary" href="{{ route('zadanie.edit', $task->id) }}">
            <i class="fa fa-edit"></i>
        </a></td>
        <td class="destroy">
          <form action="{{ route('zadanie.destroy', $task->id) }}" method="post" id="delete-form-{{$task->id}}">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button class="btn btn-primary"><i class="fa fa-remove"></i></button>
          </form>
        </td>
      </tr>
    @endforeach

    <tr class="create"><td colspan="11">
        <a class="btn btn-primary" href="{{ route('zadanie.create') }}"><i class="fa fa-plus"></i></a>
    </td></tr>
  </tbody>
</table>