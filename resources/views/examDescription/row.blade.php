<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 30.10.2021 *********************** -->
<tr data-exam_description_id="{{$examDescription->id}}">
   <td><a href="{{ route('opis_egzaminu.show', $examDescription->id) }}">{{ $lp }}</a></td>
   <td><a href="{{ route('sesja.show', $examDescription->session_id) }}">
      {{ $examDescription->session->year }} {{ $examDescription->session->type }}
   </a></td>
   <td><a href="{{ route('przedmiot.show', $examDescription->subject_id) }}">
      {{ $examDescription->subject->name }}
   </a></td>
   <td>{{ $examDescription->type }}</td>
   <td>{{ $examDescription->level }}</td>
   <td>{{ $examDescription->max_points }}</td>
   <td>{{ $examDescription->created_at }}</td>
   <td>{{ $examDescription->updated_at }}</td>
   
   <!-- modyfikowanie i usuwanie -->
   <td class="destroy edit c">
      <button class="edit btn btn-primary" data-exam_description_id="{{ $examDescription->id }}"><i class="fa fa-edit"></i></button>
      <button class="destroy btn btn-primary" data-exam_description_id="{{ $examDescription->id }}"><i class="fas fa-remove"></i></button>
   </td>
</tr>