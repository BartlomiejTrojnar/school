@if( !empty( $links ) )
   {!! $teachers->render() !!}
@endif

<h2>{{ $subTitle }}</h2>
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
      <tr>
         <td colspan="7"></td>
         <td colspan="2"><?php  print_r($schoolYearSelectField);  ?></td>
         <td colspan="4"></td>
      </tr>
   </thead>

   <tbody>
      <?php $count = 0;    if(isset($_GET['page'])) $count += ($_GET['page']-1) * 20;  ?>
      @foreach($teachers as $teacher)
      <?php $count++; ?>
      <tr data-teacher_id="{{ $teacher->id }}">
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
         <td class="c">@if($teacher->first_year_id) {{ substr($teacher->first_year->date_start, 0, 4) }} @endif</td>
         <td>@if($teacher->last_year_id) {{ substr($teacher->last_year->date_end, 0, 4) }} @endif</td>
         <td class="c">{{ $teacher->order }}</td>
         <td class="c small">{{ substr($teacher->updated_at, 0, 10) }}</td>
         <td class="edit destroy c">
            <button class="edit btn btn-primary"    data-teacher_id="{{ $teacher->id }}"><i class="fa fa-edit"></i></button>
            <button class="destroy btn btn-primary" data-teacher_id="{{ $teacher->id }}"><i class="fas fa-remove"></i></button>
         </td>
      </tr>
      @endforeach

      <tr class="create"><td colspan="12"><button id="showCreateRow" class="btn btn-primary"><i class="fa fa-plus"></i></button>
   </tbody>
</table>

<input id="countTeachers" value="{{ $count }}" hidden />