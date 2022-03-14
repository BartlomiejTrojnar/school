<h2>nauczyciele w roku szkolnym</h2>
<table id="teachers">
   <thead>
      <tr>
         <?php
            echo view('layouts.thSorting', ["thName"=>"id", "routeName"=>"nauczyciel.orderBy", "field"=>"id", "sessionVariable"=>"TeacherOrderBy"]);
         ?>
         <th>stopień</th>
         <?php
            echo view('layouts.thSorting', ["thName"=>"imię", "routeName"=>"nauczyciel.orderBy", "field"=>"first_name", "sessionVariable"=>"TeacherOrderBy"]);
            echo view('layouts.thSorting', ["thName"=>"nazwisko", "routeName"=>"nauczyciel.orderBy", "field"=>"last_name", "sessionVariable"=>"TeacherOrderBy"]);
         ?>
         <th>rodowe</th>
         <th>skrót</th>
         <?php
            echo view('layouts.thSorting', ["thName"=>"sala", "routeName"=>"nauczyciel.orderBy", "field"=>"classroom_id", "sessionVariable"=>"TeacherOrderBy"]);
            echo view('layouts.thSorting', ["thName"=>"rok pierwszy", "routeName"=>"nauczyciel.orderBy", "field"=>"first_year_id", "sessionVariable"=>"TeacherOrderBy"]);
            echo view('layouts.thSorting', ["thName"=>"rok ostatni", "routeName"=>"nauczyciel.orderBy", "field"=>"last_year_id", "sessionVariable"=>"TeacherOrderBy"]);
            echo view('layouts.thSorting', ["thName"=>"kolejność", "routeName"=>"nauczyciel.orderBy", "field"=>"order", "sessionVariable"=>"TeacherOrderBy"]);
         ?>
         <th>aktualizacja</th>
         <th colspan="2">zmień / usuń</th>
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
            <td>{{ $teacher->short }}</td>
            <td>
               @if($teacher->classroom_id)
                  <a href="{{ route('sala.show', $teacher->classroom_id) }}">{{ $teacher->classroom->name }}</a>
               @endif
            </td>
            <td>@if($teacher->first_year_id) {{ substr($teacher->first_year->date_start, 0, 4) }} @endif</td>
            <td>@if($teacher->last_year_id) {{ substr($teacher->last_year->date_end, 0, 4) }} @endif</td>
            <td>{{ $teacher->order }}</td>
            <td>{{ $teacher->updated_at }}</td>
            <td class="edit"><a class="btn btn-primary" href="{{ route('nauczyciel.edit', $teacher->id) }}">
               <i class="fa fa-edit"></i>
            </a></td>
            <td class="destroy">
               <form action="{{ route('nauczyciel.destroy', $teacher->id) }}" method="post" id="delete-form-{{$teacher->id}}">
                  {{ csrf_field() }}
                  {{ method_field('DELETE') }}
                  <button class="btn btn-primary"><i class="fa fa-remove"></i></button>
               </form>
            </td>
         </tr>
      @endforeach  
      <tr class="create"><td colspan="13">
         <a class="btn btn-primary" href="{{ route('nauczyciel.create') }}"><i class="fa fa-plus"></i></a>
      </td></tr>
   </tbody>
</table>