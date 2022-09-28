<tr data-exam_description_id="{{$examDescription->id}}">
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 28.09.2022 *********************** -->
   <td><a href="{{ route('opis_egzaminu.show', $examDescription->id) }}">{{ $lp }}</a></td>
   <td><a href="{{ route('sesja.show', $examDescription->session_id) }}">
      {{ $examDescription->session->year }} {{ $examDescription->session->type }}
   </a></td>
   <td><a href="{{ route('przedmiot.show', $examDescription->subject_id) }}">
      {{ $examDescription->subject->name }}
   </a></td>
   <td>{{ $examDescription->type }}</td>
   <td>{{ $examDescription->level }}</td>
   <td class="c">{{ $examDescription->max_points }}</td>
   <td class="c">?</td>
   <td class="c small">{{ substr($examDescription->created_at, 0, 10) }}</td>
   <td class="c small">{{ substr($examDescription->updated_at, 0, 10) }}</td>
   
   <!-- modyfikowanie i usuwanie -->
   <td class="destroy edit c">
      <button class="edit btn btn-primary" data-exam_description_id="{{ $examDescription->id }}"><i class="fa fa-edit"></i></button>
      <button class="destroy btn btn-primary" data-exam_description_id="{{ $examDescription->id }}"><i class="fas fa-remove"></i></button>
   </td>
</tr>