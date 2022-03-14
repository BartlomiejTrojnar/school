<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 06.11.2021 *********************** -->
<tr data-exam_id="{{$exam->id}}">
   <td><a href="{{ route('egzamin.show', $exam->id) }}">{{$lp}}</a></td>
   @if($version!="forDeclaration")
      <td>{{ $exam->declaration->student->first_name }} {{ $exam->declaration->student->last_name }} {{ $exam->declaration->application_number }}</td>
   @endif

   @if($version!="forExamDescription")
      <td>
         {{ $exam->examDescription->session->year }} {{ $exam->examDescription->session->type }} 
         <spam style="color: yellow;">{{ $exam->examDescription->subject->name }}</spam> {{ $exam->examDescription->type }} {{ $exam->examDescription->level }}
      </td>
   @endif

   <td>
      @if($exam->term)
         {{ substr($exam->term->date_start, 0, 16) }}-{{ substr($exam->term->date_end, 11, 5) }} sala {{ $exam->term->classroom->name }}
      @endif
   </td>
   <td>{{ $exam->type }}</td>
   <td>{{ $exam->points }}</td>
   <td>{{ $exam->comments }}</td>
   <td class="c">{{ substr($exam->created_at, 0, 10) }}</td>
   <td class="c">{{ substr($exam->updated_at, 0, 10) }}</td>

   <!-- modyfikowanie i usuwanie -->
   <td class="destroy edit c">
      <button class="edit btn btn-primary"    data-exam_id="{{ $exam->id }}" data-version="{{$version}}"><i class="fa fa-edit"></i></button>
      <button class="destroy btn btn-primary" data-exam_id="{{ $exam->id }}"><i class="fas fa-remove"></i></button>
   </td>
</tr>