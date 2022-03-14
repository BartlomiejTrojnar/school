@section('css')
  <link href="{{ asset('public/css/taughtSubject.css') }}" rel="stylesheet">
@endsection
@section('java-script')
  <script src="{{ asset('public/js/taughtSubject.js') }}"></script>
@endsection

<h2>Nauczyciele przedmiotu w roku szkolnym <?php  print_r($schoolYearSelectField);  ?></h2>
<div id="subject-id">{{ $subject->id }}</div>

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
      <li type="button" class="list-group-item active" data-taught-subject-id="{{ $subjectTeacher->id }}" data-teacher-id="{{ $subjectTeacher->teacher_id }}" data-teacher-name="{{ $subjectTeacher->teacher->first_name }} {{ $subjectTeacher->teacher->last_name }}">
        {{ $subjectTeacher->teacher->first_name }} {{ $subjectTeacher->teacher->last_name }}
        <span class="url">{{ url('nauczany_przedmiot/delete', $subjectTeacher->id) }}</span>
      </li>
    @endforeach
  </ul>
</section>

<section id="unlearningTeachersList">
  <h1>Nieuczący</h1>
  <ul class="list-group">
    @foreach($unlearningTeachers as $unlearningTeacher)
      <li type="button" class="list-group-item" data-teacher-id="{{ $unlearningTeacher->id }}">{{ $unlearningTeacher->first_name }} {{ $unlearningTeacher->last_name }}</li>
    @endforeach
  </ul>
</section>
