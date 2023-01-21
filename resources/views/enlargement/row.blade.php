<tr data-enlargement_id="{{ $enlargement->id }}">
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 21.01.2023 *********************** -->
   <td>{{ $lp }}</td>
   <td>{{ $enlargement->student->first_name }} {{ $enlargement->student->last_name }}</td>
   <td>{{ $enlargement->subject->name }}</td>
   <td>{{ $enlargement->level }}</td>
   <td class="c">{{ $enlargement->choice }}</td>
   <td class="c">{{ $enlargement->resignation }}</td>

   <!-- modyfikowanie i usuwanie -->
   <td class="destroy edit c">
      <button class="edit btn btn-primary"    data-enlargement_id="{{ $enlargement->id }}"><i class="fa fa-edit"></i></button>
      <button class="destroy btn btn-primary" data-enlargement_id="{{ $enlargement->id }}"><i class="fa fa-remove"></i></button>
   </td>
</tr>