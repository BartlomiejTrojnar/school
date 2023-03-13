<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 13.01.2023 ********************** -->
@section('css')
   <link href="{{ asset('public/css/taughtSubject.css') }}" rel="stylesheet">
@endsection

<h2>Nauczyciele przedmiotu w roku szkolnym <?php  print_r($schoolYearSF);  ?></h2>
<div id="subject_id">{{ $subject->id }}</div>

<form action="{{ route('nauczany_przedmiot.store') }}" method="post" role="form" id="formTaughtSubject" style="display:none;">
   {{ csrf_field() }}
   <input name="teacher_id" />
   <input name="subject_id" />
   <input type="hidden" name="history_view" value="{{ route('nauczyciel.show', $subject->id) }}" />
</form>
<div id="url">{{ url('nauczany_przedmiot') }}</div>
<div id="token">{{ csrf_field() }}</div>

<section id="subjectTeachersList">
   <h1>Uczący</h1>
   <ul class="list-group">
      @foreach($subjectTeachers as $subjectTeacher)
         <li type="button" class="list-group-item active" data-taughtsubject_id="{{ $subjectTeacher->id }}" data-teacher_id="{{ $subjectTeacher->teacher_id }}">
            <data class="teacherName" value="{{ $subjectTeacher->teacher->first_name }} {{ $subjectTeacher->teacher->last_name }}">{{ $subjectTeacher->teacher->first_name }} {{ $subjectTeacher->teacher->last_name }}</data>
            <var class="start">{{ $subjectTeacher->teacher->first_year_id }}</var>
            <var class="end">{{ $subjectTeacher->teacher->last_year_id }}</var>
         </li>
      @endforeach
   </ul>
</section>

<section id="unlearningTeachersList">
   <h1>Nieuczący</h1>
   <ul class="list-group">
      @foreach($unlearningTeachers as $unlearningTeacher)
         <li type="button" class="list-group-item" data-teacher_id="{{ $unlearningTeacher->id }}">
            <data class="teacherName" value="{{ $unlearningTeacher->first_name }} {{ $unlearningTeacher->last_name }}">{{ $unlearningTeacher->first_name }} {{ $unlearningTeacher->last_name }}</data>   
            <var class="start">{{ $unlearningTeacher->first_year_id }}</var>
            <var class="end">{{ $unlearningTeacher->last_year_id }}</var>
         </li>
      @endforeach
   </ul>
</section>
