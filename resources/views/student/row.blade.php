<tr data-student_id="{{ $student->id }}">
<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 15.01.2023 ********************** -->
   <td class="small">{{ $lp }}</td>
   <td>{{ $student->first_name }}</td>
   <td>{{ $student->second_name }}</td>
   <td><a href="{{ route('uczen.show', $student->id) }}">{{ $student->last_name }}</a></td>
   <td>{{ $student->family_name }}</td>
   <td class="small">{{ $student->sex }}</td>
      @if( strlen($student->PESEL)==11 )
         <td>{{ $student->PESEL }}</td>
      @else
         <td style="color: red;">{{ $student->PESEL }} ({{ strlen($student->PESEL) }})</td>
      @endif
      <td>{{ $student->place_of_birth }}</td>
      <td class="created">{{ substr($student->created_at, 0, 10) }}</td>
      <td class="updated">{{ substr($student->updated_at, 0, 10) }}</td>
      <td class="edit destroy c">
         <button class="edit btn btn-primary"      data-student_id="{{ $student->id }}"><i class="fa fa-edit"></i></button>
         <button class="destroy btn btn-primary"   data-student_id="{{ $student->id }}"><i class="fa fa-remove"></i></button>
      </td>
</tr>