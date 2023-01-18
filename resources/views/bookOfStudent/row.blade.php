<tr data-book_of_student_id="{{ $bookOfStudent->id }}">
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 18.01.2023 *********************** -->
   <td class="lp">{{$lp}}</td>
   <td><a href="{{ route('szkola.show', $bookOfStudent->school_id) }}">{{ $bookOfStudent->school->name }}</a></td>
   <td><a href="{{ route('uczen.show', $bookOfStudent->student_id) }}">{{ $bookOfStudent->student->first_name }} {{ $bookOfStudent->student->last_name }}</a></td>
   <td>{{ $bookOfStudent->number }}</td>
   <td class="small c">{{ substr($bookOfStudent->created_at, 0, 10) }}</td>
   <td class="small c">{{ substr($bookOfStudent->updated_at, 0, 10) }}</td>

   <td class="destroy edit c">
      <button class="edit btn btn-primary"    data-book_of_student_id="{{ $bookOfStudent->id }}"><i class="fa fa-edit"></i></button>
      <button class="destroy btn btn-primary" data-book_of_student_id="{{ $bookOfStudent->id }}"><i class="fa fa-remove"></i></button>
   </td>
</tr>