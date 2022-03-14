<p style="display: none;" id="teacher_id">{{ $teacher->id }}</p>
<p>{{ $teacher->family_name }}</p>
<p>{{ $teacher->short }}</p>
<p>{{ $teacher->classroom_id }}</p>
<p>nauczyciel od @if($teacher->first_year) {{ substr($teacher->first_year->date_start, 0, 4) }}/{{ substr($teacher->first_year->date_end, 0, 4)}} @endif
   do @if($teacher->last_year) {{ substr($teacher->last_year->date_start, 0, 4)}}/{{ substr($teacher->last_year->date_end, 0, 4) }} @endif</p>
<p>wprowadzono: {{ $teacher->created_at }}, aktualizacja: {{ $teacher->updated_at }}</p>

<h2>Nauczane przedmioty</h2>
<div id="teacher-id">{{ $teacher->id }}</div>

<form action="{{ route('nauczany_przedmiot.store') }}" method="post" role="form" id="formTaughtSubject" style="display:none;">
   {{ csrf_field() }}
   <input name="teacher_id" />
   <input name="subject_id" />
   <input type="hidden" name="history_view" value="{{ route('nauczyciel.show', $teacher->id) }}" />
</form>
<div id="url">{{ url('nauczany_przedmiot') }}</div>
<div id="token">{{ csrf_field() }}</div>

<section id="taughtSubjectsList">
   <ul class="list-group">
      @foreach($taughtSubjects as $taughtSubject)
         <li type="button" class="list-group-item active" data-taught-subject-id="{{ $taughtSubject->id }}" data-subject-id="{{ $taughtSubject->subject_id }}" data-subject-name="{{ $taughtSubject->subject->name }}">
            {{ $taughtSubject->subject->name }}
            <span class="url">{{ url('nauczany_przedmiot/delete', $taughtSubject->id) }}</span>
         </li>
      @endforeach
   </ul>
</section>

<section id="subjectsList">
   <ul class="list-group">
      @foreach($nonTaughtSubjects as $nonTaughtSubject)
         <li type="button" class="list-group-item" data-subject-id="{{ $nonTaughtSubject->id }}" data-teacher-id="{{ $teacher->id }}">{{ $nonTaughtSubject->name }}</li>
      @endforeach
   </ul>
</section>