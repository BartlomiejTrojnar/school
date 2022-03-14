<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 03.11.2021 *********************** -->
<tr data-declaration_id="{{$declaration->id}}">
   <td><a href="{{ route('deklaracja.show', $declaration->id) }}">{{$lp}}</a></td>
   
   @if($version!="forStudent")
      <td><a href="{{ route('uczen.show', $declaration->student_id) }}">{{ $declaration->student->first_name }} {{ $declaration->student->last_name }}</a></td>
   @endif

   @if($version!="forSession")
      <td class="c"><a href="{{ route('sesja.show', $declaration->session_id) }}">{{ $declaration->session->year }} {{ $declaration->session->type }}</a></td>
   @endif

   <td class="c">{{ $declaration->application_number }}</td>
   <td>{{ $declaration->student_code }}</td>
   <td class="c"><a href="{{ route('deklaracja.show', $declaration->id.'/showExams') }}">{{ count($declaration->exams) }}</a></td>
   <td class="c small">{{ substr($declaration->created_at, 0, 10) }}</td>
   <td class="c small">{{ substr($declaration->updated_at, 0, 10) }}</td>

   <!-- modyfikowanie i usuwanie -->
   <td class="destroy edit c">
      <button class="edit btn btn-primary" data-declaration_id="{{ $declaration->id }}" data-version="{{$version}}"><i class="fa fa-edit"></i></button>
      <button class="destroy btn btn-primary" data-declaration_id="{{ $declaration->id }}"><i class="fas fa-remove"></i></button>
   </td>
</tr>