<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 31.12.2022 *********************** -->
@if( !empty( $links ) )
   {!! $groups->render() !!}
@endif

<h2>{{ $subTitle }}</h2>

<table id="groups">
   <thead>
      <tr>
         <th>id</th>
         <th>klasy</th>

         <th>
            <a href="{{ route('grupa.orderBy', 'name') }}">przedmiot
               @if( session()->get('GroupOrderBy[0]') == 'name' )
                  @if( session()->get('GroupOrderBy[1]') == 'asc' )  <i class="fa fa-sort-alpha-asc"></i>  @else  <i class="fa fa-sort-alpha-desc"></i>  @endif
               @else  <i class="fa fa-sort"></i>  @endif
            </a><br />
            <a href="{{ route('grupa.orderBy', 'order_in_the_sheet') }}">sort. wg arkusza
               @if( session()->get('GroupOrderBy[0]') == 'order_in_the_sheet' )
                  @if( session()->get('GroupOrderBy[1]') == 'asc' )  <i class="fa fa-sort-alpha-asc"></i>  @else  <i class="fa fa-sort-alpha-desc"></i>  @endif
               @else  <i class="fa fa-sort"></i>  @endif
            </a>
         </th>
         <?php
            echo view('layouts.thSorting', ["thName"=>"poziom", "routeName"=>"grupa.orderBy", "field"=>"level", "sessionVariable"=>"GroupOrderBy"]);
            echo view('layouts.thSorting', ["thName"=>"uwagi", "routeName"=>"grupa.orderBy", "field"=>"comments", "sessionVariable"=>"GroupOrderBy"]);
         ?>
         <th>
            okres istnienia<br />
            <a href="{{ route('grupa.orderBy', 'start') }}">od
               @if( session()->get('GroupOrderBy[0]') == 'start' )
                  @if( session()->get('GroupOrderBy[1]') == 'asc' )
                     <i class="fa fa-sort-alpha-asc"></i>
                  @else
                     <i class="fa fa-sort-alpha-desc"></i>
                  @endif
               @else
                  <i class="fa fa-sort"></i>
               @endif
            </a> -
            <a href="{{ route('grupa.orderBy', 'end') }}">do
               @if( session()->get('GroupOrderBy[0]') == 'end' )
                  @if( session()->get('GroupOrderBy[1]') == 'asc' )
                     <i class="fa fa-sort-alpha-asc"></i>
                  @else
                     <i class="fa fa-sort-alpha-desc"></i>
                  @endif
               @else
                  <i class="fa fa-sort"></i>
               @endif
            </a>
         </th>
         <th>godziny</th>
         <th>nauczyciele</th>
         <th>uczniowie</th>
         <th>wprowadzono</th>
         <th>aktualizacja</th>
         <th colspan="3">operacje</th>
      </tr>

      <tr>
         <td></td>
         <td><?php  print_r($gradeSF);  ?></td>
         <td><?php  print_r($subjectSF);  ?></td>
         <td><?php  print_r($levelSF);  ?></td>
         <td></td>
         <td colspan="2" class="small">
            między <input type="date" id="start" name="start" value="{{ $start }}" />
            a <input type="date" id="end" name="end" value="{{ $end }}" />
            <input type="hidden" id="rememberDates" value="1" />
         </td>
         <td colspan="2"><?php  print_r($teacherSF);  ?></td>
         <td colspan="5"></td>
      </tr>
   </thead>

   <tbody>
      <?php $count = 0; ?>
      @foreach($groups as $group)
         <tr data-group_id="{{$group->id}}">
            <td><a href="{{ route('grupa.show', $group->id) }}">{{ ++$count }}</a></td>
            <td>
               <?php 
                  if(count($group->grades))  {
                     $studyYear = substr($start, 0, 4) - $group->grades[0]->grade->year_of_beginning;
                     if(substr($start, 5, 2) > 8) $studyYear++;
                  }
                  else $studyYear = "";
               ?>
               @if(count($group->grades))
                  {{$studyYear}}@foreach($group->grades as $grade)<a href="{{ route('klasa.show', $grade->grade_id) }}">{{ $grade->grade->symbol }}</a>@endforeach
               @endif
            </td>
            <td><a href="{{ route('przedmiot.show', $group->subject_id) }}">{{ $group->subject->name }}</a></td>
            <td>{{ $group->level }}</td>
            <td>{{ $group->comments }}</td>
            <td class="small">{{ $group->start }} - {{ $group->end }}</td>
            <td class="c">{{ $group->hours }}</td>
            <!-- nauczyciele -->
            <td class="small">
               @foreach($group->teachers as $groupTeacher)
                  @if( $groupTeacher->start <= $end && $groupTeacher->end >= $start )
                     {{ $groupTeacher->start }} {{ $groupTeacher->end }}
                     <a href="{{ route('nauczyciel.show', $groupTeacher->teacher_id) }}">
                        {{ $groupTeacher->teacher->first_name }} {{ $groupTeacher->teacher->last_name }}
                     </a><br />
                  @endif
               @endforeach
            </td>
            <td class="c">
               <!-- liczba uczniów -->
               <?php
                  $countGradeStudents=0; $countAllStudents=0;
                  foreach($group->students as $groupStudent) 
                     if($groupStudent->end >= $start && $groupStudent->start <= $end)  $countAllStudents++;
               ?>
               @if($countGradeStudents != $countAllStudents) {{ $countGradeStudents }} ({{ $countAllStudents }})
               @else {{ $countGradeStudents }}
               @endif
            </td>
            <td class="c small">{{ substr($group->created_at, 0, 10) }}</td>
            <td class="c small">{{ substr($group->updated_at, 0, 10) }}</td>

            <td class="copy"><a class="btn btn-primary" href="{{ route('grupa.copy', $group->id) }}"><i class="fa fa-copy"></i></a></td>
            <!-- modyfikowanie i usuwanie -->
            <td class="destroy edit c">
               <button class="edit btn btn-primary"    data-group_id="{{ $group->id }}" data-version="{{$version}}"><i class="fa fa-edit"></i></button>
               <button class="destroy btn btn-primary" data-group_id="{{ $group->id }}"><i class="fas fa-remove"></i></button>
            </td>
         </tr>
      @endforeach

      <tr class="create"><td colspan="13">
         <a class="btn btn-primary" href="{{ route('grupa.create') }}"><i class="fa fa-plus"></i></a>
      </td></tr>
   </tbody>
</table>
<a class="btn btn-danger" href="{{ route('grupa.editComments') }}">zmień uwagi</a>