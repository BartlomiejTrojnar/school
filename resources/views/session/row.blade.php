<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 27.10.2021 *********************** -->
<tr data-session_id="{{$session->id}}">
   <td>{{ $session->id }}</td>
   <td><a href="{{ route('sesja.show', $session->id) }}">{{ $session->year }}</a></td>
   <td>{{ $session->type }}</td>
   <td>{{ count($session->declarations) }}</td>
   <td>{{ $session->exams() }}</td>
   <td>{{ count($session->examDescriptions) }}</td>

   <!-- modyfikowanie i usuwanie -->
   <td class="destroy edit c">
      <button class="edit btn btn-primary"      data-session_id="{{ $session->id }}"><i class="fa fa-edit"></i></button>
      <button class="destroy btn btn-primary"   data-session_id="{{ $session->id }}"><i class="fas fa-remove"></i></button>
   </td>
</tr>