<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 11.04.2023 *********************** -->
<h2>Informacje o grupie</h2>

<table>
   <tr>
      <th>przedmiot</th>
      <td><a href="{{ route('przedmiot.show', $group->subject_id) }}">{{ $group->subject->name }}</a></td>
   </tr>
   <tr>
      <th>czas życia</th>
      <td>{{ $group->start }} - {{ $group->end }}</td>
   </tr>
   <tr>
      <th>godziny</th>
      <td class="c" data-group_id="{{ $group->id }}" data-url="{{ url('grupa') }}">
        <button id="hourSubtract" class="btn-xs btn-primary" data-group_id="{{ $group->id }}"><i class="fa fa-minus"></i></button>
        <span style="font-size: 1.25em;">{{ $group->hours }}</span>
        <button id="hourAdd" class="btn-xs btn-primary" data-group_id="{{ $group->id }}"><i class="fa fa-plus"></i></button>
      </td>
   </tr>
   <tr>
      <th>uwagi</th>
      <td class="c">{{ $group->comments }}</td>
   </tr>
   <tr>
      <th>nauczyciele</th>
      <td class="teachers" style="min-width: 400px;">
         <aside style="float: right; margin-left: 10px;">
            <a href="{{ url('grupa_nauczyciele/addTeacher/'. $group->id) }}">zmień
               <i class='fa fa-chalkboard-teacher' style="font-size: 24px;"></i>
            </a>
         </aside>
         @foreach($group->teachers as $groupTeacher)
            <div data-groupTeacher_id="{{ $groupTeacher->id }}">
               <a href="{{ route('nauczyciel.show', $groupTeacher->teacher_id) }}" style="font-size: 1.2em;">
                  {{ $groupTeacher->teacher->first_name }} {{ $groupTeacher->teacher->last_name }}
               </a>
               <a href="{{ route('grupa_nauczyciele.edit', $groupTeacher->id) }}">
                  <i class="fa fa-edit"></i>
               </a>
               <button class="teacherRemove" data-groupTeacher_id="{{ $groupTeacher->id }}" data-token="{{ csrf_token() }}" data-url="{{ route('grupa_nauczyciele.destroy', $groupTeacher->id) }}">
                  <i class="fa fa-remove"></i>
               </button>
               <span style="font-size: 0.8em;">{{ $groupTeacher->start }} {{ $groupTeacher->end }}</span>
            </div>
         @endforeach
      </td>
   </tr>

   <tr>
      <th>klasy</th>
      <td class="grades">
         <aside style="float: right;"><a href="{{ url('grupa_klasy/gradesList/'. $group->id .'/forGroup') }}">
            <i class="fa fa-users" style="font-size:24px"></i>
         </a></aside>
         @foreach($grades as $groupGrade)
            <div data-groupGrade_id="{{ $groupGrade->id }}">
               <a href="{{ route('klasa.show', $groupGrade->grade_id) }}">
                  @if( $year )
                    {{ $year - $groupGrade->year_of_beginning }}{{ $groupGrade->symbol }}
                  @else
                    {{ $groupGrade->year_of_beginning }}-{{ $groupGrade->year_of_graduation }}{{ $groupGrade->symbol }}
                  @endif
               </a>
               <button class="gradeRemove" data-groupGrade_id="{{ $groupGrade->id }}" data-token="{{ csrf_token() }}" data-url="{{ route('grupa_klasy.destroy', $groupGrade->id) }}">
                  <i class="fa fa-remove"></i>
               </button>
            </div>
         @endforeach
      </td>
   </tr>

   <tr>
      <th>liczba uczniów</th>
      <td class="c">{{ $group->students->count() }}</td>
   </tr>
</table>

<p>Przedłuż grupę do <input type="date" id="dateGroupExtension" value="{{ $group->end }}"><input class="hidden" type="text" id="groupExtensionId" value="{{ $group->id }}"> <button class="btn btn-primary" id="groupExtension">przedłuż</button>
   <button class="btn btn-primary" id="groupExtension4">przedłuż do 2023-04-28</button>
   <button class="btn btn-primary" id="groupExtension123">przedłuż do 2023-06-23</button>
</p>