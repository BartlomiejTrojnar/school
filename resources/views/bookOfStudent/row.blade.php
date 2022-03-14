<tr data-book_of_student_id="{{$bookOfStudent->id}}" style="display: none;">
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 03.01.2022 *********************** -->
   <td class="lp">{{$lp}}</td>
   <td><a href="{{ route('szkola.show', $bookOfStudent->school_id) }}">{{ $bookOfStudent->school->name }}</a></td>
   <td><a href="{{ route('uczen.show', $bookOfStudent->student_id) }}">{{ $bookOfStudent->student->first_name }} {{ $bookOfStudent->student->last_name }}</a></td>
   <td>{{ $bookOfStudent->number }}</td>
   <td>{{ $bookOfStudent->created_at }}</td>
   <td>{{ $bookOfStudent->updated_at }}</td>

   <td class="destroy edit c">
      <button class="edit btn btn-primary"    data-book_of_student_id="{{ $bookOfStudent->id }}"><i class="fa fa-edit"></i></button>
      <button class="destroy btn btn-primary" data-book_of_student_id="{{ $bookOfStudent->id }}"><i class="fas fa-remove"></i></button>
   </td>
</tr>