<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 13.01.2023 ********************** -->
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
      @foreach($taughtSubjects as $ts)
         <li type="button" class="list-group-item active" data-taughtsubject_id="{{ $ts->id }}" data-subject_id="{{ $ts->subject_id }}" data-subject_name="{{ $ts->subject->name }}">{{ $ts->subject->name }}</li>
      @endforeach
   </ul>
</section>

<section id="subjectsList">
   <ul class="list-group">
      @foreach($nonTaughtSubjects as $nts)
         <li type="button" class="list-group-item" data-subject_id="{{ $nts->id }}">{{ $nts->name }}</li>
      @endforeach
   </ul>
</section>