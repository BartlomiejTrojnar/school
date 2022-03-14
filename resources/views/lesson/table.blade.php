@if( !empty( $links ) )
  {!! $lessons->render() !!}
@endif

<h2>{{ $subTitle }}</h2>
<table id="lessons">
  <thead>
    <tr>
      <?php
        echo view('layouts.thSorting', ["thName"=>"id", "routeName"=>"lekcja.orderBy", "field"=>"id", "sessionVariable"=>"LessonOrderBy"]);
        echo view('layouts.thSorting', ["thName"=>"nauczyciel", "routeName"=>"lekcja.orderBy", "field"=>"teacher_id", "sessionVariable"=>"LessonOrderBy"]);
      ?>
      <th>grupa</th>
      <?php
        echo view('layouts.thSorting', ["thName"=>"data", "routeName"=>"lekcja.orderBy", "field"=>"date", "sessionVariable"=>"LessonOrderBy"]);
        echo view('layouts.thSorting', ["thName"=>"typ lekcji", "routeName"=>"lekcja.orderBy", "field"=>"type", "sessionVariable"=>"LessonOrderBy"]);
        echo view('layouts.thSorting', ["thName"=>"numer", "routeName"=>"lekcja.orderBy", "field"=>"number", "sessionVariable"=>"LessonOrderBy"]);
        echo view('layouts.thSorting', ["thName"=>"temat wpisany", "routeName"=>"lekcja.orderBy", "field"=>"topic_entered", "sessionVariable"=>"LessonOrderBy"]);
      ?>
      <th>temat zrealizowany</th>
      <th>uwagi</th>
      <th>utworzono</th>
      <th>aktualizacja</th>
      <th colspan="2">+/-</th>
    </tr>
  </thead>

  <tbody>
    <?php $count = 0; ?>
    @foreach($lessons as $lesson)
      <?php $count++; ?>
      <tr>
        <td>{{ $count }}</td>
        <td><a href="{{ route('nauczyciel.show', $lesson->teacher_id) }}">{{ $lesson->teacher->first_name }} {{ $lesson->teacher->last_name }}</a></td>
        <td><a href="{{ route('grupa.show', $lesson->group_id) }}">{{ $lesson->group->date_start }} {{ $lesson->group->subject->name }}</a></td>
        <td>{{ $lesson->date }}</td>
        <td>{{ $lesson->length }}</td>
        <td>{{ $lesson->type }}</td>
        <td>{{ $lesson->number }}</td>
        <td>{{ $lesson->topic_entered }}</td>
        <td>{{ $lesson->topic_completed }}</td>
        <td>{{ $lesson->comments }}</td>
        <td>{{ $lesson->created_at }}</td>
        <td>{{ $lesson->updated_at }}</td>

        <td class="edit"><a class="btn btn-primary" href="{{ route('lekcja.edit', $lesson->id) }}">
            <i class="fa fa-edit"></i>
        </a></td>
        <td class="destroy">
          <form action="{{ route('lekcja.destroy', $lesson->id) }}" method="post" id="delete-form-{{$lesson->id}}">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button class="btn btn-primary"><i class="fa fa-remove"></i></button>
          </form>
        </td>
      </tr>
    @endforeach

    <tr class="create"><td colspan="14">
        <a class="btn btn-primary" href="{{ route('lekcja.create') }}"><i class="fa fa-plus"></i></a>
    </td></tr>
  </tbody>
</table>