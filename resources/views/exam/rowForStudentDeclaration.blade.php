<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 30.09.2022 *********************** -->
<tr data-exam_id="{{$exam->id}}">
   <td>
      {{ $exam->examDescription->session->year }} {{ $exam->examDescription->session->type }}
      <spam style="color: yellow;">{{ $exam->examDescription->subject->name }}</spam> {{ $exam->examDescription->type }} {{ $exam->examDescription->level }}
   </td>
   <td>{{ $exam->type }}</td>
   <td>
      @if($exam->term)
         {{ substr($exam->term->date_start, 0, 16) }}-{{ substr($exam->term->date_end, 11, 5) }} sala {{ $exam->term->classroom->name }}
      @endif
   </td>
   <td>{{ $exam->comments }}</td>
   <td>{{ $exam->points }}</td>
   <td class="c small">{{ substr($exam->updated_at, 0, 10) }}</td>

   <!-- modyfikowanie i usuwanie -->
   <td class="destroy edit c">
      <button class="btn btn-primary editExam"    data-exam_id="{{ $exam->id }}"><i class="fa fa-edit"></i></button>
      <button class="btn btn-primary destroyExam" data-exam_id="{{ $exam->id }}"><i class="fas fa-remove"></i></button>
   </td>
</tr>