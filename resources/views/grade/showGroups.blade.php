<!-- ******************  (C) mgr inż. Bartłomiej Trojnar; (III) czerwiec 2021 ****************** -->
<h2>Grupy w klasie</h2>
<table id="groups">
   <thead>
      <tr>
         <th>id</th>
         <th>klasy</th>
         <th><a href="{{ url('/grupa/sortuj/subject_id') }}">przedmiot</a></th>
         <th><a href="{{ url('/grupa/sortuj/level') }}">poziom</a></th>
         <th>uwagi</th>
         <th>
            okres istnienia<br />
            <a href="{{ url('/grupa/sortuj/date_start') }}">od</a> -
            <a href="{{ url('/grupa/sortuj/date_end') }}">do</a>
         </th>
         <th>godziny</th>
         <th>nauczyciele</th>
         <th>uczniowie</th>
         <th>wprowadzono</th>
         <th>aktualizacja</th>
         <th colspan="2">+/-</th>
      </tr>
   </thead>

   <tbody>
      @foreach($grade->groups as $groupGrade)
         <tr>
            <td><a href="{{ route('grupa.show', $groupGrade->group_id) }}">{{ $groupGrade->group_id }}</a></td>
            <td><a href="{{ route('klasa.show', $groupGrade->grade_id) }}">
                  {{ $groupGrade->grade->year_of_beginning }}-{{ $groupGrade->grade->year_of_graduation }} {{$groupGrade->grade->symbol}}
            </a></td>
            <td><a href="{{ route('przedmiot.show', $groupGrade->group->subject_id) }}"> {{ $groupGrade->group->subject->name }}</a></td>
            <td>{{ $groupGrade->group->level }}</td>
            <td>{{ $groupGrade->group->comments }}</td>
            <td>{{ $groupGrade->group->date_start }} - {{ $groupGrade->group->date_end }}</td>
            <td class="c">{{ $groupGrade->group->hours }}</td>
            <td>
               @foreach($groupGrade->group->teachers as $groupTeacher)
                  <small>{{ $groupTeacher->date_start }} {{ $groupTeacher->date_end }}</small>
                  <a href="{{ route('nauczyciel.show', $groupTeacher->teacher_id) }}">
                     {{ $groupTeacher->teacher->first_name }} {{ $groupTeacher->teacher->last_name }}
                  </a>
               @endforeach
            </td>
            <td class="c">{{ count($groupGrade->group->students) }}</td>
            <td class="small">{{ $groupGrade->group->created_at }}</td>
            <td class="small">{{ $groupGrade->group->updated_at }}</td>
            <td class="edit"><a class="btn btn-primary" href="{{ route('grupa.edit', $groupGrade->id) }}">
               <i class="fa fa-edit"></i>
            </a></td>
            <td class="destroy">
               <form action="{{ route('grupa.destroy', $groupGrade->id) }}" method="post" id="delete-form-{{$groupGrade->group_id}}">
                  {{ csrf_field() }}
                  {{ method_field('DELETE') }}
                  <button class="btn btn-primary"><i class="fa fa-remove"></i></button>
               </form>
            </td>
         </tr>
      @endforeach  
      <tr class="create"><td colspan="13">
         <a class="btn btn-primary" href="{{ route('grupa.create') }}"><i class="fa fa-plus"></i></a>
      </td></tr>
   </tbody>
</table>