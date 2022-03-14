<div data-student_group="{{$groupStudent->id}}" class="studentEditForm" style="position: absolute;">
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 19.02.2021 *********************** -->
   <form action="{{ route('grupa_uczniowie.update', $groupStudent->id) }}" method="post" role="form">
      {{ csrf_field() }}
      {{ method_field('PATCH') }}

      <table>
         <tr>
            <th><label for="start">data początkowa</label></th>
            <th><label for="end">data końcowa</label></th>
            <th><label>grupa</label></th>
         </tr>

         <tr>
            <td class="c"><input type="date" name="start" value="{{$groupStudent->start}}" required /></td>
            <td class="c"><input type="date" name="end" value="{{$groupStudent->end}}" required /></td>
            <td style="min-width: 300px;">
               <span class="hidden" id="groupStart">{{$groupStudent->group->start}}</span>
               <span class="hidden" id="groupEnd">{{$groupStudent->group->end}}</span>
               <span>
                  {{$year - $groupStudent->group->grades[0]->grade->year_of_beginning}}@foreach($groupStudent->group->grades as $groupGrade){{$groupGrade->grade->symbol}}@endforeach
               </span>
               {{$groupStudent->group->subject->name}} {{$groupStudent->group->comments}}
               <small>{{$groupStudent->group->level}} {{$groupStudent->group->hours}}</small>
               @foreach($groupStudent->group->teachers as $groupTeacher)
                  <span class="small">{{ $groupTeacher->teacher->first_name }} {{ $groupTeacher->teacher->last_name }}</span>
               @endforeach

               <input type="hidden" name="group_id" value="{{$groupStudent->group_id}}" />
               <input type="hidden" name="student_id" value="{{$groupStudent->student_id}}" />
            </td>
         </tr>

         <tr><td colspan="3" class="error"><p class="error"></p></td></tr>

         <tr>
            <td class="proposedDateStart">
               <ul>
                  <li><span>{{ $groupStudent->group->start }}</span> <label>data początkowa grupy</label></li>
                  @foreach($groupStudent->student->grades as $studentGrade)
                     @if( $studentGrade->start >= $groupStudent->group->start && $studentGrade->start <= $groupStudent->group->end )
                        <li><span>{{ $studentGrade->start }}</span> <label>data początkowa ucznia w klasie</label></li>
                     @endif
                  @endforeach
                  @if( session()->get('dateView') >= $groupStudent->group->start &&  session()->get('dateView') <= $groupStudent->group->end )
                     <li><span>{{ session()->get('dateView') }}</span> <label>data widoku</label></li>
                  @endif
                  @if( date('Y-m-d') >= $groupStudent->group->start && date('Y-m-d') <= $groupStudent->group->end )
                     <li><span>{{ date('Y-m-d') }}</span> <label>data aktualna</label></li>
                  @endif
               </ul>
            </td>

            <td class="proposedDateEnd">
               <ul>
                  <li><span>{{ $groupStudent->group->end }}</span> <label>data końcowa grupy</label></li>
                  @foreach($groupStudent->student->grades as $studentGrade)
                     @if( $studentGrade->end >= $groupStudent->group->start && $studentGrade->end <= $groupStudent->group->end )
                        <li><span>{{ $studentGrade->end }}</span> <label>data końcowa ucznia w klasie</label></li>
                     @endif
                  @endforeach
                  @if( session()->get('dateView') > $groupStudent->group->start &&  session()->get('dateView') <= $groupStudent->group->end )
                     <?php $yesterday = date('Y-m-d', strtotime('-1 day', strtotime( session()->get('dateView') ))); ?>
                     <li><span>{{$yesterday}}</span> <label>data widoku -1 dzień</label></li>
                  @endif
                  @if( date('Y-m-d') > $groupStudent->group->start && date('Y-m-d') <= $groupStudent->group->end )
                     <?php  $yesterday = date('Y-m-d', strtotime('-1 day', strtotime(date('Y-m-d')) )); ?>
                     <li><span>{{ $yesterday }}</span> <label>data aktualna - 1 dzień</label></li>
                  @endif
               </ul>
            </td>

            <!-- komórka z przyciskami potwierdzenia zmiany i anulowania -->
            <td class="c edit">
               <button data-group_student="{{$groupStudent->id}}" class="update btn btn-primary">zapisz zmiany</button>
               <button data-group_student="{{$groupStudent->id}}" class="cancelUpdate btn btn-primary">anuluj</button>
            </td>
         </tr>
      </table>
   </form>
</div>